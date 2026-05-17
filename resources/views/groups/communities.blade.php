@extends('layouts.app')

@section('title', 'Small Communities - TmcsSmart')
@section('page-title', 'Small Christian Communities (SCC)')
@section('breadcrumb', 'TmcsSmart / Groups / Communities')

@section('content')
<div class="animate-in space-y-6">
  <!-- PAGE HEADER -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-black text-gray-800 tracking-tight">Small Christian Communities</h2>
      <p class="text-sm text-muted mt-1">Manage scc groups, leadership assignments, and automated membership rules.</p>
    </div>
    <div class="flex flex-wrap gap-3">
      <form action="{{ route('groups.generate-from-programs') }}" method="POST" onsubmit="return confirm('This will create Small Christian Communities for all active academic programmes. Continue?')">
        @csrf
        <button type="submit" class="btn btn-secondary flex items-center gap-2 shadow-sm border-muted/20">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          <span class="font-bold">Generate SCCs</span>
        </button>
      </form>
      <a href="{{ route('groups.create') }}?type=Community" class="btn btn-primary flex items-center gap-2 shadow-lg shadow-primary/20">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        <span class="font-bold">Add Community</span>
      </a>
    </div>
  </div>

  <!-- COMMUNITY STATISTICS -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card p-5 border-none shadow-sm bg-primary text-white relative overflow-hidden group">
      <div class="relative z-10">
        <div class="text-[10px] font-black uppercase tracking-widest opacity-70">Total SCCs</div>
        <div class="text-3xl font-black mt-1">{{ $totalCommunities }}</div>
        <div class="mt-4 flex items-center gap-2 text-[10px] font-bold">
          <span class="px-1.5 py-0.5 rounded bg-white/20 uppercase tracking-tighter">{{ $activeCommunities }} Active</span>
          <span class="opacity-60">across all tracks</span>
        </div>
      </div>
      <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
        <svg width="100" height="100" fill="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/></svg>
      </div>
    </div>

    <div class="card p-5 border-none shadow-sm bg-white border border-muted/10">
      <div class="text-[10px] font-black uppercase tracking-widest text-muted">Total Membership</div>
      <div class="text-3xl font-black mt-1 text-gray-800">{{ number_format($totalCommunityMembers) }}</div>
      <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-green-600 uppercase tracking-widest">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
        <span>Growing Strong</span>
      </div>
    </div>

    <div class="card p-5 border-none shadow-sm bg-white border border-muted/10">
      <div class="text-[10px] font-black uppercase tracking-widest text-muted">Total Collections</div>
      <div class="text-2xl font-black mt-1 text-gray-800">TZS {{ number_format($communityCollections, 0) }}</div>
      <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-amber-600 uppercase tracking-widest">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Community Giving</span>
      </div>
    </div>

    <div class="card p-5 border-none shadow-sm bg-white border border-muted/10">
      <div class="text-[10px] font-black uppercase tracking-widest text-muted">Average Size</div>
      <div class="text-3xl font-black mt-1 text-gray-800">{{ $totalCommunities > 0 ? round($totalCommunityMembers / $totalCommunities, 1) : 0 }}</div>
      <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-blue-600 uppercase tracking-widest">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Members / SCC</span>
      </div>
    </div>
  </div>

  <!-- COMMUNITIES TABLE -->
  <div class="card shadow-sm border-muted/10 overflow-hidden">
    <div class="card-header bg-white border-b border-muted/10 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <h3 class="text-lg font-black text-gray-800 tracking-tight">Active Communities</h3>
          <p class="text-xs text-muted font-bold uppercase tracking-widest mt-0.5">List of all Small Christian Communities</p>
        </div>
      </div>
      <div class="relative max-w-sm w-full">
        <input type="text" id="communitySearch" class="form-control pl-10 text-xs font-bold py-2.5 rounded-xl border-muted/20 focus:ring-primary/20" placeholder="Search communities by name or code...">
        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>
    </div>

    <div class="table-wrap overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-muted/5 border-b border-muted/10">
            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Community Details</th>
            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Leadership</th>
            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-center">Stats</th>
            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-right">Operations & Management</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($groups as $group)
          <tr class="hover:bg-primary/5 transition-all group">
            <td class="px-6 py-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center font-black text-sm border border-green-100 shadow-sm group-hover:scale-110 transition-transform">
                  {{ substr($group->name, 0, 2) }}
                </div>
                <div>
                  <div class="font-black text-base text-gray-800 tracking-tight">{{ $group->name }}</div>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="px-1.5 py-0.5 rounded bg-muted/10 text-muted text-[9px] font-black uppercase tracking-widest">{{ $group->meeting_day ?: 'No Fixed Day' }}</span>
                    @if(isset($group->criteria['program_id']))
                      @php $program = \App\Models\Program::find($group->criteria['program_id']); @endphp
                      @if($program)
                        <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-600 text-[9px] font-black uppercase tracking-widest">
                          Track: {{ $program->code }}
                        </span>
                      @endif
                    @endif
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                  <span class="w-5 h-5 rounded-md bg-gray-50 flex items-center justify-center text-[9px] font-black text-muted border border-muted/10 uppercase">CP</span>
                  <span class="text-xs font-bold text-gray-700">{{ $group->chairperson->full_name ?? 'Not Assigned' }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="w-5 h-5 rounded-md bg-gray-50 flex items-center justify-center text-[9px] font-black text-muted border border-muted/10 uppercase">SC</span>
                  <span class="text-xs font-bold text-gray-700">{{ $group->secretary->full_name ?? 'Not Assigned' }}</span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-center">
              <div class="inline-flex flex-col items-center">
                <span class="text-xl font-black text-gray-800">{{ $group->members_count }}</span>
                <span class="text-[9px] font-black text-muted uppercase tracking-tighter">Members</span>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <div class="flex items-center bg-gray-50 p-1 rounded-xl border border-muted/10">
                  <a href="{{ route('groups.operations.members', $group->id) }}" class="p-2 rounded-lg text-green-600 hover:bg-green-600 hover:text-white transition-all" title="Community Stats">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </a>
                  <a href="{{ route('groups.operations.contributions', $group->id) }}" class="p-2 rounded-lg text-green-600 hover:bg-green-600 hover:text-white transition-all" title="Giving Record">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </a>
                  <a href="{{ route('groups.operations.attendance', $group->id) }}" class="p-2 rounded-lg text-green-600 hover:bg-green-600 hover:text-white transition-all" title="Attendance">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                  </a>
                </div>
                <div class="flex items-center bg-gray-50 p-1 rounded-xl border border-muted/10">
                  <a href="{{ route('groups.show', $group->id) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Advanced View">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                  <a href="{{ route('groups.edit', $group->id) }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-800 hover:text-white transition-all" title="Edit Settings">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </a>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-20 text-center">
              <div class="flex flex-col items-center gap-3 opacity-50">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm font-bold uppercase tracking-widest">No small communities found.</p>
                <p class="text-xs max-w-xs mx-auto">Start by adding a new community or generate them automatically based on academic programmes.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($groups->hasPages())
    <div class="card-footer bg-muted/5 px-6 py-4">
      {{ $groups->links() }}
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('communitySearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      if (text.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
</script>
@endpush
@endsection
