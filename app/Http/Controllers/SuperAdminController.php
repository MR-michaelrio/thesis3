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
        $invoicedata = Invoice::all();
        return view('superadmin.invoice', compact("invoiceamount","paidinvoice","unpaidinvoice","client","invoicedata"));
    }

    public function getInvoiceData(Request $request)
    {
        $clientId = $request->input('client_id');
        $invoiceAmount = Invoice::where('id_company', $clientId)->count();
        $paidInvoice = Invoice::where('id_company', $clientId)->where('payment_status', 'paid')->count();
        $unpaidInvoice = Invoice::where('id_company', $clientId)->where('payment_status', 'unpaid')->count();

        return response()->json([
            'invoiceAmount' => $invoiceAmount,
            'paidInvoice' => $paidInvoice,
            'unpaidInvoice' => $unpaidInvoice,
            'id_company' => $clientId
        ]);
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

        return redirect()->route('home')->with('success', 'Company created successfully!');
    }

}
