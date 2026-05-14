@extends('layouts.app')

@section('title', 'Assets - TmcsSmart')
@section('page-title', 'Asset Management')
@section('breadcrumb', 'TmcsSmart / Assets')

@section('content')
<div class="animate-in">
  <!-- ASSET STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $totalAssets }}</div>
      <div class="stat-label">Total Assets</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        8 new this quarter
      </div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">TZS {{ number_format($totalValue, 0) }}</div>
      <div class="stat-label">Total Value</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        12% appreciation
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $assignedAssets }}</div>
      <div class="stat-label">Assigned Assets</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        85% utilization
      </div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $maintenanceAssets }}</div>
      <div class="stat-label">Under Maintenance</div>
      <div class="stat-change down">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 7l-9 9m0 0V4m0 12h12"/></svg>
        2 less than last month
      </div>
    </div>
  </div>

  <!-- PAGE ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Asset Management</h2>
      <p class="text-sm text-muted mt-1">Track and manage church assets</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('assets.reports'))
      <button class="btn btn-secondary" onclick="generateAssetReport()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Generate Report
      </button>
      @endif
      @if(auth()->user()->hasPermission('assets.export'))
      <button class="btn btn-secondary" onclick="exportAssets()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </button>
      @endif
      @if(auth()->user()->hasPermission('assets.create'))
      <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Add Asset
      </a>
      @endif
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="flex gap-4 items-center flex-wrap">
        <div class="flex-1 min-w-64">
          <input type="text" class="form-control" placeholder="Search assets..." id="searchInput">
        </div>
        <select class="form-control w-48" id="categoryFilter">
          <option value="">All Categories</option>
          <option value="furniture">Furniture</option>
          <option value="electronics">Electronics</option>
          <option value="vehicles">Vehicles</option>
          <option value="equipment">Equipment</option>
          <option value="buildings">Buildings</option>
        </select>
        <select class="form-control w-48" id="statusFilter">
          <option value="">All Status</option>
          <option value="available">Available</option>
          <option value="assigned">Assigned</option>
          <option value="maintenance">Under Maintenance</option>
          <option value="retired">Retired</option>
        </select>
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- ASSETS TABLE -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">All Assets</div>
      <div class="card-subtitle">{{ $assets->count() }} total assets</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Asset Name</th>
            <th>Category</th>
            <th>Purchase Date</th>
            <th>Purchase Cost</th>
            <th>Current Value</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assets as $asset)
          <tr>
            <td>
              <div style="font-weight:600;font-size:13px;">{{ $asset->asset_name }}</div>
              @if($asset->location)
              <div style="font-size:11px;color:var(--text-muted);">{{ $asset->location }}</div>
              @endif
            </td>
            <td>
              <span class="badge {{ getAssetCategoryColor($asset->category) }}">
                {{ ucfirst($asset->category) }}
              </span>
            </td>
            <td>{{ $asset->purchase_date->format('M d, Y') }}</td>
            <td>TZS {{ number_format($asset->purchase_cost, 0) }}</td>
            <td>
              <div style="font-weight:600;color:var(--green-600);">TZS {{ number_format($asset->current_value, 0) }}</div>
              @if($asset->current_value > $asset->purchase_cost)
              <div class="text-xs text-green">+{{ number_format(($asset->current_value - $asset->purchase_cost) / $asset->purchase_cost * 100, 1) }}%</div>
              @endif
            </td>
            <td>
              @if($asset->assignedMember)
                <div class="flex items-center gap-2">
                  <div class="avatar text-xs">{{ substr($asset->assignedMember->full_name, 0, 2) }}</div>
                  <div>
                    <div style="font-size:12px;">{{ $asset->assignedMember->full_name }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ $asset->assignedMember->registration_number }}</div>
                  </div>
                </div>
              @else
                <span class="text-muted">Unassigned</span>
              @endif
            </td>
            <td>
              <span class="badge {{ getAssetStatusColor($asset->status) }}">
                {{ ucfirst($asset->status) }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <button class="btn btn-ghost btn-sm" onclick="viewAsset({{ $asset->id }})" title="View">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                @if(auth()->user()->hasPermission('assets.edit'))
                <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                @endif
                @if(auth()->user()->hasPermission('assets.history'))
                <button class="btn btn-ghost btn-sm" onclick="viewHistory({{ $asset->id }})" title="History">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                @endif
                @if(auth()->user()->hasPermission('assets.delete'))
                <button class="btn btn-ghost btn-sm text-red" onclick="deleteAsset({{ $asset->id }})" title="Delete">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-8 text-muted">No assets found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer">
      {{ $assets->links() }}
    </div>
  </div>

  <!-- ASSET VALUE CHART -->
  <div class="card mt-6">
    <div class="card-header">
      <div class="card-title">Asset Value by Category</div>
      <div class="card-subtitle">Distribution of asset values</div>
    </div>
    <div class="card-body">
      <div class="chart-box">
        <canvas id="assetChart" height="80"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- VIEW ASSET MODAL -->
<div class="modal-overlay" id="viewAssetModal">
  <div class="modal" style="width: 700px;">
    <div class="modal-header">
      <div><div class="card-title">Asset Details</div><div class="card-subtitle">Complete asset information</div></div>
      <div class="modal-close" onclick="closeModal('viewAssetModal')">✕</div>
    </div>
    <div class="modal-body" id="assetDetails">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewAssetModal')">Close</button>
    </div>
  </div>
</div>

<!-- ASSET HISTORY MODAL -->
<div class="modal-overlay" id="assetHistoryModal">
  <div class="modal" style="width: 600px;">
    <div class="modal-header">
      <div><div class="card-title">Asset History</div><div class="card-subtitle">Asset assignment and maintenance history</div></div>
      <div class="modal-close" onclick="closeModal('assetHistoryModal')">✕</div>
    </div>
    <div class="modal-body" id="assetHistory">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('assetHistoryModal')">Close</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getAssetCategoryColor(category) {
  const colors = {
    'furniture' => 'amber',
    'electronics' => 'blue',
    'vehicles' => 'green',
    'equipment' => 'red',
    'buildings' => 'purple'
  };
  return colors[category] ?? 'blue';
}

function getAssetStatusColor(status) {
  const colors = {
    'available' => 'green',
    'assigned' => 'blue',
    'maintenance' => 'amber',
    'retired' => 'red'
  };
  return colors[status] ?? 'blue';
}

function viewAsset(assetId) {
  fetch(`/assets/${assetId}/show`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('assetDetails').innerHTML = html;
      document.getElementById('viewAssetModal').classList.add('open');
    });
}

function viewHistory(assetId) {
  fetch(`/assets/${assetId}/history`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('assetHistory').innerHTML = html;
      document.getElementById('assetHistoryModal').classList.add('open');
    });
}

function deleteAsset(assetId) {
  if (confirm('Are you sure you want to delete this asset? This action cannot be undone.')) {
    fetch(`/assets/${assetId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('Asset deleted successfully', 'success');
        location.reload();
      } else {
        showToast(data.message || 'Error deleting asset', 'error');
      }
    });
  }
}

function generateAssetReport() {
  window.open('/assets/report', '_blank');
}

function exportAssets() {
  window.open('/assets/export', '_blank');
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('categoryFilter').value = '';
  document.getElementById('statusFilter').value = '';
  location.href = '{{ route('assets.index') }}';
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
['categoryFilter', 'statusFilter'].forEach(id => {
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

// Asset Chart
const ctx = document.getElementById('assetChart');
if (ctx) {
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Furniture', 'Electronics', 'Vehicles', 'Equipment', 'Buildings'],
      datasets: [{
        data: [1500000, 2500000, 800000, 1200000, 5000000],
        backgroundColor: ['#f59e0b', '#2563eb', '#059669', '#ef4444', '#8b5cf6'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    }
  });
}
</script>
@endpush

<?php
// Helper functions for the view
function getAssetCategoryColor($category) {
    $colors = [
        'furniture' => 'amber',
        'electronics' => 'blue',
        'vehicles' => 'green',
        'equipment' => 'red',
        'buildings' => 'purple'
    ];
    return $colors[$category] ?? 'blue';
}

function getAssetStatusColor($status) {
    $colors = [
        'available' => 'green',
        'assigned' => 'blue',
        'maintenance' => 'amber',
        'retired' => 'red'
    ];
    return $colors[$status] ?? 'blue';
}
?>
