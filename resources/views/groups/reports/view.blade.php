@extends('layouts.app')

@section('title', $data['title'] . ' - ' . $group->name)
@section('page-title', $data['title'])
@section('breadcrumb', 'Home / Groups / ' . $group->name . ' / Reports / ' . ucfirst($data['type']))

@section('content')
<div class="animate-in space-y-6 pb-12">
    <!-- HEADER & ACTIONS -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800">{{ $data['title'] }}</h2>
            <p class="text-sm text-muted font-medium mt-1">Detailed analysis for {{ $group->name }} • {{ date('M Y') }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary bg-white shadow-sm border-gray-100">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
            <button class="btn btn-primary bg-green-600 shadow-lg shadow-green-100 border-none">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="card p-4 border-none shadow-sm flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Time Range:</label>
            <select class="form-control text-xs font-bold py-1 px-3 rounded-lg border-gray-100 bg-gray-50/50">
                <option>Last 30 Days</option>
                <option>Last 3 Months</option>
                <option selected>This Year ({{ date('Y') }})</option>
                <option>All Time</option>
            </select>
        </div>
        <div class="flex-1"></div>
        <div class="text-[10px] font-bold text-muted flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Last updated: {{ now()->format('H:i A') }}
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if($data['type'] == 'financial')
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Revenue</div>
                    <div class="text-xl font-black text-gray-800 mt-2">TZS {{ number_format($data['totalCollected'], 0) }}</div>
                    <div class="text-[9px] font-bold text-green-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Total Collected
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Avg Meeting Giving</div>
                    <div class="text-xl font-black text-gray-800 mt-2">TZS {{ number_format($data['avgMeetingGiving'], 0) }}</div>
                    <div class="text-[9px] font-bold text-blue-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Per Meeting
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Meetings</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $group->meetings->count() }}</div>
                    <div class="text-[9px] font-bold text-amber-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Meetings Held
                    </div>
                </div>
            </div>
        @elseif($data['type'] == 'administrative')
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Members</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['totalMembers'] }}</div>
                    <div class="text-[9px] font-bold text-green-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Active Members
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">New This Month</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['newThisMonth'] }}</div>
                    <div class="text-[9px] font-bold text-blue-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Joined This Month
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Active Plans</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['activePlans'] }}</div>
                    <div class="text-[9px] font-bold text-amber-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Current Plans
                    </div>
                </div>
            </div>
        @elseif($data['type'] == 'meetings')
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Meetings</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $group->meetings->count() }}</div>
                    <div class="text-[9px] font-bold text-green-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Total Held
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Avg Attendance</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['avgAttendanceRate'] }}%</div>
                    <div class="text-[9px] font-bold text-blue-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Average Rate
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Collected</div>
                    <div class="text-xl font-black text-gray-800 mt-2">TZS {{ number_format($data['totalCollected'], 0) }}</div>
                    <div class="text-[9px] font-bold text-amber-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Total Giving
                    </div>
                </div>
            </div>
        @elseif($data['type'] == 'communication')
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Messages</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['totalMessages'] }}</div>
                    <div class="text-[9px] font-bold text-green-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Total Sent
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Scheduled Messages</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['scheduledCount'] }}</div>
                    <div class="text-[9px] font-bold text-blue-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Upcoming
                    </div>
                </div>
            </div>
            <div class="card p-6 border-none shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">Templates</div>
                    <div class="text-xl font-black text-gray-800 mt-2">{{ $data['templateCount'] }}</div>
                    <div class="text-[9px] font-bold text-amber-600 mt-3 flex items-center gap-1">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        Available
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LINE/BAR CHART -->
        <div class="lg:col-span-2 card border-none shadow-sm p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Performance Trends</h3>
                    <p class="text-[10px] text-muted font-bold mt-1">Monthly {{ $data['type'] == 'financial' ? 'Giving' : 'Growth' }} Statistics</p>
                </div>
                <div class="flex bg-gray-50 rounded-lg p-1">
                    <button class="px-3 py-1 text-[9px] font-black uppercase bg-white rounded-md shadow-sm">Line</button>
                    <button class="px-3 py-1 text-[9px] font-black uppercase text-gray-400">Bar</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- DONUT CHART -->
        <div class="card border-none shadow-sm p-6">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-8 text-center">Data Distribution</h3>
            <div class="h-64 flex-center">
                <canvas id="donutChart"></canvas>
            </div>
            <div class="mt-8 space-y-3">
                @if($data['type'] == 'administrative')
                    @foreach($data['genderDist'] as $dist)
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-{{ $dist->gender == 'male' ? 'green' : ($dist->gender == 'female' ? 'blue' : 'purple') }}-500"></span>
                                <span class="text-gray-600">{{ ucfirst($dist->gender) }}</span>
                            </div>
                            <span class="text-gray-800">{{ $data['totalMembers'] > 0 ? round(($dist->count / $data['totalMembers']) * 100, 1) : 0 }}%</span>
                        </div>
                    @endforeach
                @elseif($data['type'] == 'meetings')
                    @foreach($data['attendanceDist'] as $dist)
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-{{ $dist->status == 'present' ? 'green' : ($dist->status == 'absent' ? 'red' : 'blue') }}-500"></span>
                                <span class="text-gray-600">{{ ucfirst($dist->status) }}</span>
                            </div>
                            <span class="text-gray-800">{{ $data['attendanceDist']->sum('count') > 0 ? round(($dist->count / $data['attendanceDist']->sum('count')) * 100, 1) : 0 }}%</span>
                        </div>
                    @endforeach
                @elseif($data['type'] == 'communication')
                    @foreach($data['messageDist'] as $dist)
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-gray-600">{{ ucfirst($dist->type) }}</span>
                            </div>
                            <span class="text-gray-800">{{ $data['totalMessages'] > 0 ? round(($dist->count / $data['totalMessages']) * 100, 1) : 0 }}%</span>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-between text-[10px] font-bold">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-gray-600">Meetings</span>
                        </div>
                        <span class="text-gray-800">100%</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- DATA TABLE & TIMELINE -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- DATA TABLE -->
        <div class="lg:col-span-8 card border-none shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Recent Transactions / Data</h3>
                <button class="text-[10px] font-black text-green-600 uppercase tracking-widest hover:underline">View All</button>
            </div>
            <div class="table-wrap">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount / Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if($data['type'] == 'financial')
                            @foreach($data['recentTransactions'] as $tx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $tx->meeting_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-black text-gray-800">Meeting Collection</td>
                                <td class="px-6 py-4"><span class="badge green">GIVING</span></td>
                                <td class="px-6 py-4 text-right text-xs font-black text-green-600">TZS {{ number_format($tx->total_collected, 0) }}</td>
                            </tr>
                            @endforeach
                        @elseif($data['type'] == 'administrative')
                            @foreach($data['membershipTable'] as $member)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $member->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-black text-gray-800">{{ $member->full_name }}</td>
                                <td class="px-6 py-4"><span class="badge blue">MEMBER</span></td>
                                <td class="px-6 py-4 text-right text-xs font-black text-gray-800">{{ ucfirst($member->gender ?? 'N/A') }}</td>
                            </tr>
                            @endforeach
                        @elseif($data['type'] == 'meetings')
                            @foreach($data['recentMeetings'] as $meeting)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-black text-gray-800">Group Meeting</td>
                                <td class="px-6 py-4"><span class="badge amber">MEETING</span></td>
                                <td class="px-6 py-4 text-right text-xs font-black text-green-600">TZS {{ number_format($meeting->total_collected, 0) }}</td>
                            </tr>
                            @endforeach
                        @elseif($data['type'] == 'communication')
                            @foreach($data['recentMessages'] as $msg)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $msg->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-black text-gray-800">{{ Str::limit($msg->content ?? 'No content', 30) }}</td>
                                <td class="px-6 py-4"><span class="badge purple">{{ strtoupper($msg->type) }}</span></td>
                                <td class="px-6 py-4 text-right text-xs font-black text-gray-800">{{ $msg->status }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if($data['type'] == 'administrative' && $data['membershipTable']->hasPages())
                <div class="p-6 border-t border-gray-50 bg-gray-50/30">
                    {{ $data['membershipTable']->links() }}
                </div>
            @endif
        </div>

        <!-- ACTIVITY TIMELINE -->
        <div class="lg:col-span-4 card border-none shadow-sm p-6">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-8">Activity Timeline</h3>
            <div class="space-y-6 relative before:absolute before:left-3.5 before:top-2 before:bottom-2 before:w-px before:bg-gray-100">
                <div class="relative pl-10">
                    <div class="absolute left-0 top-1.5 w-7 h-7 rounded-full bg-green-50 border-4 border-white shadow-sm flex-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-800 uppercase">Monthly Meeting Conducted</div>
                        <p class="text-[10px] text-muted font-bold mt-1">Chairperson updated the attendance and collections.</p>
                        <div class="text-[8px] font-black text-gray-400 mt-2 uppercase">2 Hours Ago</div>
                    </div>
                </div>
                <div class="relative pl-10">
                    <div class="absolute left-0 top-1.5 w-7 h-7 rounded-full bg-blue-50 border-4 border-white shadow-sm flex-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-800 uppercase">New Member Joined</div>
                        <p class="text-[10px] text-muted font-bold mt-1">John Doe was added to the group via community portal.</p>
                        <div class="text-[8px] font-black text-gray-400 mt-2 uppercase">Yesterday, 4:30 PM</div>
                    </div>
                </div>
                <div class="relative pl-10">
                    <div class="absolute left-0 top-1.5 w-7 h-7 rounded-full bg-amber-50 border-4 border-white shadow-sm flex-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-800 uppercase">Strategic Plan Updated</div>
                        <p class="text-[10px] text-muted font-bold mt-1">Community roadmap for Q3 2026 was finalized.</p>
                        <div class="text-[8px] font-black text-gray-400 mt-2 uppercase">May 12, 2026</div>
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
    // MAIN CHART (LINE/BAR)
    const mainCtx = document.getElementById('mainChart').getContext('2d');
    const mainChart = new Chart(mainCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: '{{ $data["type"] == "financial" ? "Giving (TZS)" : ($data["type"] == "administrative" ? "New Members" : ($data["type"] == "meetings" ? "Meetings Held" : "Messages Sent")) }}',
                data: @json(array_values($data['chartData'])),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f9fafb' },
                    ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '700' }, color: '#9ca3af' }
                }
            }
        }
    });

    // DONUT CHART DATA
    let donutLabels = [];
    let donutData = [];
    let donutColors = [];
    
    @if($data['type'] == 'administrative')
        @foreach($data['genderDist'] as $dist)
            donutLabels.push('{{ ucfirst($dist->gender) }}');
            donutData.push({{ $dist->count }});
            donutColors.push('{{ $dist->gender == "male" ? "#10b981" : ($dist->gender == "female" ? "#3b82f6" : "#8b5cf6") }}');
        @endforeach
    @elseif($data['type'] == 'meetings')
        @foreach($data['attendanceDist'] as $dist)
            donutLabels.push('{{ ucfirst($dist->status) }}');
            donutData.push({{ $dist->count }});
            donutColors.push('{{ $dist->status == "present" ? "#10b981" : ($dist->status == "absent" ? "#ef4444" : "#3b82f6") }}');
        @endforeach
    @elseif($data['type'] == 'communication')
        @foreach($data['messageDist'] as $dist)
            donutLabels.push('{{ ucfirst($dist->type) }}');
            donutData.push({{ $dist->count }});
            donutColors.push('#10b981');
        @endforeach
    @else
        donutLabels = ['Meetings'];
        donutData = [1];
        donutColors = ['#10b981'];
    @endif

    // DONUT CHART
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: donutLabels,
            datasets: [{
                data: donutData,
                backgroundColor: donutColors,
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
