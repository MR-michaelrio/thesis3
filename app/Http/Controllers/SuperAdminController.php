<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    public function clientindex()
    {
        $company = Company::all();
        return view('superadmin.clientdata', compact('company'));
    }

    public function clientstatus(Request $request, $id)
    {
        $company = Company::find($id);
        $company->is_active = $request->status;
        $company->save();
        return response()->json(['message' => 'Company Status Successfully.']);
    }

    public function clientcreate()
    {
        return view('superadmin.clientadd');
    }

    public function clientadd(Request $request)
    {
        // Buat user baru
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => "admin",
            'is_active' => "1"
        ]);
    
        // Buat employee baru
        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'id_user' => $user->id_user,
            'status' => "active"
        ]);
    
        // Buat instance Company
        $company = new Company([
            'company_name' => $request->company_name,
            'company_code' => $request->company_code,
            'country' => $request->country,
            'full_address' => $request->full_address,
            'postal_code' => $request->postal_code,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'pic' => $user->id_user,
            'is_active' => 1
        ]);
    
        // Handle logo file upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('img'), $logoName);
            $company->logo = 'img/' . $logoName;
        }
    
        // Simpan company
        $company->save();
    
        // Update user dengan ID company
        $employee->id_company = $company->id_company;
        $employee->save();
    
        return redirect()->route('clientindex')->with('success', 'Company created successfully!');
    }
    
    public function invoiceindex()
    {
        $invoiceamount = Invoice::all()->count();
        $paidinvoice = Invoice::where("payment_status","paid")->count();
        $unpaidinvoice = Invoice::where("payment_status","unpaid")->count();
        $client = Company::all();
        $history = Invoice::where('payment_status', 'paid')->get();
        return view('superadmin.invoice', compact("invoiceamount","paidinvoice","unpaidinvoice","client","history"));
    }

    public function getInvoiceData(Request $request)
    {
        $clientId = $request->input('client_id');
        Log::debug('Received client_id:', ['client_id' => $clientId]);


        $invoiceAmount = Invoice::where('id_company', $clientId)->count();
        $paidInvoice = Invoice::where('id_company', $clientId)->where('payment_status', 'paid')->count();
        $unpaidInvoice = Invoice::where('id_company', $clientId)->where('payment_status', 'unpaid')->count();
        $invoicedata = Invoice::where('id_company', $clientId)->get();
        $client = Company::all();
        $history = Invoice::where('id_company', $clientId)->where('payment_status', 'paid')->get();

        if ($history->isEmpty()) {
            $history = [];
        }

        // Pass the full URL for the evidence field
        foreach ($invoicedata as $invoice) {
            $sub_total = $invoice->invoiceitem->sub_total; // Total dari relasi invoiceitems
            $currency = $invoice->invoiceitem->currency ?? 'N/A'; // Ambil currency dari item pertama atau default ke 'N/A'

            // Assign to invoice data for use in frontend
            $invoice->sub_total = $sub_total;
            $invoice->currency = $currency;
    
            $invoice->evidence_url = $invoice->evidence ? asset($invoice->evidence) : null;
        }

        if ($request->ajax()) {
            return response()->json([
                'invoiceAmount' => $invoiceAmount,
                'paidInvoice' => $paidInvoice,
                'unpaidInvoice' => $unpaidInvoice,
                'id_company' => $clientId,
                'invoicedata' => $invoicedata,
                'history' => $history
            ]);
        }

        return view('superadmin.invoice', compact('client','invoiceAmount', 'paidInvoice', 'unpaidInvoice', 'invoicedata', 'history'));
    }

    public function invoicecreate(Request $request)
    {
        $invoiceDate = Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d');
        $dueDays = $request->due_days;
        $periodStart = Carbon::createFromFormat('d/m/Y', $request->period_start)->format('Y-m-d');
        $periodEnd = Carbon::createFromFormat('d/m/Y', $request->period_end)->format('Y-m-d');
        // Menghitung tanggal jatuh tempo berdasarkan due_days
        $paymentDue = Carbon::createFromFormat('Y-m-d', $invoiceDate)->addDays((int) $dueDays);
    
        $total = $request->total;
        $tax = $request->tax;

        do {
            $invoiceNumber = 'INV-' . Str::upper(Str::random(8));
            $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();
        } while ($exists);

        // Buat user baru
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'payment_due' => $paymentDue,
            'id_company' => $request->id_company,
            'total' => $total,
            'payment_status' => "unpaid",
            'payed_amount' => 0,
            'period_end' => $periodEnd,
            'period_start' => $periodStart,
            'tax' => $tax
        ]);

        InvoiceItem::create([
            'id_invoice' => $invoice->id_invoice_hdrs,
            'item' => "Face Recognition Attendance System",
            'currency' => "IDR",
            'price' => $request->price,
            'discount' => $request->discount,
            'sub_total' => $request->subtotalinput,
            'id_company' => $request->id_company,
        ]);

        return redirect()->route('home')->with('success', 'Invoice created successfully!');
    }

    public function invoiceupdate(Request $request){
        $invoice = Invoice::find($request->id_invoice);
        $invoice->payment_status = "paid";
        $invoice->payed_amount = $request->nominal;
        $invoice->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->format('Y-m-d');
        $invoice->save();

        return redirect()->route('home')->with('success', 'Invoice updated successfully!');
    }

    public function invoiceupdateunpaid(Request $request)
    {
        // Ensure the invoice exists before updating
        $invoice = Invoice::find($request->id_invoice);
        $invoice->payment_status = 'unpaid';
        $invoice->save();
        return response()->json(['message' => 'Invoice updated successfully!', 'id_company' => $invoice->id_company]);
    }

}
