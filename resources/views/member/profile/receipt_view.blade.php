@extends('layouts.app')

@section('title', 'View Receipt - TMCS Smart')
@section('page-title', 'Payment Receipt')
@section('breadcrumb', 'Home / Profile / Receipt')

@section('content')
<div class="animate-in max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- MAIN RECEIPT COLUMN -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden border-none shadow-xl">
                <div class="h-3 bg-green-600"></div>
                <div class="card-body p-0">
                    <!-- Receipt Header -->
                    <div class="p-8 text-center border-b border-gray-50 bg-gray-50/30">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Official Payment Receipt</h2>
                        <p class="text-sm text-muted font-bold mt-1 uppercase tracking-widest">ST. JOSEPH THE WORKER CHAPLAINCY</p>
                    </div>

                    <div class="p-8">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Receipt Number</label>
                                <div class="text-lg font-mono font-bold text-blue-600">#{{ $contribution->receipt_number }}</div>
                            </div>
                            <div class="text-right">
                                <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Date Issued</label>
                                <div class="text-sm font-bold text-gray-900">{{ $contribution->contribution_date->format('d M, Y') }}</div>
                                <div class="text-[10px] text-muted">{{ $contribution->contribution_date->format('h:i A') }}</div>
                            </div>
                        </div>

                        <!-- Amount Focus -->
                        <div class="mb-10 p-6 bg-green-50 rounded-[2rem] border border-green-100 text-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <svg width="100" height="100" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            </div>
                            <label class="text-[10px] text-green-600 uppercase font-black tracking-widest block mb-2">Total Amount Contributed</label>
                            <div class="text-4xl font-black text-green-700 tracking-tighter">TZS {{ number_format($contribution->amount) }}</div>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $words = $f->format($contribution->amount);
                            @endphp
                            <div class="text-xs text-green-600/70 font-bold italic mt-2 capitalize">"{{ $words }} Tanzanian Shillings Only"</div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Contributed By</label>
                                    <div class="text-sm font-bold text-gray-900">{{ strtoupper($contribution->member->full_name) }}</div>
                                    <div class="text-xs text-muted">{{ $contribution->member->registration_number }}</div>
                                </div>
                                <div>
                                    <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Purpose of Giving</label>
                                    <div class="text-sm font-bold text-gray-900">{{ strtoupper(str_replace('_', ' ', $contribution->contribution_type)) }}</div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Payment Method</label>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                        <div class="text-sm font-bold text-gray-900">{{ strtoupper(str_replace('_', ' ', $contribution->payment_method)) }}</div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[10px] text-muted uppercase font-black tracking-widest block mb-1">Verification Status</label>
                                    <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }} px-3 py-1 text-[10px] font-black uppercase">
                                        {{ $contribution->is_verified ? 'CONFIRMED' : 'PENDING' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- System Reference -->
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <label class="text-[9px] text-muted uppercase font-black tracking-widest block">Transaction Reference</label>
                                <div class="text-xs font-mono font-bold text-gray-600">{{ $contribution->transaction_reference ?? 'INTERNAL-'.$contribution->id }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] text-muted italic">Verified by TMCS SMART</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 text-white p-6 text-center">
                    <p class="text-[10px] font-bold tracking-widest opacity-60">THANK YOU FOR YOUR GENEROUS CONTRIBUTION</p>
                </div>
            </div>
        </div>

        <!-- SIDEBAR: QR & ACTIONS -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card text-center">
                <div class="card-header border-b">
                    <h4 class="card-title text-sm font-black uppercase tracking-widest">Secure Verification</h4>
                </div>
                <div class="card-body">
                    @php
                        $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                        $qrContent .= "MEMBER: " . $contribution->member->full_name . "\n";
                        $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                        $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                    @endphp
                    <div class="inline-block p-4 bg-white border-2 border-gray-50 rounded-[2rem] shadow-sm mb-4">
                        <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->margin(0)->generate($qrContent)) !!} " class="w-40 h-40">
                    </div>
                    <p class="text-[10px] text-muted leading-relaxed px-4">
                        Scan this QR code with any mobile device to verify the authenticity of this receipt.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-b">
                    <h4 class="card-title text-sm font-black uppercase tracking-widest">Actions</h4>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('finance.receipt', ['contribution' => $contribution->id, 'download' => 1]) }}" class="btn btn-primary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-sm shadow-lg shadow-green-100">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download PDF
                    </a>
                    <a href="{{ route('member.contributions.index') }}" class="btn btn-secondary w-full py-4 rounded-2xl flex items-center justify-center gap-3 font-black text-sm">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        History
                    </a>
                </div>
            </div>

            <!-- HELP CARD -->
            <div class="p-6 bg-blue-600 rounded-[2rem] text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-20 transform rotate-12">
                    <svg width="120" height="120" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
                </div>
                <h5 class="font-black text-sm mb-2 uppercase tracking-widest">Need Help?</h5>
                <p class="text-[10px] leading-relaxed opacity-90">
                    If you notice any discrepancy in your contribution records, please contact the Treasury office immediately with your receipt number.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
