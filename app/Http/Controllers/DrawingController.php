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
        $request->validate([
            'drawing_id' => 'required|exists:drawings,id',
            'drawing_details_no' => 'required|string',
            'drawing_details_name' => 'required|string',
            'isScopeDrawing' => 'sometimes|boolean',
            'submitted_at' => 'required|date',
            'drawing_file' => 'required|file|mimes:pdf,jpg,png',
            'report_file' => 'required|file|mimes:pdf,jpg,png',
        ]);

        $drawing = Drawing::findOrFail($request->drawing_id);

        $drawingDetail = DrawingDetail::create([
            'drawing_id' => $request->drawing_id,
            'drawing_details_no' => $request->drawing_details_no,
            'drawing_details_name' => $request->drawing_details_name,
            'isScopeDrawing' => $request->has('isScopeDrawing') ? 1 : 0,
            'submitted_at' => $request->submitted_at,
            'submitted_by' => Auth::id(),  // Automatically capture logged-in user
        ]);

        // Handle Drawing file upload
        if ($request->hasFile('drawing_file')) {
            $drawingFileName = time() . '_' . $request->file('drawing_file')->getClientOriginalName();
            $request->file('drawing_file')->move(public_path('drawing/' . $drawing->name), $drawingFileName);
            DrawingFile::create([
                'drawing_detail_id' => $drawingDetail->id,
                'file_path' => 'drawing/' . $drawing->name . '/' . $drawingFileName,
            ]);
        }

        // Handle Report file upload
        if ($request->hasFile('report_file')) {
            $reportFileName = time() . '_' . $request->file('report_file')->getClientOriginalName();
            $request->file('report_file')->move(public_path('report/' . $drawing->name), $reportFileName);
            ReportFile::create([
                'drawing_detail_id' => $drawingDetail->id,
                'file_path' => 'report/' . $drawing->name . '/' . $reportFileName,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Drawing created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Drawing $drawing)
    {
        // Fetch the drawing details along with submitter, approver, comments, drawing files, and report files
        $drawingDetails = DrawingDetail::where('drawing_id', $drawing->id)
            ->with(['submitter', 'approver', 'comments.commenter', 'drawingFiles', 'reportFiles'])
            ->get();
    
        // Expand each drawing detail to include rows for each comment, file, and report
        $details = $drawingDetails->flatMap(function($detail) {
            // Number of maximum rows needed to cover comments, files, and reports
            $maxRows = max($detail->comments->count(), $detail->drawingFiles->count(), $detail->reportFiles->count());
    
            $rows = [];
            for ($i = 0; $i < $maxRows; $i++) {
                $comment = $detail->comments[$i] ?? null;  // Check if comment exists
                $drawingFile = $detail->drawingFiles[$i] ?? null;  // Check if drawing file exists
                $reportFile = $detail->reportFiles[$i] ?? null;  // Check if report file exists
    
                $rows[] = [
                    'id' => $i === 0 ? $detail->id : '',  // Show only on the first row
                    'drawing_details_no' => $i === 0 ? $detail->drawing_details_no : '',
                    'drawing_details_name' => $i === 0 ? $detail->drawing_details_name : '',
                    'isScopeDrawing' => $i === 0 ? ($detail->isScopeDrawing ? 'Yes' : 'No') : '',
                    'submitted_at' => $i === 0 ? $detail->submitted_at : '',
                    'submitted_by' => $i === 0 ? optional($detail->submitter)->name : '',
                    // Safeguard for nullable comment fields
                    'comment' => optional($comment)->comment_body ?? '',  
                    'commented_at' => optional($comment)->commented_at ?? '',
                    'commented_by' => optional(optional($comment)->commenter)->name ?? '',
                    'resubmitted_at' => optional($comment)->resubmitted_at ?? '',
                    'approved_at' => $i === 0 ? $detail->approved_at ?? '' : '',
                    'approved_by' => $i === 0 ? optional($detail->approver)->name ?? '' : '',
                    // Safeguard for nullable drawing and report files
                    'drawing_file_url' => $drawingFile ? asset($drawingFile->file_path) : '',
                    'report_file_url' => $reportFile ? asset($reportFile->file_path) : ''
                ];
            }
    
            return $rows;  // Return the expanded rows
        });
    
        return response()->json([
            'details' => $details,
            'chartData' => [
                'scopeDrawings' => $drawingDetails->where('isScopeDrawing', 1)->count(),
                'submittedDrawings' => $drawingDetails->whereNotNull('submitted_at')->count(),
                'approvedDrawings' => $drawingDetails->whereNotNull('approved_at')->count(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate incoming request
        $request->validate([
            'drawing_details_no' => 'required|string',
            'drawing_details_name' => 'required|string',
            'isScopeDrawing' => 'sometimes|boolean',
            'submitted_at' => 'required|date',
            'drawing_file' => 'nullable|file|mimes:pdf,jpg,png',
            'report_file' => 'nullable|file|mimes:pdf,jpg,png',
        ]);

        // Find the drawing detail
        $drawingDetail = DrawingDetail::findOrFail($id);
        
        // Update fields
        $drawingDetail->update([
            'drawing_details_no' => $request->drawing_details_no,
            'drawing_details_name' => $request->drawing_details_name,
            'isScopeDrawing' => $request->has('isScopeDrawing'),
            'submitted_at' => $request->submitted_at,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now()
        ]);

        // Handle Drawing file upload (if new file provided)
        if ($request->hasFile('drawing_file')) {
            $drawingFileName = time() . '_' . $request->file('drawing_file')->getClientOriginalName();
            $drawing = Drawing::findOrFail($drawingDetail->drawing_id);  // Find drawing category
            $request->file('drawing_file')->move(public_path('drawing/' . $drawing->name), $drawingFileName);
            
            // Save to DrawingFile model
            DrawingFile::create([
                'drawing_detail_id' => $drawingDetail->id,
                'file_path' => 'drawing/' . $drawing->name . '/' . $drawingFileName,
            ]);
        }

        // Handle Report file upload (if new file provided)
        if ($request->hasFile('report_file')) {
            $reportFileName = time() . '_' . $request->file('report_file')->getClientOriginalName();
            $drawing = Drawing::findOrFail($drawingDetail->drawing_id);
            $request->file('report_file')->move(public_path('report/' . $drawing->name), $reportFileName);
            
            // Save to ReportFile model
            ReportFile::create([
                'drawing_detail_id' => $drawingDetail->id,
                'file_path' => 'report/' . $drawing->name . '/' . $reportFileName,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Drawing updated successfully']);
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
