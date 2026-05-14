<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $category = $request->get('category');

        $query = Expense::with('recorder')->whereYear('expense_date', $year);

        if ($month) {
            $query->whereMonth('expense_date', $month);
        }
        if ($category) {
            $query->where('category', $category);
        }

        $expenses = $query->latest()->paginate(15);
        $totalExpenses = $query->sum('amount');
        
        // Data for charts
        $monthlyData = Expense::whereYear('expense_date', $year)
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyData as $m => $total) {
            $chartData[$m] = (float)$total;
        }

        return view('finance.expenses.index', compact('expenses', 'totalExpenses', 'chartData', 'year'));
    }

    public function create()
    {
        return view('finance.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['voucher_number'] = 'EXP-' . date('Ymd') . '-' . str_pad(Expense::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['recorded_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('expenses', 'public');
            $validated['attachment'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully');
    }

    public function show(Expense $expense)
    {
        return view('finance.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('finance.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|string|in:Pending,Approved,Rejected',
        ]);

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $path = $request->file('attachment')->store('expenses', 'public');
            $validated['attachment'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');
    }
}
