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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (request()->ajax()) {
            // Fetch drawing details for the specified drawing ID
            $details = DrawingDetail::where('drawing_id', $id)
                        ->get(['drawing_details_name', 'drawing_details_no', 'isScopeDrawing', 'isSubmitted', 'isApproved']);
            
            // Count the number of true values for each field
            $scopeCount = $details->where('isScopeDrawing', true)->count();
            $submittedCount = $details->where('isSubmitted', true)->count();
            $approvedCount = $details->where('isApproved', true)->count();
    
            return response()->json([
                'scopeCount' => $scopeCount,
                'submittedCount' => $submittedCount,
                'approvedCount' => $approvedCount,
                'details' => $details, // Include the detailed data for the DataTable
            ]);
        }
    
        // If it's not an AJAX request, handle the normal show logic here
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
