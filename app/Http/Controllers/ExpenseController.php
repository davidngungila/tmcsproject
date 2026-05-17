<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Account;
use App\Models\LedgerEntry;
use App\Mail\GenericMailable;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $validated['recorded_by'] = Auth::id();
        $validated['status'] = 'Pending'; // Default to pending for approval flow

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('expenses', 'public');
            $validated['attachment'] = $path;
        }

        try {
            $expense = Expense::create($validated);
            return redirect()->route('expenses.index')->with('success', 'Expense request submitted successfully and is awaiting approval.');
        } catch (\Exception $e) {
            Log::error('Expense Store Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function approve(Expense $expense)
    {
        if ($expense->status !== 'Pending') {
            return back()->with('warning', 'This expense is already ' . $expense->status);
        }

        DB::beginTransaction();
        try {
            $expense->update([
                'status' => 'Approved'
            ]);

            // Create accounting entries when approved
            $this->createAccountingEntries($expense);

            DB::commit();

            // Load recorder for notification
            $expense->load('recorder');

            // Send Notifications
            $this->sendExpenseNotifications($expense, 'Approved');

            return back()->with('success', 'Expense approved and accounting records updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Expense Approval Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve expense: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Expense $expense)
    {
        if ($expense->status !== 'Pending') {
            return back()->with('warning', 'This expense is already ' . $expense->status);
        }

        $expense->update([
            'status' => 'Rejected'
        ]);

        // Load recorder for notification
        $expense->load('recorder');

        // Send Notifications
        $this->sendExpenseNotifications($expense, 'Rejected');

        return back()->with('success', 'Expense has been rejected.');
    }

    /**
     * Send notifications for expense status changes
     */
    protected function sendExpenseNotifications(Expense $expense, string $status)
    {
        $user = $expense->recorder;
        if (!$user) return;

        $amount = number_format($expense->amount, 0);
        $voucher = $expense->voucher_number;
        $subject = "Expense Voucher {$status}: {$voucher}";
        
        $message = "Dear {$user->name}, your expense request for TZS {$amount} ({$expense->description}) has been {$status}. Voucher: {$voucher}.";

        // 1. Send SMS
        if ($user->phone) {
            SendSmsJob::dispatch($user->phone, $message);
        }

        // 2. Send Email
        if ($user->email) {
            $emailContent = "
                <h2>Expense Request {$status}</h2>
                <p>Dear {$user->name},</p>
                <p>Your expense request has been <strong>" . strtolower($status) . "</strong> by the finance department.</p>
                <div style='padding: 20px; background: #f9f9f9; border-radius: 10px; margin: 20px 0;'>
                    <p><strong>Voucher No:</strong> {$voucher}</p>
                    <p><strong>Amount:</strong> TZS {$amount}</p>
                    <p><strong>Category:</strong> {$expense->category}</p>
                    <p><strong>Description:</strong> {$expense->description}</p>
                    <p><strong>Date:</strong> {$expense->expense_date->format('M d, Y')}</p>
                </div>
                <p>Thank you for using the TMCS Smart System.</p>
            ";
            Mail::to($user->email)->queue(new GenericMailable($subject, $emailContent));
        }
    }

    /**
     * Create double-entry accounting records for an expense
     */
    protected function createAccountingEntries(Expense $expense)
    {
        // 1. Determine Debit Account (Expense Category)
        $debitAccountCode = match ($expense->category) {
            'Salaries' => '5000',
            'Utilities' => '5100',
            'Maintenance' => '5200',
            'Charity' => '5300',
            'Administrative' => '5400',
            default => '5900',
        };
        $debitAccount = Account::where('code', $debitAccountCode)->first();

        // 2. Determine Credit Account (Asset: Cash or Bank)
        $creditAccountCode = $expense->payment_method === 'cash' ? '1000' : '1100';
        $creditAccount = Account::where('code', $creditAccountCode)->first();

        if (!$debitAccount || !$creditAccount) {
            throw new \Exception("Accounting accounts not found (Debit: $debitAccountCode, Credit: $creditAccountCode). Please run seeder.");
        }

        // 3. Create Debit Entry
        LedgerEntry::create([
            'account_id' => $debitAccount->id,
            'transaction_date' => $expense->expense_date,
            'description' => "Expense Voucher: {$expense->voucher_number} - {$expense->description}",
            'debit' => $expense->amount,
            'credit' => 0,
            'reference_type' => 'Expense',
            'reference_id' => $expense->id,
            'recorded_by' => Auth::id(),
        ]);

        // 4. Create Credit Entry
        LedgerEntry::create([
            'account_id' => $creditAccount->id,
            'transaction_date' => $expense->expense_date,
            'description' => "Expense Voucher: {$expense->voucher_number} - {$expense->description}",
            'debit' => 0,
            'credit' => $expense->amount,
            'reference_type' => 'Expense',
            'reference_id' => $expense->id,
            'recorded_by' => Auth::id(),
        ]);

        // 5. Update Account Balances
        $debitAccount->increment('balance', $expense->amount);
        $creditAccount->decrement('balance', $expense->amount);
    }

    public function show(Expense $expense)
    {
        $expense->load('recorder');
        
        $ledgerEntries = LedgerEntry::with('account')
            ->where('reference_type', 'Expense')
            ->where('reference_id', $expense->id)
            ->get();

        if (request()->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.expenses.voucher_pdf', compact('expense', 'ledgerEntries'));
            $safeVoucherNo = str_replace(['/', '\\'], '-', $expense->voucher_number);
            return $pdf->download("Voucher_{$safeVoucherNo}.pdf");
        }

        return view('finance.expenses.show', compact('expense', 'ledgerEntries'));
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
