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

    public function storeExpense(Request $request, Event $event)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
        ]);

        $validated['voucher_number'] = 'EXP-' . date('Ymd') . '-' . str_pad(\App\Models\Expense::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['recorded_by'] = Auth::id();
        $validated['status'] = 'Pending';
        $validated['event_id'] = $event->id;

        try {
            \App\Models\Expense::create($validated);
            return response()->json(['success' => true, 'message' => 'Expense recorded successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record expense: ' . $e->getMessage()]);
        }
    }

    public function storeContribution(Request $request, Event $event)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'contribution_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'contribution_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Check if member already contributed to this event
        $existingContribution = \App\Models\Contribution::where('member_id', $validated['member_id'])
            ->where('event_id', $event->id)
            ->first();

        if ($existingContribution) {
            return response()->json([
                'success' => false, 
                'message' => 'This member has already contributed to this event'
            ]);
        }

        $receiptNumber = 'RCP-' . date('Y') . '-' . str_pad(\App\Models\Contribution::count() + 1, 4, '0', STR_PAD_LEFT);
        
        $contributionData = [
            'member_id' => $validated['member_id'],
            'contribution_type' => $validated['contribution_type'],
            'amount' => $validated['amount'],
            'contribution_date' => $validated['contribution_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'receipt_number' => $receiptNumber,
            'recorded_by' => Auth::id(),
            'is_verified' => $validated['payment_method'] === 'cash',
            'verified_at' => $validated['payment_method'] === 'cash' ? now() : null,
            'verified_by' => $validated['payment_method'] === 'cash' ? Auth::id() : null,
            'event_id' => $event->id,
        ];

        try {
            \App\Models\Contribution::create($contributionData);
            return response()->json(['success' => true, 'message' => 'Contribution recorded successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record contribution: ' . $e->getMessage()]);
        }
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
