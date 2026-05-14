@extends('layouts.app')

@section('title', 'Expenses - TmcsSmart')
@section('page-title', 'Expense Management')
@section('breadcrumb', 'TmcsSmart / Finance / Expenses')

@section('content')
<div class="animate-in">
    <!-- FILTERS & STATS -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-3">
            <div class="card h-full">
                <div class="card-header flex items-center justify-between">
                    <div>
                        <h3 class="card-title">Expense Overview ({{ $year }})</h3>
                        <p class="card-subtitle">Monthly expenditure trends</p>
                    </div>
                    <form action="{{ route('expenses.index') }}" method="GET" class="flex gap-2">
                        <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="expenseChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <div class="stat-card red h-full">
                <div class="stat-icon red">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-value">TZS {{ number_format($totalExpenses, 0) }}</div>
                <div class="stat-label">Total Expenses ({{ $year }})</div>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm w-full mt-4">Record New Expense</a>
            </div>
        </div>
    </div>

    <!-- EXPENSE TABLE -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="card-title">Expense Transactions</h3>
            <div class="flex gap-2">
                <button class="btn btn-secondary btn-sm">Export PDF</button>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Voucher</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="mono text-xs">{{ $expense->voucher_number }}</td>
                        <td><span class="badge blue">{{ $expense->category }}</span></td>
                        <td class="text-sm">{{ Str::limit($expense->description, 30) }}</td>
                        <td class="font-bold text-red-600">TZS {{ number_format($expense->amount, 0) }}</td>
                        <td class="text-sm text-muted">{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td><span class="badge {{ $expense->status == 'Approved' ? 'green' : ($expense->status == 'Rejected' ? 'red' : 'amber') }}">{{ $expense->status }}</span></td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-ghost btn-sm p-1"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-ghost btn-sm p-1"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-muted">No expenses found for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $expenses->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('expenseChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Expenditure',
                    data: @json(array_values($chartData)),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
</script>
@endpush
