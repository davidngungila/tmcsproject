@extends('layouts.app')

@section('title', $type->name . ' Details - TmcsSmart')
@section('page-title', $type->name)
@section('breadcrumb', 'TmcsSmart / Finance / Types / ' . $type->name)

@section('content')
<div class="animate-in space-y-6">
  <!-- HEADER STATS -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="card p-5 bg-{{ $type->color }}-500/5 border-l-4 border-{{ $type->color }}-500">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Total Lifetime</p>
      <h3 class="text-xl font-bold text-primary">TZS {{ number_format($totalCollected, 0) }}</h3>
    </div>
    <div class="card p-5 bg-green-500/5 border-l-4 border-green-500">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Collected This Month</p>
      <h3 class="text-xl font-bold text-primary">TZS {{ number_format($thisMonth, 0) }}</h3>
    </div>
    <div class="card p-5 bg-blue-500/5 border-l-4 border-blue-500">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Collected This Year</p>
      <h3 class="text-xl font-bold text-primary">TZS {{ number_format($thisYear, 0) }}</h3>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- DETAILS & CHART -->
    <div class="lg:col-span-2 space-y-6">
      <div class="card bg-white shadow-sm p-6">
        <h3 class="font-bold text-lg mb-6">Annual Performance</h3>
        <div class="h-64">
          <canvas id="typeTrendChart"></canvas>
        </div>
      </div>

      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b flex items-center justify-between">
          <h3 class="font-bold text-lg">Payment History</h3>
          <form action="{{ route('finance.types.show', $type->id) }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Member name..." class="form-control text-xs w-40">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
          </form>
        </div>
        <div class="table-wrap">
          <table class="w-full">
            <thead>
              <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
                <th class="px-6 py-4 text-left">Receipt</th>
                <th class="px-6 py-4 text-left">Member</th>
                <th class="px-6 py-4 text-left">Amount</th>
                <th class="px-6 py-4 text-left">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
              @forelse($contributions as $contribution)
              <tr class="hover:bg-primary/5 transition-colors text-xs">
                <td class="px-6 py-4 mono font-bold text-primary">{{ $contribution->receipt_number }}</td>
                <td class="px-6 py-4 font-bold">{{ $contribution->member->full_name }}</td>
                <td class="px-6 py-4 font-bold text-green-600">TZS {{ number_format($contribution->amount, 0) }}</td>
                <td class="px-6 py-4 text-muted">{{ $contribution->contribution_date->format('d M, Y') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-12 text-center text-muted italic">No payment records found</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="px-6 py-4 border-t">
          {{ $contributions->links() }}
        </div>
      </div>
    </div>

    <!-- CONFIGURATION PANEL -->
    <div class="card bg-white shadow-sm p-6 h-fit">
      <h3 class="font-bold text-lg mb-4">Configuration</h3>
      <div class="space-y-4">
        <div>
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Code</label>
          <div class="text-sm font-bold text-primary">{{ $type->code }}</div>
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">GL Account</label>
          <div class="text-sm font-bold text-primary">{{ $type->gl_account ?: 'Not Assigned' }}</div>
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Minimum Amount</label>
          <div class="text-sm font-bold text-primary">TZS {{ number_format($type->min_amount, 0) }}</div>
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Frequency</label>
          <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase">
            {{ $type->frequency }}
          </span>
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-1">Requirement</label>
          <span class="px-2 py-0.5 rounded-full {{ $type->is_mandatory ? 'bg-red-500/10 text-red-600' : 'bg-green-500/10 text-green-600' }} text-[10px] font-black uppercase">
            {{ $type->is_mandatory ? 'Mandatory' : 'Optional' }}
          </span>
        </div>
        <div class="pt-6 border-t flex flex-col gap-2">
          <a href="{{ route('finance.types.edit', $type->id) }}" class="btn btn-primary w-full justify-center">Edit Settings</a>
          <a href="{{ route('finance.types.index') }}" class="btn btn-secondary w-full justify-center">Back to List</a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('typeTrendChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Monthly Collection',
        data: @json($chartData),
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: '#3b82f6',
        borderWidth: 2,
        borderRadius: 5,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          grid: { borderDash: [5, 5], color: '#f3f4f6' },
          ticks: { font: { size: 10 }, callback: v => 'TZS ' + (v/1000) + 'k' }
        },
        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
      }
    }
  });
});
</script>
@endpush
@endsection
