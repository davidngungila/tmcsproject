@extends('layouts.app')

@section('title', 'Communication History - TmcsSmart')
@section('page-title', 'Communication History')
@section('breadcrumb', 'TmcsSmart / Communications / History')

@section('content')
<div class="animate-in space-y-6">
  <!-- COMMUNICATION STATISTICS -->
  <div class="stat-grid">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
      </div>
      <div class="stat-value">{{ $totalCommunications }}</div>
      <div class="stat-label">Total Communications</div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $sentCommunications }}</div>
      <div class="stat-label">Sent Successfully</div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $pendingCommunications }}</div>
      <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $failedCommunications }}</div>
      <div class="stat-label">Failed</div>
    </div>
  </div>

  <!-- COMMUNICATION HISTORY TABLE -->
  <div class="card shadow-sm border-none">
    <div class="card-header border-b bg-light/30">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Communication History</h3>
          <p class="text-xs text-muted font-medium">All sent and scheduled communications</p>
        </div>
        <a href="{{ route('communications.create') }}" class="btn btn-primary btn-sm rounded-xl px-4">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M12 4v16m8-8H4"/></svg>
          New Communication
        </a>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-light/50 border-b">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Title</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Type</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Recipients</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Date</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Status</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($communications as $communication)
              <tr class="hover:bg-light/30 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-bold text-sm">{{ $communication->message_title }}</div>
                  <div class="text-[10px] text-muted line-clamp-1">{{ Str::limit($communication->message_body, 50) }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="badge {{ $communication->type === 'SMS' ? 'blue' : 'green' }} text-[9px] uppercase font-bold">
                    {{ $communication->type }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-muted">
                  {{ $communication->recipients_count ?? $communication->recipients->count() }} recipients
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ $communication->created_at ? $communication->created_at->format('M d, Y - g:i A') : 'Date not set' }}
                </td>
                <td class="px-6 py-4">
                  <span class="badge {{ $communication->status === 'sent' ? 'green' : ($communication->status === 'pending' ? 'blue' : 'red') }}">
                    {{ ucfirst($communication->status) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('communications.show', $communication->id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">
                      View
                    </a>
                    @if($communication->status === 'failed')
                      <form action="{{ route('communications.resend', $communication->id) }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="text-amber-600 hover:text-amber-800 text-xs font-semibold">
                          Resend
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-muted text-sm">
                  <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-light flex-center">
                      <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-gray-300"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="font-semibold">No communication history found</p>
                    <a href="{{ route('communications.create') }}" class="btn btn-primary btn-sm rounded-xl px-4">
                      Send Your First Communication
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($communications->hasPages())
      <div class="p-4 border-t">
        {{ $communications->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
