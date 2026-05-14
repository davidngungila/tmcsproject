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
        
        foreach ($incomeData as $m => $total) $incomeChart[$m] = (float)$total;
        foreach ($expenseData as $m => $total) $expenseChart[$m] = (float)$total;
        for ($i=1; $i<=12; $i++) $profitChart[$i] = $incomeChart[$i] - $expenseChart[$i];
        
        $totalIncome = array_sum($incomeChart);
        $totalExpenses = array_sum($expenseChart);
        $netBalance = $totalIncome - $totalExpenses;
        
        // Category breakdown
        $incomeByCategory = Contribution::whereYear('contribution_date', $year)
            ->selectRaw('contribution_type as category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
            
        $expenseByCategory = Expense::whereYear('expense_date', $year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('finance.reports.index', compact(
            'year', 'incomeChart', 'expenseChart', 'profitChart',
            'totalIncome', 'totalExpenses', 'netBalance',
            'incomeByCategory', 'expenseByCategory'
        ));
    }
}
