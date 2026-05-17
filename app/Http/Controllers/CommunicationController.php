<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Member;
use App\Models\Group;
use App\Models\ApiConfig;
use App\Services\MessagingService;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessCommunicationJob;

class CommunicationController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    public function index()
    {
        $communications = Communication::latest()->paginate(10);
        $totalCommunications = Communication::count();
        $sentCommunications = Communication::where('status', 'Sent')->count();
        $failedCommunications = Communication::where('status', 'Failed')->count();
        $pendingCommunications = Communication::where('status', 'Pending')->count();
        
        // Add specific type counts for the stat cards
        $sentSMS = Communication::where('status', 'Sent')->where('type', 'SMS')->count();
        $sentEmails = Communication::where('status', 'Sent')->where('type', 'Email')->count();
        
        return view('communications.index', compact(
            'communications', 
            'totalCommunications', 
            'sentCommunications', 
            'failedCommunications', 
            'pendingCommunications',
            'sentSMS',
            'sentEmails'
        ));
    }

    public function create()
    {
        $groups = Group::all();
        $members = Member::all();
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $templates = MessageTemplate::where('is_active', true)->get();
        return view('communications.create', compact('groups', 'members', 'activeGateways', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string', // SMS, Email, WhatsApp
            'recipient_type' => 'required|string', // All, Group, Individual
            'group_id' => 'required_if:recipient_type,Group|exists:groups,id',
            'member_id' => 'required_if:recipient_type,Individual|exists:members,id',
        ]);

        $recipients = [];
        $emailRecipients = [];

        if ($validated['recipient_type'] === 'All') {
            $recipients = Member::whereNotNull('phone')->pluck('phone')->toArray();
            $emailRecipients = Member::whereNotNull('email')->pluck('email')->toArray();
        } elseif ($validated['recipient_type'] === 'Group') {
            $group = Group::find($request->group_id);
            $recipients = $group->members()->whereNotNull('phone')->pluck('phone')->toArray();
            $emailRecipients = $group->members()->whereNotNull('email')->pluck('email')->toArray();
        } elseif ($validated['recipient_type'] === 'Individual') {
            $member = Member::find($request->member_id);
            $recipients = [$member->phone];
            $emailRecipients = [$member->email];
        }

        $recipients = array_filter($recipients); // Remove nulls
        $emailRecipients = array_filter($emailRecipients); // Remove nulls

        if ($validated['type'] === 'SMS' && empty($recipients)) {
            return back()->with('error', 'No valid phone numbers found for the selected recipients.');
        }

        if ($validated['type'] === 'Email' && empty($emailRecipients)) {
            return back()->with('error', 'No valid email addresses found for the selected recipients.');
        }

        $validated['sent_by'] = Auth::id();
        $validated['status'] = 'Pending';
        $validated['sent_at'] = now();

        $communication = Communication::create($validated);

        // Dispatch background job for processing
        $finalRecipients = $validated['type'] === 'Email' ? $emailRecipients : $recipients;
        ProcessCommunicationJob::dispatch($communication, $finalRecipients);

        return redirect()->route('communications.index')->with('success', 'Message queued for sending');
    }

    public function announcements()
    {
        $announcements = Communication::where('type', 'Announcement')->latest()->paginate(10);
        return view('communications.announcements', compact('announcements'));
    }
}
