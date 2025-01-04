<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Auth;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paid = Invoice::with("invoiceitem")->where("id_company", Auth::user()->id_company)->where("payment_status","paid")->get();
        $unpaid = Invoice::with("invoiceitem")->where("id_company", Auth::user()->id_company)->where("payment_status","unpaid")->get();
        return view('invoice',compact("paid","unpaid"));
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
        //
    }
    public function update(Request $request,$id)
    {
    }
    /**
     * Update the specified resource in storage.
     */
    public function updateevidence(Request $request)
    {
        // Find the invoice by the provided invoice number
        $invoice = Invoice::where("invoice_number", $request->modalInvoiceinput)->first();
    
        if (!$invoice) {
            // If the invoice is not found, log and return an error
            Log::error('Invoice not found for invoice_number: ' . $request->modalInvoiceinput);
            return redirect()->route('invoice.index')->with('error', 'Invoice not found');
        }
    
        Log::info('Invoice Retrieved:', ['invoice' => $invoice]);
    
        // Handle the file upload if a new file is provided
        if ($request->hasFile('evidence')) {
            try {
                // Delete the old file if it exists
                if ($invoice->evidence && file_exists(public_path('evidence/' . $invoice->evidence))) {
                    unlink(public_path('evidence/' . $invoice->evidence));  // Menghapus file lama
                }
    
                // Simpan file di direktori public/evidence
                $file = $request->file('evidence');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('evidence'), $fileName);
    
                // Update field evidence dengan path file
                $invoice->evidence = 'evidence/' . $fileName;  // Menggunakan path relatif
    
                Log::info('Evidence file stored at:', ['path' => $invoice->evidence]); // Log path file
            } catch (\Exception $e) {
                Log::error('Error uploading evidence: ' . $e->getMessage());
                return redirect()->route('invoice.index')->with('error', 'Error uploading the evidence file.');
            }
        }
    
        $invoice->user_comment = $request->comment;  // Update this as needed
        $invoice->payment_status = "validation";
        // Attempt to save the updated invoice
        try {
            $invoice->save();
        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage());
            return redirect()->route('invoice.index')->with('error', 'There was an error processing the request.');
        }
    
        Log::info('Invoice updated:', ['invoice' => $invoice]);
    
        // Return success response
        return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully.');
    }
    
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generatePdf($id)
    {
        $u = Invoice::with("invoiceitem")->where("invoice_number", $id)->first();

        $logo = base64_encode(file_get_contents(public_path('assets/logo/logo.png')));
    
        $pdf = PDF::loadView('invoice_pdf', compact('u', 'logo'), [
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]);
    
        return $pdf->download('Invoice-' . $u->invoice_number . '.pdf');
    }
    

}
