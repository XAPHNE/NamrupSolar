<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

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
        ]);

        if ($invoice) {
            return redirect()->back()->with('success', 'Invoice created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create invoice');
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
