@extends('layouts.app')

@section('title', 'Communications - TmcsSmart')
@section('page-title', 'Communication Management')
@section('breadcrumb', 'TmcsSmart / Communications')

@section('content')
<div class="animate-in">
  <!-- COMMUNICATION STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $totalCommunications }}</div>
      <div class="stat-label">Total Communications</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        12% from last month
      </div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
      </div>
      <div class="stat-value">{{ $sentSMS }}</div>
      <div class="stat-label">SMS Sent</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        8% from last week
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $sentEmails }}</div>
      <div class="stat-label">Emails Sent</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        15% from last month
      </div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $failedCommunications }}</div>
      <div class="stat-label">Failed</div>
      <div class="stat-change down">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 7l-9 9m0 0V4m0 12h12"/></svg>
        2 less than yesterday
      </div>
    </div>
  </div>

  <!-- PAGE ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Communications</h2>
      <p class="text-sm text-muted mt-1">Manage email and SMS communications</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('message-templates.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Templates
      </a>
      <a href="{{ route('communications.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Send Message
      </a>
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="filter-row">
        <input type="text" class="form-control search-input" placeholder="Search communications..." id="searchInput">
        <select class="form-control filter-select" id="typeFilter">
          <option value="">All Types</option>
          <option value="email">Email</option>
          <option value="sms">SMS</option>
          <option value="both">Email & SMS</option>
        </select>
        <select class="form-control filter-select" id="statusFilter">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="sent">Sent</option>
          <option value="failed">Failed</option>
          <option value="scheduled">Scheduled</option>
        </select>
        <input type="date" class="form-control filter-select" id="dateFilter">
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- COMMUNICATIONS TABLE -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">All Communications</div>
      <div class="card-subtitle">{{ $communications->count() }} total messages</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Subject</th>
            <th>Type</th>
            <th>Recipients</th>
            <th>Sent By</th>
            <th>Scheduled</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($communications as $communication)
          <tr>
            <td>
              <div>
                <div style="font-weight:600;font-size:13px;">{{ $communication->subject }}</div>
                <div style="font-size:11px;color:var(--text-muted);max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  {{ Str::limit(strip_tags($communication->message), 80) }}
                </div>
              </div>
            </td>
            <td>
              <div class="flex gap-1">
                @if($communication->type === 'email' || $communication->type === 'both')
                <span class="badge blue">Email</span>
                @endif
                @if($communication->type === 'sms' || $communication->type === 'both')
                <span class="badge green">SMS</span>
                @endif
              </div>
            </td>
            <td>
              <div style="font-size:12px;">
                @if($communication->recipient_type === 'all')
                  <span class="badge amber">All Members</span>
                @elseif($communication->recipient_type === 'group')
                  <span class="badge green">{{ $communication->group->name ?? 'Group' }}</span>
                @elseif($communication->recipient_type === 'member')
                  <span>{{ $communication->member->full_name ?? 'Member' }}</span>
                @endif
              </div>
              <div style="font-size:11px;color:var(--text-muted);">
                {{ $communication->recipients_count ?? count(json_decode($communication->recipients ?? '[]')) }} recipients
              </div>
            </td>
            <td>
              <div class="flex items-center gap-2">
                <div class="avatar text-xs">{{ substr($communication->sentBy->name, 0, 2) }}</div>
                <span style="font-size:12px;">{{ $communication->sentBy->name }}</span>
              </div>
            </td>
            <td>
              @if($communication->scheduled_at)
                <div style="font-size:12px;">{{ $communication->scheduled_at->format('M d, Y') }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $communication->scheduled_at->format('H:i') }}</div>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              <span class="badge {{ getCommunicationStatusColor($communication->status) }}">
                {{ ucfirst($communication->status) }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <button class="btn btn-ghost btn-sm" onclick="viewCommunication({{ $communication->id }})" title="View">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                @if(auth()->user()->hasPermission('communications.resend') && $communication->status === 'failed')
                <button class="btn btn-ghost btn-sm" onclick="resendCommunication({{ $communication->id }})" title="Resend">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </button>
                @endif
                @if(auth()->user()->hasPermission('communications.delete'))
                <button class="btn btn-ghost btn-sm text-red" onclick="deleteCommunication({{ $communication->id }})" title="Delete">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-8 text-muted">No communications found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer">
      {{ $communications->links() }}
    </div>
  </div>

  <!-- COMMUNICATION CHART -->
  <div class="card mt-6">
    <div class="card-header">
      <div class="card-title">Communication Trends</div>
      <div class="card-subtitle">Last 30 days overview</div>
    </div>
    <div class="card-body">
      <div class="chart-box">
        <canvas id="communicationChart" height="80"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- VIEW COMMUNICATION MODAL -->
<div class="modal-overlay" id="viewCommunicationModal">
  <div class="modal" style="width: 700px;">
    <div class="modal-header">
      <div><div class="card-title">Communication Details</div><div class="card-subtitle">Complete message information</div></div>
      <div class="modal-close" onclick="closeModal('viewCommunicationModal')">✕</div>
    </div>
    <div class="modal-body" id="communicationDetails">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewCommunicationModal')">Close</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getCommunicationStatusColor(status) {
  const colors = {
    'pending': 'amber',
    'sent': 'green',
    'failed': 'red',
    'scheduled': 'blue'
  };
  return colors[status] || 'blue';
}

function viewCommunication(communicationId) {
  fetch(`/communications/${communicationId}/show`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('communicationDetails').innerHTML = html;
      document.getElementById('viewCommunicationModal').classList.add('open');
    });
}

function resendCommunication(communicationId) {
  if (confirm('Are you sure you want to resend this communication?')) {
    fetch(`/communications/${communicationId}/resend`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('Communication resent successfully', 'success');
        location.reload();
      } else {
        showToast(data.message || 'Error resending communication', 'error');
      }
    });
  }
}

function deleteCommunication(communicationId) {
  if (confirm('Are you sure you want to delete this communication? This action cannot be undone.')) {
    fetch(`/communications/${communicationId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('Communication deleted successfully', 'success');
        location.reload();
      } else {
        showToast(data.message || 'Error deleting communication', 'error');
      }
    });
  }
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('typeFilter').value = '';
  document.getElementById('statusFilter').value = '';
  document.getElementById('dateFilter').value = '';
  location.href = '{{ route('communications.index') }}';
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
['typeFilter', 'statusFilter', 'dateFilter'].forEach(id => {
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

// Communication Chart
const ctx = document.getElementById('communicationChart');
if (ctx) {
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [{
        label: 'Email',
        data: [45, 52, 38, 65],
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37,99,235,0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }, {
        label: 'SMS',
        data: [28, 35, 42, 38],
        borderColor: '#059669',
        backgroundColor: 'rgba(5,150,105,0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}
</script>
@endpush

<?php
// Helper functions for the view
function getCommunicationStatusColor($status) {
    $colors = [
        'pending' => 'amber',
        'sent' => 'green',
        'failed' => 'red',
        'scheduled' => 'blue'
    ];
    return $colors[$status] ?? 'blue';
}
?>
