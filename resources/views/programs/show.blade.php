@extends('layouts.app')

@section('title', 'Programme Details - TmcsSmart')
@section('page-title', 'Academic Programme Details')
@section('breadcrumb', 'TmcsSmart / Members / Programmes / ' . $program->code)

@section('content')
<div class="animate-in space-y-6">
  <!-- HEADER & ACTIONS -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
      <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-sm border border-primary/20">
        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $program->name }}</h2>
          <span class="px-2 py-0.5 rounded-md bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest">{{ $program->code }}</span>
        </div>
        <p class="text-sm text-muted mt-1">Managed academic track for members enrolled in {{ $program->level }} studies.</p>
      </div>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-secondary flex items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Programme
      </a>
      <a href="{{ route('programs.index') }}" class="btn btn-ghost">Back to List</a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- LEFT: STATS & INFO -->
    <div class="lg:col-span-1 space-y-6">
      <!-- QUICK STATS -->
      <div class="grid grid-cols-2 gap-4">
        <div class="card p-5 border-none shadow-sm bg-primary text-white">
          <div class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Enrolled</div>
          <div class="text-3xl font-black mt-1">{{ number_format($totalMembers) }}</div>
          <div class="text-[10px] font-bold mt-2 flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-white/40"></span>
            Lifetime members
          </div>
        </div>
        <div class="card p-5 border-none shadow-sm bg-green-600 text-white">
          <div class="text-[10px] font-black uppercase tracking-widest opacity-70">Active Now</div>
          <div class="text-3xl font-black mt-1">{{ number_format($activeMembers) }}</div>
          <div class="text-[10px] font-bold mt-2 flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-green-300"></span>
            Current students
          </div>
        </div>
      </div>

      <!-- PROGRAMME DETAILS CARD -->
      <div class="card shadow-sm border-muted/10 overflow-hidden">
        <div class="card-header border-b border-muted/10 bg-muted/5 p-4">
          <h3 class="text-xs font-black uppercase tracking-widest text-muted">Programme Specifications</h3>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-muted/10">
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs font-bold text-muted uppercase">Academic Level</span>
              <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase tracking-wider">{{ $program->level }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs font-bold text-muted uppercase">Duration</span>
              <span class="text-sm font-bold text-gray-800">{{ $program->duration }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs font-bold text-muted uppercase">Delivery Mode</span>
              <span class="text-sm font-bold text-gray-800">{{ $program->delivery_mode }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs font-bold text-muted uppercase">Session/Intake</span>
              <span class="text-sm font-bold text-gray-800">{{ $program->session }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs font-bold text-muted uppercase">System Status</span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $program->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                {{ $program->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- RECENTLY JOINED -->
      <div class="card shadow-sm border-muted/10 overflow-hidden">
        <div class="card-header border-b border-muted/10 bg-muted/5 p-4">
          <h3 class="text-xs font-black uppercase tracking-widest text-muted">Recently Enrolled</h3>
        </div>
        <div class="card-body p-4 space-y-4">
          @forelse($recentMembers as $member)
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-[10px] font-bold">
              {{ substr($member->full_name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs font-bold text-gray-800 truncate">{{ $member->full_name }}</div>
              <div class="text-[10px] text-muted">{{ $member->registration_number }}</div>
            </div>
            <a href="{{ route('members.show', $member->id) }}" class="text-primary hover:underline text-[10px] font-bold">View</a>
          </div>
          @empty
          <p class="text-xs text-muted text-center py-2">No members enrolled yet.</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- RIGHT: ENROLLED MEMBERS TABLE -->
    <div class="lg:col-span-2 space-y-6">
      <div class="card shadow-sm border-muted/10 overflow-hidden">
        <div class="card-header border-b border-muted/10 bg-white p-6 flex items-center justify-between">
          <div>
            <h3 class="text-lg font-black text-gray-800 tracking-tight">Enrolled Members</h3>
            <p class="text-xs text-muted mt-0.5">List of all members currently under this programme.</p>
          </div>
          <div class="flex gap-2">
            <button class="btn btn-secondary btn-sm flex items-center gap-2">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Export
            </button>
          </div>
        </div>
        <div class="table-wrap">
          <table class="w-full">
            <thead>
              <tr class="bg-muted/5 border-b border-muted/10">
                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Member</th>
                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Registration</th>
                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Contact</th>
                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Status</th>
                <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-muted">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
              @forelse($program->members as $member)
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold text-sm border border-primary/10 shadow-sm">
                      @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover rounded-xl">
                      @else
                        {{ substr($member->full_name, 0, 2) }}
                      @endif
                    </div>
                    <div>
                      <div class="text-sm font-bold text-gray-800">{{ $member->full_name }}</div>
                      <div class="text-[10px] text-muted">{{ $member->category ? $member->category->name : 'N/A' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="mono text-xs font-bold text-primary">{{ $member->registration_number }}</div>
                  <div class="text-[10px] text-muted mt-0.5">{{ $member->registration_date->format('M d, Y') }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-xs font-medium text-gray-700">{{ $member->phone ?? 'No Phone' }}</div>
                  <div class="text-[10px] text-muted mt-0.5">{{ $member->email ?? 'No Email' }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $member->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[9px] font-black uppercase tracking-wider">
                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('members.show', $member->id) }}" class="p-2 rounded-lg text-muted hover:text-primary hover:bg-primary/10 transition-all inline-block">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-muted text-sm">No members enrolled in this programme.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($program->members->count() > 0)
        <div class="card-footer bg-muted/5 px-6 py-4">
          <!-- Note: Pagination for the relation is handled by the controller's load mapping if using paginate on relation -->
          {{-- $program->members->links() --}}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
