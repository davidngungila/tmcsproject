<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->paginate(10);
        $allEvents = Event::all(); // For calendar
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('event_date', '>', now())->count();
        $pastEvents = Event::where('event_date', '<', now())->orWhere('status', 'completed')->count();
        $totalAttendees = EventAttendance::where('status', 'attended')->count();

        // Format events for FullCalendar
        $calendarEvents = $allEvents->map(function($event) {
            $color = $this->getEventStatusColor($event->status);
            return [
                'id' => $event->id,
                'title' => $event->event_name,
                'start' => $event->event_date->format('Y-m-d') . 'T' . $event->event_time->format('H:i:s'),
                'className' => 'bg-' . $color . '-500',
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
            'created_by' => Auth::id(),
        ];

        Event::create($eventData);

        return redirect()->route('events.index')->with('success', 'Event planned successfully');
    }

    public function show(Event $event)
    {
        $event->load('expenses', 'contributions.member');
        $totalExpenses = $event->expenses->sum('amount');
        $totalContributions = $event->contributions->sum('amount');
        $balance = $totalContributions - $totalExpenses;

        return view('events.show', compact('event', 'totalExpenses', 'totalContributions', 'balance'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
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

        $event->update([
            'event_name' => $validated['title'],
            'description' => $validated['description'],
            'venue' => $validated['location'],
            'event_date' => $startDateTime->format('Y-m-d'),
            'event_time' => $startDateTime->format('H:i:s'),
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }

    public function attendance()
    {
        $events = Event::with('attendance.member')->orderBy('event_date', 'desc')->get();
        $attendances = EventAttendance::with('event', 'member')->latest()->paginate(20);
        
        $totalAttendances = EventAttendance::count();
        $attendedCount = EventAttendance::where('status', 'attended')->count();
        $absentCount = EventAttendance::where('status', 'absent')->count();
        $pendingCount = EventAttendance::where('status', 'pending')->count();

        return view('events.attendance', compact(
            'events',
            'attendances',
            'totalAttendances',
            'attendedCount',
            'absentCount',
            'pendingCount'
        ));
    }

    public function updateAttendance(Request $request, EventAttendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,attended,absent'
        ]);

        $attendance->update([
            'status' => $validated['status'],
            'checked_in_at' => $validated['status'] == 'attended' ? now() : null,
            'checked_in_by' => $validated['status'] == 'attended' ? Auth::id() : null
        ]);

        return redirect()->route('events.attendance')->with('success', 'Attendance updated successfully');
    }

    private function getEventStatusColor($status)
    {
        $colors = [
            'upcoming' => 'blue',
            'ongoing' => 'green',
            'completed' => 'amber',
            'cancelled' => 'red'
        ];
        return $colors[$status] ?? 'blue';
    }
}
