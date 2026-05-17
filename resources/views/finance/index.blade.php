@extends('layouts.app')

@section('title', 'Finance Analytics - TmcsSmart')
@section('page-title', 'Financial Insights')
@section('breadcrumb', 'TmcsSmart / Finance')

@section('content')
<div class="animate-in space-y-6">
  <!-- Main Receipt-Style Header -->
  <div class="card overflow-hidden">
    <div class="p-8 text-center border-b border-gray-50 bg-gray-50/30">
        <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Financial Management Dashboard</h2>
        <p class="text-[10px] text-muted font-black mt-1 uppercase tracking-widest">ST. JOSEPH THE WORKER CHAPLAINCY</p>
    </div>
  </div>

  <!-- TAB NAVIGATION -->
  <div class="flex items-center justify-between border-b border-muted/10 pb-1">
    <div class="flex gap-1">
      <button onclick="switchTab('overview')" id="btn-overview" class="tab-btn active px-4 py-2 text-sm font-bold border-b-2 border-primary text-primary transition-all">Analytics Dashboard</button>
      <button onclick="switchTab('transactions')" id="btn-transactions" class="tab-btn px-4 py-2 text-sm font-bold text-muted border-b-2 border-transparent hover:text-primary transition-all">Transactions</button>
      <button onclick="switchTab('management')" id="btn-management" class="tab-btn px-4 py-2 text-sm font-bold text-muted border-b-2 border-transparent hover:text-primary transition-all">Management</button>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('finance.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        New Record
      </a>
    </div>
  </div>

  <!-- TAB: OVERVIEW -->
  <div id="tab-overview-content" class="tab-pane space-y-6">
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
  </div>

  <!-- TAB: TRANSACTIONS -->
  <div id="tab-transactions-content" class="tab-pane hidden space-y-4">
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

  <!-- TAB: MANAGEMENT -->
  <div id="tab-management-content" class="tab-pane hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="card p-6 bg-white shadow-sm border border-muted/10 hover:border-primary/30 transition-all">
        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-4">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01"/><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/></svg>
        </div>
        <h4 class="font-bold text-lg mb-2">Contribution Types</h4>
        <p class="text-sm text-muted mb-4">Manage the different categories of contributions (Tithe, Offering, etc.) and their display colors.</p>
        <a href="{{ route('finance.types.index') }}" class="text-primary font-bold text-sm flex items-center gap-2">
          Manage Types
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
      </div>

      <div class="card p-6 bg-white shadow-sm border border-muted/10 hover:border-primary/30 transition-all">
        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center mb-4">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h4 class="font-bold text-lg mb-2">Financial Reports</h4>
        <p class="text-sm text-muted mb-4">Generate comprehensive financial statements, income statements, and balance sheets.</p>
        <a href="{{ route('finance.reports') }}" class="text-primary font-bold text-sm flex items-center gap-2">
          View Reports
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
      </div>

      <div class="card p-6 bg-white shadow-sm border border-muted/10 hover:border-primary/30 transition-all opacity-60">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
        </div>
        <h4 class="font-bold text-lg mb-2">Finance Settings</h4>
        <p class="text-sm text-muted mb-4">Configure bank accounts, payment methods, and notification preferences.</p>
        <span class="text-muted font-bold text-sm flex items-center gap-2">
          Coming Soon
        </span>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function switchTab(tabId) {
  // Hide all panes
  document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
  // Show target pane
  document.getElementById(`tab-${tabId}-content`).classList.remove('hidden');
  
  // Update buttons
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active', 'border-primary', 'text-primary');
    btn.classList.add('border-transparent', 'text-muted');
  });
  
  const activeBtn = document.getElementById(`btn-${tabId}`);
  activeBtn.classList.remove('border-transparent', 'text-muted');
  activeBtn.classList.add('active', 'border-primary', 'text-primary');

  // Store preference
  localStorage.setItem('finance_active_tab', tabId);
}

document.addEventListener('DOMContentLoaded', function() {
  // Restore tab preference
  const savedTab = localStorage.getItem('finance_active_tab');
  if (savedTab && document.getElementById(`btn-${savedTab}`)) {
    switchTab(savedTab);
  } else if (window.location.search.includes('search') || window.location.search.includes('page') || window.location.search.includes('type')) {
    switchTab('transactions');
  } else {
    switchTab('overview');
  }

  // Trend Chart
  const trendCtx = document.getElementById('trendChart').getContext('2d');
  new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: @json($trendData['labels']),
      datasets: [
        {
          label: 'Daily Revenue',
          data: @json($trendData['revenue']),
          borderColor: '#059669',
          backgroundColor: 'rgba(5, 150, 105, 0.05)',
          borderWidth: 3,
          pointRadius: 3,
          pointBackgroundColor: '#fff',
          pointBorderColor: '#059669',
          pointBorderWidth: 2,
          tension: 0.4,
          fill: true
        },
        {
          label: 'Daily Expenses',
          data: @json($trendData['expenses']),
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239, 68, 68, 0.05)',
          borderWidth: 2,
          pointRadius: 0,
          tension: 0.4,
          fill: false,
          borderDash: [5, 5]
        }
      ]
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
