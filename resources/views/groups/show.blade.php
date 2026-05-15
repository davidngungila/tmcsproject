@extends('layouts.app')

@section('title', $group->name . ' - Group Details')
@section('page-title', 'Group Profile')
@section('breadcrumb', 'Home / Groups / ' . $group->name)

@section('content')
<div class="animate-in space-y-6">
    <!-- HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex-center shadow-lg text-2xl font-black">
                {{ substr($group->name, 0, 2) }}
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800">{{ $group->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="badge {{ $group->type == 'Community' ? 'green' : 'amber' }} uppercase font-bold text-[10px]">{{ $group->type }}</span>
                    <span class="text-muted text-xs font-medium">•</span>
                    <span class="text-muted text-xs font-medium">Meeting Day: <span class="text-green-600 font-bold">{{ $group->meeting_day ?: 'Not Set' }}</span></span>
                    <span class="text-muted text-xs font-medium">•</span>
                    <span class="badge {{ $group->is_active ? 'green' : 'red' }} uppercase font-bold text-[10px]">{{ $group->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('groups.reports.index', $group->id) }}" class="btn btn-secondary px-4 bg-blue-600 text-white hover:bg-blue-700">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                View Full Reports
            </a>
            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-secondary px-4">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Group
            </a>
            <div class="topbar-dropdown">
                <button class="btn btn-primary px-4" onclick="toggleTopbarDropdown('commOpsMenu')">
                    Community Operations
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="ml-2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="dropdown-menu" id="commOpsMenu" style="right: 0; left: auto;">
                    <a href="{{ route('groups.operations.members', $group->id) }}" class="dropdown-menu-item">Manage Members</a>
                    <a href="{{ route('groups.operations.contributions', $group->id) }}" class="dropdown-menu-item">Record Giving</a>
                    <a href="{{ route('groups.operations.attendance', $group->id) }}" class="dropdown-menu-item">Mark Attendance</a>
                    <a href="{{ route('groups.operations.planning', $group->id) }}" class="dropdown-menu-item">Strategic Planning</a>
                    <a href="{{ route('groups.operations.messages', $group->id) }}" class="dropdown-menu-item">Send Broadcast</a>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-6 border-none shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Members</div>
                    <div class="text-lg font-black text-gray-800">{{ $group->members->count() }}</div>
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Giving</div>
                    <div class="text-lg font-black text-gray-800">TZS {{ number_format($totalCollected, 0) }}</div>
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Avg Attendance</div>
                    <div class="text-lg font-black text-gray-800">{{ $attendanceRate }}%</div>
                </div>
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Active Plans</div>
                    <div class="text-lg font-black text-gray-800">{{ $group->plans->where('status', 'active')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- LEFT: LEADERSHIP & INFO -->
        <div class="lg:col-span-4 space-y-6">
            <!-- LEADERSHIP -->
            <div class="card border-none shadow-sm">
                <div class="card-header border-b p-6 bg-gray-50/50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Leadership Team</h3>
                </div>
                <div class="card-body p-6 space-y-5">
                    <!-- GROUP LEADER -->
                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-blue-50/30 border border-blue-50 group hover:bg-blue-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex-center font-black text-xs">LD</div>
                        <div class="flex-1">
                            <div class="text-[10px] font-black uppercase text-blue-600 tracking-widest">Group Leader</div>
                            @if($group->leader_id)
                                <div class="text-sm font-black text-gray-800">{{ $group->leader->full_name }}</div>
                            @else
                                <form action="{{ route('groups.assign-leadership', $group->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <input type="hidden" name="role" value="leader_id">
                                    <select name="member_id" class="select2-inline">
                                        <option value=""></option>
                                        @foreach($group->members as $member)
                                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- CHAIRPERSON -->
                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-green-50/30 border border-green-50 group hover:bg-green-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-green-600 text-white flex-center font-black text-xs">CP</div>
                        <div class="flex-1">
                            <div class="text-[10px] font-black uppercase text-green-600 tracking-widest">Chairperson</div>
                            @if($group->chairperson_id)
                                <div class="text-sm font-black text-gray-800">{{ $group->chairperson->full_name }}</div>
                            @else
                                <form action="{{ route('groups.assign-leadership', $group->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <input type="hidden" name="role" value="chairperson_id">
                                    <select name="member_id" class="select2-inline">
                                        <option value=""></option>
                                        @foreach($group->members as $member)
                                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- SECRETARY -->
                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-green-50/30 border border-green-50 group hover:bg-green-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex-center font-black text-xs">SC</div>
                        <div class="flex-1">
                            <div class="text-[10px] font-black uppercase text-green-600 tracking-widest">Secretary</div>
                            @if($group->secretary_id)
                                <div class="text-sm font-black text-gray-800">{{ $group->secretary->full_name }}</div>
                            @else
                                <form action="{{ route('groups.assign-leadership', $group->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <input type="hidden" name="role" value="secretary_id">
                                    <select name="member_id" class="select2-inline">
                                        <option value=""></option>
                                        @foreach($group->members as $member)
                                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- ACCOUNTANT -->
                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-amber-50/30 border border-amber-50 group hover:bg-amber-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex-center font-black text-xs">AC</div>
                        <div class="flex-1">
                            <div class="text-[10px] font-black uppercase text-amber-600 tracking-widest">Accountant</div>
                            @if($group->accountant_id)
                                <div class="text-sm font-black text-gray-800">{{ $group->accountant->full_name }}</div>
                            @else
                                <form action="{{ route('groups.assign-leadership', $group->id) }}" method="POST" class="mt-1">
                                    @csrf
                                    <input type="hidden" name="role" value="accountant_id">
                                    <select name="member_id" class="select2-inline">
                                        <option value=""></option>
                                        @foreach($group->members as $member)
                                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="card border-none shadow-sm">
                <div class="card-header border-b p-6 bg-gray-50/50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">About this Community</h3>
                </div>
                <div class="card-body p-6">
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $group->description ?: 'No description provided for this community yet.' }}</p>
                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Formed On</span>
                        <span>{{ $group->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: REPORTS & DATA -->
        <div class="lg:col-span-8 space-y-6">
            <!-- REPORT TABS -->
            <div class="card border-none shadow-sm overflow-hidden">
                <div class="flex border-b bg-gray-50/50 p-2 gap-2">
                    <button class="tab-btn active px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Overview Report</button>
                    <button class="tab-btn px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Members</button>
                    <button class="tab-btn px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Giving History</button>
                    <button class="tab-btn px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Attendance</button>
                    <button class="tab-btn px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Roadmap</button>
                </div>
                
                <div class="p-6">
                    <!-- OVERVIEW REPORT TAB -->
                    <div class="tab-content block">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div>
                                <h4 class="text-sm font-black text-gray-800 mb-4 uppercase tracking-wider">Financial Performance</h4>
                                <div class="h-64 flex items-end gap-2 pb-6 border-b border-l border-gray-100">
                                    @php 
                                        $maxTotal = max(array_merge($monthlyCollections->pluck('total')->toArray(), [1]));
                                    @endphp
                                    @foreach($monthlyCollections as $collection)
                                    <div class="flex-1 bg-green-500 rounded-t-lg relative group" style="height: {{ ($collection->total / $maxTotal) * 100 }}%">
                                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap z-10">
                                            TZS {{ number_format($collection->total) }}
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($monthlyCollections->isEmpty())
                                    <div class="flex-1 flex-center h-full text-xs text-muted">No data for {{ date('Y') }}</div>
                                    @endif
                                </div>
                                <div class="flex justify-between mt-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                    <span>JAN</span><span>JUN</span><span>DEC</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="text-sm font-black text-gray-800 mb-4 uppercase tracking-wider">Key Metrics</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                        <span class="text-xs font-bold text-gray-600 uppercase">Avg Giving / Meeting</span>
                                        <span class="text-sm font-black text-green-600">TZS {{ number_format($group->meetings->avg('total_collected'), 0) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                        <span class="text-xs font-bold text-gray-600 uppercase">Retention Rate</span>
                                        <span class="text-sm font-black text-green-600">98%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                        <span class="text-xs font-bold text-gray-600 uppercase">Active Meetings</span>
                                        <span class="text-sm font-black text-amber-600">{{ $group->meetings->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-black text-gray-800 mb-4 uppercase tracking-wider">Recent Activity Report</h4>
                            <div class="space-y-3">
                                @forelse($group->meetings as $meeting)
                                <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 hover:border-green-100 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center font-black text-[10px] uppercase">
                                            {{ $meeting->meeting_date->format('M') }}<br>{{ $meeting->meeting_date->format('d') }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-black text-gray-800 uppercase tracking-wider">Meeting Record</div>
                                            <div class="text-[10px] text-muted font-bold">{{ $meeting->attendances->where('status', 'present')->count() }} Members Attended • {{ $meeting->notes ?: 'Regular community meeting' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-black text-green-600">+TZS {{ number_format($meeting->total_collected, 0) }}</div>
                                        <div class="text-[9px] font-black text-gray-300 uppercase">Collected</div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12 text-muted italic text-xs">No recent activity recorded.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- OTHER TABS (HIDDEN IN THIS VIEW BUT CONTENT LOADED) -->
                    <div class="tab-content hidden">
                        <div class="table-wrap">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>Member Name</th>
                                        <th>Reg. No</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->members as $member)
                                    <tr>
                                        <td>
                                            <div class="font-bold">{{ $member->full_name }}</div>
                                            <div class="text-[10px] text-muted">{{ $member->email }}</div>
                                        </td>
                                        <td class="text-xs mono">{{ $member->registration_number }}</td>
                                        <td class="text-xs">{{ $member->pivot->join_date ? \Carbon\Carbon::parse($member->pivot->join_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td><span class="badge {{ $member->pivot->is_active ? 'green' : 'red' }} scale-90">{{ $member->pivot->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-content hidden">
                        <div class="table-wrap">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->meetings as $meeting)
                                    <tr>
                                        <td class="font-bold text-xs">{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                        <td class="font-black text-green-600 text-xs">TZS {{ number_format($meeting->total_collected, 0) }}</td>
                                        <td class="text-xs text-muted">{{ $meeting->notes ?: 'Regular giving' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-content hidden">
                        <div class="table-wrap">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Attendance</th>
                                        <th>Givings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->meetings as $meeting)
                                    <tr>
                                        <td class="font-bold text-xs">{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <span class="badge green text-[9px]">{{ $meeting->present_count }} P</span>
                                                <span class="badge amber text-[9px]">{{ $meeting->guest_count }} G</span>
                                                <span class="badge red text-[9px]">{{ $meeting->absent_count }} A</span>
                                            </div>
                                        </td>
                                        <td class="text-xs font-black">TZS {{ number_format($meeting->total_collected, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-content hidden">
                        <div class="space-y-6">
                            @foreach($group->plans as $plan)
                            <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-black text-gray-800">{{ $plan->title }}</h4>
                                        <p class="text-xs text-muted mt-1">{{ $plan->description }}</p>
                                    </div>
                                    <span class="badge {{ $plan->status == 'active' ? 'green' : 'blue' }} uppercase font-bold text-[9px]">{{ $plan->status }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-4">
                                    <div class="text-[10px] font-black uppercase text-gray-400">Budget: <span class="text-gray-800">TZS {{ number_format($plan->budget_amount, 0) }}</span></div>
                                    <div class="text-[10px] font-black uppercase text-gray-400">Timeline: <span class="text-gray-800">{{ $plan->start_date->format('M Y') }}</span></div>
                                </div>
                            </div>
                            @endforeach
                            @if($group->plans->isEmpty())
                            <div class="text-center py-12 text-muted italic text-xs">No roadmap items recorded.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: transparent;
        border: none;
        height: auto;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
        padding-left: 0;
        line-height: normal;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        display: none;
    }
    .tab-btn.active {
        background: white;
        color: #047857;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .tab-btn:not(.active) {
        color: #94a3b8;
    }
    .tab-btn:not(.active):hover {
        color: #047857;
        background: rgba(255,255,255,0.5);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2-inline').select2({
            placeholder: 'Search or Select...',
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            $(this).closest('form').submit();
        });

        // Tab Switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                // Remove active from all buttons
                tabBtns.forEach(b => b.classList.remove('active'));
                // Add active to clicked
                btn.classList.add('active');
                
                // Hide all content
                tabContents.forEach(c => {
                    c.classList.add('hidden');
                    c.classList.remove('block');
                });
                // Show selected content
                tabContents[index].classList.remove('hidden');
                tabContents[index].classList.add('block');
            });
        });
    });
</script>
@endpush
@endsection
