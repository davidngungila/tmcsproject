@extends('layouts.app')

@section('title', 'Events - TmcsSmart')
@section('page-title', 'My Events & Attendance')
@section('breadcrumb', 'TmcsSmart / Member / Events')

@section('content')
<div class="animate-in">
  <div class="card overflow-hidden">
    <div class="card-header border-b">
      <div class="card-title">My Event Attendance</div>
      <div class="card-subtitle">List of church events you have participated in</div>
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
