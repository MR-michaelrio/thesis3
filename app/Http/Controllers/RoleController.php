<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Auth;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $admin = User::where("role","admin")->where("id_company",Auth::user()->id_company)->get();
        $employee = User::where("role","employee")->where("id_company",Auth::user()->id_company)->get();
        $supervisor = User::where("role","supervisor")->where("id_company",Auth::user()->id_company)->get();

        $company = Company::where('id_company', Auth::user()->id_company)->first();
        $otherPicExists = User::where('id_company', Auth::user()->id_company)
                      ->where('id_user', '!=', Auth::user()->id_user)
                      ->where('id_user', $company->pic)
                      ->exists();
        return view("settings.role-management", compact("admin","employee","supervisor","otherPicExists"));
    }

    public function roleadmin($id)
    {
        // Find the user by their ID
        $user = User::find($id);

        // Check if the user exists
        $user->role = 'admin';
        $user->save();
        return redirect()->route('role.index');
        
    }

    public function rolepic($id)
    {
        // Find the user by their ID
        $company = Company::find(Auth::user()->id_company);
        $company->pic = $id;
        $company->save();
        return redirect()->route('role.index');
        
    }


    public function roleemployee($id)
    {
        // Find the user by their ID
        $user = User::find($id);

        $user->role = 'employee';
        $user->save();
        return redirect()->route('role.index');
    }

    public function rolesupervisor($id)
    {
        // Find the user by their ID
        $user = User::find($id);

        $user->role = 'supervisor';
        $user->save();
        return redirect()->route('role.index');
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
