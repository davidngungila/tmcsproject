@extends('layouts.app')

@section('title', 'Community Members - ' . $group->name)
@section('page-title', 'Community Membership: ' . $group->name)
@section('breadcrumb', 'Home / Group / Operations / Members')

@section('content')
<div class="animate-in space-y-6">
    <!-- TOP STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 border-none shadow-sm bg-green-600 text-white">
            <div class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Total Members</div>
            <div class="text-2xl font-black">{{ $group->members->count() }}</div>
            <div class="mt-2 text-[10px] font-bold opacity-60">Active: {{ $activeMembers }} • Inactive: {{ $inactiveMembers }}</div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">New This Month</div>
            <div class="text-2xl font-black text-green-600">+{{ $newThisMonth }}</div>
            <div class="mt-2 text-[10px] font-bold text-muted">Recent growth</div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Participation Rate</div>
            <div class="text-2xl font-black text-green-600">92%</div>
            <div class="mt-2 text-[10px] font-bold text-muted">Avg meeting attendance</div>
        </div>
        <div class="card p-6 border-none shadow-sm">
            <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Avg Contribution</div>
            <div class="text-2xl font-black text-green-600">TZS {{ number_format($group->regular_contribution_amount, 0) }}</div>
            <div class="mt-2 text-[10px] font-bold text-muted">Per member / meeting</div>
        </div>
    </div>

    <!-- ANALYTICS GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- MEMBER TYPE CHART -->
        <div class="card shadow-sm border-none overflow-hidden">
            <div class="card-header bg-gray-50/50 border-b p-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Member Type Breakdown</h3>
            </div>
            <div class="card-body p-6">
                <div class="h-48 flex items-center justify-center">
                    <canvas id="typeChart"></canvas>
                </div>
                <div class="mt-6 space-y-2">
                    @foreach($memberTypes as $type => $count)
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-gray-500 uppercase">{{ $type ?: 'Other' }}</span>
                        <span class="font-black text-gray-800">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- GENDER CHART -->
        <div class="card shadow-sm border-none overflow-hidden">
            <div class="card-header bg-gray-50/50 border-b p-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Gender Distribution</h3>
            </div>
            <div class="card-body p-6">
                <div class="h-48 flex items-center justify-center">
                    <canvas id="genderChart"></canvas>
                </div>
                <div class="mt-6 space-y-2">
                    @foreach($genders as $gender => $count)
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-gray-500 uppercase">{{ $gender ?: 'Unknown' }}</span>
                        <span class="font-black text-gray-800">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ACTIVITY SUMMARY -->
        <div class="card shadow-sm border-none overflow-hidden">
            <div class="card-header bg-gray-50/50 border-b p-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Community Health</h3>
            </div>
            <div class="card-body p-6 space-y-6">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] font-black uppercase text-gray-400">Active Membership</span>
                        <span class="text-[10px] font-black text-green-600">{{ round(($activeMembers / max($group->members->count(), 1)) * 100) }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-600 rounded-full" style="width: {{ ($activeMembers / max($group->members->count(), 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] font-black uppercase text-gray-400">Growth Rate (MTD)</span>
                        <span class="text-[10px] font-black text-green-600">+{{ round(($newThisMonth / max($group->members->count(), 1)) * 100) }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ ($newThisMonth / max($group->members->count(), 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div class="p-4 bg-green-50 rounded-2xl border border-green-100">
                    <p class="text-[10px] font-bold text-green-900 leading-relaxed">
                        <svg width="14" height="14" class="inline mr-1 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        This community has a high retention rate. Most members are active in weekly meetings.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- MEMBERS TABLE -->
    <div class="card shadow-sm border-none overflow-hidden">
        <div class="card-header border-b p-6 flex items-center justify-between bg-white">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Member Directory</h3>
            <div class="flex gap-2">
                <button class="btn btn-secondary btn-sm text-[10px] font-black uppercase tracking-widest">Export CSV</button>
                <button onclick="openAddMemberModal()" class="btn btn-primary btn-sm text-[10px] font-black uppercase tracking-widest">Add Member</button>
            </div>
        </div>
        <div class="table-wrap overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4">Member Name</th>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4">Contact Info</th>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4">Member Type</th>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4">Join Date</th>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4">Status</th>
                        <th class="text-[10px] font-black uppercase text-gray-400 p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($group->members as $member)
                    <tr class="hover:bg-light/30 transition-all">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center font-black text-xs">
                                    {{ substr($member->full_name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-black text-sm text-gray-800">{{ $member->full_name }}</div>
                                    <div class="text-[10px] text-muted font-bold tracking-widest uppercase">{{ $member->registration_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-xs font-bold text-gray-600">{{ $member->phone ?: 'No Phone' }}</div>
                            <div class="text-[10px] text-muted">{{ $member->email ?: 'No Email' }}</div>
                        </td>
                        <td class="p-4">
                            <span class="badge green scale-90 uppercase text-[9px] font-black">{{ $member->member_type ?: 'Member' }}</span>
                        </td>
                        <td class="p-4 text-xs font-bold text-gray-500">
                            {{ $member->pivot->join_date ? \Carbon\Carbon::parse($member->pivot->join_date)->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="p-4">
                            <span class="badge {{ $member->pivot->is_active ? 'green' : 'red' }} scale-90 uppercase text-[9px] font-black">
                                {{ $member->pivot->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('members.show', $member->id) }}" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex-center hover:bg-green-600 hover:text-white transition-all" title="View Profile">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form action="{{ route('groups.operations.members.remove', [$group->id, $member->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this member from the group?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex-center hover:bg-red-600 hover:text-white transition-all" title="Remove from Group">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 text-gray-300 flex-center mx-auto mb-4">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No members found in this community.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></div>

<!-- ADD MEMBER MODAL -->
<div id="addMemberModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex-center hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-gray-800">Add New Member</h3>
                <p class="text-xs text-muted font-bold uppercase tracking-widest mt-1">Select a member to join this community</p>
            </div>
            <button onclick="closeAddMemberModal()" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex-center hover:bg-red-50 hover:text-red-500 transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('groups.operations.members.add', $group->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="form-group">
                <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Select Member</label>
                <select name="member_id" class="form-control w-full p-4 rounded-2xl border-gray-100 bg-gray-50/50 font-bold text-sm focus:ring-2 focus:ring-green-500 transition-all select2" required>
                    <option value="">Choose a member...</option>
                    @foreach($allMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->full_name }} ({{ $m->registration_number }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-full py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">
                Add to Community
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function openAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAddMemberModal();
});

document.addEventListener('DOMContentLoaded', function() {
    // Member Type Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($memberTypes->keys()) !!},
            datasets: [{
                data: {!! json_encode($memberTypes->values()) !!},
                backgroundColor: ['#047857', '#10b981', '#f59e0b', '#ef4444', '#34d399'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Gender Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($genders->keys()) !!},
            datasets: [{
                data: {!! json_encode($genders->values()) !!},
                backgroundColor: ['#047857', '#f59e0b', '#94a3b8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
@endsection
