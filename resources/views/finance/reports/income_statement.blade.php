@extends('layouts.app')

@section('content')
<div class="animate-in max-w-4xl mx-auto space-y-6">
    <div class="card overflow-hidden">
        <div class="p-8 text-center border-b border-gray-100 bg-white">
            <div class="w-20 h-20 bg-primary/5 text-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-primary/10">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase mb-1">St. Joseph the Worker Chaplaincy</h1>
            <p class="text-[11px] text-primary font-black uppercase tracking-[0.2em] mb-4">Catholic Community of Moshi Co-operative University</p>
            
            <div class="inline-block px-8 py-2 bg-gray-50 border border-gray-100 rounded-xl">
                <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest">Income Statement (P&L)</h2>
                <p class="text-[10px] text-muted font-bold mt-1">For the Financial Year Ended December 31, {{ $year }}</p>
            </div>
        </div>

        <div class="p-6 border-b flex items-center justify-between bg-white">
            <form action="{{ route('finance.reports.income_statement') }}" method="GET" class="flex gap-2">
                <select name="year" class="form-control text-xs py-1.5" onchange="this.form.submit()">
                    @for($i = date('Y'); $i >= 2023; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>FY {{ $i }}</option>
                    @endfor
                </select>
            </form>
            <div class="flex gap-2">
                <a href="{{ route('finance.reports.income_statement', ['year' => $year, 'download' => 1]) }}" class="btn btn-secondary btn-sm flex items-center gap-2">
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
            <div class="space-y-8">
                <!-- REVENUE -->
                <section>
                    <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">REVENUE & INCOME</h4>
                    <div class="space-y-3">
                        @foreach($revenueAccounts as $account)
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-gray-600">{{ $account->name }}</span>
                            <span class="font-mono font-bold">{{ number_format($account->period_balance, 2) }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between items-center text-sm font-black border-t-2 border-gray-100 pt-3 mt-4">
                            <span class="text-gray-900">TOTAL OPERATING REVENUE</span>
                            <span class="text-lg">TZS {{ number_format($totalRevenue, 2) }}</span>
                        </div>
                    </div>
                </section>

                <!-- EXPENSES -->
                <section>
                    <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">OPERATING EXPENSES</h4>
                    <div class="space-y-3">
                        @foreach($expenseAccounts as $account)
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-gray-600">{{ $account->name }}</span>
                            <span class="font-mono text-red-600 font-bold">({{ number_format($account->period_balance, 2) }})</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between items-center text-sm font-black border-t-2 border-gray-100 pt-3 mt-4">
                            <span class="text-gray-900">TOTAL OPERATING EXPENSES</span>
                            <span class="text-lg text-red-600">TZS ({{ number_format($totalExpenses, 2) }})</span>
                        </div>
                    </div>
                </section>

                <!-- NET INCOME -->
                <section class="bg-gray-900 text-white p-6 rounded-3xl shadow-xl shadow-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Final Result</p>
                            <h4 class="font-black text-xl">NET SURPLUS / (DEFICIT)</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black {{ $netIncome >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $netIncome < 0 ? '(' . number_format(abs($netIncome), 2) . ')' : number_format($netIncome, 2) }} TZS
                            </span>
                        </div>
                    </div>
                </section>
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
