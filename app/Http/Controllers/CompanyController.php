<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('settings.company-profile', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'company_code' => 'required',
            'country' => 'required',
            'full_address' => 'required',
            'postal_code' => 'required',
            'company_email' => 'required|email',
            'company_phone' => 'required',
        ]);

        $company = new Company();
        $company->company_name = $request->company_name;
        $company->company_code = $request->company_code;
        $company->country = $request->country;
        $company->full_address = $request->full_address;
        $company->postal_code = $request->postal_code;
        $company->company_email = $request->company_email;
        $company->company_phone = $request->company_phone;

        // Handle logo file upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $company->logo = $logoPath;
        }

        $company->save();

        return redirect()->route('companies.company-profile', $company->id_company)->with('success', 'Company created successfully!');
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('company.company-profile', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required',
            'company_code' => 'required',
            'country' => 'required',
            'full_address' => 'required',
            'postal_code' => 'required',
            'company_email' => 'required|email',
            'company_phone' => 'required',
        ]);

        $company = Company::findOrFail($id);
        $company->company_name = $request->company_name;
        $company->company_code = $request->company_code;
        $company->country = $request->country;
        $company->full_address = $request->full_address;
        $company->postal_code = $request->postal_code;
        $company->company_email = $request->company_email;
        $company->company_phone = $request->company_phone;

        // Handle logo file upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $company->logo = $logoPath;
        }

        $company->save();

        return redirect()->route('companies.company-profile', $company->id_company)->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
