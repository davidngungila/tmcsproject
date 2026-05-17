<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SnippePaymentService;
use App\Models\Contribution;
use App\Models\ContributionType;
use App\Services\GroupService;

use App\Models\Group;
use App\Models\LedgerEntry;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected $snipeService;

    public function __construct(SnippePaymentService $snipeService)
    {
        $this->snipeService = $snipeService;
    }

    public function pay()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        $savedMethods = $member->savedPaymentMethods;

        return view('member.profile.pay', compact('member', 'savedMethods'));
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:500',
            'contribution_type' => 'required|string',
            'payment_method' => 'required|string|in:mobile_money,card',
            'phone_number' => 'nullable|string|min:10',
            'save_method' => 'nullable|boolean',
        ]);

        $receiptNumber = 'RCP-ONLINE-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $type = $validated['payment_method'] === 'mobile_money' ? 'mobile' : 'card';

        $paymentPhone = $validated['phone_number'] ?? $member->phone;

        $contribution = Contribution::create([
            'member_id' => $member->id,
            'contribution_type' => $validated['contribution_type'],
            'amount' => $validated['amount'],
            'contribution_date' => now(),
            'payment_method' => $validated['payment_method'],
            'payment_phone' => $paymentPhone,
            'notes' => 'Online payment initiated via Member Portal.',
            'receipt_number' => $receiptNumber,
            'is_verified' => false,
            'recorded_by' => $user->id,
        ]);

        // Save payment method if requested
        if ($request->has('save_method') && $request->save_method && $paymentPhone) {
            $member->savedPaymentMethods()->updateOrCreate(
                ['identifier' => $paymentPhone, 'type' => $validated['payment_method']],
                ['label' => $request->get('method_label', 'Mobile Money'), 'provider' => $this->detectProvider($paymentPhone)]
            );
        }

        if ($validated['payment_method'] === 'mobile_money') {
            $response = $this->snipeService->createMobileMoneyPayment($contribution);
            
            if (isset($response['error'])) {
                if ($request->ajax()) {
                    return response()->json(['error' => $response['error']], 422);
                }
                return back()->with('error', 'Payment failed: ' . $response['error']);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated! Please check your phone for the USSD prompt.',
                    'contribution_id' => $contribution->id,
                    'redirect_url' => route('member.contributions.index')
                ]);
            }

            return redirect()->route('member.contributions.index')->with('success', 'Payment initiated! Please check your phone for the USSD prompt.');
        }

        $checkoutResponse = $this->snipeService->createCheckout($contribution);

        if ($checkoutResponse && isset($checkoutResponse['checkout_url'])) {
            if ($request->ajax()) {
                return response()->json(['checkout_url' => $checkoutResponse['checkout_url']]);
            }
            return redirect($checkoutResponse['checkout_url']);
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Failed to initiate payment session.'], 422);
        }
        return back()->with('error', 'Failed to initiate payment session. Please try again.');
    }

    public function checkStatus(Contribution $contribution)
    {
        // Ensure the contribution belongs to the authenticated member
        if ($contribution->member_id !== Auth::user()->member->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'is_verified' => (bool) $contribution->is_verified,
            'status' => $contribution->is_verified ? 'Success' : 'Pending'
        ]);
    }

    protected function detectProvider($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '255')) {
            $prefix = substr($phone, 3, 2);
        } elseif (str_starts_with($phone, '0')) {
            $prefix = substr($phone, 1, 2);
        } else {
            $prefix = substr($phone, 0, 2);
        }

        return match ($prefix) {
            '74', '75', '76' => 'Vodacom (M-Pesa)',
            '65', '67', '71' => 'Tigo (TigoPesa)',
            '68', '69', '78' => 'Airtel (AirtelMoney)',
            '61', '62' => 'Halotel (Halopesa)',
            default => 'Mobile Money'
        };
    }

    public function index()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found for this user.');
        }

        $member->load(['groups', 'contributions' => function($query) {
            $query->latest()->limit(10);
        }, 'eventAttendance.event']);

        return view('member.profile.index', compact('user', 'member'));
    }

    public function edit()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        return view('member.profile.edit', compact('user', 'member'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'baptismal_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'region' => 'nullable|string',
            'diocese' => 'nullable|string',
            'parish' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $path = $request->file('photo')->store('member_photos', 'public');
            $validated['photo'] = $path;
        }

        $member->update($validated);

        // Automatically assign to communities based on new info
        app(GroupService::class)->autoAssignMemberToCommunities($member);

        return redirect()->route('member.profile.index')->with('success', 'Profile updated successfully and communities reassigned.');
    }

    public function joinGroup(Request $request, Group $group)
    {
        $member = Auth::user()->member;

        if ($group->type === 'Community') {
            return back()->with('error', 'Communities are automatically assigned by the system.');
        }

        if ($member->groups()->where('groups.id', $group->id)->exists()) {
            return back()->with('info', 'You are already a member of this group.');
        }

        $member->groups()->attach($group->id, [
            'join_date' => now(),
            'is_active' => true,
        ]);

        return back()->with('success', 'You have successfully joined ' . $group->name);
    }

    public function leaveGroup(Request $request, Group $group)
    {
        $member = Auth::user()->member;

        if ($group->type === 'Community') {
            return back()->with('error', 'You cannot leave an automatically assigned community.');
        }

        $member->groups()->detach($group->id);

        return back()->with('success', 'You have left ' . $group->name);
    }

    public function communities()
    {
        $member = Auth::user()->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }
        
        // Groups the member is in
        $communities = $member->groups()->where('type', 'Community')->get();
        
        // Groups the member LEADS
        $ledCommunities = Group::where('type', 'Community')
            ->where(function($query) use ($member) {
                $query->where('chairperson_id', $member->id)
                    ->orWhere('secretary_id', $member->id)
                    ->orWhere('accountant_id', $member->id);
            })->get();

        return view('member.profile.communities', compact('member', 'communities', 'ledCommunities'));
    }

    public function groups()
    {
        $member = Auth::user()->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }
        
        // Groups the member is in
        $groups = $member->groups()->where('type', '!=', 'Community')->get();
        
        // Groups the member LEADS
        $ledGroups = Group::where('type', '!=', 'Community')
            ->where(function($query) use ($member) {
                $query->where('chairperson_id', $member->id)
                    ->orWhere('secretary_id', $member->id)
                    ->orWhere('accountant_id', $member->id);
            })->get();

        // Available groups to join (Active, NOT Community, NOT already a member)
        $availableGroups = Group::where('type', '!=', 'Community')
            ->where('is_active', true)
            ->whereDoesntHave('members', function($query) use ($member) {
                $query->where('members.id', $member->id);
            })->get();

        return view('member.profile.groups', compact('member', 'groups', 'ledGroups', 'availableGroups'));
    }

    public function contributions()
    {
        $member = Auth::user()->member;
        
        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        $contributions = $member->contributions()->latest()->paginate(15);
        return view('member.profile.contributions', compact('member', 'contributions'));
    }

    public function contributionShow(Contribution $contribution)
    {
        $member = Auth::user()->member;

        if (!$member || $contribution->member_id !== $member->id) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $contribution->load(['member', 'recorder', 'verifier']);

        $ledgerEntries = LedgerEntry::with('account')
            ->where('reference_type', 'Contribution')
            ->where('reference_id', $contribution->id)
            ->get();

        return view('member.profile.contribution_show', compact('member', 'contribution', 'ledgerEntries'));
    }

    public function events()
    {
        $member = Auth::user()->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        $attendance = $member->eventAttendance()->with('event')->latest()->paginate(15);
        return view('member.profile.events', compact('member', 'attendance'));
    }

    public function idCard()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('dashboard')->with('error', 'Member profile not found.');
        }

        // Reuse the logic from MemberController if possible, or just call it
        return app(\App\Http\Controllers\MemberController::class)->idCard($member);
    }
}
