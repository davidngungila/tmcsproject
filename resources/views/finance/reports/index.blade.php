@extends('layouts.app')

@section('title', 'Financial Reports - TmcsSmart')
@section('page-title', 'Financial Analysis & Reports')
@section('breadcrumb', 'TmcsSmart / Finance / Reports')

@section('content')
<div class="animate-in space-y-6">
    <!-- FILTERS -->
    <div class="card border-none shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-6">
            <form action="{{ route('finance.reports') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex-center text-white">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white">Financial Insights</h2>
                        <p class="text-xs text-green-100 font-bold uppercase tracking-widest">Yearly Performance Analysis</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <select name="year" class="bg-white/10 border-white/20 text-white font-black text-sm rounded-xl py-2 px-4 focus:ring-0 cursor-pointer" onchange="this.form.submit()">
                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }} class="text-gray-800">{{ $i }} Financial Year</option>
                        @endfor
                    </select>
                    <button type="button" class="bg-white text-green-700 font-black text-[10px] uppercase tracking-widest px-6 py-2.5 rounded-xl shadow-lg hover:scale-105 transition-all">
                        Export PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-all duration-700"></div>
            <div class="relative">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Income</div>
                <div class="text-2xl font-black text-gray-800 mt-2">TZS {{ number_format($totalIncome, 0) }}</div>
                <div class="text-[9px] font-bold text-green-600 mt-3 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                    Revenue generated in {{ $year }}
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-all duration-700"></div>
            <div class="relative">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Expenses</div>
                <div class="text-2xl font-black text-gray-800 mt-2">TZS {{ number_format($totalExpenses, 0) }}</div>
                <div class="text-[9px] font-bold text-red-600 mt-3 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    Total spending for {{ $year }}
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-all duration-700"></div>
            <div class="relative">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Net Balance</div>
                <div class="text-2xl font-black {{ $netBalance >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-2">TZS {{ number_format($netBalance, 0) }}</div>
                <div class="text-[9px] font-bold text-blue-600 mt-3 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    {{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }} for the year
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-all duration-700"></div>
            <div class="relative">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Health Ratio</div>
                <div class="text-2xl font-black text-amber-600 mt-2">{{ $totalIncome > 0 ? round(($netBalance / $totalIncome) * 100, 1) : 0 }}%</div>
                <div class="text-[9px] font-bold text-amber-600 mt-3 flex items-center gap-1">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Financial efficiency score
                </div>
            </div>
        </div>
    </div>

    <!-- QUARTERLY SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($quarterlyIncome as $q => $income)
        <div class="card p-4 border-none shadow-sm bg-gray-50/50">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $q }} Performance</span>
                <span class="badge {{ ($income - $quarterlyExpense[$q]) >= 0 ? 'green' : 'red' }} text-[8px] font-black">
                    {{ round(($income > 0 ? (($income - $quarterlyExpense[$q]) / $income) * 100 : 0), 1) }}%
                </span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <span class="text-[9px] font-bold text-muted">Income:</span>
                    <span class="text-xs font-black text-gray-700">{{ number_format($income, 0) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[9px] font-bold text-muted">Expense:</span>
                    <span class="text-xs font-black text-red-600">{{ number_format($quarterlyExpense[$q], 0) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- MAIN CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- BAR CHART -->
        <div class="lg:col-span-8 card border-none shadow-sm p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Income vs Expenses Trend</h3>
                    <p class="text-[10px] text-muted font-bold mt-1">Monthly cash flow comparison for {{ $year }}</p>
                </div>
                <div class="flex bg-gray-50 rounded-lg p-1">
                    <button class="px-3 py-1 text-[9px] font-black uppercase bg-white rounded-md shadow-sm">Bar</button>
                    <button class="px-3 py-1 text-[9px] font-black uppercase text-gray-400">Line</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>

        <!-- PIE CHART (INCOME BY TYPE) -->
        <div class="lg:col-span-4 card border-none shadow-sm p-6">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-8 text-center">Income Distribution</h3>
            <div class="h-64 flex-center">
                <canvas id="incomePieChart"></canvas>
            </div>
            <div class="mt-8 space-y-3">
                @foreach($incomeByCategory->take(4) as $index => $item)
                <div class="flex items-center justify-between text-[10px] font-bold">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" style="background-color: {{ ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'][$index % 5] }}"></span>
                        <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</span>
                    </div>
                    <span class="text-gray-800">{{ $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 1) : 0 }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECONDARY CHARTS & TABLES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- NET PROFIT LINE CHART -->
        <div class="lg:col-span-4 card border-none shadow-sm p-6">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-8">Monthly Net Profit</h3>
            <div class="h-64">
                <canvas id="profitChart"></canvas>
            </div>
            <div class="mt-6 p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                <div class="text-[9px] font-black uppercase text-blue-600 tracking-widest mb-1">Growth Forecast</div>
                <p class="text-[10px] text-gray-600 font-bold leading-relaxed">Based on current trends, the financial health is stable with a {{ $totalIncome > 0 ? round(($netBalance / $totalIncome) * 100, 1) : 0 }}% retention rate.</p>
            </div>
        </div>

        <!-- TOP CONTRIBUTORS TABLE -->
        <div class="lg:col-span-8 card border-none shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Top Contributors ({{ $year }})</h3>
                <button class="text-[10px] font-black text-green-600 uppercase tracking-widest hover:underline">View All</button>
            </div>
            <div class="table-wrap">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Member Name</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">ID #</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Frequency</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($topContributors as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex-center font-black text-[10px]">
                                        {{ substr($item->member->full_name, 0, 2) }}
                                    </div>
                                    <div class="text-xs font-black text-gray-800">{{ $item->member->full_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">{{ $item->member->registration_number }}</td>
                            <td class="px-6 py-4"><span class="badge green uppercase text-[9px] font-black">HIGH</span></td>
                            <td class="px-6 py-4 text-right text-xs font-black text-green-600">TZS {{ number_format($item->total, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- EXPENSE BREAKDOWN & LARGE EXPENSES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12">
        <div class="card border-none shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Expense by Category</h3>
            </div>
            <div class="table-wrap">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($expenseByCategory as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-black text-gray-800">{{ $item->category }}</td>
                            <td class="px-6 py-4 text-right text-xs font-black text-red-600">TZS {{ number_format($item->total, 0) }}</td>
                            <td class="px-6 py-4 text-right text-[10px] font-bold text-gray-400">{{ $totalExpenses > 0 ? round(($item->total / $totalExpenses) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-none shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Top 10 Largest Expenses</h3>
            </div>
            <div class="table-wrap">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($largeExpenses as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-xs font-black text-gray-800">{{ Str::limit($item->description, 30) }}</div>
                                <div class="text-[9px] text-muted font-bold uppercase">{{ $item->category }}</div>
                            </td>
                            <td class="px-6 py-4 text-[10px] font-bold text-gray-500">{{ $item->expense_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right text-xs font-black text-red-600">TZS {{ number_format($item->amount, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6', drawBorder: false },
                ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af' }
            }
        }
    };

    // Comparison Chart (Income vs Expenses)
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    { 
                        label: 'Income', 
                        data: @json(array_values($incomeChart)), 
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barThickness: 12
                    },
                    { 
                        label: 'Expenses', 
                        data: @json(array_values($expenseChart)), 
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        barThickness: 12
                    }
                ]
            },
            options: chartOptions
        });
    }

    // Income Distribution Pie Chart
    const incomePieCtx = document.getElementById('incomePieChart');
    if (incomePieCtx) {
        new Chart(incomePieCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeByCategory->pluck('category')->map(fn($c) => ucfirst(str_replace('_', ' ', $c)))) !!},
                datasets: [{
                    data: @json($incomeByCategory->pluck('total')),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // Profit Chart (Line)
    const profitCtx = document.getElementById('profitChart');
    if (profitCtx) {
        new Chart(profitCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Net Balance',
                    data: @json(array_values($profitChart)),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: chartOptions
        });
    }
});
</script>
@endpush
