@extends('layouts.app')

@section('title', 'Transaction Details - TmcsSmart')
@section('page-title', 'Contribution Details')
@section('breadcrumb', 'Member / Contributions / View')

@section('content')
<div class="animate-in max-w-5xl mx-auto">
    <!-- Top Alert for Status -->
    @if(!$contribution->is_verified)
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4 text-amber-800">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Awaiting Verification</h4>
            <p class="text-xs opacity-80">This transaction has been recorded but not yet verified by the finance office. You will receive an official receipt once verified.</p>
        </div>
    </div>
    @else
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-4 text-green-800">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Transaction Verified</h4>
            <p class="text-xs opacity-80">This transaction has been verified and officially posted to the chaplaincy ledger.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden border-none shadow-xl">
                <div class="h-3 {{ $contribution->is_verified ? 'bg-green-600' : 'bg-amber-500' }}"></div>
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">TRANSACTION RECORD</h2>
                            <p class="text-[10px] text-muted font-black uppercase tracking-widest mt-1">St. Joseph the Worker Chaplaincy</p>
                        </div>
                        <div class="text-right">
                            <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Receipt ID</label>
                            <div class="text-xl font-mono font-black text-primary">#{{ $contribution->receipt_number }}</div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- Key Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Amount</label>
                                <div class="text-lg font-black text-green-600">TZS {{ number_format($contribution->amount, 0) }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Date</label>
                                <div class="text-sm font-bold text-gray-900">{{ $contribution->contribution_date->format('M d, Y') }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Type</label>
                                <div class="text-sm font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Method</label>
                                <div class="text-sm font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $contribution->payment_method) }}</div>
                            </div>
                        </div>

                        <!-- Member Info -->
                        <div class="mb-10 flex items-center gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl font-black">
                                {{ substr($contribution->member->full_name, 0, 1) }}
                            </div>
                            <div>
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block mb-1">Contributor</label>
                                <h3 class="text-lg font-black text-gray-900">{{ $contribution->member->full_name }}</h3>
                                <p class="text-xs text-muted font-bold">{{ $contribution->member->registration_number }}</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($contribution->notes)
                        <div class="mb-10">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-2">Transaction Notes</label>
                            <div class="p-6 bg-blue-50/30 border border-blue-100/50 rounded-3xl text-sm font-medium text-gray-800 leading-relaxed italic">
                                "{{ $contribution->notes }}"
                            </div>
                        </div>
                        @endif

                        <!-- Ledger Entries (Only if verified) -->
                        @if($contribution->is_verified && isset($ledgerEntries) && $ledgerEntries->count() > 0)
                        <div class="mb-6">
                            <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-3">Accounting Transparency</label>
                            <div class="table-wrap border rounded-2xl overflow-hidden">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-black uppercase tracking-widest text-[9px]">Account Affected</th>
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
                    <p class="text-[10px] font-bold tracking-widest opacity-60 uppercase">TMCS SMART MEMBER PORTAL</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- QR Verification -->
            <div class="card p-6 text-center">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">Digital Receipt Seal</h4>
                @php
                    $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                    $qrContent .= "MEMBER: " . ($contribution->member->full_name) . "\n";
                    $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                    $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                @endphp
                <div class="p-4 bg-white rounded-3xl border border-gray-100 shadow-inner inline-block mb-4">
                    <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(140)->margin(0)->generate($qrContent)) !!} " class="w-32 h-32">
                </div>
                <p class="text-[10px] text-muted font-bold leading-relaxed px-4">Scan this code to verify the authenticity of this transaction record.</p>
            </div>

            <!-- Audit Trail -->
            <div class="card p-6">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6 flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21a11.955 11.955 0 01-8.618-3.04m17.236 0A11.955 11.955 0 0112 21c-4.474 0-8.064-2.095-9.618-5.04"/></svg>
                    Activity Log
                </h4>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100 flex-shrink-0">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-muted font-black uppercase tracking-widest mb-1">Initiated By</div>
                            <div class="text-sm font-bold text-gray-900">{{ $contribution->recorder->name ?? 'Member Portal' }}</div>
                            <div class="text-[10px] text-muted italic mt-0.5">{{ $contribution->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>

                    @if($contribution->is_verified)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center border border-green-100 flex-shrink-0">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-muted font-black uppercase tracking-widest mb-1">Verified By</div>
                            <div class="text-sm font-bold text-gray-900">{{ $contribution->verifier->name ?? 'Finance Dept' }}</div>
                            <div class="text-[10px] text-muted italic mt-0.5">{{ $contribution->verified_at ? $contribution->verified_at->format('M d, Y \a\t h:i A') : 'Processed' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-6">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">Available Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('finance.receipt', ['contribution' => $contribution->id, 'download' => 1]) }}" target="_blank" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        DOWNLOAD RECEIPT
                    </a>

                    <button onclick="window.print()" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT RECORD
                    </button>
                    
                    <a href="{{ route('member.contributions.index') }}" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-xs">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        BACK TO LIST
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
