@extends('layouts.app')

@section('title', 'Dashboard - TmcsSmart')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'TmcsSmart / Dashboard')

@section('content')
<div class="animate-in">
  <style>
    /* Dashboard Theme & Responsive Overrides */
    .welcome-header {
      background: linear-gradient(135deg, var(--green-600), var(--green-500));
      color: white !important;
    }
    [data-theme="dark"] .stat-card {
      background: var(--bg-card);
      border-color: var(--border);
    }
    [data-theme="dark"] .stat-value {
      color: var(--text-primary);
    }
    [data-theme="dark"] .stat-label {
      color: var(--text-secondary);
    }
    [data-theme="dark"] .stat-subtitle {
      color: var(--text-muted);
    }
    [data-theme="dark"] .card {
      background: var(--bg-card);
      border-color: var(--border);
    }
    [data-theme="dark"] .card-title {
      color: var(--text-primary);
    }
    [data-theme="dark"] tr:hover {
      background: var(--hover-row);
    }
    [data-theme="dark"] td {
      color: var(--text-primary);
      border-color: var(--border-light);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .stat-grid {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
      }
      .welcome-header {
        padding: 20px;
      }
      .welcome-title {
        font-size: 20px;
      }
    }
    @media (max-width: 480px) {
      .stat-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <!-- WELCOME HEADER -->
  <div class="welcome-header mb-6">
    <div class="welcome-content">
      <div class="welcome-text">
        <h1 class="welcome-title">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="welcome-subtitle">Here's what's happening with your church today</p>
      </div>
      <div class="welcome-actions">
        <div class="date-time" id="currentDateTime">{{ now()->format('l, F j, Y') }}</div>
        <div class="weather-widget">
          <div class="weather-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          </div>
          <div class="weather-info">
            <div class="temperature">28°C</div>
            <div class="condition">Partly Cloudy</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- QUICK ACCESS & REPORTS -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('finance.reports') }}" class="card p-4 border-none shadow-sm hover:shadow-md transition-all group bg-gradient-to-br from-green-50 to-white">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex-center group-hover:bg-green-600 group-hover:text-white transition-all">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Financial</div>
          <div class="text-sm font-black text-gray-800">Reports</div>
        </div>
      </div>
    </a>
    <a href="{{ route('activity-logs.index') }}" class="card p-4 border-none shadow-sm hover:shadow-md transition-all group bg-gradient-to-br from-blue-50 to-white">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex-center group-hover:bg-blue-600 group-hover:text-white transition-all">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
          <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Admin</div>
          <div class="text-sm font-black text-gray-800">Reports</div>
        </div>
      </div>
    </a>
    <a href="{{ route('communications.index') }}" class="card p-4 border-none shadow-sm hover:shadow-md transition-all group bg-gradient-to-br from-purple-50 to-white">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex-center group-hover:bg-purple-600 group-hover:text-white transition-all">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <div>
          <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Comm</div>
          <div class="text-sm font-black text-gray-800">Reports</div>
        </div>
      </div>
    </a>
    <a href="{{ route('groups.communities') }}" class="card p-4 border-none shadow-sm hover:shadow-md transition-all group bg-gradient-to-br from-amber-50 to-white">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex-center group-hover:bg-amber-600 group-hover:text-white transition-all">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Meeting</div>
          <div class="text-sm font-black text-gray-800">Reports</div>
        </div>
      </div>
    </a>
  </div>

  <!-- KEY METRICS OVERVIEW -->
  <div class="metrics-overview mb-6">
    <div class="metrics-header">
      <h2 class="metrics-title">Key Metrics Overview</h2>
      <div class="metrics-period">
        <select class="period-selector" id="metricsPeriod">
          <option value="today">Today</option>
          <option value="week" selected>This Week</option>
          <option value="month">This Month</option>
          <option value="year">This Year</option>
        </select>
      </div>
    </div>
    
    <div class="stat-grid">
      <div class="stat-card green premium">
        <div class="stat-header">
          <div class="stat-icon green">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
        </div>
        <div class="stat-content">
          <div class="stat-value">{{ number_format($totalMembers) }}</div>
          <div class="stat-label">Total Members</div>
          <div class="stat-subtitle">Registered church members</div>
        </div>
      </div>

      <div class="stat-card gold premium">
        <div class="stat-header">
          <div class="stat-icon gold">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>
        <div class="stat-content">
          <div class="stat-value">TZS {{ number_format($totalIncome/1000000, 1) }}M</div>
          <div class="stat-label">Total Income</div>
          <div class="stat-subtitle">Lifetime contributions</div>
        </div>
      </div>

      <div class="stat-card blue premium">
        <div class="stat-header">
          <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>
        <div class="stat-content">
          <div class="stat-value">{{ number_format($totalGroups) }}</div>
          <div class="stat-label">Total Communities</div>
          <div class="stat-subtitle">Active groups & fellowships</div>
        </div>
      </div>

      <div class="stat-card red premium">
        <div class="stat-header">
          <div class="stat-icon red">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        </div>
        <div class="stat-content">
          <div class="stat-value">TZS {{ number_format($netBalance/1000, 0) }}K</div>
          <div class="stat-label">Net Balance</div>
          <div class="stat-subtitle">Current available funds</div>
        </div>
      </div>
    </div>
  </div>

  <!-- REAL DATA SECTIONS -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- UPCOMING EVENTS -->
      <div class="card">
          <div class="card-header flex items-center justify-between">
              <h3 class="card-title">Upcoming Events</h3>
              <a href="{{ route('events.index') }}" class="text-xs text-green-600 font-bold hover:underline">View All</a>
          </div>
          <div class="card-body p-0">
              <div class="table-wrap">
                  <table class="w-full">
                      <tbody>
                          @forelse($upcomingEvents as $event)
                          <tr class="border-b border-light hover:bg-light/30 transition-all">
                              <td class="p-4">
                                  <div class="flex items-center gap-3">
                                      <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex flex-col items-center justify-center leading-none">
                                          <span class="text-[10px] uppercase font-bold">{{ $event->event_date->format('M') }}</span>
                                          <span class="text-lg font-bold">{{ $event->event_date->format('d') }}</span>
                                      </div>
                                      <div>
                                          <div class="font-bold text-sm">{{ $event->event_name }}</div>
                                          <div class="text-[10px] text-muted">{{ $event->venue }} • {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</div>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                          @empty
                          <tr><td class="p-8 text-center text-muted text-xs">No upcoming events scheduled.</td></tr>
                          @endforelse
                      </tbody>
                  </table>
              </div>
          </div>
      </div>

      <!-- RECENT CONTRIBUTIONS -->
      <div class="card">
          <div class="card-header flex items-center justify-between">
              <h3 class="card-title">Recent Giving</h3>
              <a href="{{ route('finance.index') }}" class="text-xs text-green-600 font-bold hover:underline">View All</a>
          </div>
          <div class="card-body p-0">
              <div class="table-wrap">
                  <table class="w-full">
                      <tbody>
                          @forelse($recentContributions as $giving)
                          <tr class="border-b border-light hover:bg-light/30 transition-all">
                              <td class="p-4">
                                  <div class="flex items-center justify-between">
                                      <div class="flex items-center gap-3">
                                          <div class="w-8 h-8 rounded-full bg-light flex items-center justify-center text-xs font-bold">
                                              {{ substr($giving->member->full_name ?? 'A', 0, 2) }}
                                          </div>
                                          <div>
                                              <div class="font-bold text-sm">{{ $giving->member->full_name ?? 'Anonymous' }}</div>
                                              <div class="text-[10px] text-muted">{{ ucfirst($giving->contribution_type) }} • {{ $giving->contribution_date->diffForHumans() }}</div>
                                          </div>
                                      </div>
                                      <div class="text-sm font-bold text-green-600">
                                          +TZS {{ number_format($giving->amount, 0) }}
                                      </div>
                                  </div>
                              </td>
                          </tr>
                          @empty
                          <tr><td class="p-8 text-center text-muted text-xs">No recent contributions found.</td></tr>
                          @endforelse
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>

  <!-- ANALYTICS DASHBOARD -->
  <div class="analytics-dashboard mb-6">
    <div class="analytics-header">
      <h2 class="analytics-title">Financial Performance ({{ date('Y') }})</h2>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="chart-container" style="height: 300px;">
                <canvas id="financeTrendChart"></canvas>
            </div>
        </div>
    </div>
  </div>

  <!-- COMMUNITY ANALYTICS (ADMIN) -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
      <!-- TOP COMMUNITIES -->
      <div class="lg:col-span-4">
          <div class="card h-full">
              <div class="card-header bg-gray-50/50 border-b">
                  <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Largest Communities</h3>
              </div>
              <div class="card-body p-0">
                  <div class="divide-y divide-gray-100">
                      @foreach($topGroups as $group)
                      <div class="p-4 flex items-center justify-between hover:bg-light/30 transition-all">
                          <div class="flex items-center gap-3">
                              <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex-center font-bold text-[10px]">
                                  {{ substr($group->name, 0, 2) }}
                              </div>
                              <div>
                                  <div class="text-sm font-bold text-gray-800">{{ $group->name }}</div>
                                  <div class="text-[9px] text-muted font-bold uppercase tracking-widest">{{ $group->type }}</div>
                              </div>
                          </div>
                          <div class="text-right">
                              <div class="text-sm font-black text-green-600">{{ $group->members_count }}</div>
                              <div class="text-[8px] text-gray-400 font-bold uppercase">Members</div>
                          </div>
                      </div>
                      @endforeach
                  </div>
              </div>
              <div class="card-footer bg-green-900 p-4 rounded-b-2xl">
                  <div class="flex items-center justify-between text-white">
                      <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Community Giving</span>
                      <span class="text-sm font-black">TZS {{ number_format($communityCollections, 0) }}</span>
                  </div>
              </div>
          </div>
      </div>

      <!-- RECENT COMMUNITY ACTIVITY -->
      <div class="lg:col-span-8">
          <div class="card h-full border-none shadow-sm">
              <div class="card-header bg-gray-50/50 border-b flex items-center justify-between">
                  <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Recent Community Operations</h3>
                  <a href="{{ route('groups.index') }}" class="text-[9px] font-black text-green-600 uppercase tracking-widest hover:underline">View All Groups</a>
              </div>
              <div class="table-wrap">
                  <table class="w-full">
                      <thead>
                          <tr class="bg-gray-50/30">
                              <th class="text-[9px] font-black uppercase p-4 text-gray-400">Community</th>
                              <th class="text-[9px] font-black uppercase p-4 text-gray-400">Meeting Date</th>
                              <th class="text-[9px] font-black uppercase p-4 text-gray-400 text-center">Attendance</th>
                              <th class="text-[9px] font-black uppercase p-4 text-gray-400 text-right">Collected</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100">
                          @forelse($recentGroupActivity as $activity)
                          <tr class="hover:bg-light/30 transition-all">
                              <td class="p-4 font-bold text-sm text-gray-800">{{ $activity->group->name }}</td>
                              <td class="p-4 text-xs text-muted">{{ $activity->meeting_date->format('M d, Y') }}</td>
                              <td class="p-4 text-center">
                                  <div class="flex justify-center gap-1">
                                      <span class="badge green scale-75">{{ $activity->present_count }}P</span>
                                      <span class="badge amber scale-75">{{ $activity->guest_count }}G</span>
                                  </div>
                              </td>
                              <td class="p-4 text-right font-black text-sm text-green-600">TZS {{ number_format($activity->total_collected, 0) }}</td>
                          </tr>
                          @empty
                          <tr><td colspan="4" class="p-12 text-center text-muted italic text-xs">No recent community activities recorded.</td></tr>
                          @endforelse
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>

  <!-- ADMIN ANNOUNCEMENTS & ALERTS -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
      <div class="lg:col-span-12">
          <div class="card border-l-4 border-green-600 shadow-lg">
              <div class="card-header bg-green-50/30 border-b p-6 flex items-center justify-between">
                  <div>
                      <h3 class="text-sm font-black uppercase tracking-widest text-green-900">Community Announcements</h3>
                      <p class="text-[10px] text-green-600 font-bold uppercase tracking-widest mt-1">Targeted Communication & Advertising</p>
                  </div>
                  <a href="{{ route('communications.create') }}" class="btn btn-primary px-6 py-2 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-100">
                      Create New Broadcast
                  </a>
              </div>
              <div class="card-body p-0">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-x divide-gray-100">
                      <div class="p-6">
                          <div class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Active Alerts</div>
                          <div class="space-y-4">
                              @php
                                  $activeAnnouncements = \App\Models\Communication::where('recipient_type', 'all')->where('status', 'sent')->latest()->limit(3)->get();
                              @endphp
                              @forelse($activeAnnouncements as $ann)
                              <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-green-100 transition-all cursor-pointer">
                                  <div class="text-[10px] font-black text-gray-800 uppercase line-clamp-1">{{ $ann->subject }}</div>
                                  <div class="text-[9px] text-muted font-bold mt-1 uppercase">{{ $ann->created_at->diffForHumans() }}</div>
                              </div>
                              @empty
                              <div class="text-center py-4 text-muted italic text-[10px]">No active general alerts.</div>
                              @endforelse
                          </div>
                      </div>
                      <div class="lg:col-span-3 p-6">
                          <div class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Targeted Group Issues</div>
                          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                              @foreach($topGroups as $group)
                              <div class="p-4 rounded-2xl bg-light/30 border border-gray-100 flex items-center justify-between group hover:bg-white hover:shadow-md transition-all">
                                  <div>
                                      <div class="text-sm font-black text-gray-800">{{ $group->name }}</div>
                                      <div class="text-[9px] text-muted font-bold uppercase tracking-widest">{{ $group->members_count }} Members</div>
                                  </div>
                                  <a href="{{ route('communications.create', ['group_id' => $group->id]) }}" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex-center opacity-0 group-hover:opacity-100 transition-all">
                                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                  </a>
                              </div>
                              @endforeach
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financeTrendChart');
    if (ctx) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartMonths),
                datasets: [{
                    label: 'Monthly Income',
                    data: @json(array_values($financeData)),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'TZS ' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            borderDash: [5, 5], 
                            color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0,0,0,0.05)' 
                        },
                        ticks: {
                            color: isDark ? '#7ecfa0' : '#3d6b54',
                            callback: function(value) {
                                if (value >= 1000000) return (value/1000000) + 'M';
                                if (value >= 1000) return (value/1000) + 'K';
                                return value;
                            }
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: {
                            color: isDark ? '#7ecfa0' : '#3d6b54'
                        }
                    }
                }
            }
        });

        // Listen for theme changes
        window.addEventListener('themeChanged', (e) => {
            const dark = e.detail.theme === 'dark';
            chart.options.scales.y.grid.color = dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0,0,0,0.05)';
            chart.options.scales.y.ticks.color = dark ? '#7ecfa0' : '#3d6b54';
            chart.options.scales.x.ticks.color = dark ? '#7ecfa0' : '#3d6b54';
            chart.update();
        });
    }
});
</script>
@endpush
