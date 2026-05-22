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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
     * Get member profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load(['member.category', 'member.program']);
        return response()->json(['user' => $user]);
    }

    /**
     * Get member dashboard stats.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $totalContributions = Contribution::where('member_id', $member->id)
            ->where('is_verified', true)
            ->sum('amount');

        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        $recentAnnouncements = Communication::where('type', 'announcement')
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->limit(3)
            ->get();

        $groupCount = $member->groups()->count();

        return response()->json([
            'stats' => [
                'total_contributions' => $totalContributions,
                'group_count' => $groupCount,
                'upcoming_events_count' => Event::where('status', 'upcoming')->count(),
            ],
            'upcoming_events' => $upcomingEvents,
            'recent_announcements' => $recentAnnouncements,
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
     * Get member contributions.
     */
    public function contributions(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $contributions = Contribution::with('type')
            ->where('member_id', $member->id)
            ->orderBy('contribution_date', 'desc')
            ->get();

        return response()->json(['contributions' => $contributions]);
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

        // Here you would typically call a payment gateway service (e.g., M-Pesa STK Push)
        // For now, we'll create a pending contribution record.

        $contribution = Contribution::create([
            'member_id' => $member->id,
            'contribution_type' => $type->name,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_phone' => $request->phone_number,
            'contribution_date' => now(),
            'is_verified' => false,
            'transaction_reference' => 'APP-' . strtoupper(uniqid()),
            'receipt_number' => 'REC-' . time(),
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Payment initiated successfully.',
            'contribution' => $contribution
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
     * Record event attendance.
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

        return response()->json(['message' => 'Attendance recorded successfully.']);
    }

    /**
     * Get member groups.
     */
    public function groups(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) {
            return response()->json(['message' => 'Member record not found.'], 404);
        }

        $groups = $member->groups()->get();
        return response()->json(['groups' => $groups]);
    }

    /**
     * Get announcements/communications.
     */
    public function announcements(Request $request)
    {
        $communications = Communication::where('type', 'announcement')
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->get();
        return response()->json(['announcements' => $communications]);
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
