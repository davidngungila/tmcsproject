<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialStatementController extends Controller
{
    /**
     * Display the Income Statement (Profit & Loss)
     */
    public function incomeStatement(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $revenueAccounts = Account::where('type', 'Revenue')->get();
        $expenseAccounts = Account::where('type', 'Expense')->get();
        
        $totalRevenue = 0;
        $totalExpenses = 0;

        foreach ($revenueAccounts as $account) {
            $account->period_balance = LedgerEntry::where('account_id', $account->id)
                ->whereYear('transaction_date', $year)
                ->sum('credit') - LedgerEntry::where('account_id', $account->id)
                ->whereYear('transaction_date', $year)
                ->sum('debit');
            $totalRevenue += $account->period_balance;
        }

        foreach ($expenseAccounts as $account) {
            $account->period_balance = LedgerEntry::where('account_id', $account->id)
                ->whereYear('transaction_date', $year)
                ->sum('debit') - LedgerEntry::where('account_id', $account->id)
                ->whereYear('transaction_date', $year)
                ->sum('credit');
            $totalExpenses += $account->period_balance;
        }

        $netIncome = $totalRevenue - $totalExpenses;

        if ($request->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.reports.income_statement_pdf', compact(
                'revenueAccounts', 'expenseAccounts', 'totalRevenue', 'totalExpenses', 'netIncome', 'year'
            ));
            return $pdf->download("Income_Statement_{$year}.pdf");
        }

        return view('finance.reports.income_statement', compact(
            'revenueAccounts', 'expenseAccounts', 'totalRevenue', 'totalExpenses', 'netIncome', 'year'
        ));
    }

    /**
     * Display the Statement of Financial Position (Balance Sheet)
     */
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->get('date', date('Y-m-d'));
        
        $assetAccounts = Account::where('type', 'Asset')->get();
        $liabilityAccounts = Account::where('type', 'Liability')->get();
        $equityAccounts = Account::where('type', 'Equity')->get();
        
        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquity = 0;

        foreach ($assetAccounts as $account) {
            $account->current_balance = LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('debit') - LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('credit');
            $totalAssets += $account->current_balance;
        }

        foreach ($liabilityAccounts as $account) {
            $account->current_balance = LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('credit') - LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('debit');
            $totalLiabilities += $account->current_balance;
        }

        // Calculate Net Income for the period to add to Equity
        $totalRevenue = LedgerEntry::whereHas('account', function($q) { $q->where('type', 'Revenue'); })
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('credit') - LedgerEntry::whereHas('account', function($q) { $q->where('type', 'Revenue'); })
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('debit');
            
        $totalExpenses = LedgerEntry::whereHas('account', function($q) { $q->where('type', 'Expense'); })
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('debit') - LedgerEntry::whereHas('account', function($q) { $q->where('type', 'Expense'); })
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('credit');
            
        $retainedEarnings = $totalRevenue - $totalExpenses;

        foreach ($equityAccounts as $account) {
            $account->current_balance = LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('credit') - LedgerEntry::where('account_id', $account->id)
                ->where('transaction_date', '<=', $asOfDate)
                ->sum('debit');
            
            if ($account->code === '3000') { // Retained Earnings
                $account->current_balance += $retainedEarnings;
            }
            
            $totalEquity += $account->current_balance;
        }

        if ($request->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.reports.balance_sheet_pdf', compact(
                'assetAccounts', 'liabilityAccounts', 'equityAccounts', 
                'totalAssets', 'totalLiabilities', 'totalEquity', 'asOfDate'
            ));
            return $pdf->download("Balance_Sheet_{$asOfDate}.pdf");
        }

        return view('finance.reports.balance_sheet', compact(
            'assetAccounts', 'liabilityAccounts', 'equityAccounts', 
            'totalAssets', 'totalLiabilities', 'totalEquity', 'asOfDate'
        ));
    }
}
