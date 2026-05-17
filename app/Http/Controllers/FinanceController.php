<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Member;
use App\Models\Account;
use App\Models\LedgerEntry;
use App\Services\SnippePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\MessagingService;
use App\Mail\ContributionReceiptMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\SendSmsJob;

use App\Models\Expense;
use App\Models\ContributionType;

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

        // Data for Trend Line Chart (Last 30 days)
        $trendQuery = Contribution::selectRaw('DATE(contribution_date) as date, SUM(amount) as total')
            ->where('contribution_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $expenseTrendQuery = Expense::selectRaw('DATE(expense_date) as date, SUM(amount) as total')
            ->where('expense_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendData = [
            'labels' => $trendQuery->pluck('date')->map(fn($d) => date('M d', strtotime($d)))->toArray(),
            'revenue' => $trendQuery->pluck('total')->toArray(),
            'expenses' => []
        ];

        // Map daily expenses to the same labels as revenue
        $expenseMap = $expenseTrendQuery->pluck('total', 'date')->toArray();
        foreach ($trendQuery->pluck('date') as $date) {
            $trendData['expenses'][] = $expenseMap[$date] ?? 0;
        }

        // Monthly Summary for Comparison
        $monthlySummary = Contribution::selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->whereYear('contribution_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = array_fill(1, 12, 0);
        foreach ($monthlySummary as $month => $total) {
            $chartData[(int)$month] = (float)$total;
        }

        // Expense Summary for Comparison
        $expenseSummary = Expense::selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->whereYear('expense_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $expenseChart = array_fill(1, 12, 0);
        foreach ($expenseSummary as $month => $total) {
            $expenseChart[(int)$month] = (float)$total;
        }

        $contributionTypes = ContributionType::where('is_active', true)->get();
        
        return view('finance.index', compact(
            'contributions', 'totalContributions', 'thisMonthContributions', 
            'pendingReceipts', 'contributionsCount', 'chartData', 'expenseChart', 'typeDistribution', 
            'trendData', 'contributionTypes', 'period'
        ));
    }

    public function create()
    {
        $members = Member::where('is_active', true)->get();
        $contributionTypes = ContributionType::where('is_active', true)->get();
        return view('finance.create', compact('members', 'contributionTypes'));
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
            'recorded_by' => Auth::id(),
            'is_verified' => $validated['payment_method'] === 'cash',
            'verified_at' => $validated['payment_method'] === 'cash' ? now() : null,
            'verified_by' => $validated['payment_method'] === 'cash' ? Auth::id() : null,
        ];

        DB::beginTransaction();
        try {
            // Handle digital payments via Snipe
            if (in_array($validated['payment_method'], ['mobile_money', 'card', 'dynamic-qr'])) {
                $contribution = Contribution::create($contributionData);
                
                if ($validated['payment_method'] === 'mobile_money') {
                    $response = $this->snipeService->createMobileMoneyPayment($contribution);
                    
                    if (isset($response['error'])) {
                        DB::rollBack();
                        return back()->with('error', 'Payment failed: ' . $response['error']);
                    }

                    DB::commit();
                    $this->sendContributionNotifications($contribution);
                    return redirect()->route('finance.index')->with('success', 'Payment initiated! Please check the member\'s phone for the USSD prompt.');
                }

                $checkoutResponse = $this->snipeService->createCheckout($contribution);

                if ($checkoutResponse && isset($checkoutResponse['checkout_url'])) {
                    DB::commit();
                    $this->sendContributionNotifications($contribution);
                    return redirect($checkoutResponse['checkout_url']);
                }

                DB::rollBack();
                return back()->with('error', 'Failed to initiate payment session. Please check your configuration.');
            }

            $contribution = Contribution::create($contributionData);

            // If verified (Cash), create accounting entries
            if ($contribution->is_verified) {
                $this->createAccountingEntries($contribution);
            }

            DB::commit();
            $this->sendContributionNotifications($contribution);
            return redirect()->route('finance.index')->with('success', 'Contribution recorded successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contribution Store Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while recording the contribution: ' . $e->getMessage());
        }
    }

    /**
     * Verify a contribution and create accounting entries
     */
    public function verify(Contribution $finance)
    {
        $contribution = $finance;

        if ($contribution->is_verified) {
            return back()->with('warning', 'This contribution is already verified.');
        }

        DB::beginTransaction();
        try {
            $contribution->update([
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            $this->createAccountingEntries($contribution);

            DB::commit();
            return back()->with('success', 'Contribution verified successfully and accounting entries recorded.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contribution Verification Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to verify contribution: ' . $e->getMessage());
        }
    }

    /**
     * Create double-entry accounting records for a contribution
     */
    protected function createAccountingEntries(Contribution $contribution)
    {
        // 1. Determine Debit Account (Asset: Cash or Bank)
        $debitAccountCode = $contribution->payment_method === 'cash' ? '1000' : '1100';
        $debitAccount = Account::where('code', $debitAccountCode)->first();

        // 2. Determine Credit Account (Revenue based on contribution type)
        $creditAccountCode = match ($contribution->contribution_type) {
            'Tithe' => '4000',
            'Offering' => '4100',
            'Special' => '4200',
            'Harvest' => '4300',
            default => '4900',
        };
        $creditAccount = Account::where('code', $creditAccountCode)->first();

        if (!$debitAccount || !$creditAccount) {
            throw new \Exception("Accounting accounts not found (Debit: $debitAccountCode, Credit: $creditAccountCode). Please run seeder.");
        }

        // 3. Create Debit Entry
        LedgerEntry::create([
            'account_id' => $debitAccount->id,
            'transaction_date' => $contribution->contribution_date,
            'description' => "Contribution Receipt: {$contribution->receipt_number} - {$contribution->member->full_name}",
            'debit' => $contribution->amount,
            'credit' => 0,
            'reference_type' => 'Contribution',
            'reference_id' => $contribution->id,
            'recorded_by' => Auth::id(),
        ]);

        // 4. Create Credit Entry
        LedgerEntry::create([
            'account_id' => $creditAccount->id,
            'transaction_date' => $contribution->contribution_date,
            'description' => "Contribution Receipt: {$contribution->receipt_number} - {$contribution->member->full_name}",
            'debit' => 0,
            'credit' => $contribution->amount,
            'reference_type' => 'Contribution',
            'reference_id' => $contribution->id,
            'recorded_by' => Auth::id(),
        ]);

        // 5. Update Account Balances
        $debitAccount->increment('balance', $contribution->amount);
        $creditAccount->increment('balance', $contribution->amount);
    }

    /**
     * Send SMS and Email notifications for a contribution
     */
    protected function sendContributionNotifications(Contribution $contribution)
    {
        $member = $contribution->member;
        $amount = number_format($contribution->amount, 0);
        $type = ucfirst(str_replace('_', ' ', $contribution->contribution_type));
        
        // 1. Send SMS (Queued)
        if ($member->phone) {
            $smsMessage = "Dear {$member->full_name}, thank you for your contribution of TZS {$amount} for {$type}. Receipt: {$contribution->receipt_number}. God bless you!";
            SendSmsJob::dispatch($member->phone, $smsMessage);
        }

        // 2. Send Email with PDF attachment (Queued)
        if ($member->email) {
            // The mailable is Queued, and it will generate the PDF internally in its attachments() method
            Mail::to($member->email)->queue(new ContributionReceiptMailable($contribution));
        }
    }

    public function show(Contribution $finance)
    {
        $contribution = $finance;
        $contribution->load(['member', 'recorder', 'verifier']);
        
        $ledgerEntries = LedgerEntry::with('account')
            ->where('reference_type', 'Contribution')
            ->where('reference_id', $contribution->id)
            ->get();

        return view('finance.show', compact('contribution', 'ledgerEntries'));
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
        $user = Auth::user();
        /** @var \App\Models\User $user */

        // Check if the user is a member and ensure they can only access their own receipts
        if ($user->member) {
            if ($user->member->id !== $contribution->member_id) {
                abort(403, 'Unauthorized access to this receipt.');
            }
        } else {
            // For non-member users (Admins/Finance), check for global permission
            if (!$user->hasPermission('finance.view')) {
                abort(403, 'Unauthorized access to financial records.');
            }
        }

        $contribution->load(['member', 'recorder', 'verifier']);

        $ledgerEntries = LedgerEntry::with('account')
            ->where('reference_type', 'Contribution')
            ->where('reference_id', $contribution->id)
            ->get();

        // Generate PDF for the receipt
        if (request()->has('download')) {
            $pdf = Pdf::loadView('finance.receipt_pdf', compact('contribution', 'ledgerEntries'));
            
            // Sanitize filename to remove slashes
            $safeReceiptNo = str_replace(['/', '\\'], '-', $contribution->receipt_number);
            
            return $pdf->download("Receipt_{$safeReceiptNo}.pdf");
        }

        // Return a professional web view of the receipt (reusing finance.show or a dedicated member view)
        if ($user->member) {
            return view('member.profile.receipt_view', compact('contribution', 'ledgerEntries'));
        }

        return view('finance.show', compact('contribution', 'ledgerEntries'));
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
