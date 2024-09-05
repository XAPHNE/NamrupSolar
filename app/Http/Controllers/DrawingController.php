<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\DrawingDetail;
use App\Models\DrawingFile;
use App\Models\ReportFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DrawingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DrawingDetail::with([
                'drawing', 
                'submitter', 
                'approver', 
                'drawingFiles', 
                'reportFiles', 
                'comments.commenter:id,name' // Load comments and commenter relationships
            ])->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('comment_body', function($row) {
                    return $row->comments->pluck('comment_body')->implode(', ') ?: 'N/A';
                })
                ->addColumn('commented_at', function($row) {
                    return $row->comments->pluck('commented_at')->implode(', ') ?: 'N/A';
                })
                ->addColumn('commented_by', function($row) {
                    return $row->comments->pluck('commenter.name')->implode(', ') ?: 'N/A';
                })
                ->addColumn('drawing_file_path', function($row) {
                    if ($row->drawingFiles->isNotEmpty()) {
                        return $row->drawingFiles->pluck('file_path')->map(function($file) {
                            return '<a href="' . asset($file) . '" target="_blank">Download/View</a>';
                        })->implode('<br>');
                    }
                    return 'N/A';
                })
                ->addColumn('report_file_path', function($row) {
                    if ($row->reportFiles->isNotEmpty()) {
                        return $row->reportFiles->pluck('file_path')->map(function($file) {
                            return '<a href="' . asset($file) . '" target="_blank">Download/View</a>';
                        })->implode('<br>');
                    }
                    return 'N/A';
                })
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-warning btn-sm editDrawing"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm deleteDrawing"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['comment_body', 'commented_at', 'drawing_file_path', 'report_file_path', 'action'])
                ->make(true);
        }
    
        $drawings = Drawing::all();
        return view('drawings', compact('drawings'));
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
    public function show(string $id)
    {
        //
    }
    
    
    
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        $drawingDetail->delete();
        return response()->json(['success' => true, 'message' => 'Drawing deleted successfully']);
    }
}
