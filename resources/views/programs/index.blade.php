@extends('layouts.app')

@section('title', 'Academic Programmes - TmcsSmart')
@section('page-title', 'Academic Programmes')
@section('breadcrumb', 'TmcsSmart / Members / Programmes')

@section('content')
<div class="animate-in space-y-6">
  <!-- PAGE HEADER -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">Academic Programmes</h2>
      <p class="text-sm text-muted mt-1">Manage undergraduate, postgraduate, and other academic tracks.</p>
    </div>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('programs.create') }}" class="btn btn-primary flex items-center gap-2 px-6 shadow-lg shadow-primary/20">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        <span>Add Programme</span>
      </a>
    </div>
  </div>

  <!-- PROGRAMMES TABLE -->
  <div class="card shadow-sm border-muted/10 overflow-hidden">
    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5">
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Code</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Programme Name</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Level</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Duration</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Session</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Status</th>
            <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-muted">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($programs as $program)
          <tr class="hover:bg-primary/5 transition-colors">
            <td class="px-6 py-4">
              <div class="mono text-xs font-bold text-primary">{{ $program->code }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm font-bold text-gray-800">{{ $program->name }}</div>
              <div class="text-[10px] text-muted mt-0.5">{{ $program->delivery_mode }}</div>
            </td>
            <td class="px-6 py-4">
              <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                {{ $program->level }}
              </span>
            </td>
            <td class="px-6 py-4 text-xs font-medium text-gray-600">
              {{ $program->duration }}
            </td>
            <td class="px-6 py-4 text-xs text-muted">
              {{ $program->session }}
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $program->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $program->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $program->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ route('programs.edit', $program->id) }}" class="p-2 rounded-lg text-muted hover:text-blue-600 hover:bg-blue-500/10 transition-all" title="Edit Programme">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('programs.destroy', $program->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this programme?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-2 rounded-lg text-muted hover:text-red-600 hover:bg-red-500/10 transition-all" title="Delete Programme">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2 opacity-50">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                <p class="text-sm font-medium">No academic programmes found.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer bg-muted/5 px-6 py-4">
      {{ $programs->links() }}
    </div>
  </div>
</div>
@endsection
