<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ExternalEvent;
use Auth;
class EventController extends Controller
{
    public function store(Request $request)
    {
        $event = Event::create([
            'id_company' => Auth::user()->id_company,
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'background_color' => $request->background_color ?? '#3788d8',
            'border_color' => $request->border_color ?? '#3788d8',
            'text_color' => $request->text_color ?? '#ffffff',
            'holiday' => $request->holiday
        ]);

        return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
    }

    public function index()
    {
        // $events = Event::where('id_user', 1)->get();
        // $today = now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $events = Event::where("status","aktif")->where("id_company",Auth::user()->id_company)->get()->map(function ($event) {
            $event->end_time2 = \Carbon\Carbon::parse($event->end_time)->format('Y-m-d H:i:s');
            $event->end_time = \Carbon\Carbon::parse($event->end_time)->addDay()->format('Y-m-d');
            return $event;
        });
        return response()->json($events);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'tidak aktif';
        $event->save();

        return response()->json(['message' => 'Event status updated to inactive successfully']);
    }


}
