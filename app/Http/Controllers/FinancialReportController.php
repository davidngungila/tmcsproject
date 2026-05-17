<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Income stats
        $incomeData = Contribution::whereYear('contribution_date', $year)
            ->selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        // Expense stats
        $expenseData = Expense::whereYear('expense_date', $year)
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $incomeChart = array_fill(1, 12, 0);
        $expenseChart = array_fill(1, 12, 0);
        $profitChart = array_fill(1, 12, 0);
        
        foreach ($incomeData as $m => $total) $incomeChart[(int)$m] = (float)$total;
        foreach ($expenseData as $m => $total) $expenseChart[(int)$m] = (float)$total;
        for ($i=1; $i<=12; $i++) $profitChart[$i] = $incomeChart[$i] - $expenseChart[$i];
        
        $totalIncome = array_sum($incomeChart);
        $totalExpenses = array_sum($expenseChart);
        $netBalance = $totalIncome - $totalExpenses;
        
        // Quarterly breakdown
        $quarterlyIncome = [
            'Q1' => array_sum(array_slice($incomeChart, 0, 3)),
            'Q2' => array_sum(array_slice($incomeChart, 3, 3)),
            'Q3' => array_sum(array_slice($incomeChart, 6, 3)),
            'Q4' => array_sum(array_slice($incomeChart, 9, 3)),
        ];

        $quarterlyExpense = [
            'Q1' => array_sum(array_slice($expenseChart, 0, 3)),
            'Q2' => array_sum(array_slice($expenseChart, 3, 3)),
            'Q3' => array_sum(array_slice($expenseChart, 6, 3)),
            'Q4' => array_sum(array_slice($expenseChart, 9, 3)),
        ];
        
        // Category breakdown
        $incomeByCategory = Contribution::whereYear('contribution_date', $year)
            ->selectRaw('contribution_type as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();
            
        $expenseByCategory = Expense::whereYear('expense_date', $year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Payment Method distribution
        $paymentMethods = Contribution::whereYear('contribution_date', $year)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Top Contributors
        $topContributors = Contribution::whereYear('contribution_date', $year)
            ->with('member')
            ->selectRaw('member_id, SUM(amount) as total')
            ->groupBy('member_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Recent large expenses
        $largeExpenses = Expense::whereYear('expense_date', $year)
            ->orderBy('amount', 'desc')
            ->limit(10)
            ->get();

        return view('finance.reports.index', compact(
            'year', 'incomeChart', 'expenseChart', 'profitChart',
            'totalIncome', 'totalExpenses', 'netBalance',
            'incomeByCategory', 'expenseByCategory',
            'quarterlyIncome', 'quarterlyExpense',
            'paymentMethods', 'topContributors', 'largeExpenses'
        ));
    }
}
