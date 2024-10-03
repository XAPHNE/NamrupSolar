<?php

namespace App\Http\Controllers;

use App\Models\MajorActivity;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Set your target datetime
        $targetDate = '2025-03-31 23:59:59'; // Target datetime variable

        // Fetch all MajorActivity records
        $majorActivities = MajorActivity::all();

        // Calculate total progress by averaging individual progress for each activity
        $totalProgress = 0;
        $activityCount = $majorActivities->count();

        if ($activityCount > 0) {
            foreach ($majorActivities as $activity) {
                if ($activity->scope > 0) {
                    $individualProgress = ($activity->completed / $activity->scope) * 100;
                    $totalProgress += $individualProgress;
                }
            }
            // Average total progress
            $totalProgress = $totalProgress / $activityCount;
        }

        // Pass all necessary variables to the view
        return view('home', compact('targetDate', 'majorActivities', 'totalProgress'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the MajorActivity by ID
        $majorActivity = MajorActivity::findOrFail($id);

        // Pass the MajorActivity to the view
        return view('update-major-activities', compact('majorActivity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the MajorActivity by ID
        $majorActivity = MajorActivity::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'scope' => 'required|numeric',
            'completed' => 'required|numeric',
        ]);

        // Try to update the major activity
        try {
            // Update the major activity with the new data
            $majorActivity->update($validatedData);

            // If update is successful, flash a success message to the session

            // Redirect back to the home page with a success message
            return redirect()->route('home.index')->with('success', 'Major activity updated successfully!');
        } catch (Exception $e) {
            // If there's an error, flash an error message to the session
            return redirect()->route('home.index')->with('error', 'Failed to update major activity.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
