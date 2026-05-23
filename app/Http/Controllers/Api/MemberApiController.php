<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Event;
use App\Models\Group;
use App\Models\Communication;
use App\Models\EventAttendance;
use App\Models\ContributionType;
use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\ElectionVote;
use App\Models\SavedPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberApiController extends Controller
{
    /**
     * Member login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account is inactive. Please contact administrator.',
            ], 403);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('member'),
        ]);
    }

    /**
     * Get member profile with all details.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $member->load([
            'category', 
            'program',
            'groups' => function($q) {
                $q->withPivot(['join_date', 'is_active']);
            },
            'contributions' => function($query) {
                $query->orderBy('contribution_date', 'desc')->limit(50)->with(['type', 'recorder']);
            },
            'eventAttendance.event',
            'certificates',
            'electionVotes.election',
            'financials' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(12);
            }
        ]);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                'is_active' => $user->is_active,
            ],
            'member' => [
                'id' => $member->id,
                'registration_number' => $member->registration_number,
                'full_name' => $member->full_name,
                'member_type' => $member->member_type,
                'gender' => $member->gender,
                'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : null,
                'phone' => $member->phone,
                'address' => $member->address,
                'parish' => $member->parish,
                'diocese' => $member->diocese,
                'region' => $member->region,
                'baptismal_name' => $member->baptismal_name,
                'photo' => $member->photo ? asset('storage/' . $member->photo) : null,
                'qr_code' => $member->qr_code,
                'is_active' => $member->is_active,
                'registration_date' => $member->registration_date ? $member->registration_date->format('Y-m-d') : null,
                'category' => $member->category,
                'program' => $member->program,
                'groups' => $member->groups,
                'recent_contributions' => $member->contributions,
                'attendance' => $member->eventAttendance,
                'certificates' => $member->certificates,
                'votes' => $member->electionVotes,
                'financial_history' => $member->financials,
            ]
        ]);
    }

    /**
     * Get member dashboard stats and recent data.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $member->load(['category', 'program']);

        // Financial Summary from Contributions table
        $totalContributions = Contribution::where('member_id', $member->id)
            ->where('is_verified', true)
            ->sum('amount');
        
        $pendingContributions = Contribution::where('member_id', $member->id)
            ->where('is_verified', false)
            ->sum('amount');

        // Financial Summary from MemberFinancials table (Savings, Loans etc)
        $latestFinancial = $member->financials()->orderBy('created_at', 'desc')->first();

        // Activity Summary
        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        $recentAnnouncements = Communication::where(function($q) use ($member) {
                $q->where('type', 'announcement')
                  ->orWhere('member_id', $member->id);
            })
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->limit(5)
            ->get();

        $activeElectionsCount = Election::where('status', 'ongoing')->count();
        $groupCount = $member->groups()->count();
        $attendanceCount = $member->eventAttendance()->count();

        // Recent Contributions
        $recentContributions = Contribution::with(['type', 'recorder'])
            ->where('member_id', $member->id)
            ->orderBy('contribution_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_contributions' => (float)$totalContributions,
                'pending_contributions' => (float)$pendingContributions,
                'total_balance' => (float)$totalContributions, // Fallback if app expects balance
                'group_count' => $groupCount,
                'attendance_count' => $attendanceCount,
                'upcoming_events_count' => $upcomingEvents->count(),
                'active_elections_count' => $activeElectionsCount,
                'financial_summary' => $latestFinancial ? [
                    'savings' => (float)$latestFinancial->savings,
                    'loans' => (float)$latestFinancial->loans,
                    'collections' => (float)$latestFinancial->collections,
                    'month' => $latestFinancial->month,
                ] : [
                    'savings' => 0,
                    'loans' => 0,
                    'collections' => 0,
                    'month' => now()->format('F Y'),
                ],
            ],
            'recent_contributions' => $recentContributions,
            'upcoming_events' => $upcomingEvents,
            'recent_announcements' => $recentAnnouncements,
            'member_details' => [
                'full_name' => $member->full_name,
                'reg_no' => $member->registration_number,
                'qr_code' => $member->qr_code,
                'photo' => $member->photo ? asset('storage/' . $member->photo) : null,
                'member_type' => $member->member_type,
                'category' => $member->category->name ?? 'N/A',
                'program' => $member->program->name ?? 'N/A',
            ],
            'quick_actions' => [
                ['label' => 'Make Payment', 'icon' => 'payment', 'route' => '/payments/new'],
                ['label' => 'ID Card', 'icon' => 'badge', 'route' => '/profile/id-card'],
                ['label' => 'Events', 'icon' => 'event', 'route' => '/events'],
                ['label' => 'Voting', 'icon' => 'how_to_vote', 'route' => '/elections'],
                ['label' => 'My Groups', 'icon' => 'groups', 'route' => '/groups'],
                ['label' => 'Certificates', 'icon' => 'verified', 'route' => '/certificates'],
            ]
        ]);
    }

    /**
     * Update member profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
            'parish' => 'sometimes|string|max:255',
            'diocese' => 'sometimes|string|max:255',
            'profile_image' => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('full_name')) {
            $member->full_name = $request->full_name;
            $user->name = $request->full_name;
        }

        if ($request->has('phone')) {
            $member->phone = $request->phone;
            $user->phone = $request->phone;
        }

        if ($request->has('address')) {
            $member->address = $request->address;
        }

        if ($request->has('parish')) {
            $member->parish = $request->parish;
        }

        if ($request->has('diocese')) {
            $member->diocese = $request->diocese;
        }

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
            $member->photo = $path;
        }

        $member->save();
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->load('member')
        ]);
    }

    /**
     * Get member contributions history.
     */
    public function contributions(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $contributions = Contribution::with(['type', 'recorder', 'verifier'])
            ->where('member_id', $member->id)
            ->orderBy('contribution_date', 'desc')
            ->get();

        $summary = [
            'total_verified' => (float) Contribution::where('member_id', $member->id)->where('is_verified', true)->sum('amount'),
            'total_pending' => (float) Contribution::where('member_id', $member->id)->where('is_verified', false)->sum('amount'),
            'count' => $contributions->count(),
        ];

        return response()->json([
            'contributions' => $contributions,
            'summary' => $summary
        ]);
    }

    /**
     * Get contribution types for payment.
     */
    public function contributionTypes(Request $request)
    {
        $types = ContributionType::where('is_active', true)->get();
        return response()->json(['types' => $types]);
    }

    /**
     * Initiate a contribution payment.
     */
    public function initiatePayment(Request $request)
    {
        $user = $request->user();
        $member = $user->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'contribution_type_id' => 'required|exists:contribution_types,id',
            'amount' => 'required|numeric|min:500',
            'payment_method' => 'required|in:mobile_money,card,m-pesa,bank,cash',
            'phone_number' => 'nullable|string',
            'saved_method_id' => 'nullable|exists:saved_payment_methods,id',
            'save_method' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = ContributionType::find($request->contribution_type_id);
        
        $method = $request->payment_method;
        if ($method === 'm-pesa') $method = 'mobile_money';

        $phone = $request->phone_number;
        
        // If saved method is provided, use its identifier
        if ($request->saved_method_id) {
            $savedMethod = SavedPaymentMethod::where('member_id', $member->id)
                ->where('id', $request->saved_method_id)
                ->first();
            if ($savedMethod) {
                $phone = $savedMethod->identifier;
                $method = $savedMethod->type;
            }
        }

        if (!$phone && $method === 'mobile_money') {
            $phone = $member->phone;
        }

        // Save new method if requested
        if ($request->save_method && $phone && $method === 'mobile_money') {
            SavedPaymentMethod::updateOrCreate(
                ['member_id' => $member->id, 'identifier' => $phone],
                ['type' => 'mobile_money', 'provider' => 'Mobile Money', 'label' => 'Saved Number']
            );
        }

        $receiptNumber = 'RCP-APP-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $contribution = Contribution::create([
            'member_id' => $member->id,
            'contribution_type' => $type->name,
            'amount' => $request->amount,
            'payment_method' => $method,
            'payment_phone' => $phone,
            'contribution_date' => now()->toDateString(),
            'is_verified' => false,
            'transaction_reference' => 'APP-' . strtoupper(Str::random(10)),
            'receipt_number' => $receiptNumber,
            'recorded_by' => $user->id,
            'notes' => 'Payment initiated via Mobile App.',
        ]);

        // Integrate with SnippePaymentService for digital payments
        if (in_array($method, ['mobile_money', 'card'])) {
            $snipeService = app(\App\Services\SnippePaymentService::class);
            
            if ($method === 'mobile_money') {
                $response = $snipeService->createMobileMoneyPayment($contribution);
                
                if (isset($response['error'])) {
                    return response()->json(['message' => 'Payment initiation failed: ' . $response['error']], 422);
                }

                return response()->json([
                    'message' => 'Payment initiated! Please check your phone for the USSD prompt.',
                    'contribution' => $contribution->load('type'),
                    'status' => 'pending_ussd'
                ]);
            } else {
                // For card, try createCardPayment first for direct URL
                $cardResponse = $snipeService->createCardPayment($contribution);
                
                if ($cardResponse && isset($cardResponse['payment_url'])) {
                    return response()->json([
                        'message' => 'Card payment URL generated.',
                        'checkout_url' => $cardResponse['payment_url'],
                        'contribution' => $contribution->load('type'),
                        'status' => 'pending_checkout'
                    ]);
                }

                // Fallback to checkout session
                $checkoutResponse = $snipeService->createCheckout($contribution);
                if ($checkoutResponse && (isset($checkoutResponse['checkout_url']) || isset($checkoutResponse['payment_link_url']))) {
                    return response()->json([
                        'message' => 'Checkout URL generated.',
                        'checkout_url' => $checkoutResponse['checkout_url'] ?? $checkoutResponse['payment_link_url'],
                        'contribution' => $contribution->load('type'),
                        'status' => 'pending_checkout'
                    ]);
                }

                return response()->json(['message' => 'Failed to initiate card payment. Please try again later.'], 500);
            }
        }

        // Fallback for cash/bank or failed service
        return response()->json([
            'message' => 'Payment recorded. Please complete verification if required.',
            'contribution' => $contribution->load('type'),
            'status' => 'recorded'
        ]);
    }

    /**
     * Get available events.
     */
    public function events(Request $request)
    {
        $events = Event::where('status', '!=', 'cancelled')
            ->orderBy('event_date', 'desc')
            ->get();
        return response()->json(['events' => $events]);
    }

    /**
     * Record event attendance (Self check-in via QR or Location).
     */
    public function markAttendance(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::find($request->event_id);
        if ($event->status === 'completed' || $event->status === 'cancelled') {
            return response()->json(['message' => 'This event is no longer active for attendance.'], 422);
        }

        $existing = EventAttendance::where('event_id', $request->event_id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Attendance already recorded.'], 200);
        }

        EventAttendance::create([
            'event_id' => $request->event_id,
            'member_id' => $member->id,
            'status' => 'present',
            'checked_in_at' => now(),
            'checked_in_by' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Attendance recorded successfully. Welcome!']);
    }

    /**
     * Get member groups/communities.
     */
    public function groups(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        // 1. Groups I Lead
        $ledGroups = Group::where('is_active', true)
            ->where(function($q) use ($member) {
                $q->where('chairperson_id', $member->id)
                  ->orWhere('secretary_id', $member->id)
                  ->orWhere('accountant_id', $member->id);
            })
            ->withCount('members')
            ->get();

        // 2. My Memberships (Joined)
        $joinedGroups = $member->groups()->withPivot(['join_date', 'is_active'])->get();
        $joinedGroupIds = $joinedGroups->pluck('id')->toArray();

        // 3. Available Groups (Not Joined)
        $availableGroups = Group::where('is_active', true)
            ->whereNotIn('id', $joinedGroupIds)
            ->get();

        return response()->json([
            'led_groups' => $ledGroups,
            'joined_groups' => $joinedGroups,
            'available_groups' => $availableGroups
        ]);
    }

    /**
     * Get saved payment methods.
     */
    public function savedMethods(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $methods = SavedPaymentMethod::where('member_id', $member->id)->get();
        return response()->json(['saved_methods' => $methods]);
    }

    /**
     * Join a group.
     */
    public function joinGroup(Request $request, $id)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $group = Group::findOrFail($id);
        
        // Check if already a member
        if ($member->groups()->where('group_id', $id)->exists()) {
            return response()->json(['message' => 'You are already a member of this group.'], 422);
        }

        $member->groups()->attach($id, [
            'join_date' => now(),
            'is_active' => true
        ]);

        return response()->json(['message' => 'Successfully joined ' . $group->name]);
    }

    /**
     * Leave a group.
     */
    public function leaveGroup(Request $request, $id)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $group = Group::findOrFail($id);

        // Check if is a leader
        if ($group->chairperson_id == $member->id || $group->secretary_id == $member->id || $group->accountant_id == $member->id) {
            return response()->json(['message' => 'Leaders cannot leave a group without resigning from their position.'], 422);
        }

        $member->groups()->detach($id);

        return response()->json(['message' => 'Successfully left ' . $group->name]);
    }

    /**
     * Get stats for a group (for leaders).
     */
    public function groupStats(Request $request, $id)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $group = Group::withCount('members')->findOrFail($id);

        // Check leadership access
        if ($group->chairperson_id != $member->id && $group->secretary_id != $member->id && $group->accountant_id != $member->id) {
            return response()->json(['message' => 'Access denied. You are not a leader of this group.'], 403);
        }

        $totalCollected = \App\Models\GroupMeeting::where('group_id', $id)->sum('total_collected');
        $meetingCount = \App\Models\GroupMeeting::where('group_id', $id)->count();
        $recentMeetings = \App\Models\GroupMeeting::where('group_id', $id)
            ->orderBy('meeting_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'group' => $group,
            'stats' => [
                'member_count' => $group->members_count,
                'total_collected' => (float)$totalCollected,
                'meeting_count' => $meetingCount,
            ],
            'recent_meetings' => $recentMeetings
        ]);
    }

    /**
     * Get members of a group (for leaders).
     */
    public function groupMembers(Request $request, $id)
    {
        $member = $request->user()->member;
        $group = Group::findOrFail($id);

        if ($group->chairperson_id != $member->id && $group->secretary_id != $member->id && $group->accountant_id != $member->id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $members = $group->members()->withPivot(['join_date', 'is_active'])->get();
        return response()->json(['members' => $members]);
    }

    /**
     * Get meetings/giving history for a group (for leaders).
     */
    public function groupMeetings(Request $request, $id)
    {
        $member = $request->user()->member;
        $group = Group::findOrFail($id);

        if ($group->chairperson_id != $member->id && $group->secretary_id != $member->id && $group->accountant_id != $member->id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $meetings = \App\Models\GroupMeeting::where('group_id', $id)
            ->with(['attendances.member'])
            ->orderBy('meeting_date', 'desc')
            ->get();

        return response()->json(['meetings' => $meetings]);
    }

    /**
     * Send a message to all group members (for leaders).
     */
    public function sendGroupMessage(Request $request, $id)
    {
        $member = $request->user()->member;
        $group = Group::findOrFail($id);

        if ($group->chairperson_id != $member->id && $group->secretary_id != $member->id && $group->accountant_id != $member->id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:500',
            'type' => 'required|in:sms,announcement',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $members = $group->members()->where('members.is_active', true)->get();
        $message = $request->message;

        if ($request->type === 'sms') {
            $phones = $members->pluck('phone')->filter()->toArray();
            if (empty($phones)) {
                return response()->json(['message' => 'No active members with phone numbers found.'], 422);
            }

            $messagingService = app(\App\Services\MessagingService::class);
            $result = $messagingService->sendSms($phones, $message);

            if ($result['status'] === 'error') {
                return response()->json(['message' => 'Failed to send SMS: ' . $result['message']], 500);
            }
        }

        // Record the communication
        Communication::create([
            'group_id' => $group->id,
            'type' => $request->type === 'sms' ? 'sms' : 'announcement',
            'subject' => 'Group Message: ' . $group->name,
            'content' => $message,
            'status' => 'sent',
            'sent_at' => now(),
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Message sent successfully to ' . $members->count() . ' members.']);
    }

    /**
     * Get elections and candidates.
     */
    public function elections(Request $request)
    {
        $elections = Election::whereIn('status', ['ongoing', 'upcoming'])
            ->with(['candidates.member'])
            ->get();

        $member = $request->user()->member;
        $votedElectionIds = ElectionVote::where('voter_id', $member->id)
            ->pluck('election_id')
            ->toArray();

        $elections->each(function($election) use ($votedElectionIds) {
            $election->has_voted = in_array($election->id, $votedElectionIds);
        });

        return response()->json(['elections' => $elections]);
    }

    /**
     * Cast a vote.
     */
    public function castVote(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'election_id' => 'required|exists:elections,id',
            'candidate_id' => 'required|exists:election_candidates,id',
            'position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $election = Election::find($request->election_id);
        if ($election->status !== 'ongoing') {
            return response()->json(['message' => 'Voting is not currently open for this election.'], 422);
        }

        $existingVote = ElectionVote::where('election_id', $request->election_id)
            ->where('voter_id', $member->id)
            ->where('position', $request->position)
            ->first();

        if ($existingVote) {
            return response()->json(['message' => 'You have already voted for this position.'], 422);
        }

        ElectionVote::create([
            'election_id' => $request->election_id,
            'voter_id' => $member->id,
            'candidate_id' => $request->candidate_id,
            'position' => $request->position,
            'voted_at' => now(),
        ]);

        return response()->json(['message' => 'Vote cast successfully.']);
    }

    /**
     * Get digital ID card data.
     */
    public function idCard(Request $request)
    {
        $member = $request->user()->member->load(['category', 'program', 'groups']);
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        // Validity logic: 1 year from now or based on registration
        $expiryDate = $member->registration_date ? $member->registration_date->addYear() : now()->addYear();

        return response()->json([
            'id_card' => [
                'full_name' => $member->full_name,
                'reg_no' => $member->registration_number,
                'member_type' => $member->member_type,
                'category' => $member->category->name ?? 'N/A',
                'program' => $member->program->name ?? 'N/A',
                'groups' => $member->groups->pluck('name'),
                'photo_url' => $member->photo ? asset('storage/' . $member->photo) : null,
                'qr_code' => $member->qr_code,
                'barcode' => $member->registration_number, // Mobile app can generate barcode from this
                'issue_date' => $member->registration_date ? $member->registration_date->format('Y-m-d') : now()->format('Y-m-d'),
                'expiry_date' => $expiryDate->format('Y-m-d'),
                'status' => $member->is_active ? 'Active' : 'Inactive',
                'institution' => 'TMCS SMART COMMUNITY',
                'diocese' => $member->diocese,
                'parish' => $member->parish,
            ]
        ]);
    }

    /**
     * Get announcements/communications.
     */
    public function announcements(Request $request)
    {
        $member = $request->user()->member;
        $communications = Communication::where(function($q) use ($member) {
                $q->where('type', 'announcement')
                  ->orWhere('member_id', $member->id);
            })
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->get();
        return response()->json(['announcements' => $communications]);
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password does not match our records.'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    /**
     * Get details for a specific contribution/receipt.
     */
    public function getReceipt(Request $request, $id)
    {
        $member = $request->user()->member;
        $contribution = Contribution::with('type')
            ->where('member_id', $member->id)
            ->where('id', $id)
            ->first();

        if (!$contribution) {
            return response()->json(['message' => 'Contribution record not found.'], 404);
        }

        return response()->json([
            'receipt' => [
                'id' => $contribution->id,
                'receipt_number' => $contribution->receipt_number,
                'amount' => $contribution->amount,
                'type' => $contribution->contribution_type,
                'date' => $contribution->contribution_date->format('Y-m-d'),
                'method' => $contribution->payment_method,
                'reference' => $contribution->transaction_reference,
                'status' => $contribution->is_verified ? 'Verified' : 'Pending',
                'qr_code' => $contribution->receipt_qr_code,
                'notes' => $contribution->notes,
                'member_name' => $member->full_name,
                'reg_no' => $member->registration_number,
            ]
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
