@extends('layouts.app')

@section('title', 'Contribution Details - TmcsSmart')
@section('page-title', 'Contribution Receipt')
@section('breadcrumb', 'Finance / Contributions / View')

@section('content')
<div class="animate-in max-w-5xl mx-auto pb-20">
    <!-- Top Alert for Status -->
    @if(!$contribution->is_verified)
    <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex items-center gap-4 text-amber-800 dark:text-amber-200">
        <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-black text-sm uppercase tracking-tight">Awaiting Verification</h4>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">This contribution has been recorded but not yet verified. Accounting entries will be generated upon verification.</p>
        </div>
    </div>
    @else
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-900/50 rounded-2xl flex items-center gap-4 text-green-800 dark:text-green-200">
        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-black text-sm uppercase tracking-tight">Contribution Verified</h4>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">This transaction has been verified and posted to the general ledger.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Receipt Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden border-none shadow-2xl bg-white dark:bg-gray-800">
                <!-- Status Strip -->
                <div class="h-3 {{ $contribution->is_verified ? 'bg-green-600' : 'bg-amber-500' }}"></div>
                
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="p-10 border-b border-gray-50 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/20 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white shadow-xl shadow-primary/20">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">OFFICIAL RECEIPT</h2>
                                <p class="text-[10px] text-muted font-black uppercase tracking-widest mt-1">St. Joseph the Worker Chaplaincy</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right w-full md:w-auto p-4 md:p-0 bg-gray-100 dark:bg-gray-700 md:bg-transparent rounded-2xl">
                            <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Receipt Number</label>
                            <div class="text-2xl font-mono font-black text-primary">{{ $contribution->receipt_number }}</div>
                        </div>
                    </div>

                    <div class="p-10">
                        <!-- Key Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                            <div class="p-5 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-2">Total Amount</label>
                                <div class="text-xl font-black text-green-600">TZS {{ number_format($contribution->amount, 0) }}</div>
                            </div>
                            <div class="p-5 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-2">Transaction Date</label>
                                <div class="text-sm font-black text-gray-900 dark:text-gray-200">{{ $contribution->contribution_date->format('M d, Y') }}</div>
                            </div>
                            <div class="p-5 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-2">Income Type</label>
                                <div class="text-sm font-black text-gray-900 dark:text-gray-200 uppercase tracking-wider">{{ str_replace('_', ' ', $contribution->contribution_type) }}</div>
                            </div>
                            <div class="p-5 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-2">Payment Mode</label>
                                <div class="text-sm font-black text-gray-900 dark:text-gray-200 uppercase tracking-wider">{{ str_replace('_', ' ', $contribution->payment_method) }}</div>
                            </div>
                        </div>

                        <!-- Contributor Details -->
                        <div class="mb-12">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-4">Contributor Information</label>
                            <div class="flex items-center gap-6 p-8 bg-primary/5 dark:bg-primary/10 rounded-[2.5rem] border border-primary/10 dark:border-primary/20">
                                <div class="w-20 h-20 rounded-3xl bg-white dark:bg-gray-800 text-primary flex items-center justify-center text-3xl font-black shadow-lg shadow-primary/5">
                                    {{ $contribution->member ? substr($contribution->member->full_name, 0, 1) : '?' }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1">{{ $contribution->member->full_name ?? 'Anonymous' }}</h3>
                                    <div class="flex flex-wrap gap-3">
                                        <span class="px-3 py-1 bg-white dark:bg-gray-800 rounded-full text-[10px] font-black uppercase tracking-widest text-muted border border-gray-100 dark:border-gray-700">Reg: {{ $contribution->member->registration_number ?? 'N/A' }}</span>
                                        @if($contribution->member->phone)
                                        <span class="px-3 py-1 bg-white dark:bg-gray-800 rounded-full text-[10px] font-black uppercase tracking-widest text-muted border border-gray-100 dark:border-gray-700">Tel: {{ $contribution->member->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Breakdown -->
                        <div class="mb-12">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-4">Transaction Details</label>
                            <div class="bg-gray-50 dark:bg-gray-900/40 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">Description</span>
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-500 text-right">Amount</span>
                                </div>
                                <div class="p-8 space-y-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight">{{ str_replace('_', ' ', $contribution->contribution_type) }} Contribution</div>
                                            @if($contribution->notes)
                                                <p class="text-xs text-muted font-bold mt-2 leading-relaxed italic">"{{ $contribution->notes }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-lg font-black text-gray-900 dark:text-white">TZS {{ number_format($contribution->amount, 0) }}</div>
                                    </div>
                                    <div class="pt-6 border-t border-dashed border-gray-300 dark:border-gray-600 flex justify-between items-center">
                                        <span class="text-sm font-black uppercase tracking-widest text-gray-400">Net Total</span>
                                        <span class="text-2xl font-black text-primary">TZS {{ number_format($contribution->amount, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ledger Entries (Double Entry) -->
                        @if($contribution->is_verified && isset($ledgerEntries) && $ledgerEntries->count() > 0)
                        <div class="mb-12 animate-in" style="--delay: 200ms">
                            <div class="flex items-center justify-between mb-4">
                                <label class="text-[10px] text-muted uppercase font-black tracking-widest block">Double Entry Journal Impact</label>
                                <span class="px-3 py-1 bg-green-500/10 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Balanced</span>
                            </div>
                            <div class="table-wrap border dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-100 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-black uppercase tracking-widest text-[9px] text-gray-500">GL Account</th>
                                            <th class="px-6 py-4 text-right font-black uppercase tracking-widest text-[9px] text-gray-500">Debit (TZS)</th>
                                            <th class="px-6 py-4 text-right font-black uppercase tracking-widest text-[9px] text-gray-500">Credit (TZS)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y dark:divide-gray-700">
                                        @foreach($ledgerEntries as $entry)
                                        <tr class="bg-white dark:bg-gray-800/50">
                                            <td class="px-6 py-4">
                                                <div class="font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight">{{ $entry->account->name }}</div>
                                                <div class="text-[10px] text-muted font-bold tracking-widest">{{ $entry->account->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-sm {{ $entry->debit > 0 ? 'text-gray-900 dark:text-white font-black' : 'text-gray-300 dark:text-gray-600' }}">
                                                {{ $entry->debit > 0 ? number_format($entry->debit, 0) : '0.00' }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-sm {{ $entry->credit > 0 ? 'text-gray-900 dark:text-white font-black' : 'text-gray-300 dark:text-gray-600' }}">
                                                {{ $entry->credit > 0 ? number_format($entry->credit, 0) : '0.00' }}
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
                
                <!-- Footer Info -->
                <div class="bg-gray-900 p-8 text-center">
                    <div class="text-[10px] font-black tracking-[0.3em] text-white opacity-40 uppercase mb-2">Financial Integrity & Transparency</div>
                    <p class="text-[9px] text-white opacity-30 font-bold uppercase tracking-widest">System ID: {{ $contribution->id }} • Hash: {{ substr(md5($contribution->receipt_number . $contribution->amount), 0, 16) }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:col-span-1 space-y-8">
            <!-- QR Verification -->
            <div class="card p-8 text-center bg-white dark:bg-gray-800 border-none shadow-xl rounded-[2rem]">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-8">Digital Signature</h4>
                @php
                    $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                    $qrContent .= "MEMBER: " . ($contribution->member->full_name ?? 'N/A') . "\n";
                    $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                    $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                @endphp
                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-inner inline-block mb-6">
                    <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(160)->margin(0)->generate($qrContent)) !!} " class="w-32 h-32 dark:invert">
                </div>
                <p class="text-[10px] text-muted font-bold leading-relaxed px-6 uppercase tracking-widest">Scan this cryptographic code to verify transaction authenticity.</p>
            </div>

            <!-- Audit Trail -->
            <div class="card p-8 bg-white dark:bg-gray-800 border-none shadow-xl rounded-[2rem]">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-8 flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21a11.955 11.955 0 01-8.618-3.04m17.236 0A11.955 11.955 0 0112 21c-4.474 0-8.064-2.095-9.618-5.04"/></svg>
                    Maker-Checker Audit
                </h4>
                <div class="space-y-8">
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600 flex-shrink-0">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div class="text-[9px] text-muted font-black uppercase tracking-widest mb-1">Recorded (Maker)</div>
                            <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $contribution->recorder->name ?? 'System' }}</div>
                            <div class="text-[10px] text-muted font-bold mt-1">{{ $contribution->created_at->format('M d, Y • h:i A') }}</div>
                        </div>
                    </div>

                    @if($contribution->is_verified)
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-green-500/10 text-green-600 flex items-center justify-center border border-green-500/20 flex-shrink-0">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[9px] text-muted font-black uppercase tracking-widest mb-1">Verified (Checker)</div>
                            <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $contribution->verifier->name ?? 'System' }}</div>
                            <div class="text-[10px] text-muted font-bold mt-1">{{ $contribution->verified_at ? $contribution->verified_at->format('M d, Y • h:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-8 bg-white dark:bg-gray-800 border-none shadow-xl rounded-[2rem]">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-8">System Operations</h4>
                <div class="space-y-4">
                    @if(!$contribution->is_verified)
                        <form action="{{ route('finance.verify', $contribution->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full py-5 rounded-[1.5rem] flex items-center justify-center gap-3 font-black text-xs shadow-xl shadow-green-500/20">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                APPROVE & POST TO GL
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('finance.receipt', ['contribution' => $contribution->id]) }}" target="_blank" class="btn btn-secondary w-full py-5 rounded-[1.5rem] flex items-center justify-center gap-3 font-black text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        EXPORT AS PDF
                    </a>

                    <button onclick="window.print()" class="btn btn-secondary w-full py-5 rounded-[1.5rem] flex items-center justify-center gap-3 font-black text-xs dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT VOUCHER
                    </button>
                    
                    <a href="{{ route('finance.index') }}" class="btn btn-ghost w-full py-5 rounded-[1.5rem] flex items-center justify-center gap-3 font-black text-xs text-muted hover:text-primary transition-all">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        RETURN TO LEDGER
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
