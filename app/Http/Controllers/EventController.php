<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('event_date', '>', now())->count();
        $pastEvents = Event::where('event_date', '<', now())->orWhere('status', 'completed')->count();
        $totalAttendees = \App\Models\EventAttendance::where('status', 'attended')->count();
        return view('events.index', compact('events', 'totalEvents', 'upcomingEvents', 'pastEvents', 'totalAttendees'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_type' => 'nullable|string',
        ]);

        $startDateTime = new \DateTime($validated['start_date']);

        $eventData = [
            'event_name' => $validated['title'],
            'description' => $validated['description'],
            'venue' => $validated['location'],
            'event_date' => $startDateTime->format('Y-m-d'),
            'event_time' => $startDateTime->format('H:i:s'),
            'status' => 'upcoming',
            'created_by' => auth()->id(),
        ];

        Event::create($eventData);

        return redirect()->route('events.index')->with('success', 'Event planned successfully');
    }

    public function attendance()
    {
        return view('events.attendance');
    }
}
