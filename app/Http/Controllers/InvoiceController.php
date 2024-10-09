<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices', compact('invoices'));
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
            'bill_no' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'raised_at' => 'required|date',
            'paid_at' => 'nullable|date',
        ]);
        if($request->hasFile('file_path')) {
            $invoiceFileName = time() . '_' . $request->file('file_path')->getClientOriginalName();
            $filePath = 'invoice/' . $invoiceFileName;
            $request->file('file_path')->move(public_path('invoice/'), $invoiceFileName);
        }

        $invoice = Invoice::create([
            'bill_no' => $request->bill_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'raised_at' => $request->raised_at,
            'file_path' => $filePath,
            'paid_at' => $request->paid_at,
            'action_taken_by' => Auth::id(),
        ]);

        if ($invoice) {
            return redirect()->back()->with('success', 'Invoice added successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to add invoice');
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
            'bill_no' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'raised_at' => 'required|date',
            'paid_at' => 'nullable|date',
            'file_path' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048', // Example validation for file
        ]);

        $invoice = Invoice::findOrFail($id);

        // Handle file update
        if ($request->hasFile('file_path')) {
            // Delete the old file if it exists
            if (File::exists(public_path($invoice->file_path))) {
                File::delete(public_path($invoice->file_path));
            }

            // Upload the new file
            $invoiceFileName = time() . '_' . $request->file('file_path')->getClientOriginalName();
            $filePath = 'invoice/' . $invoiceFileName;
            $request->file('file_path')->move(public_path('invoice/'), $invoiceFileName);
        } else {
            // If no new file is uploaded, retain the existing file path
            $filePath = $invoice->file_path;
        }

        // Update the invoice
        $invoice->update([
            'bill_no' => $request->bill_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'raised_at' => $request->raised_at,
            'file_path' => $filePath,
            'paid_at' => $request->paid_at,
            'action_taken_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Update the 'action_taken_by' field before deleting
        $invoice->update([
            'action_taken_by' => Auth::id(),
        ]);

        // Delete the associated file if it exists
        if (File::exists(public_path($invoice->file_path))) {
            File::delete(public_path($invoice->file_path));
        }

        // Proceed to delete the invoice
        $invoice->delete();

        return redirect()->back()->with('success', 'Invoice deleted successfully');
    }
}
