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
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Method</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Status</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-right">Amount</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($contributions as $contribution)
            <tr class="hover:bg-light/30 transition-colors">
              <td class="px-6 py-4 text-sm font-mono text-xs">{{ $contribution->receipt_number }}</td>
              <td class="px-6 py-4 text-sm">{{ $contribution->contribution_date->format('d M, Y') }}</td>
              <td class="px-6 py-4 text-sm font-bold">{{ $contribution->contribution_type }}</td>
              <td class="px-6 py-4 text-sm capitalize text-muted">{{ str_replace('_', ' ', $contribution->payment_method) }}</td>
              <td class="px-6 py-4">
                <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
                  {{ $contribution->is_verified ? 'Verified' : 'Pending' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm font-bold text-right">{{ number_format($contribution->amount) }} TZS</td>
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
