@extends('layouts.app')

@section('title', 'Finance - TmcsSmart')
@section('page-title', 'Finance Management')
@section('breadcrumb', 'TmcsSmart / Finance')

@section('content')
<div class="animate-in">
  <!-- FINANCE STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">TZS {{ number_format($totalContributions, 0) }}</div>
      <div class="stat-label">Total Contributions</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        12% from last month
      </div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $contributionsCount }}</div>
      <div class="stat-label">Total Transactions</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        8% from last month
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <div class="stat-value">TZS {{ number_format($thisMonthContributions, 0) }}</div>
      <div class="stat-label">This Month</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        15% from last month
      </div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <div class="stat-value">{{ $pendingReceipts }}</div>
      <div class="stat-label">Pending Receipts</div>
      <div class="stat-change down">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 7l-9 9m0 0V4m0 12h12"/></svg>
        3 less than yesterday
      </div>
    </div>
  </div>

  <!-- PAGE ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Financial Contributions</h2>
      <p class="text-sm text-muted mt-1">Manage and track all financial contributions</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('finance.reports'))
      <button class="btn btn-secondary" onclick="generateReport()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Generate Report
      </button>
      @endif
      @if(auth()->user()->hasPermission('finance.create'))
      <a href="{{ route('finance.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Record Contribution
      </a>
      @endif
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="filter-row">
        <input type="text" class="form-control search-input" placeholder="Search contributions..." id="searchInput">
        <select class="form-control filter-select" id="typeFilter">
          <option value="">All Types</option>
          <option value="almsgiving">Almsgiving/Zaka</option>
          <option value="tithe">Tithe</option>
          <option value="offering">Offering</option>
          <option value="special_donation">Special Donation</option>
        </select>
        <select class="form-control filter-select" id="methodFilter">
          <option value="">All Methods</option>
          <option value="cash">Cash</option>
          <option value="bank_transfer">Bank Transfer</option>
          <option value="mobile_money">Mobile Money</option>
          <option value="stripe">Stripe</option>
          <option value="click_pesa">Click Pesa</option>
        </select>
        <input type="date" class="form-control filter-select" id="dateFilter">
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- CONTRIBUTIONS TABLE -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">All Contributions</div>
      <div class="card-subtitle">{{ $contributions->count() }} total transactions</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Receipt No.</th>
            <th>Member</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contributions as $contribution)
          <tr>
            <td class="mono text-sm">{{ $contribution->receipt_number }}</td>
            <td>
              <div class="flex items-center gap-2">
                <div class="avatar">{{ substr($contribution->member->full_name, 0, 2) }}</div>
                <div>
                  <div style="font-weight:600;font-size:13px;">{{ $contribution->member->full_name }}</div>
                  <div style="font-size:11px;color:var(--text-muted);">{{ $contribution->member->registration_number }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge {{ getContributionTypeColor($contribution->contribution_type) }}">
                {{ getContributionTypeLabel($contribution->contribution_type) }}
              </span>
            </td>
            <td style="font-weight:700;color:var(--green-600);">TZS {{ number_format($contribution->amount, 0) }}</td>
            <td>
              <span class="badge blue">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</span>
            </td>
            <td>{{ $contribution->contribution_date ? $contribution->contribution_date->format('M d, Y') : 'N/A' }}</td>
            <td>
              <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
                {{ $contribution->is_verified ? 'Verified' : 'Pending' }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <a href="{{ route('finance.show', $contribution->id) }}" class="btn btn-ghost btn-sm" title="View">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                @if(auth()->user()->hasPermission('finance.receipts'))
                <button class="btn btn-ghost btn-sm" onclick="generateReceipt({{ $contribution->id }})" title="Generate Receipt">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </button>
                @endif
                @if(auth()->user()->hasPermission('finance.edit'))
                <button class="btn btn-ghost btn-sm" onclick="editContribution({{ $contribution->id }})" title="Edit">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                @endif
                @if(auth()->user()->hasPermission('finance.delete'))
                <button class="btn btn-ghost btn-sm text-red" onclick="deleteContribution({{ $contribution->id }})" title="Delete">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-8 text-muted">No contributions found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer">
      {{ $contributions->links() }}
    </div>
  </div>

  <!-- MONTHLY CHART -->
  <div class="card mt-6">
    <div class="card-header">
      <div class="card-title">Monthly Contributions Trend</div>
      <div class="card-subtitle">Last 12 months overview</div>
    </div>
    <div class="card-body">
      <div class="chart-box">
        <canvas id="monthlyChart" height="80"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getContributionTypeColor(type) {
  const colors = {
    'almsgiving': 'green',
    'offering': 'gold',
    'tithe': 'blue',
    'special_donation': 'amber'
  };
  return colors[type] || 'blue';
}

function getContributionTypeLabel(type) {
  const labels = {
    'almsgiving': 'Almsgiving/Zaka',
    'offering': 'Offering',
    'tithe': 'Tithe',
    'special_donation': 'Special Donation'
  };
  return labels[type] || type;
}

function generateReceipt(contributionId) {
  window.open(`/finance/${contributionId}/receipt`, '_blank');
}

function editContribution(contributionId) {
  window.location.href = `/finance/${contributionId}/edit`;
}

function deleteContribution(contributionId) {
  if (confirm('Are you sure you want to delete this contribution? This action cannot be undone.')) {
    fetch(`/finance/${contributionId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.reload();
      }
    });
  }
}

function generateReport() {
  const dateRange = prompt('Enter date range (YYYY-MM-DD to YYYY-MM-DD):');
  if (dateRange) {
    window.open(`/finance/report?range=${encodeURIComponent(dateRange)}`, '_blank');
  }
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('typeFilter').value = '';
  document.getElementById('methodFilter').value = '';
  document.getElementById('dateFilter').value = '';
  location.href = '{{ route('finance.index') }}';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const search = e.target.value;
  const url = new URL(window.location);
  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }
  window.location = url.toString();
});

// Filter functionality
['typeFilter', 'methodFilter', 'dateFilter'].forEach(id => {
  document.getElementById(id).addEventListener('change', function(e) {
    const value = e.target.value;
    const url = new URL(window.location);
    const param = id.replace('Filter', '');
    if (value) {
      url.searchParams.set(param, value);
    } else {
      url.searchParams.delete(param);
    }
    window.location = url.toString();
  });
});

// Monthly Contributions Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Contributions',
                    data: @json(array_values($chartData)),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

<?php
// Helper functions for the view
function getContributionTypeColor($type) {
    $colors = [
        'almsgiving' => 'green',
        'offering' => 'gold',
        'tithe' => 'blue',
        'special_donation' => 'amber'
    ];
    return $colors[$type] ?? 'blue';
}

function getContributionTypeLabel($type) {
    $labels = [
        'almsgiving' => 'Almsgiving/Zaka',
        'offering' => 'Offering',
        'tithe' => 'Tithe',
        'special_donation' => 'Special Donation'
    ];
    return $labels[$type] ?? ucfirst(str_replace('_', ' ', $type));
}
?>
