<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\DrawingDetail;
use Illuminate\Http\Request;

class DrawingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        ]);
    
        $drawing = Drawing::findOrFail($request->drawing_id);
        $fileName = $request->file('drawing_file')->getClientOriginalName();
        $filePath = 'drawing/' . $drawing->name . '/' . $fileName;
    
        $request->file('drawing_file')->move(public_path('drawing/' . $drawing->name), $fileName);
    
        DrawingDetail::create([
            'drawing_id' => $request->drawing_id,
            'drawing_details_no' => $request->drawing_details_no,
            'drawing_details_name' => $request->drawing_details_name,
            'isScopeDrawing' => $request->has('isScopeDrawing') ? 1 : 0,
            'submitted_at' => $request->submitted_at,
            'filepath' => $filePath,
        ]);
    
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (request()->ajax()) {
            $details = DrawingDetail::with([
                'drawing',
                'submitter:id,name',
                'approver:id,name',
                'comments.commenter:id,name'
            ])
            ->where('drawing_id', $id)
            ->get()
            ->map(function ($detail) {
                return [
                    'drawing_details_no' => $detail->drawing_details_no,
                    'drawing_details_name' => $detail->drawing_details_name,
                    'submitted_at' => $detail->submitted_at ?? 'N/A',
                    'submitted_by' => $detail->submitter->name ?? 'N/A',
                    'comment_body' => $detail->comments->pluck('comment_body')->implode(', ') ?: 'N/A',
                    'commented_at' => $detail->comments->pluck('commented_at')->implode(', ') ?: 'N/A',
                    'commented_by' => $detail->comments->pluck('commenter.name')->implode(', ') ?: 'N/A',
                    'resubmitted_at' => $detail->comments->pluck('resubmitted_at')->implode(', ') ?: 'N/A',
                    'approved_at' => $detail->approved_at ?? 'N/A',
                    'approved_by' => $detail->approver->name ?? 'N/A',
                    'isScopeDrawing' => $detail->isScopeDrawing ? 'Yes' : 'No'
                ];
            });
    
            $scopeCount = DrawingDetail::where('drawing_id', $id)->where('isScopeDrawing', true)->count();
            $submittedCount = DrawingDetail::where('drawing_id', $id)->whereNotNull('submitted_at')->count();
            $approvedCount = DrawingDetail::where('drawing_id', $id)->whereNotNull('approved_at')->count();
    
            return response()->json([
                'scopeCount' => $scopeCount,
                'submittedCount' => $submittedCount,
                'approvedCount' => $approvedCount,
                'details' => $details,
            ]);
        }
    
        $drawing = Drawing::findOrFail($id);
        return view('drawings.show', compact('drawing'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
