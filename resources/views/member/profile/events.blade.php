@extends('layouts.app')

@section('title', 'Events - TMCS Smart')
@section('page-title', 'Events')
@section('breadcrumb', 'Home / Member / Events')

@section('content')
<div class="animate-in space-y-6">
  <!-- UPCOMING EVENTS SECTION -->
  <div class="card overflow-hidden border-l-4 border-blue-500">
    <div class="card-header border-b bg-blue-50/30">
      <div class="flex items-center justify-between">
        <div>
          <div class="card-title text-sm font-bold text-blue-700">Upcoming Church Events</div>
          <div class="card-subtitle text-[10px]">Don't miss out on these upcoming activities</div>
        </div>
        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex-center">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      @php
        $upcoming = \App\Models\Event::where('event_date', '>=', now())->orderBy('event_date')->get();
      @endphp
      <div class="divide-y divide-gray-50">
        @forelse($upcoming as $event)
          <div class="p-5 flex items-center gap-6 hover:bg-light/30 transition-colors group">
            <div class="w-16 h-16 rounded-2xl bg-white border-2 border-blue-100 text-blue-600 flex-center flex-col shadow-sm group-hover:border-blue-500 transition-colors">
              <span class="text-lg font-black leading-none">{{ $event->event_date->format('d') }}</span>
              <span class="text-[10px] font-bold uppercase tracking-tighter">{{ $event->event_date->format('M Y') }}</span>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <div class="font-black text-base group-hover:text-blue-600 transition-colors">{{ $event->event_name }}</div>
                <span class="badge blue text-[9px] px-2 py-0.5">Upcoming</span>
              </div>
              <div class="flex items-center gap-4 text-xs text-muted">
                <div class="flex items-center gap-1">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $event->event_time->format('h:i A') }}
                </div>
                <div class="flex items-center gap-1">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  {{ $event->venue }}
                </div>
              </div>
            </div>
            <div class="hidden md:block">
              <button class="btn btn-primary btn-sm rounded-xl px-6">I'm Attending</button>
            </div>
          </div>
        @empty
          <div class="p-12 text-center text-muted text-sm italic">No upcoming events at the moment.</div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- MY ATTENDANCE HISTORY -->
  <div class="card overflow-hidden">
    <div class="card-header border-b bg-light/30">
      <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">My Participation History</div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-light/50 border-b">
          <tr>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Event Name</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Date</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Venue</th>
            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-right">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($attendance as $item)
            <tr class="hover:bg-light/30 transition-colors">
              <td class="px-6 py-4">
                <div class="font-bold text-sm">{{ $item->event->event_name }}</div>
                <div class="text-[10px] text-muted">{{ Str::limit($item->event->description, 50) }}</div>
              </td>
              <td class="px-6 py-4 text-sm">{{ $item->event->event_date->format('d M, Y') }}</td>
              <td class="px-6 py-4 text-sm text-muted">{{ $item->event->venue }}</td>
              <td class="px-6 py-4 text-right">
                <span class="badge {{ $item->status === 'attended' ? 'green' : 'amber' }}">
                  {{ ucfirst($item->status) }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-12 text-center text-muted text-sm">No event attendance records found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($attendance->hasPages())
      <div class="p-4 border-t">
        {{ $attendance->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
