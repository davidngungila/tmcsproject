@extends('layouts.app')

@section('title', 'Expense Details - TmcsSmart')
@section('page-title', 'Expense Voucher')
@section('breadcrumb', 'Finance / Expenses / View')

@section('content')
<div class="animate-in max-w-5xl mx-auto">
    <!-- Top Alert for Status -->
    @if($expense->status == 'Pending')
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4 text-amber-800">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Awaiting Approval</h4>
            <p class="text-xs opacity-80">This expense has been recorded but not yet approved. Accounting entries will be generated upon approval.</p>
        </div>
    </div>
    @elseif($expense->status == 'Approved')
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-4 text-green-800">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Voucher Approved</h4>
            <p class="text-xs opacity-80">This expense has been verified and posted to the general ledger.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Voucher Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden border-none shadow-xl">
                <div class="h-3 {{ $expense->status == 'Approved' ? 'bg-green-600' : ($expense->status == 'Rejected' ? 'bg-red-600' : 'bg-amber-500') }}"></div>
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">EXPENSE VOUCHER</h2>
                            <p class="text-[10px] text-muted font-black uppercase tracking-widest mt-1">St. Joseph the Worker Chaplaincy</p>
                        </div>
                        <div class="text-right">
                            <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Voucher ID</label>
                            <div class="text-xl font-mono font-black text-primary">{{ $expense->voucher_number }}</div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Key Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Amount</label>
                                <div class="text-lg font-black text-red-600">TZS {{ number_format($expense->amount, 0) }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Date</label>
                                <div class="text-sm font-bold text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Category</label>
                                <div class="text-sm font-bold text-gray-900">{{ $expense->category }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Method</label>
                                <div class="text-sm font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $expense->payment_method) }}</div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-10">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-2">Purpose & Description</label>
                            <div class="p-6 bg-blue-50/30 border border-blue-100/50 rounded-3xl text-sm font-medium text-gray-800 leading-relaxed italic">
                                "{{ $expense->description }}"
                            </div>
                        </div>

                        <!-- Ledger Entries (Only if approved) -->
                        @if($expense->status == 'Approved' && isset($ledgerEntries) && $ledgerEntries->count() > 0)
                        <div class="mb-6">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-3">Accounting Impact (Double Entry)</label>
                            <div class="table-wrap border rounded-2xl overflow-hidden">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-black uppercase tracking-widest text-[9px]">Account</th>
                                            <th class="px-4 py-3 text-right font-black uppercase tracking-widest text-[9px]">Debit</th>
                                            <th class="px-4 py-3 text-right font-black uppercase tracking-widest text-[9px]">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($ledgerEntries as $entry)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="font-bold">{{ $entry->account->name }}</div>
                                                <div class="text-[10px] text-muted">{{ $entry->account->code }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono {{ $entry->debit > 0 ? 'text-gray-900 font-bold' : 'text-muted' }}">
                                                {{ $entry->debit > 0 ? number_format($entry->debit, 0) : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono {{ $entry->credit > 0 ? 'text-gray-900 font-bold' : 'text-muted' }}">
                                                {{ $entry->credit > 0 ? number_format($entry->credit, 0) : '-' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-900 text-white p-6 text-center">
                    <p class="text-[10px] font-bold tracking-widest opacity-60">TMCS SMART FINANCIAL MANAGEMENT SYSTEM</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Audit Trail -->
            <div class="card p-6">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6 flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21a11.955 11.955 0 01-8.618-3.04m17.236 0A11.955 11.955 0 0112 21c-4.474 0-8.064-2.095-9.618-5.04"/></svg>
                    Audit Trail
                </h4>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100 flex-shrink-0">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-muted font-black uppercase tracking-widest mb-1">Recorded By</div>
                            <div class="text-sm font-bold text-gray-900">{{ $expense->recorder->name ?? 'System' }}</div>
                            <div class="text-[10px] text-muted italic mt-0.5">{{ $expense->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>

                    @if($expense->status != 'Pending')
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $expense->status == 'Approved' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }} flex items-center justify-center border flex-shrink-0">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-muted font-black uppercase tracking-widest mb-1">{{ $expense->status }} By</div>
                            <div class="text-sm font-bold text-gray-900">Finance Dept.</div>
                            <div class="text-[10px] text-muted italic mt-0.5">{{ $expense->updated_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-6">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">Available Actions</h4>
                <div class="space-y-3">
                    @if($expense->status == 'Pending')
                        <form action="{{ route('expenses.approve', $expense->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs shadow-lg shadow-green-100">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                APPROVE & POST
                            </button>
                        </form>
                        <form action="{{ route('expenses.reject', $expense->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs text-red-600 hover:bg-red-50 border-red-100 transition-all">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                REJECT VOUCHER
                            </button>
                        </form>
                    @endif

                    @if($expense->attachment)
                    <a href="{{ Storage::url($expense->attachment) }}" target="_blank" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L19 14.5"/></svg>
                        VIEW RECEIPT
                    </a>
                    @endif

                    <a href="{{ route('expenses.show', ['expense' => $expense->id, 'download' => 1]) }}" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        EXPORT PDF
                    </a>

                    <button onclick="window.print()" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT VOUCHER
                    </button>
                    
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        BACK TO LIST
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
