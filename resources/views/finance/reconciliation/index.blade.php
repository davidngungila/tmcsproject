@extends('layouts.app')

@section('title', 'Reconciliation - TmcsSmart')
@section('page-title', 'Financial Reconciliation')
@section('breadcrumb', 'TmcsSmart / Finance / Reconciliation')

@section('content')
<div class="animate-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold">Reconciliation History</h2>
            <p class="text-sm text-muted mt-1">Audit and match internal records with bank statements</p>
        </div>
        <a href="{{ route('reconciliation.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            New Reconciliation
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ref. ID</th>
                        <th>Period</th>
                        <th>Opening</th>
                        <th>Income</th>
                        <th>Expenses</th>
                        <th>Closing</th>
                        <th>Difference</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $rec)
                    <tr>
                        <td class="mono text-xs">{{ $rec->reference_id }}</td>
                        <td class="text-[10px]">{{ $rec->period_start->format('M d') }} - {{ $rec->period_end->format('M d, Y') }}</td>
                        <td class="text-xs">TZS {{ number_format($rec->opening_balance, 0) }}</td>
                        <td class="text-green-600 font-bold text-xs">+{{ number_format($rec->total_income, 0) }}</td>
                        <td class="text-red-600 font-bold text-xs">-{{ number_format($rec->total_expenses, 0) }}</td>
                        <td class="font-black text-xs">TZS {{ number_format($rec->closing_balance, 0) }}</td>
                        <td>
                            @if($rec->difference == 0)
                                <span class="badge green uppercase font-black text-[9px]">Matched</span>
                            @else
                                <span class="badge red uppercase font-black text-[9px]">Diff: {{ number_format($rec->difference, 0) }}</span>
                            @endif
                        </td>
                        <td><span class="badge blue uppercase font-black text-[9px]">{{ $rec->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-8 text-muted">No reconciliation records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $reconciliations->links() }}</div>
    </div>
</div>
@endsection
