@extends('layouts.app')

@section('title', 'Financial Reports - TmcsSmart')
@section('page-title', 'Financial Analysis & Reports')
@section('breadcrumb', 'TmcsSmart / Finance / Reports')

@section('content')
<div class="animate-in">
    <!-- FILTERS -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('finance.reports') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="form-group mb-0">
                    <label class="form-label">Report Year</label>
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }} Financial Year</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1"></div>
                <button type="button" class="btn btn-secondary flex items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Full Report
                </button>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="stat-grid mb-6">
        <div class="stat-card green">
            <div class="stat-label">Total Income</div>
            <div class="stat-value">TZS {{ number_format($totalIncome, 0) }}</div>
            <div class="text-[10px] text-muted mt-1">Total church revenue for {{ $year }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Total Expenses</div>
            <div class="stat-value">TZS {{ number_format($totalExpenses, 0) }}</div>
            <div class="text-[10px] text-muted mt-1">Operational costs and charity</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Net Balance</div>
            <div class="stat-value">TZS {{ number_format($netBalance, 0) }}</div>
            <div class="text-[10px] text-muted mt-1">Surplus/Deficit for the period</div>
        </div>
        <div class="stat-card gold">
            <div class="stat-label">Financial Health</div>
            <div class="stat-value">{{ $totalIncome > 0 ? round(($netBalance / $totalIncome) * 100, 1) : 0 }}%</div>
            <div class="text-[10px] text-muted mt-1">Net profit margin ratio</div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Income vs Expenses Trend</h3></div>
            <div class="card-body">
                <canvas id="comparisonChart" height="200"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Monthly Net Profit</h3></div>
            <div class="card-body">
                <canvas id="profitChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- BREAKDOWN TABLES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Income by Category</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Category</th><th class="text-right">Total Amount</th><th class="text-right">%</th></tr></thead>
                    <tbody>
                        @foreach($incomeByCategory as $item)
                        <tr>
                            <td class="text-sm">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</td>
                            <td class="text-right font-bold">TZS {{ number_format($item->total, 0) }}</td>
                            <td class="text-right text-xs text-muted">{{ $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Expense by Category</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Category</th><th class="text-right">Total Amount</th><th class="text-right">%</th></tr></thead>
                    <tbody>
                        @foreach($expenseByCategory as $item)
                        <tr>
                            <td class="text-sm">{{ $item->category }}</td>
                            <td class="text-right font-bold text-red-600">TZS {{ number_format($item->total, 0) }}</td>
                            <td class="text-right text-xs text-muted">{{ $totalExpenses > 0 ? round(($item->total / $totalExpenses) * 100, 1) : 0 }}%</td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    { label: 'Income', data: @json(array_values($incomeChart)), backgroundColor: '#10b981' },
                    { label: 'Expenses', data: @json(array_values($expenseChart)), backgroundColor: '#ef4444' }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } } 
            }
        });
    }

    // Profit Chart
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
                    tension: 0.4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } } 
            }
        });
    }
});
</script>
@endpush
