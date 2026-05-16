@extends('layouts.app')

@section('title', 'Contribution Details - TmcsSmart')
@section('page-title', 'Contribution')
@section('breadcrumb', 'TmcsSmart / Finance / Contribution')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: CONTRIBUTION INFO -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-green-600 to-green-400"></div>
                <div class="card-body -mt-12 text-center">
                    <div class="relative inline-block mb-4">
                        <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg mx-auto">
                            <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center text-white text-3xl font-bold">
                                {{ $contribution->member ? substr($contribution->member->full_name, 0, 2) : 'NA' }}
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold">{{ $contribution->member->full_name ?? 'Anonymous / System' }}</h3>
                    <p class="text-xs text-muted mb-4">{{ $contribution->member->registration_number ?? 'N/A' }}</p>
                    
                    <div class="flex items-center justify-center gap-2">
                        <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
                            {{ $contribution->is_verified ? 'Verified' : 'Pending Verification' }}
                        </span>
                    </div>
                </div>
                <div class="border-t border-light p-4">
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Amount</label>
                            <p class="text-xl font-bold text-green-600">TZS {{ number_format($contribution->amount, 0) }}</p>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $words = $f->format($contribution->amount);
                            @endphp
                            <p class="text-[10px] text-muted italic mt-1 capitalize">{{ $words }} Tanzanian Shillings Only</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Type</label>
                                <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Date</label>
                                <p class="text-sm font-medium">{{ $contribution->contribution_date ? $contribution->contribution_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Method</label>
                                <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Receipt No.</label>
                                <p class="text-sm font-medium mono">{{ $contribution->receipt_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="card">
                <div class="card-header"><h4 class="card-title">Verification QR</h4></div>
                <div class="card-body text-center">
                    @php
                        $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                        $qrContent .= "MEMBER: " . ($contribution->member->full_name ?? 'N/A') . "\n";
                        $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                        $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                    @endphp
                    <div class="p-4 bg-white rounded-xl border border-light inline-block mb-4">
                        <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->margin(0)->generate($qrContent)) !!} ">
                    </div>
                    <p class="text-[10px] text-muted">Scan to verify this transaction authenticity using any QR scanner.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4 class="card-title">Quick Actions</h4></div>
                <div class="card-body space-y-2">
                    <a href="{{ route('finance.receipt', ['contribution' => $contribution->id]) }}" target="_blank" class="btn btn-secondary w-full flex items-center justify-center gap-2">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Download Receipt
                    </a>
                    <button class="btn btn-secondary w-full flex items-center justify-center gap-2">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Send SMS
                    </button>
                    <a href="{{ route('finance.edit', ['finance' => $contribution->id]) }}" class="btn btn-secondary w-full flex items-center justify-center gap-2 text-blue-600">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Transaction
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT: ADDITIONAL DETAILS -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Transaction Audit</h3></div>
                <div class="card-body">
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-lg bg-light flex items-center justify-center text-muted">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Recorded By</h4>
                                <p class="text-xs text-muted mt-1">{{ $contribution->recorder->name ?? 'System' }}</p>
                                <p class="text-[10px] text-muted italic">{{ $contribution->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-lg bg-light flex items-center justify-center text-muted">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold">Internal Notes / Comments</h4>
                                <p class="text-xs text-muted mt-1 bg-light p-3 rounded-lg border border-light">
                                    {{ $contribution->notes ?? 'No internal notes provided for this transaction.' }}
                                </p>
                            </div>
                        </div>

                        @if($contribution->transaction_reference)
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-lg bg-light flex items-center justify-center text-muted">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">External Reference</h4>
                                <p class="text-xs text-muted mt-1 mono">{{ $contribution->transaction_reference }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-amber-50 border-amber-100">
                <div class="card-body flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900">Need to reconcile this transaction?</h4>
                        <p class="text-xs text-amber-800">You can mark this transaction for manual reconciliation if there's a discrepancy between church records and bank statements.</p>
                        <button class="text-xs font-bold text-amber-900 mt-2 underline">Start Reconciliation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
