<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\DrawingDetail;
use App\Models\DrawingFile;
use App\Models\ReportFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class DrawingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {    
        $drawings = Drawing::all();

        // Prepare data for the line chart (global data without filtering by ID)
        $totalDrawings = DrawingDetail::count();
        $totalScopeDrawings = DrawingDetail::where('isScopeDrawing', 1)->count();
        $totalSubmittedDrawings = DrawingDetail::whereNotNull('submitted_at')->count();
        $totalApprovedDrawings = DrawingDetail::whereNotNull('approved_at')->count();

        // Data for line chart
        $lineChartData = [
            'totalDrawings' => $totalDrawings,
            'totalScopeDrawings' => $totalScopeDrawings,
            'totalSubmittedDrawings' => $totalSubmittedDrawings,
            'totalApprovedDrawings' => $totalApprovedDrawings,
        ];

        return view('drawings', compact('drawings', 'lineChartData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Step 1: Validate the request
        $request->validate([
            'drawing_id' => 'required|exists:drawings,id',
            'drawing_details_no' => 'required|string',
            'drawing_details_name' => 'required|string',
            'isScopeDrawing' => 'sometimes|boolean',
            'submitted_at' => 'required|date',
            'drawing_file' => 'required|file|mimes:pdf,jpg,png',  // Drawing file is required
            'report_file' => 'nullable|file|mimes:pdf,jpg,png',  // Supporting document is optional
        ]);

        // Step 2: Create the DrawingDetail record and get its ID
        $drawingDetail = DrawingDetail::create([
            'drawing_id' => $request->drawing_id,
            'drawing_details_no' => $request->drawing_details_no,
            'drawing_details_name' => $request->drawing_details_name,
            'isScopeDrawing' => $request->has('isScopeDrawing') ? 1 : 0,
            'submitted_at' => $request->submitted_at,
            'submitted_by' => Auth::id(),
        ]);

        // Step 3: Handle Drawing file upload and manually save the record in drawing_files
        if ($request->hasFile('drawing_file')) {
            // Generate a unique file name for the drawing file
            $drawingFileName = time() . '_' . $request->file('drawing_file')->getClientOriginalName();
            $drawing = Drawing::findOrFail($request->drawing_id);  // Fetch drawing for folder name
            $filePath = 'drawing/' . $drawing->name . '/' . $drawingFileName;

            // Move the file to the public directory
            $request->file('drawing_file')->move(public_path('drawing/' . $drawing->name), $drawingFileName);

            // Manually create a DrawingFile instance and save it
            $drawingfile = new DrawingFile();
            $drawingfile->drawing_detail_id = $drawingDetail->id;  // Use the fetched drawing_detail_id
            $drawingfile->file_path = $filePath;
            $drawingfile->save();  // Save the model instance
        }

        // Step 4: Handle the optional report file upload and manually save the record in report_files
        if ($request->hasFile('report_file')) {
            // Generate a unique file name for the report file
            $reportFileName = time() . '_' . $request->file('report_file')->getClientOriginalName();
            $drawing = Drawing::findOrFail($request->drawing_id);  // Fetch drawing for folder name
            $filePath = 'report/' . $drawing->name . '/' . $reportFileName;

            // Move the file to the public directory
            $request->file('report_file')->move(public_path('report/' . $drawing->name), $reportFileName);

            // Manually create a ReportFile instance and save it
            $reportfile = new ReportFile();
            $reportfile->drawing_detail_id = $drawingDetail->id;  // Use the fetched drawing_detail_id
            $reportfile->file_path = $filePath;
            $reportfile->save();  // Save the model instance
        }

        return response()->json(['success' => true, 'message' => 'Drawing and files created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Drawing $drawing)
    {
        // Fetch the drawing details along with submitter, approver, comments, drawing files, and report files
        $drawingDetails = DrawingDetail::where('drawing_id', $drawing->id)
            ->with(['submitter', 'approver', 'drawingFiles', 'reportFiles'])
            ->get();
        
        // Prepare each detail with the latest comment, drawing file, and report file
        $details = $drawingDetails->map(function($detail) {
            // Get the latest comment (if it exists)
            // $latestComment = optional($detail->comments->sortByDesc('commented_at')->first());
            
            // Get the latest drawing file (if it exists)
            $latestDrawingFile = optional($detail->drawingFiles->sortByDesc('created_at')->first());
            
            // Get the latest report file (if it exists)
            $latestReportFile = optional($detail->reportFiles->sortByDesc('created_at')->first());

            return [
                'id' => $detail->id,
                'drawing_details_no' => $detail->drawing_details_no,
                'drawing_details_name' => $detail->drawing_details_name,
                'isScopeDrawing' => $detail->isScopeDrawing ? 'Yes' : 'No',
                'submitted_at' => Carbon::parse($detail->submitted_at)->format('d-m-Y'),
                // 'submitted_by' => optional($detail->submitter)->name,
                // Fetch the latest values from the comment (if exists)
                // 'comment_body' => $latestComment->comment_body,
                // 'commented_at' => $latestComment->commented_at ? Carbon::parse($latestComment->commented_at)->format('d-m-Y') : null,
                // 'commented_by' => optional($latestComment->commenter)->name,
                // 'resubmitted_at' => $latestComment->resubmitted_at ? Carbon::parse($latestComment->resubmitted_at)->format('d-m-Y') : null,
                'approved_at' => Carbon::parse($detail->approved_at)->format('d-m-Y'),
                // 'approved_by' => optional($detail->approver)->name,
                // Latest drawing file (if exists)
                'drawing_file_url' => $latestDrawingFile->file_path ? : null,
                // Latest report file (if exists)
                // 'report_file_url' => $latestReportFile->file_path ? : null,
            ];
        });

        // Return both latest details for DataTable and all data for the bar chart
        return response()->json([
            'details' => $details,  // Latest details for the DataTable
            'chartData' => [
                'scopeDrawings' => $drawingDetails->where('isScopeDrawing', 1)->count(),
                'submittedDrawings' => $drawingDetails->whereNotNull('submitted_at')->count(),
                'approvedDrawings' => $drawingDetails->whereNotNull('approved_at')->count(),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the drawing detail with its associated data
        $drawingDetail = DrawingDetail::with([
            'submitter',
            'approver',
            'drawingFiles.comments.commenter',  // Eager load comments from drawingFiles
            'reportFiles'
        ])->findOrFail($id);
        $drawing = Drawing::findOrFail($drawingDetail->drawing_id);

        // Pass the drawing detail to the view
        return view('update-drawing-details', compact('drawingDetail', 'drawing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // // Validate incoming request
        // $request->validate([
        //     'drawing_details_no' => 'required|string',
        //     'drawing_details_name' => 'required|string',
        //     'isScopeDrawing' => 'sometimes|boolean',
        //     'submitted_at' => 'required|date',
        //     'drawing_file' => 'nullable|file|mimes:pdf,jpg,png',
        //     'report_file' => 'nullable|file|mimes:pdf,jpg,png',
        // ]);

        // // Find the drawing detail
        // $drawingDetail = DrawingDetail::findOrFail($id);
        
        // // Update fields
        // $drawingDetail->update([
        //     'drawing_details_no' => $request->drawing_details_no,
        //     'drawing_details_name' => $request->drawing_details_name,
        //     'isScopeDrawing' => $request->has('isScopeDrawing'),
        //     'submitted_at' => $request->submitted_at,
        //     'approved_by' => Auth::id(),
        //     'approved_at' => Carbon::now()
        // ]);

        // // Handle Drawing file upload (if new file provided)
        // if ($request->hasFile('drawing_file')) {
        //     $drawingFileName = time() . '_' . $request->file('drawing_file')->getClientOriginalName();
        //     $drawing = Drawing::findOrFail($drawingDetail->drawing_id);  // Find drawing category
        //     $request->file('drawing_file')->move(public_path('drawing/' . $drawing->name), $drawingFileName);
            
        //     // Save to DrawingFile model
        //     DrawingFile::create([
        //         'drawing_detail_id' => $drawingDetail->id,
        //         'file_path' => 'drawing/' . $drawing->name . '/' . $drawingFileName,
        //     ]);
        // }

        // // Handle Report file upload (if new file provided)
        // if ($request->hasFile('report_file')) {
        //     $reportFileName = time() . '_' . $request->file('report_file')->getClientOriginalName();
        //     $drawing = Drawing::findOrFail($drawingDetail->drawing_id);
        //     $request->file('report_file')->move(public_path('report/' . $drawing->name), $reportFileName);
            
        //     // Save to ReportFile model
        //     ReportFile::create([
        //         'drawing_detail_id' => $drawingDetail->id,
        //         'file_path' => 'report/' . $drawing->name . '/' . $reportFileName,
        //     ]);
        // }

        // return response()->json(['success' => true, 'message' => 'Drawing updated successfully']);

        // Retrieve the drawing by ID from the request
        $drawingDetail = DrawingDetail::findOrFail($id);

        // Check if the request is for approving the drawing
        if ($request->input('action') === 'approve_drawing') {
            // Check if the drawing is already approved
            if ($drawingDetail->approved_at) {
                return back()->with('message', 'Drawing is already approved.');
            }

            // Update the `approved_at` and `approved_by` fields
            $drawingDetail->approved_at = Carbon::now(); // Current timestamp
            $drawingDetail->approved_by = Auth::id();    // Get the authenticated user's ID

            $drawingDetail->save();

            return redirect()->route('drawings.edit', $id)->with('message', 'Drawing has been approved.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $drawingDetail = DrawingDetail::findOrFail($id);

        // Delete related drawing and report files from storage
        foreach ($drawingDetail->drawingFiles as $file) {
            if (File::exists(public_path($file->file_path))) {
                File::delete(public_path($file->file_path));  // Remove file from server
            }
            $file->delete();  // Remove file record from database
        }

        foreach ($drawingDetail->reportFiles as $file) {
            if (File::exists(public_path($file->file_path))) {
                File::delete(public_path($file->file_path));
            }
            $file->delete();
        }

        $drawingDetail->delete();
        return response()->json(['success' => true, 'message' => 'Drawing deleted successfully']);
    }
}
