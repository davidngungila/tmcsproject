<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MessagingService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContributionReceiptMailable;
use App\Mail\GenericMailable;

class EventController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

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
        $pendingCount = EventAttendance::where('status', 'registered')->count();

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
            'status' => 'required|in:registered,attended,absent'
        ]);

        $attendance->update([
            'status' => $validated['status'],
            'checked_in_at' => $validated['status'] == 'attended' ? now() : null,
            'checked_in_by' => $validated['status'] == 'attended' ? Auth::id() : null
        ]);

        return redirect()->route('events.attendance')->with('success', 'Attendance updated successfully');
    }

    public function storeAttendance(Request $request, Event $event)
    {
        $member = Auth::user()->member;
        
        if (!$member) {
            return back()->with('error', 'Member profile not found.');
        }

        // Check if already registered for this event
        $existingAttendance = EventAttendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingAttendance) {
            return back()->with('info', 'You have already registered for this event.');
        }

        EventAttendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Successfully registered for the event!');
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
            $expense = \App\Models\Expense::create($validated);
            
            // Send SMS and email notifications for event expense
            $this->sendEventExpenseNotifications($expense, $event);
            
            return response()->json(['success' => true, 'message' => 'Expense recorded successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record expense: ' . $e->getMessage()]);
        }
    }

    protected function sendEventExpenseNotifications($expense, $event)
    {
        $user = Auth::user();
        if (!$user) return;

        $amount = number_format($expense->amount, 0);
        $voucher = $expense->voucher_number;
        
        // Send SMS notification
        if ($user->phone) {
            try {
                $smsMessage = "Dear {$user->name}, your expense request for TZS {$amount} for event '{$event->event_name}' has been recorded. Voucher: {$voucher}. Status: Pending.";
                $this->messagingService->sendSms($user->phone, $smsMessage);
            } catch (\Exception $e) {
                \Log::error("Failed to send event expense SMS: " . $e->getMessage());
            }
        }

        // Send email notification
        if ($user->email) {
            try {
                $subject = "Event Expense Recorded: {$voucher}";
                $emailContent = "
                    <h2>Event Expense Recorded</h2>
                    <p>Dear {$user->name},</p>
                    <p>Your expense request for event <strong>{$event->event_name}</strong> has been recorded successfully.</p>
                    <div style='padding: 20px; background: #f9f9f9; border-radius: 10px; margin: 20px 0;'>
                        <p><strong>Voucher No:</strong> {$voucher}</p>
                        <p><strong>Category:</strong> {$expense->category}</p>
                        <p><strong>Amount:</strong> TZS {$amount}</p>
                        <p><strong>Event:</strong> {$event->event_name}</p>
                        <p><strong>Status:</strong> Pending</p>
                    </div>
                    <p>Thank you for using the TMCS Smart System.</p>
                ";
                Mail::to($user->email)->send(new GenericMailable($subject, $emailContent));
            } catch (\Exception $e) {
                \Log::error("Failed to send event expense email: " . $e->getMessage());
            }
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
            $contribution = \App\Models\Contribution::create($contributionData);
            
            // Send SMS and email notifications for event contribution
            $this->sendEventContributionNotifications($contribution, $event);
            
            return response()->json(['success' => true, 'message' => 'Contribution recorded successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record contribution: ' . $e->getMessage()]);
        }
    }

    protected function sendEventContributionNotifications($contribution, $event)
    {
        $member = $contribution->member;
        if (!$member) return;

        $amount = number_format($contribution->amount, 0);
        $type = ucfirst(str_replace('_', ' ', $contribution->contribution_type));
        $receipt = $contribution->receipt_number;
        
        // Send SMS notification to member
        if ($member->phone) {
            try {
                $smsMessage = "Dear {$member->full_name}, thank you for your contribution of TZS {$amount} for {$type} at event '{$event->event_name}'. Receipt: {$receipt}. God bless you!";
                $this->messagingService->sendSms($member->phone, $smsMessage);
            } catch (\Exception $e) {
                \Log::error("Failed to send event contribution SMS: " . $e->getMessage());
            }
        }

        // Send email notification to member
        if ($member->email) {
            try {
                Mail::to($member->email)->send(new ContributionReceiptMailable($contribution));
            } catch (\Exception $e) {
                \Log::error("Failed to send event contribution email: " . $e->getMessage());
            }
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
