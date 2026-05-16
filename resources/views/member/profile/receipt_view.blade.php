@extends('layouts.app')

@section('title', 'View Receipt - TMCS Smart')
@section('page-title', 'Payment Receipt')
@section('breadcrumb', 'Home / Profile / Receipt')

@section('content')
<div class="animate-in max-w-2xl mx-auto">
    <div class="card mb-6 overflow-hidden">
        <div class="h-2 bg-green-500"></div>
        <div class="card-body">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold">Contribution Receipt</h3>
                    <p class="text-xs text-muted">Receipt No: {{ $contribution->receipt_number }}</p>
                </div>
                <div class="text-right">
                    <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
                        {{ $contribution->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-light/50 rounded-xl">
                        <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Amount Paid</label>
                        <div class="text-xl font-black text-green-600">TZS {{ number_format($contribution->amount) }}</div>
                        @php
                            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                            $words = $f->format($contribution->amount);
                        @endphp
                        <div class="text-[9px] text-muted italic mt-1 capitalize">{{ $words }} Shillings Only</div>
                    </div>
                    <div class="p-4 bg-light/50 rounded-xl">
                        <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Payment Date</label>
                        <div class="text-sm font-bold">{{ $contribution->contribution_date->format('d M, Y') }}</div>
                        <div class="text-[10px] text-muted">{{ $contribution->contribution_date->format('h:i A') }}</div>
                    </div>
                </div>

                <div class="p-4 border border-light rounded-xl">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Purpose</span>
                            <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Payment Method</span>
                            <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</span>
                        </div>
                        @if($contribution->transaction_reference)
                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Reference</span>
                            <span class="font-mono text-xs">{{ $contribution->transaction_reference }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="text-center py-6">
                    @php
                        $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                        $qrContent .= "MEMBER: " . $contribution->member->full_name . "\n";
                        $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                        $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                    @endphp
                    <div class="inline-block p-3 bg-white border border-light rounded-2xl mb-2">
                        <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->margin(0)->generate($qrContent)) !!} " class="w-32 h-32">
                    </div>
                    <p class="text-[10px] text-muted">Scan to verify authenticity</p>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light/30 border-t p-4 flex gap-3">
            <a href="{{ route('member.profile.index') }}" class="btn btn-secondary flex-1">Back to Profile</a>
            <a href="{{ route('finance.receipt', ['contribution' => $contribution->id, 'download' => 1]) }}" class="btn btn-primary flex-1">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </a>
        </div>
    </div>
</div>
@endsection
