@extends('layouts.app')

@section('title', 'Perform Reconciliation - TmcsSmart')
@section('page-title', 'Financial Reconciliation')
@section('breadcrumb', 'TmcsSmart / Finance / Reconciliation / Create')

@section('content')
<div class="animate-in">
    <div class="max-w-6xl mx-auto">
        <form action="{{ route('reconciliation.store') }}" method="POST">
            @csrf
            
            <!-- HEADER INFO -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">New Audit Entry</h2>
                    <p class="text-sm text-muted font-medium">Verify system records against bank statement: <span class="text-green-600 font-bold">{{ \Carbon\Carbon::parse($start)->format('M d') }} - {{ \Carbon\Carbon::parse($end)->format('M d, Y') }}</span></p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary px-6">Cancel Audit</a>
                    <button type="submit" class="btn btn-primary px-8 shadow-lg shadow-green-100">Finalize & Lock Period</button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- LEFT: SYSTEM RECORDS -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="card shadow-sm border-none bg-white">
                        <div class="card-header bg-gray-50/50 border-b p-6">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">System Transaction Logs</h3>
                        </div>
                        <div class="card-body p-6">
                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div class="p-4 rounded-2xl bg-green-50 border border-green-100">
                                    <div class="text-[10px] font-black uppercase text-green-600 tracking-widest mb-1">Total Income</div>
                                    <div class="text-xl font-black text-green-700">TZS {{ number_format($totalIncome, 0) }}</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-red-50 border border-red-100">
                                    <div class="text-[10px] font-black uppercase text-red-600 tracking-widest mb-1">Total Expenses</div>
                                    <div class="text-xl font-black text-red-700">TZS {{ number_format($totalExpenses, 0) }}</div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- INCOME BREAKDOWN -->
                                <div>
                                    <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                        Income Categorization
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($incomeByType as $income)
                                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                                            <span class="text-sm font-bold text-gray-600">{{ $income->contribution_type }}</span>
                                            <span class="text-sm font-black text-green-600">+{{ number_format($income->total, 0) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- EXPENSE BREAKDOWN -->
                                <div>
                                    <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                        Expense Categorization
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($expenseByCategory as $expense)
                                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                                            <span class="text-sm font-bold text-gray-600">{{ $expense->category }}</span>
                                            <span class="text-sm font-black text-red-600">-{{ number_format($expense->total, 0) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-green-900 p-6 rounded-b-2xl">
                            <div class="flex items-center justify-between text-white">
                                <span class="text-xs font-bold uppercase tracking-widest opacity-60">Projected Net Change</span>
                                <span class="text-2xl font-black">TZS {{ number_format($totalIncome - $totalExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: BANK VERIFICATION -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="card shadow-lg border-green-100 bg-white sticky top-6">
                        <div class="card-header bg-green-50/30 border-b p-6">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-green-600">Bank Verification</h3>
                        </div>
                        <div class="card-body p-8">
                            <div class="space-y-6">
                                <div class="form-group">
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Opening Balance (From Statement)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-xs font-black text-gray-400">TZS</span>
                                        </div>
                                        <input type="number" name="opening_balance" class="form-control pl-12 py-4 rounded-xl border-gray-200 font-black text-lg focus:ring-green-500 focus:border-green-500" value="{{ $openingBalance }}" step="0.01" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Closing Balance (Actual Statement)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-xs font-black text-gray-400">TZS</span>
                                        </div>
                                        <input type="number" name="closing_balance" class="form-control pl-12 py-4 rounded-xl border-gray-200 font-black text-lg focus:ring-green-500 focus:border-green-500" value="{{ $closingBalance }}" step="0.01" required>
                                    </div>
                                </div>

                                <div class="p-6 bg-green-50 rounded-2xl border border-green-100">
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex-center shrink-0">
                                            <svg width="20" height="20" class="text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase text-green-900 tracking-wider mb-1">Audit Compliance</p>
                                            <p class="text-xs text-green-700 leading-relaxed font-medium">Verify that the <span class="font-bold">Closing Balance</span> matches the final figure on your physical bank statement to ensure no missing transactions.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Auditor's Remarks</label>
                                    <textarea name="notes" class="form-control rounded-xl border-gray-200 p-4 text-sm" rows="4" placeholder="Mention any discrepancies or internal findings..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="period_start" value="{{ $start }}">
            <input type="hidden" name="period_end" value="{{ $end }}">
            <input type="hidden" name="total_income" value="{{ $totalIncome }}">
            <input type="hidden" name="total_expenses" value="{{ $totalExpenses }}">
        </form>
    </div>
</div>
@endsection
