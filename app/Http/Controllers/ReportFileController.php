<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\DrawingDetail;
use App\Models\ReportFile;
use Illuminate\Http\Request;

class ReportFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validate the request
        $request->validate([
            'drawingDetailId' => 'required|exists:drawing_details,id',
            'drawing_file' => 'required|file|mimes:pdf,jpg,png|max:2048', // Ensure file is present and of correct type
        ]);
    
        // Check if the file was uploaded
        if ($request->hasFile('drawing_file')) {
            // Handle the file upload
            $reportFileName = time() . '_' . $request->file('drawing_file')->getClientOriginalName();
            
            // Get the drawing detail and drawing for folder structure
            $drawingDetail = DrawingDetail::findOrFail($request->drawingDetailId);
            $drawing = Drawing::findOrFail($drawingDetail->drawing_id); // Get related drawing
            
            // Define the file path
            $filePath = 'report/' . $drawing->name . '/' . $reportFileName;
    
            // Move the file to the public directory
            $request->file('drawing_file')->move(public_path('report/' . $drawing->name), $reportFileName);
    
            // Create a new DrawingFile entry
            $reportfile = new ReportFile();
            $reportfile->drawing_detail_id = $request->drawingDetailId;  // Link to the drawing detail
            $reportfile->file_path = $filePath;
            $reportfile->save();  // Save the record in the database
    
            return redirect()->back()->with('success', 'Updated drawing uploaded successfully!');
        }
    
        return redirect()->back()->withErrors('No file was uploaded');
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
