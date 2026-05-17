<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SnippePaymentService;
use App\Models\Contribution;

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

        return view('member.profile.pay', compact('member'));
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:500',
            'contribution_type' => 'required|string',
            'payment_method' => 'required|string|in:mobile_money,card',
        ]);

        $receiptNumber = 'RCP-ONLINE-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $type = $validated['payment_method'] === 'mobile_money' ? 'mobile' : 'card';

        $contribution = Contribution::create([
            'member_id' => $member->id,
            'contribution_type' => $validated['contribution_type'],
            'amount' => $validated['amount'],
            'contribution_date' => now(),
            'payment_method' => $validated['payment_method'],
            'notes' => 'Online payment initiated via Member Portal.',
            'receipt_number' => $receiptNumber,
            'is_verified' => false,
            'recorded_by' => 1, // System admin or appropriate user
        ]);

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

        return redirect()->route('member.profile.index')->with('success', 'Profile updated successfully.');
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

        return view('member.profile.groups', compact('member', 'groups', 'ledGroups'));
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
