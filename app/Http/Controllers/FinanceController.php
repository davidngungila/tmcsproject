<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Member;
use App\Services\SnippePaymentService;
use Illuminate\Http\Request;

use App\Services\MessagingService;
use App\Mail\ContributionReceiptMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\ContributionType;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    protected $snipeService;
    protected $messagingService;

    public function __construct(SnippePaymentService $snipeService, MessagingService $messagingService)
    {
        $this->snipeService = $snipeService;
        $this->messagingService = $messagingService;
    }

    public function index(Request $request)
    {
        $query = Contribution::with('member');

        // Time Filters
        $period = $request->get('period', 'month'); // week, month, year, all
        if ($period == 'week') {
            $query->whereBetween('contribution_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('contribution_date', now()->month)
                  ->whereYear('contribution_date', now()->year);
        } elseif ($period == 'year') {
            $query->whereYear('contribution_date', now()->year);
        }

        // Search & Other Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%$search%")
                  ->orWhereHas('member', function($mq) use ($search) {
                      $mq->where('full_name', 'like', "%$search%")
                        ->orWhere('registration_number', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('contribution_type', $request->type);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('date')) {
            $query->whereDate('contribution_date', $request->date);
        }

        $contributions = $query->latest()->paginate(15);
        $totalContributions = Contribution::sum('amount');
        $thisMonthContributions = Contribution::whereMonth('contribution_date', now()->month)->sum('amount');
        $pendingReceipts = Contribution::where('is_verified', false)->count();
        $contributionsCount = Contribution::count();

        // Data for Pie Chart (Distribution by Type)
        $typeDistribution = Contribution::select('contribution_type', DB::raw('SUM(amount) as total'))
            ->groupBy('contribution_type')
            ->get()
            ->map(function($item) {
                return [
                    'label' => ucfirst(str_replace('_', ' ', $item->contribution_type)),
                    'value' => (float)$item->total
                ];
            });

        // Data for Trend Line Chart
        $trendQuery = Contribution::selectRaw('DATE(contribution_date) as date, SUM(amount) as total')
            ->where('contribution_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendData = [
            'labels' => $trendQuery->pluck('date')->map(fn($d) => date('M d', strtotime($d)))->toArray(),
            'values' => $trendQuery->pluck('total')->toArray()
        ];

        // Monthly Summary for Comparison
        $monthlySummary = Contribution::selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->whereYear('contribution_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = array_fill(1, 12, 0);
        foreach ($monthlySummary as $month => $total) {
            $chartData[$month] = (float)$total;
        }

        $contributionTypes = ContributionType::where('is_active', true)->get();
        
        return view('finance.index', compact(
            'contributions', 'totalContributions', 'thisMonthContributions', 
            'pendingReceipts', 'contributionsCount', 'chartData', 'typeDistribution', 
            'trendData', 'contributionTypes', 'period'
        ));
    }

    public function create()
    {
        $members = Member::all();
        return view('finance.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'contribution_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'contribution_date' => 'required|date',
            'payment_method' => 'required|string', // cash, mobile_money, card, dynamic-qr
            'notes' => 'nullable|string',
        ]);

        $member = Member::find($validated['member_id']);
        $receiptNumber = 'RCP-' . date('Y') . '-' . str_pad(Contribution::count() + 1, 4, '0', STR_PAD_LEFT);
        
        $contributionData = [
            'member_id' => $validated['member_id'],
            'contribution_type' => $validated['contribution_type'],
            'amount' => $validated['amount'],
            'contribution_date' => $validated['contribution_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $request->notes,
            'receipt_number' => $receiptNumber,
            'recorded_by' => auth()->id(),
            'is_verified' => $validated['payment_method'] === 'cash',
        ];

        // Handle digital payments via Snipe
        if (in_array($validated['payment_method'], ['mobile_money', 'card', 'dynamic-qr'])) {
            $contribution = Contribution::create($contributionData);
            
            if ($validated['payment_method'] === 'mobile_money') {
                $response = $this->snipeService->createMobileMoneyPayment($contribution);
                
                if (isset($response['error'])) {
                    return back()->with('error', 'Payment failed: ' . $response['error']);
                }

                $this->sendContributionNotifications($contribution);
                return redirect()->route('finance.index')->with('success', 'Payment initiated! Please check the member\'s phone for the USSD prompt.');
            }

            $checkoutResponse = $this->snipeService->createCheckout($contribution);

            if ($checkoutResponse && isset($checkoutResponse['checkout_url'])) {
                // Send notifications (maybe wait for confirmation, but we send 'initiated' for now)
                $this->sendContributionNotifications($contribution);
                return redirect($checkoutResponse['checkout_url']);
            }

            return back()->with('error', 'Failed to initiate payment session. Please check your configuration.');
        }

        $contribution = Contribution::create($contributionData);

        // Send notifications
        $this->sendContributionNotifications($contribution);

        return redirect()->route('finance.index')->with('success', 'Contribution recorded successfully');
    }

    /**
     * Send SMS and Email notifications for a contribution
     */
    protected function sendContributionNotifications(Contribution $contribution)
    {
        $member = $contribution->member;
        $amount = number_format($contribution->amount, 0);
        $type = ucfirst(str_replace('_', ' ', $contribution->contribution_type));
        
        // 1. Send SMS
        if ($member->phone) {
            try {
                $smsMessage = "Dear {$member->full_name}, thank you for your contribution of TZS {$amount} for {$type}. Receipt: {$contribution->receipt_number}. God bless you!";
                $this->messagingService->sendSms($member->phone, $smsMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send contribution SMS: " . $e->getMessage());
            }
        }

        // 2. Send Email with PDF attachment
        if ($member->email) {
            try {
                $pdf = Pdf::loadView('finance.receipt_pdf', compact('contribution'))->output();
                Mail::to($member->email)->send(new ContributionReceiptMailable($contribution, $pdf));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send contribution email: " . $e->getMessage());
            }
        }
    }

    public function show(Contribution $finance)
    {
        $contribution = $finance;
        $contribution->load(['member', 'recorder']);
        return view('finance.show', compact('contribution'));
    }

    public function edit(Contribution $finance)
    {
        $contribution = $finance;
        $members = Member::all();
        return view('finance.edit', compact('contribution', 'members'));
    }

    public function update(Request $request, Contribution $finance)
    {
        $contribution = $finance;
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'contribution_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'contribution_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $contribution->update($validated);

        return redirect()->route('finance.index')->with('success', 'Contribution updated successfully');
    }

    public function destroy(Contribution $finance)
    {
        $contribution = $finance;
        $contribution->delete();

        return redirect()->route('finance.index')->with('success', 'Contribution deleted successfully');
    }

    public function receipt(Contribution $contribution)
    {
        // Check if the user is a member and ensure they can only access their own receipts
        if (auth()->user()->member) {
            if (auth()->user()->member->id !== $contribution->member_id) {
                abort(403, 'Unauthorized access to this receipt.');
            }
        } else {
            // For non-member users (Admins/Finance), check for global permission
            if (!auth()->user()->hasPermission('finance.view')) {
                abort(403, 'Unauthorized access to financial records.');
            }
        }

        $contribution->load('member');

        // Check if the request wants a PDF download or a web view
        if (request()->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.receipt_pdf', compact('contribution'));
            
            // Sanitize filename to remove slashes
            $safeReceiptNo = str_replace(['/', '\\'], '-', $contribution->receipt_number);
            return $pdf->download("Receipt_{$safeReceiptNo}.pdf");
        }

        // Return a professional web view of the receipt (reusing finance.show or a dedicated member view)
        if (auth()->user()->member) {
            return view('member.profile.receipt_view', compact('contribution'));
        }

        return view('finance.show', compact('contribution'));
    }

    public function reports()
    {
        return view('finance.reports');
    }

    public function settings()
    {
        return view('finance.settings');
    }
}
