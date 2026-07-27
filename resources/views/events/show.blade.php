@extends('layouts.app')

@section('title', 'Event Details - TmcsSmart')
@section('page-title', 'Event Details')
@section('breadcrumb', 'TmcsSmart / Events / Details')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header">
      <div class="card-title">{{ $event->event_name }}</div>
      <div class="card-subtitle">{{ $event->event_date->format('F j, Y') }} at {{ $event->event_time->format('g:i A') }}</div>
    </div>
    <div class="card-body">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-lg font-semibold mb-4">Event Information</h3>
          <div class="space-y-3">
            <div>
              <label class="text-sm text-muted">Venue</label>
              <p class="font-medium">{{ $event->venue }}</p>
            </div>
            <div>
              <label class="text-sm text-muted">Date</label>
              <p class="font-medium">{{ $event->event_date->format('F j, Y') }}</p>
            </div>
            <div>
              <label class="text-sm text-muted">Time</label>
              <p class="font-medium">{{ $event->event_time->format('g:i A') }}</p>
            </div>
            <div>
              <label class="text-sm text-muted">Status</label>
              <p class="font-medium">
                <span class="badge {{ $event->status == 'upcoming' ? 'blue' : ($event->status == 'completed' ? 'green' : 'red') }}">
                  {{ ucfirst($event->status) }}
                </span>
              </p>
            </div>
          </div>
        </div>
        
        <div>
          <h3 class="text-lg font-semibold mb-4">Description</h3>
          <p class="text-gray-600">{{ $event->description ?? 'No description provided' }}</p>
        </div>
      </div>
      
      <div class="flex gap-3 mt-6 pt-6 border-t">
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary">Edit Event</a>
      </div>
    </div>
  </div>
</div>
@endsection
