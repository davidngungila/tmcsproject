@extends('layouts.app')

@section('title', 'Member Dashboard - TmcsSmart')
@section('page-title', 'Welcome, ' . $member->full_name)
@section('breadcrumb', 'TmcsSmart / Member / Dashboard')

@section('content')
<div class="animate-in">
    <!-- TOP STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card bg-gradient-to-br from-blue-600 to-blue-400 text-white p-6">
            <div class="text-xs uppercase font-bold opacity-80 mb-1">Total Contributed</div>
            <div class="text-2xl font-bold">{{ number_format($totalContributed) }} <span class="text-sm">TZS</span></div>
        </div>
        <div class="card p-6 border-l-4 border-green-500">
            <div class="text-xs text-muted uppercase font-bold mb-1">My Groups</div>
            <div class="text-2xl font-bold">{{ $member->groups->count() }}</div>
        </div>
        <div class="card p-6 border-l-4 border-amber-500">
            <div class="text-xs text-muted uppercase font-bold mb-1">Events Attended</div>
            <div class="text-2xl font-bold">{{ $member->eventAttendance->count() }}</div>
        </div>
        <div class="card p-6 border-l-4 border-purple-500">
            <div class="text-xs text-muted uppercase font-bold mb-1">Member Status</div>
            <div class="badge green">Active</div>
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
