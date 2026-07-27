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
use App\Models\ContributionType;

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
        $sentCommunications = Communication::where('status', 'sent')->count();
        $failedCommunications = Communication::where('status', 'failed')->count();
        $pendingCommunications = Communication::where('status', 'pending')->count();
        
        return view('communications.index', compact(
            'communications', 
            'totalCommunications', 
            'sentCommunications', 
            'failedCommunications', 
            'pendingCommunications'
        ));
    }

    public function show($id)
    {
        $communication = Communication::findOrFail($id);
        return view('communications.show', compact('communication'));
    }

    public function resend($id)
    {
        $communication = Communication::findOrFail($id);
        
        $recipients = json_decode($communication->recipients, true) ?? [];
        
        if (!empty($recipients)) {
            ProcessCommunicationJob::dispatch($communication, $recipients);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'No recipients found']);
    }

    public function destroy($id)
    {
        $communication = Communication::findOrFail($id);
        $communication->delete();
        return response()->json(['success' => true]);
    }

    public function create()
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        $members = Member::all();
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $templates = MessageTemplate::where('is_active', true)->get();
        $communications = Communication::latest()->take(10)->get();
        $contributionTypes = ContributionType::where('is_active', true)->get();
        
        // Get default sender name from first active gateway
        $defaultSenderName = 'TMCSMART CHURCH';
        if ($activeGateways->isNotEmpty()) {
            $defaultGateway = $activeGateways->first();
            $defaultSenderName = $defaultGateway->sender_id ?: $defaultGateway->name ?: 'TMCSMART CHURCH';
        }
        
        return view('communications.create', compact('groups', 'members', 'activeGateways', 'templates', 'categories', 'programs', 'communications', 'contributionTypes', 'defaultSenderName'));
    }

    public function store(Request $request)
    {
        $formAction = $request->input('form_action', 'send');

        $validated = $request->validate([
            'form_action' => 'nullable|in:send,save_draft',
            'message_title' => $formAction === 'save_draft' ? 'nullable|string|max:255' : 'required|string|max:255',
            'message_body' => $formAction === 'save_draft' ? 'nullable|string' : 'required|string',
            'sms_category' => 'nullable|string',
            'recipient_option' => $formAction === 'save_draft' ? 'nullable|in:all,cell_group,visitors,custom' : 'required|in:all,cell_group,visitors,custom',
            'cell_group' => 'nullable|exists:groups,id',
            'custom_members' => 'nullable|array',
            'custom_members.*' => 'exists:members,id',
            'gender' => 'nullable|string',
            'age_group' => 'nullable|string',
            'membership_status' => 'nullable|string',
            'payment_status' => 'nullable|in:all,paid,unpaid',
            'contribution_type_id' => 'nullable|exists:contribution_types,id',
            'reg_start_date' => 'nullable|date',
            'reg_end_date' => 'nullable|date',
            'manual_phones' => 'nullable|string',
            'send_option' => $formAction === 'save_draft' ? 'nullable|in:now,schedule' : 'required|in:now,schedule',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable',
        ]);

        $query = Member::query();
        $recipients = [];

        // Handle recipient option
        $recipientOption = $validated['recipient_option'] ?? 'all';

        if ($formAction !== 'save_draft' && $recipientOption === 'cell_group' && empty($validated['cell_group'])) {
            return back()->with('error', 'Please select a cell group before sending the SMS.');
        }

        if ($formAction !== 'save_draft' && $recipientOption === 'custom' && empty($validated['custom_members'])) {
            return back()->with('error', 'Please select at least one member before sending the SMS.');
        }

        if ($formAction !== 'save_draft' && ($validated['send_option'] ?? null) === 'schedule' && (!$request->scheduled_date || !$request->scheduled_time)) {
            return back()->with('error', 'Please provide both schedule date and schedule time.');
        }

        if ($recipientOption === 'all') {
            $query->whereNotNull('phone');
        } elseif ($recipientOption === 'cell_group' && !empty($validated['cell_group'])) {
            $groupId = $request->cell_group;
            $query->whereHas('groups', function($q) use ($groupId) {
                $q->where('groups.id', $groupId);
            });
        } elseif ($recipientOption === 'visitors') {
            $query->whereNotNull('phone')->where('member_type', 'visitor');
        } elseif ($recipientOption === 'custom' && !empty($validated['custom_members'])) {
            $memberIds = $request->custom_members;
            $recipients = Member::whereIn('id', $memberIds)->whereNotNull('phone')->pluck('phone')->toArray();
        }

        // Apply filters
        if ($request->gender && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }
        if ($request->membership_status && $request->membership_status !== 'all') {
            $query->where('is_active', $request->membership_status === 'active');
        }
        if ($request->payment_status && $request->payment_status !== 'all') {
            if ($request->payment_status === 'paid') {
                $query->whereHas('contributions');
            } else {
                $query->whereDoesntHave('contributions');
            }
        }
        if ($request->contribution_type_id) {
            $contributionType = ContributionType::find($request->contribution_type_id);
            if ($contributionType) {
                $query->whereHas('contributions', function($q) use ($contributionType) {
                    $q->where('contribution_type', $contributionType->name);
                });
            }
        }
        if ($request->reg_start_date) {
            $query->where('created_at', '>=', $request->reg_start_date);
        }
        if ($request->reg_end_date) {
            $query->where('created_at', '<=', $request->reg_end_date);
        }

        // Get recipients from query
        if ($recipientOption !== 'custom') {
            $recipients = array_merge($recipients, $query->whereNotNull('phone')->pluck('phone')->toArray());
        }

        // Add manual phones
        if ($request->manual_phones) {
            $manualPhones = explode("\n", trim($request->manual_phones));
            $manualPhones = array_map('trim', $manualPhones);
            $recipients = array_merge($recipients, $manualPhones);
        }

        // Remove duplicates and nulls
        $recipients = array_unique(array_filter($recipients));

        if ($formAction !== 'save_draft' && empty($recipients)) {
            return back()->with('error', 'No valid phone numbers found for the selected recipients.');
        }

        // Prepare scheduled_at if needed
        $scheduledAt = null;
        if (($validated['send_option'] ?? null) === 'schedule' && $request->scheduled_date && $request->scheduled_time) {
            $scheduledAt = $request->scheduled_date . ' ' . $request->scheduled_time;
        }

        // Get default sender name from first active gateway
        $activeGateways = ApiConfig::where('is_active', true)->get();
        $senderName = 'TMCSMART CHURCH';
        if ($activeGateways->isNotEmpty()) {
            $defaultGateway = $activeGateways->first();
            $senderName = $defaultGateway->sender_id ?: $defaultGateway->name ?: 'TMCSMART CHURCH';
        }
        
        $recipientType = match ($recipientOption) {
            'cell_group' => 'group',
            'custom' => 'individual',
            default => 'all',
        };

        $communicationData = [
            'subject' => $validated['message_title'] ?: 'Untitled Draft',
            'message' => $validated['message_body'] ?? '',
            'type' => 'sms',
            'recipient_type' => $recipientType,
            'group_id' => $request->cell_group ?? null,
            'criteria' => [
                'sender_name' => $senderName,
                'category' => $request->sms_category,
                'recipient_option' => $recipientOption,
                'gender' => $request->gender,
                'age_group' => $request->age_group,
                'membership_status' => $request->membership_status,
                'payment_status' => $request->payment_status,
                'contribution_type_id' => $request->contribution_type_id,
            ],
            'sent_by' => Auth::id(),
            'recipients' => json_encode($recipients),
            'status' => 'draft',
            'scheduled_at' => $scheduledAt,
        ];

        $communication = Communication::create($communicationData);

        if ($formAction === 'save_draft') {
            return redirect()->route('communications.index')->with('success', 'SMS draft saved successfully.');
        }

        if (($validated['send_option'] ?? 'now') === 'schedule' && $scheduledAt) {
            $communication->update([
                'status' => 'scheduled',
            ]);

            return redirect()
                ->route('communications.index')
                ->with('success', 'Bulk SMS scheduled successfully for ' . $scheduledAt);
        }

        $response = $this->messagingService->sendSms($recipients, $validated['message_body']);

        if (($response['status'] ?? 'error') === 'success') {
            $communication->update([
                'status' => 'sent',
                'sent_at' => now(),
                'scheduled_at' => null,
                'error_message' => null,
            ]);

            return redirect()
                ->route('communications.index')
                ->with('success', 'Bulk SMS sent successfully to ' . count($recipients) . ' recipient(s).');
        }

        $communication->update([
            'status' => 'failed',
            'error_message' => $response['message'] ?? 'Failed to send SMS.',
        ]);

        return back()->with('error', $response['message'] ?? 'Failed to send SMS.');
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
