@extends('layouts.app')

@section('title', 'My Contributions - TmcsSmart')
@section('page-title', 'Contribution History')
@section('breadcrumb', 'TmcsSmart / Member / Contributions')

@section('content')
<div class="animate-in">
  <div class="card overflow-hidden">
    <div class="card-header border-b flex items-center justify-between">
      <div>
        <div class="card-title">Full Contribution History</div>
        <div class="card-subtitle">Complete list of all your church offerings and tithes</div>
      </div>
      <a href="{{ route('member.profile.pay') }}" class="btn btn-primary btn-sm">Make New Payment</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-light/50 border-b">
          <tr>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Receipt #</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Date</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Type</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-right">Amount</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($contributions as $contribution)
            <tr class="hover:bg-light/30 transition-colors">
              <td class="px-6 py-4 text-sm font-mono text-xs">{{ $contribution->receipt_number }}</td>
              <td class="px-6 py-4 text-sm">{{ $contribution->contribution_date->format('d M, Y') }}</td>
              <td class="px-6 py-4 text-sm font-bold">{{ $contribution->contribution_type }}</td>
              <td class="px-6 py-4 text-sm font-bold text-right">{{ number_format($contribution->amount) }} TZS</td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <a href="#" class="btn btn-ghost btn-xs text-blue-500" title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                  <a href="{{ route('finance.receipt', $contribution->id) }}" target="_blank" class="btn btn-ghost btn-xs text-green-500" title="Download Receipt">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-muted">
                <p>No contributions found in your record.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($contributions->hasPages())
      <div class="p-4 border-t">
        {{ $contributions->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
