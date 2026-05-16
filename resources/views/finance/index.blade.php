@extends('layouts.app')

@section('title', 'Finance Analytics - TmcsSmart')
@section('page-title', 'Financial Insights')
@section('breadcrumb', 'TmcsSmart / Finance')

@section('content')
<div class="animate-in space-y-6">
  <!-- TOP STATS -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card p-5 border-l-4 border-green-500 bg-white shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-black uppercase tracking-widest text-muted mb-1">Total Revenue</p>
          <h3 class="text-xl font-bold text-primary">TZS {{ number_format($totalContributions, 0) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
    </div>

    <div class="card p-5 border-l-4 border-blue-500 bg-white shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-black uppercase tracking-widest text-muted mb-1">This Month</p>
          <h3 class="text-xl font-bold text-primary">TZS {{ number_format($thisMonthContributions, 0) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
      </div>
    </div>

    <div class="card p-5 border-l-4 border-amber-500 bg-white shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-black uppercase tracking-widest text-muted mb-1">Transactions</p>
          <h3 class="text-xl font-bold text-primary">{{ number_format($contributionsCount) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
      </div>
    </div>

    <div class="card p-5 border-l-4 border-red-500 bg-white shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-black uppercase tracking-widest text-muted mb-1">Pending Approval</p>
          <h3 class="text-xl font-bold text-primary">{{ $pendingReceipts }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
    </div>
  </div>

  <!-- CHARTS SECTION -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Trend Line Chart -->
    <div class="lg:col-span-2 card bg-white shadow-sm p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="font-bold text-lg">Contribution Trend</h3>
          <p class="text-xs text-muted">Daily collection overview for the last 30 days</p>
        </div>
        <div class="flex bg-muted/10 p-1 rounded-lg">
          <a href="{{ request()->fullUrlWithQuery(['period' => 'week']) }}" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md {{ $period == 'week' ? 'bg-white shadow-sm text-primary' : 'text-muted' }}">Week</a>
          <a href="{{ request()->fullUrlWithQuery(['period' => 'month']) }}" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md {{ $period == 'month' ? 'bg-white shadow-sm text-primary' : 'text-muted' }}">Month</a>
          <a href="{{ request()->fullUrlWithQuery(['period' => 'year']) }}" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md {{ $period == 'year' ? 'bg-white shadow-sm text-primary' : 'text-muted' }}">Year</a>
        </div>
      </div>
      <div class="h-64">
        <canvas id="trendChart"></canvas>
      </div>
    </div>

    <!-- Distribution Pie Chart -->
    <div class="card bg-white shadow-sm p-6">
      <h3 class="font-bold text-lg mb-1">By Category</h3>
      <p class="text-xs text-muted mb-6">Revenue distribution across types</p>
      <div class="h-56 relative">
        <canvas id="distributionChart"></canvas>
      </div>
      <div class="mt-4 space-y-2 overflow-y-auto max-h-32 pr-2">
        @foreach($typeDistribution as $dist)
        <div class="flex items-center justify-between text-xs">
          <span class="text-muted">{{ $dist['label'] }}</span>
          <span class="font-bold">TZS {{ number_format($dist['value'], 0) }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- FILTERS & TABLE -->
  <div class="card bg-white shadow-sm overflow-hidden">
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h3 class="font-bold text-lg">Detailed Transactions</h3>
        <p class="text-xs text-muted">Filter and manage individual contribution records</p>
      </div>
      <form action="{{ route('finance.index') }}" method="GET" class="flex flex-wrap gap-2">
        <input type="hidden" name="period" value="{{ $period }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Receipt or Member..." class="form-control text-xs w-40">
        <select name="type" class="form-control text-xs w-32">
          <option value="">All Types</option>
          @foreach($contributionTypes as $type)
            <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('finance.index') }}" class="btn btn-secondary btn-sm">Clear</a>
      </form>
    </div>

    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
            <th class="px-6 py-4 text-left">Receipt No.</th>
            <th class="px-6 py-4 text-left">Member</th>
            <th class="px-6 py-4 text-left">Type</th>
            <th class="px-6 py-4 text-left">Amount</th>
            <th class="px-6 py-4 text-left">Date</th>
            <th class="px-6 py-4 text-left">Status</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($contributions as $contribution)
          <tr class="hover:bg-primary/5 transition-colors text-xs">
            <td class="px-6 py-4 mono font-bold text-primary">{{ $contribution->receipt_number }}</td>
            <td class="px-6 py-4">
              <div class="font-bold">{{ $contribution->member->full_name }}</div>
              <div class="text-[10px] text-muted">{{ $contribution->member->registration_number }}</div>
            </td>
            <td class="px-6 py-4">
              @php
                $typeObj = $contributionTypes->where('name', $contribution->contribution_type)->first();
                $color = $typeObj ? $typeObj->color : 'blue';
              @endphp
              <span class="px-2 py-0.5 rounded-full bg-{{ $color }}-500/10 text-{{ $color }}-600 font-bold">
                {{ $contribution->contribution_type }}
              </span>
            </td>
            <td class="px-6 py-4 font-bold text-green-600">TZS {{ number_format($contribution->amount, 0) }}</td>
            <td class="px-6 py-4 text-muted">{{ $contribution->contribution_date->format('d M, Y') }}</td>
            <td class="px-6 py-4">
              <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ $contribution->is_verified ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                {{ $contribution->is_verified ? 'Verified' : 'Pending' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ route('finance.show', $contribution->id) }}" class="p-1.5 rounded hover:bg-muted/10 text-muted" title="View">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('finance.receipt', $contribution->id) }}" target="_blank" class="p-1.5 rounded hover:bg-muted/10 text-muted" title="Receipt">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center text-muted italic">No records found matching your criteria</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-6 py-4 bg-muted/5 border-t">
      {{ $contributions->appends(request()->query())->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Trend Chart
  const trendCtx = document.getElementById('trendChart').getContext('2d');
  new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: @json($trendData['labels']),
      datasets: [{
        label: 'Daily Revenue',
        data: @json($trendData['values']),
        borderColor: '#059669',
        backgroundColor: 'rgba(5, 150, 105, 0.05)',
        borderWidth: 3,
        pointRadius: 3,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#059669',
        pointBorderWidth: 2,
        tension: 0.4,
        fill: true
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

  // Distribution Chart
  const distCtx = document.getElementById('distributionChart').getContext('2d');
  new Chart(distCtx, {
    type: 'doughnut',
    data: {
      labels: @json($typeDistribution->pluck('label')),
      datasets: [{
        data: @json($typeDistribution->pluck('value')),
        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'],
        borderWidth: 0,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '75%',
      plugins: { legend: { display: false } }
    }
  });
});
</script>
@endpush
@endsection
