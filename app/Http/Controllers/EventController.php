<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->paginate(10);
        $allEvents = Event::all(); // For calendar
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('event_date', '>', now())->count();
        $pastEvents = Event::where('event_date', '<', now())->orWhere('status', 'completed')->count();
        $totalAttendees = \App\Models\EventAttendance::where('status', 'attended')->count();

        // Format events for FullCalendar
        $calendarEvents = $allEvents->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->event_name,
                'start' => $event->event_date->format('Y-m-d') . 'T' . $event->event_time->format('H:i:s'),
                'className' => 'bg-' . getEventStatusColor($event->status) . '-500',
                'description' => $event->description,
                'venue' => $event->venue,
                'url' => route('events.show', $event->id)
            ];
        });

        return view('events.index', compact(
            'events', 
            'totalEvents', 
            'upcomingEvents', 
            'pastEvents', 
            'totalAttendees',
            'calendarEvents'
        ));
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

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }

    public function attendance()
    {
        return view('events.attendance');
    }
}
