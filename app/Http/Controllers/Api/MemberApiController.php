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
        $user = $request->user()->load([
            'member.category', 
            'member.program',
            'member.groups',
            'member.contributions' => function($query) {
                $query->orderBy('contribution_date', 'desc')->limit(20)->with('type');
            },
            'member.eventAttendance.event',
            'member.certificates'
        ]);
        return response()->json(['user' => $user]);
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
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'contribution_type_id' => 'required|exists:contribution_types,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:m-pesa,bank,cash',
            'phone_number' => 'required_if:payment_method,m-pesa',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = ContributionType::find($request->contribution_type_id);

        $contribution = Contribution::create([
            'member_id' => $member->id,
            'contribution_type' => $type->name,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_phone' => $request->phone_number,
            'contribution_date' => now()->toDateString(),
            'is_verified' => false,
            'transaction_reference' => 'APP-' . strtoupper(Str::random(10)),
            'receipt_number' => 'REC-' . time(),
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Payment initiated successfully. Please complete the transaction on your phone.',
            'contribution' => $contribution->load('type')
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

        $groups = $member->groups()->with('leaders')->get();
        return response()->json(['groups' => $groups]);
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
        $member = $request->user()->member->load(['category', 'program']);
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        return response()->json([
            'id_card' => [
                'full_name' => $member->full_name,
                'reg_no' => $member->registration_number,
                'member_type' => $member->member_type,
                'category' => $member->category->name ?? 'N/A',
                'program' => $member->program->name ?? 'N/A',
                'photo_url' => $member->photo ? asset('storage/' . $member->photo) : null,
                'qr_code' => $member->qr_code,
                'expiry_date' => now()->addYear()->format('Y-m-d'), // Example logic
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
