<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ExternalEvent;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'background_color' => 'nullable|string',
            'border_color' => 'nullable|string',
            'text_color' => 'nullable|string',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'background_color' => $request->background_color ?? '#3788d8',
            'border_color' => $request->border_color ?? '#3788d8',
            'text_color' => $request->text_color ?? '#ffffff',
        ]);

        return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
    }

    public function index($userId)
    {
        // $events = Event::where('id_user', 1)->get();
        // $today = now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $events = Event::where("status","aktif")->get()->map(function ($event) {
            $event->end_time = \Carbon\Carbon::parse($event->end_time)->addDay()->format('Y-m-d');
            return $event;
        });
        return response()->json($events);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Memastikan hanya pengguna yang memiliki event yang bisa mengubah statusnya
        // if ($event->user_id != auth()->id()) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // Ubah status menjadi 'tidak aktif' tanpa menghapus data dari database
        $event->status = 'tidak aktif';
        $event->save();

        return response()->json(['message' => 'Event status updated to inactive successfully']);
    }


}
