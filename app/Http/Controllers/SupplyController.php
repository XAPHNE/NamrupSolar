<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplies = Supply::latest()->get();
        return view('supply', compact('supplies'));
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
            'particulars' => 'required|string',
            'status' => 'required|in:Ordered,In-Transit,Delivered',
            'ordered_on' => 'required|date',
            'delivered_on' => 'nullable|date'
        ]);

        $supply = Supply::create([
            'particulars' => $request->particulars,
            'status' => $request->status,
            'ordered_on' => $request->ordered_on,
            'delivered_on' => $request->delivered_on,
            'action_taken_by' => Auth::id(),
        ]);

        if ($supply) {
            return redirect()->back()->with('success', 'Supply created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create supply');
        }
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
        $request->validate([
            'particulars' => 'required|string',
            'status' => 'required|in:Ordered,In-Transit,Delivered',
            'ordered_on' => 'required|date',
            'delivered_on' => 'nullable|date'
        ]);

        $supply = Supply::findOrFail($id);
        $supply->update([
            'particulars' => $request->particulars,
            'status' => $request->status,
            'ordered_on' => $request->ordered_on,
            'delivered_on' => $request->delivered_on,
            'action_taken_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Supply updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supply = Supply::findOrFail($id);

        // Update the 'action_taken_by' field before deleting
        $supply->update([
            'action_taken_by' => Auth::id(),
        ]);

        // Proceed to delete the supply
        $supply->delete();

        return redirect()->back()->with('success', 'Supply deleted successfully');
    }
}
