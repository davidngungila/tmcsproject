@extends('layouts.app')

@section('title', 'Dashboard - TMCS Smart')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Member / Dashboard')

@section('content')
<div class="animate-in">
    <style>
        /* Member Dashboard Theme Overrides */
        [data-theme="dark"] .card {
            background: var(--bg-card);
            border-color: var(--border);
            color: var(--text-primary);
        }
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        [data-theme="dark"] .bg-light\/50 {
            background: rgba(255, 255, 255, 0.05) !important;
        }
        [data-theme="dark"] .divide-gray-50 > * {
            border-color: var(--border-light) !important;
        }
        [data-theme="dark"] .hover\:bg-light\/30:hover {
            background: var(--hover-row) !important;
        }
        [data-theme="dark"] th {
            background: rgba(255, 255, 255, 0.03) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border) !important;
        }
        [data-theme="dark"] td {
            border-color: var(--border-light) !important;
            color: var(--text-primary) !important;
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .grid-cols-4 {
                grid-template-columns: 1fr 1fr !important;
            }
            .text-2xl {
                font-size: 1.25rem !important;
            }
        }
        @media (max-width: 480px) {
            .grid-cols-4 {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <!-- TOP STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <!-- TOTAL GIVING -->
<div class="card p-6 shadow-sm border-l-4 border-blue-500">
    <div class="flex items-center gap-4">

        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex-center">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div>
            <div class="text-2xl font-black">
                {{ number_format($totalContributed) }}
            </div>

            <div class="text-[10px] text-muted uppercase font-bold tracking-widest">
                Total Giving
            </div>
        </div>

    </div>
</div>

        <div class="card p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex-center">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-black">{{ $member->groups->count() }}</div>
                    <div class="text-[10px] text-muted uppercase font-bold tracking-widest">Active Groups</div>
                </div>
            </div>
        </div>

        <div class="card p-6 shadow-sm border-l-4 border-amber-500">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex-center">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-black">{{ $member->eventAttendance->count() }}</div>
                    <div class="text-[10px] text-muted uppercase font-bold tracking-widest">Events Attended</div>
                </div>
            </div>
        </div>

        <div class="card p-6 shadow-sm border-l-4 border-purple-500">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex-center">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div class="badge green mb-1 uppercase font-black text-[9px]">Active</div>
                    <div class="text-[10px] text-muted uppercase font-bold tracking-widest">Member Status</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- CONTRIBUTION TRENDS (BAR CHART) -->
        <div class="lg:col-span-2 card">
            <div class="card-header border-b flex items-center justify-between">
                <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Contribution Trends</div>
                <div class="flex gap-1">
                    <a href="?trend_filter=week" class="px-2 py-1 text-[10px] rounded {{ request('trend_filter', 'month') == 'week' ? 'bg-blue-600 text-white' : 'bg-light text-muted hover:bg-gray-200' }} font-bold transition-all">WEEK</a>
                    <a href="?trend_filter=month" class="px-2 py-1 text-[10px] rounded {{ request('trend_filter', 'month') == 'month' ? 'bg-blue-600 text-white' : 'bg-light text-muted hover:bg-gray-200' }} font-bold transition-all">MONTH</a>
                    <a href="?trend_filter=year" class="px-2 py-1 text-[10px] rounded {{ request('trend_filter', 'month') == 'year' ? 'bg-blue-600 text-white' : 'bg-light text-muted hover:bg-gray-200' }} font-bold transition-all">YEAR</a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="contributionChart" height="250"></canvas>
            </div>
        </div>

        <!-- CONTRIBUTION TYPES (PIE CHART) -->
        <div class="lg:col-span-1 card">
            <div class="card-header border-b flex items-center justify-between">
                <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Giving Distribution</div>
                <div class="flex gap-1">
                    <a href="?dist_filter=month" class="px-2 py-1 text-[10px] rounded {{ request('dist_filter', 'year') == 'month' ? 'bg-amber-500 text-white' : 'bg-light text-muted hover:bg-gray-200' }} font-bold transition-all">MONTH</a>
                    <a href="?dist_filter=year" class="px-2 py-1 text-[10px] rounded {{ request('dist_filter', 'year') == 'year' ? 'bg-amber-500 text-white' : 'bg-light text-muted hover:bg-gray-200' }} font-bold transition-all">YEAR</a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="typeChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- UPCOMING EVENTS -->
        <div class="card">
            <div class="card-header border-b flex items-center justify-between">
                <div class="card-title text-sm">Upcoming Church Events</div>
                <a href="{{ route('member.events') }}" class="text-xs text-blue-500 font-bold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-50">
                    @forelse($upcomingEvents as $event)
                        <div class="p-4 flex items-center gap-4 hover:bg-light/30 transition-colors">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex-center flex-col leading-none">
                                <span class="text-xs font-bold">{{ $event->event_date->format('d') }}</span>
                                <span class="text-[9px] uppercase">{{ $event->event_date->format('M') }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-sm">{{ $event->event_name }}</div>
                                <div class="text-xs text-muted">{{ $event->venue }} • {{ $event->event_time->format('h:i A') }}</div>
                            </div>
                            <span class="badge blue text-[10px]">Plan to attend</span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-muted text-sm">No upcoming events scheduled.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RECENT CONTRIBUTIONS -->
        <div class="card">
            <div class="card-header border-b flex items-center justify-between">
                <div class="card-title text-sm">My Recent Giving</div>
                <a href="{{ route('member.contributions.index') }}" class="text-xs text-blue-500 font-bold">Full History</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-light/50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Type</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentContributions as $c)
                            <tr class="hover:bg-light/30 transition-colors">
                                <td class="px-6 py-3 text-xs">{{ $c->contribution_date->format('d M, Y') }}</td>
                                <td class="px-6 py-3 text-xs font-medium">{{ $c->contribution_type }}</td>
                                <td class="px-6 py-3 text-xs font-bold text-right">{{ number_format($c->amount) }} TZS</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-muted text-xs">No contributions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    
    // Trends Chart - Line style like Admin Dashboard
    const trendCtx = document.getElementById('contributionChart');
    if (trendCtx) {
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Contributions (TZS)',
                    data: @json($trendData),
                    borderColor: '#2563eb', // blue-600
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb'
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
                            font: { size: 10 },
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
                            color: isDark ? '#7ecfa0' : '#3d6b54',
                            font: { size: 10 } 
                        }
                    }
                }
            }
        });

        window.addEventListener('themeChanged', (e) => {
            const dark = e.detail.theme === 'dark';
            trendChart.options.scales.y.grid.color = dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0,0,0,0.05)';
            trendChart.options.scales.y.ticks.color = dark ? '#7ecfa0' : '#3d6b54';
            trendChart.options.scales.x.ticks.color = dark ? '#7ecfa0' : '#3d6b54';
            trendChart.update();
        });
    }

    // Types Chart
    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        const typeData = @json($contributionTypes->pluck('total'));
        const typeLabels = @json($contributionTypes->pluck('contribution_type'));

        if (typeData.length > 0) {
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: typeLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1).replace('_', ' ')),
                    datasets: [{
                        data: typeData,
                        backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#6b7280'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                color: isDark ? '#e2f5eb' : '#0a1a12',
                                boxWidth: 10, 
                                font: { size: 10, weight: 'bold' },
                                padding: 15
                            } 
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return ` TZS ${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            window.addEventListener('themeChanged', (e) => {
                const dark = e.detail.theme === 'dark';
                typeChart.options.plugins.legend.labels.color = dark ? '#e2f5eb' : '#0a1a12';
                typeChart.update();
            });
        } else {
            const ctx = typeCtx.getContext('2d');
            ctx.font = "12px DM Sans";
            ctx.fillStyle = isDark ? "#7ecfa0" : "#6b9e82";
            ctx.textAlign = "center";
            ctx.fillText("No giving records found", typeCtx.width/2, typeCtx.height/2);
            
            window.addEventListener('themeChanged', (e) => {
                const dark = e.detail.theme === 'dark';
                ctx.clearRect(0, 0, typeCtx.width, typeCtx.height);
                ctx.fillStyle = dark ? "#7ecfa0" : "#6b9e82";
                ctx.fillText("No giving records found", typeCtx.width/2, typeCtx.height/2);
            });
        }
    }
});
</script>
@endpush
