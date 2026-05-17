@extends('layouts.app')

@section('content')
<div class="animate-in max-w-4xl mx-auto space-y-6">
    <div class="card overflow-hidden">
        <div class="p-8 text-center border-b border-gray-100 bg-white">
            <div class="w-20 h-20 bg-emerald-100/30 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-emerald-100">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase mb-1">St. Joseph the Worker Chaplaincy</h1>
            <p class="text-[11px] text-primary font-black uppercase tracking-[0.2em] mb-4">Catholic Community of Moshi Co-operative University</p>
            
            <div class="inline-block px-8 py-2 bg-gray-50 border border-gray-100 rounded-xl">
                <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest">Statement of Financial Position</h2>
                <p class="text-[10px] text-muted font-bold mt-1">As at {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="p-6 border-b flex items-center justify-between bg-white">
            <form action="{{ route('finance.reports.balance_sheet') }}" method="GET" class="flex gap-2">
                <input type="date" name="date" value="{{ $asOfDate }}" class="form-control text-xs py-1.5" onchange="this.form.submit()">
            </form>
            <div class="flex gap-2">
                <a href="{{ route('finance.reports.balance_sheet', ['date' => $asOfDate, 'download' => 1]) }}" class="btn btn-secondary btn-sm flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export PDF
                </a>
                <button onclick="window.print()" class="btn btn-secondary btn-sm flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print
                </button>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- ASSETS SIDE -->
                <div class="space-y-8">
                    <section>
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">ASSETS</h4>
                        <div class="space-y-3">
                            @foreach($assetAccounts as $account)
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-gray-600">{{ $account->name }}</span>
                                <span class="font-mono font-bold">{{ number_format($account->current_balance, 2) }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between items-center text-sm font-black border-t-2 border-gray-100 pt-3 mt-4">
                                <span class="text-gray-900">TOTAL ASSETS</span>
                                <span class="text-lg">TZS {{ number_format($totalAssets, 2) }}</span>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- LIABILITIES & EQUITY SIDE -->
                <div class="space-y-8">
                    <section>
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">LIABILITIES</h4>
                        <div class="space-y-3">
                            @foreach($liabilityAccounts as $account)
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-gray-600">{{ $account->name }}</span>
                                <span class="font-mono font-bold">{{ number_format($account->current_balance, 2) }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between items-center text-sm font-black border-t-2 border-gray-100 pt-3 mt-4">
                                <span class="text-gray-900">TOTAL LIABILITIES</span>
                                <span class="text-lg">TZS {{ number_format($totalLiabilities, 2) }}</span>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">EQUITY</h4>
                        <div class="space-y-3">
                            @foreach($equityAccounts as $account)
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-gray-600">{{ $account->name }}</span>
                                <span class="font-mono font-bold">{{ number_format($account->current_balance, 2) }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between items-center text-sm font-black border-t-2 border-gray-100 pt-3 mt-4">
                                <span class="text-gray-900">TOTAL EQUITY</span>
                                <span class="text-lg">TZS {{ number_format($totalEquity, 2) }}</span>
                            </div>
                        </div>
                    </section>

                    <section class="bg-gray-900 text-white p-6 rounded-3xl shadow-xl shadow-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Total Balancing</p>
                                <h4 class="font-black text-sm">LIABILITIES & EQUITY</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-black text-emerald-400">
                                    {{ number_format($totalLiabilities + $totalEquity, 2) }} TZS
                                </span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="mr-2 h-2 w-2 text-emerald-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                        Accounts in Balance
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                        <svg class="mr-2 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                        Out of Balance: {{ number_format($totalAssets - ($totalLiabilities + $totalEquity), 2) }}
                    </span>
                @endif
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 flex justify-between items-end">
                <div class="text-center w-48">
                    <div class="border-b-2 border-gray-900 mb-2"></div>
                    <p class="text-[9px] uppercase font-black tracking-widest text-gray-500">Finance Secretary</p>
                </div>
                <div class="text-center w-48">
                    <div class="border-b-2 border-gray-900 mb-2"></div>
                    <p class="text-[9px] uppercase font-black tracking-widest text-gray-500">Church Treasurer</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
