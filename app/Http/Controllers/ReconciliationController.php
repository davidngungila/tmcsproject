<?php

namespace App\Http\Controllers;

use App\Models\Reconciliation;
use App\Models\Contribution;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReconciliationController extends Controller
{
    public function index()
    {
        $reconciliations = Reconciliation::with('reconciler')->latest()->paginate(10);
        return view('finance.reconciliation.index', compact('reconciliations'));
    }

    public function create(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $totalIncome = Contribution::whereBetween('contribution_date', [$start, $end])->sum('amount');
        $totalExpenses = Expense::whereBetween('expense_date', [$start, $end])->sum('amount');
        
        $incomeByType = Contribution::whereBetween('contribution_date', [$start, $end])
            ->select('contribution_type', DB::raw('SUM(amount) as total'))
            ->groupBy('contribution_type')
            ->get();

        $expenseByCategory = Expense::whereBetween('expense_date', [$start, $end])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $openingBalance = 0; // In real app, get from previous reconciliation
        $closingBalance = $openingBalance + $totalIncome - $totalExpenses;

        return view('finance.reconciliation.create', compact(
            'start', 'end', 'totalIncome', 'totalExpenses', 
            'openingBalance', 'closingBalance', 'incomeByType', 'expenseByCategory'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'opening_balance' => 'required|numeric',
            'closing_balance' => 'required|numeric',
            'total_income' => 'required|numeric',
            'total_expenses' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['reference_id'] = 'REC-' . date('Ymd') . '-' . str_pad(Reconciliation::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['difference'] = $validated['closing_balance'] - ($validated['opening_balance'] + $validated['total_income'] - $validated['total_expenses']);
        $validated['reconciled_by'] = auth()->id();
        $validated['status'] = 'Completed';

        Reconciliation::create($validated);

        return redirect()->route('reconciliation.index')->with('success', 'Financial reconciliation completed');
    }

    public function show(Reconciliation $reconciliation)
    {
        return view('finance.reconciliation.show', compact('reconciliation'));
    }
}
