@extends('layouts.app')

@section('title', 'Contribution Types - TmcsSmart')
@section('page-title', 'Contribution Types')
@section('breadcrumb', 'TmcsSmart / Finance / Contribution Types')

@section('content')
<div class="animate-in space-y-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">Contribution Types</h2>
      <p class="text-sm text-muted mt-1">Manage different types of contributions (Tithes, Offerings, etc.)</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('finance.types.create') }}" class="btn btn-primary flex items-center gap-2 px-6 shadow-lg shadow-primary/20">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        <span>Add New Type</span>
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-muted/10 overflow-hidden">
    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
            <th class="px-6 py-4 text-left">Name</th>
            <th class="px-6 py-4 text-left">Description</th>
            <th class="px-6 py-4 text-left">Status</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($types as $type)
          <tr class="hover:bg-primary/5 transition-colors">
            <td class="px-6 py-4">
              <a href="{{ route('finance.types.show', $type->id) }}" class="group">
                <div class="text-sm font-bold text-primary group-hover:text-{{ $type->color }}-600 transition-colors">{{ $type->name }}</div>
                <div class="text-[10px] font-mono text-muted">{{ $type->code }}</div>
              </a>
            </td>
            <td class="px-6 py-4">
              <div class="text-xs text-muted line-clamp-1">{{ $type->description ?: 'No description' }}</div>
              <div class="flex gap-1 mt-1">
                <span class="text-[9px] font-black uppercase tracking-tighter px-1.5 py-0.5 rounded bg-muted/10 text-muted">{{ $type->frequency }}</span>
                @if($type->is_mandatory)
                <span class="text-[9px] font-black uppercase tracking-tighter px-1.5 py-0.5 rounded bg-red-500/10 text-red-600">Mandatory</span>
                @endif
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $type->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $type->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $type->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ route('finance.types.show', $type->id) }}" class="p-2 rounded-lg text-muted hover:text-green-600 hover:bg-green-500/10 transition-all" title="View Details">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('finance.types.edit', $type->id) }}" class="p-2 rounded-lg text-muted hover:text-blue-600 hover:bg-blue-500/10 transition-all" title="Edit">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('finance.types.destroy', $type->id) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-2 rounded-lg text-muted hover:text-red-600 hover:bg-red-500/10 transition-all" onclick="return confirm('Delete this type?')" title="Delete">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2 opacity-50">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">No contribution types defined yet.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
