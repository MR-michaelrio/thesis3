<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $user = User::where("id_user",Auth::id())->first();
        $companies = Company::with("Pic")->where("id_company",$user->id_company)->first();
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
            if ($company->logo && file_exists(public_path('img/' . $company->logo))) {
                unlink(public_path('img/' . $company->logo));
            }
    
            // Store the new logo in the public/img folder
            $logo = $request->file('logo');
            $logoPath = 'img/' . $logo->getClientOriginalName();
            $logo->move(public_path('img'), $logoPath); // Move file to public/img
            $company->logo = $logoPath; // Store the file name in the database
        }

        $company->save();

        return redirect()->route('companies.company-profile', $company->id_company)->with('success', 'Company created successfully!');
    }

    public function show($id)
    {
        $companies = Company::with("Pic")->where("id_company",$id)->first();
        return view('settings.company-profile', compact('companies'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        
        // Update company details
        $company->company_name = $request->company_name;
        $company->company_code = $request->company_code;
        $company->country = $request->country;
        $company->full_address = $request->full_address;
        $company->postal_code = $request->postal_code;
        $company->company_email = $request->company_email;
        $company->company_phone = $request->company_phone;

        // Handle logo file upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists in public/img
            if ($company->logo && file_exists(public_path('img/' . $company->logo))) {
                unlink(public_path('img/' . $company->logo));
            }

            // Get the file from the request
            $logo = $request->file('logo');
            
            // Generate the file name (you can add a timestamp to avoid conflicts)
            $logoName = time() . '-' . $logo->getClientOriginalName();
            
            // Move the file to the public/img directory
            $logo->move(public_path('img'), $logoName);

            // Update the company's logo with the new file name
            $company->logo = $logoName; // Store the file name
        }

        // Save the company details
        $company->save();
        if(Auth::user()->role != "superadmin"){
            return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
        }else{
            return redirect()->route('clientindex')->with('success', 'Company updated successfully!');
        }
    }


    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
