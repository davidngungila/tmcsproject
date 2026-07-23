<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Member;
use App\Models\Group;
use App\Models\MemberCategory;
use App\Models\Program;
use App\Models\ApiConfig;
use App\Services\MessagingService;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessCommunicationJob;
use App\Models\Contribution;

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
        
        return view('communications.index', compact(
            'communications', 
            'totalCommunications', 
            'sentCommunications', 
            'failedCommunications', 
            'pendingCommunications'
        ));
    }

    public function create()
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        $members = Member::all();
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $templates = MessageTemplate::where('is_active', true)->get();
        return view('communications.create', compact('groups', 'members', 'activeGateways', 'templates', 'categories', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:SMS', // Only SMS allowed
            'recipient_type' => 'required|string', // All, Group, Individual, Advanced
            'group_id' => 'required_if:recipient_type,Group|exists:groups,id',
            'member_id' => 'required_if:recipient_type,Individual|exists:members,id',
            'criteria' => 'array',
            'criteria.category_ids' => 'array',
            'criteria.program_ids' => 'array',
            'criteria.community_ids' => 'array',
            'criteria.contribution_min' => 'numeric|min:0',
            'criteria.contribution_max' => 'numeric|min:0',
            'criteria.is_active' => 'nullable|string',
            'send_option' => 'required|in:now,schedule',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $query = Member::query();
        $recipients = [];

        if ($validated['recipient_type'] === 'All') {
            $query->whereNotNull('phone');
        } elseif ($validated['recipient_type'] === 'Group') {
            $group = Group::find($request->group_id);
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('groups.id', $request->group_id);
            });
        } elseif ($validated['recipient_type'] === 'Individual') {
            $member = Member::find($request->member_id);
            $recipients = [$member->phone];
        } elseif ($validated['recipient_type'] === 'Advanced') {
            if (isset($validated['criteria']['category_ids']) && !empty($validated['criteria']['category_ids'])) {
                $query->whereIn('category_id', $validated['criteria']['category_ids']);
            }
            if (isset($validated['criteria']['program_ids']) && !empty($validated['criteria']['program_ids'])) {
                $query->whereIn('program_id', $validated['criteria']['program_ids']);
            }
            if (isset($validated['criteria']['community_ids']) && !empty($validated['criteria']['community_ids'])) {
                $query->whereHas('groups', function($q) use ($validated) {
                    $q->where('type', 'Community')
                      ->whereIn('groups.id', $validated['criteria']['community_ids']);
                });
            }
            if (isset($validated['criteria']['contribution_min']) || isset($validated['criteria']['contribution_max'])) {
                $query->whereHas('contributions', function($q) use ($validated) {
                    if (isset($validated['criteria']['contribution_min'])) {
                        $q->where('amount', '>=', $validated['criteria']['contribution_min']);
                    }
                    if (isset($validated['criteria']['contribution_max'])) {
                        $q->where('amount', '<=', $validated['criteria']['contribution_max']);
                    }
                });
            }
            if (isset($validated['criteria']['is_active'])) {
                $query->where('is_active', $validated['criteria']['is_active'] === '1');
            }
        }

        if ($validated['recipient_type'] !== 'Individual') {
            $recipients = $query->whereNotNull('phone')->pluck('phone')->toArray();
        }

        $recipients = array_filter($recipients); // Remove nulls

        if (empty($recipients)) {
            return back()->with('error', 'No valid phone numbers found for the selected recipients.');
        }

        $communicationData = [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => 'sms', // Always SMS
            'recipient_type' => $validated['recipient_type'],
            'group_id' => $validated['group_id'] ?? null,
            'member_id' => $validated['member_id'] ?? null,
            'criteria' => $validated['criteria'] ?? null,
            'sent_by' => Auth::id(),
            'recipients' => json_encode($recipients),
        ];

        if ($validated['send_option'] === 'schedule' && $validated['scheduled_at']) {
            $communicationData['status'] = 'scheduled';
            $communicationData['scheduled_at'] = $validated['scheduled_at'];
            $successMessage = 'Bulk SMS scheduled successfully for ' . $validated['scheduled_at'];
        } else {
            $communicationData['status'] = 'pending';
            $communicationData['sent_at'] = now();
            $successMessage = 'Bulk SMS queued for sending to ' . count($recipients) . ' recipient(s)';
        }

        $communication = Communication::create($communicationData);

        // If sending now, dispatch the job
        if ($validated['send_option'] === 'now') {
            ProcessCommunicationJob::dispatch($communication, $recipients);
        }

        return redirect()->route('communications.index')->with('success', $successMessage);
    }

    public function announcements()
    {
        $announcements = Communication::where('type', 'Announcement')->latest()->paginate(10);
        return view('communications.announcements', compact('announcements'));
    }

    public function sendSms()
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        $members = Member::all();
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $templates = MessageTemplate::where('is_active', true)->get();
        return view('communications.send-sms', compact('groups', 'members', 'activeGateways', 'templates', 'categories', 'programs'));
    }

    public function sendEmail()
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        $members = Member::all();
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $templates = MessageTemplate::where('is_active', true)->get();
        return view('communications.send-email', compact('groups', 'members', 'activeGateways', 'templates', 'categories', 'programs'));
    }
}
