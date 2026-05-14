<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Member;
use App\Services\SnipePaymentService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected $snipeService;

    public function __construct(SnipePaymentService $snipeService)
    {
        $this->snipeService = $snipeService;
    }

    public function index()
    {
        $contributions = Contribution::with('member')->latest()->paginate(10);
        $totalContributions = Contribution::sum('amount');
        $thisMonthContributions = Contribution::whereMonth('contribution_date', now()->month)->sum('amount');
        $pendingReceipts = Contribution::where('is_verified', false)->count();
        $contributionsCount = Contribution::count();

        // Data for monthly chart
        $monthlyContributions = Contribution::selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->whereYear('contribution_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyContributions as $month => $total) {
            $chartData[$month] = (float)$total;
        }
        
        return view('finance.index', compact(
            'contributions', 'totalContributions', 'thisMonthContributions', 
            'pendingReceipts', 'contributionsCount', 'chartData'
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
            $type = $validated['payment_method'] === 'mobile_money' ? 'mobile' : ($validated['payment_method'] === 'card' ? 'card' : 'dynamic-qr');
            
            $paymentResponse = $this->snipeService->initializePayment($type, [
                'amount' => $validated['amount'],
                'phone' => $member->phone,
                'name' => $member->full_name,
                'email' => $member->email,
                'reference' => $receiptNumber,
                'metadata' => [
                    'member_id' => $member->id,
                    'contribution_type' => $validated['contribution_type'],
                ]
            ]);

            if ($paymentResponse['status'] === 'success') {
                Contribution::create($contributionData);
                
                $message = 'Payment initialized successfully.';
                if (isset($paymentResponse['data']['payment_url'])) {
                    return redirect($paymentResponse['data']['payment_url']);
                }
                
                return redirect()->route('finance.index')->with('success', $message . ' Please follow the prompts on your device.');
            }

            return back()->with('error', $paymentResponse['message']);
        }

        Contribution::create($contributionData);

        return redirect()->route('finance.index')->with('success', 'Contribution recorded successfully');
    }

    public function show(Contribution $contribution)
    {
        $contribution->load(['member', 'recorder']);
        return view('finance.show', compact('contribution'));
    }

    public function edit(Contribution $contribution)
    {
        $members = Member::all();
        return view('finance.edit', compact('contribution', 'members'));
    }

    public function update(Request $request, Contribution $contribution)
    {
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

    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

        return redirect()->route('finance.index')->with('success', 'Contribution deleted successfully');
    }

    public function receipt(Contribution $contribution)
    {
        // Allow members to download their own receipts
        if (auth()->user()->member && auth()->user()->member->id !== $contribution->member_id) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $contribution->load('member');

        // Check if the request wants a PDF download or a web view
        if (request()->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.receipt_pdf', compact('contribution'));
            return $pdf->download("Receipt_{$contribution->receipt_number}.pdf");
        }

        return view('finance.receipt', compact('contribution'));
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
