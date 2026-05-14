@extends('layouts.app')

@section('title', 'Dashboard - TMCS Smart')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Member / Dashboard')

@section('content')
<div class="animate-in">
    <!-- TOP STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card bg-gradient-to-br from-blue-700 to-indigo-500 text-white p-6 shadow-lg border-0">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-bold">Total Giving</span>
            </div>
            <div class="text-2xl font-black leading-none">{{ number_format($totalContributed) }}</div>
            <div class="text-[10px] mt-1 opacity-80 font-bold uppercase tracking-wider">Tanzanian Shillings</div>
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
                <div class="card-title text-sm">Contribution Trends (Last 6 Months)</div>
            </div>
            <div class="card-body">
                <canvas id="contributionChart" height="200"></canvas>
            </div>
        </div>

        <!-- CONTRIBUTION TYPES (PIE CHART) -->
        <div class="lg:col-span-1 card">
            <div class="card-header border-b">
                <div class="card-title text-sm">Giving Distribution</div>
            </div>
            <div class="card-body">
                <canvas id="typeChart" height="200"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Trends Chart
    const trendCtx = document.getElementById('contributionChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($contributionTrends->pluck('month')) !!},
            datasets: [{
                label: 'Contributions (TZS)',
                data: {!! json_encode($contributionTrends->pluck('total')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { font: { size: 10 } } }, x: { ticks: { font: { size: 10 } } } }
        }
    });

    // Types Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($contributionTypes->pluck('contribution_type')) !!},
            datasets: [{
                data: {!! json_encode($contributionTypes->pluck('total')) !!},
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#6B7280'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
        }
    });
</script>
@endpush
