<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $user = User::where("id_user", Auth::id())->first();
        $shifts = Shift::where("id_company",$user->id_company)->get();
        return view('settings.shift-data', compact('shifts'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'shift_name' => 'required|string|max:255',
        //     'clock_in' => 'required',
        //     'clock_out' => 'required',
        //     'shift_description' => 'nullable|string',
        //     'id_company' => 'required|integer',
        // ]);
        $user = User::where("id_user",Auth::id())->first();
        $data = array_merge($request->all(), ['id_company' => $user->id_company]);
        Shift::create($data);
        return redirect()->route('shift.index')->with('success', 'Shift created successfully.');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return view('shift.edit', compact('shift'));
    }

    public function update(Request $request,$id)
    {
        // $request->validate([
        //     'shift_name' => 'required|string|max:255',
        //     'clock_in' => 'required',
        //     'clock_out' => 'required',
        //     'shift_description' => 'nullable|string',
        //     'id_company' => 'required|integer',
        // ]);
        $shift = Shift::findOrFail($id);
        $data = $request->all();
        $shift->update($data);

        return redirect()->route('shift.index')->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shift.index')->with('success', 'Shift deleted successfully.');
    }
}
