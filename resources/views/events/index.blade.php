@extends('layouts.app')

@section('title', 'Events - TmcsSmart')
@section('page-title', 'Event Management')
@section('breadcrumb', 'TmcsSmart / Events')

@section('content')
<div class="animate-in">
  <!-- EVENT STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $totalEvents }}</div>
      <div class="stat-label">Total Events</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        5 new this month
      </div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalAttendees }}</div>
      <div class="stat-label">Total Attendees</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        12% from last month
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $upcomingEvents }}</div>
      <div class="stat-label">Upcoming Events</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        3 this week
      </div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $pastEvents }}</div>
      <div class="stat-label">Past Events</div>
      <div class="stat-change down">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 7l-9 9m0 0V4m0 12h12"/></svg>
        Completed
      </div>
    </div>
  </div>

  <!-- PAGE ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Event Management</h2>
      <p class="text-sm text-muted mt-1">Create and manage church events</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('events.calendar'))
      <button class="btn btn-secondary" onclick="showCalendarView()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Calendar View
      </button>
      @endif
      @if(auth()->user()->hasPermission('events.export'))
      <button class="btn btn-secondary" onclick="exportEvents()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </button>
      @endif
      @if(auth()->user()->hasPermission('events.create'))
      <a href="{{ route('events.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Create Event
      </a>
      @endif
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="flex gap-4 items-center flex-wrap">
        <div class="flex-1 min-w-64">
          <input type="text" class="form-control" placeholder="Search events..." id="searchInput">
        </div>
        <select class="form-control w-48" id="statusFilter">
          <option value="">All Status</option>
          <option value="upcoming">Upcoming</option>
          <option value="ongoing">Ongoing</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <input type="month" class="form-control w-48" id="monthFilter">
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- EVENTS GRID -->
  <div class="grid-2 mb-6">
    @forelse($events as $event)
    <div class="card hover:shadow-lg transition-shadow">
      @if($event->photo)
      <div class="h-48 bg-cover bg-center rounded-t-lg" style="background-image: url('{{ $event->photo }}');"></div>
      @else
      <div class="h-48 bg-gradient-to-br from-green-500 to-green-300 rounded-t-lg flex items-center justify-center">
        <svg width="48" height="48" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
      @endif
      
      <div class="card-body">
        <div class="flex items-center justify-between mb-2">
          <span class="badge {{ getEventStatusColor($event->status) }}">
            {{ ucfirst($event->status) }}
          </span>
          @if($event->max_capacity)
          <span class="text-sm text-muted">{{ $event->attendance->count() }}/{{ $event->max_capacity }}</span>
          @endif
        </div>
        
        <h3 class="font-bold text-lg mb-2">{{ $event->event_name }}</h3>
        <p class="text-sm text-muted mb-4">{{ Str::limit($event->description, 100) }}</p>
        
        <div class="space-y-2 mb-4">
          <div class="flex justify-between text-sm">
            <span class="text-muted">Date:</span>
            <span>{{ $event->event_date->format('M d, Y') }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-muted">Time:</span>
            <span>{{ $event->event_time->format('h:i A') }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-muted">Venue:</span>
            <span>{{ $event->venue }}</span>
          </div>
          @if($event->max_capacity)
          <div class="flex justify-between text-sm">
            <span class="text-muted">Capacity:</span>
            <span>{{ $event->max_capacity }} people</span>
          </div>
          @endif
        </div>
        
        <div class="flex gap-2">
          <button class="btn btn-ghost btn-sm flex-1" onclick="viewEvent({{ $event->id }})">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View
          </button>
          @if(auth()->user()->hasPermission('events.edit'))
          <a href="{{ route('events.edit', $event->id) }}" class="btn btn-ghost btn-sm flex-1">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </a>
          @endif
          @if($event->status === 'upcoming' && auth()->user()->hasPermission('events.checkin'))
          <button class="btn btn-primary btn-sm flex-1" onclick="checkInAttendees({{ $event->id }})">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Check-in
          </button>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-2 text-center py-12 text-muted">
      <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      <p>No events found</p>
      @if(auth()->user()->hasPermission('events.create'))
      <a href="{{ route('events.create') }}" class="btn btn-primary mt-4">Create First Event</a>
      @endif
    </div>
    @endforelse
  </div>

  <!-- PAGINATION -->
  {{ $events->links() }}
</div>

<!-- VIEW EVENT MODAL -->
<div class="modal-overlay" id="viewEventModal">
  <div class="modal" style="width: 800px;">
    <div class="modal-header">
      <div><div class="card-title">Event Details</div><div class="card-subtitle">Complete event information</div></div>
      <div class="modal-close" onclick="closeModal('viewEventModal')">✕</div>
    </div>
    <div class="modal-body" id="eventDetails">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewEventModal')">Close</button>
    </div>
  </div>
</div>

<!-- CHECK-IN MODAL -->
<div class="modal-overlay" id="checkInModal">
  <div class="modal" style="width: 600px;">
    <div class="modal-header">
      <div><div class="card-title">Event Check-in</div><div class="card-subtitle">Register attendee attendance</div></div>
      <div class="modal-close" onclick="closeModal('checkInModal')">✕</div>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Search Member</label>
        <input type="text" class="form-control" placeholder="Enter member name or registration number" id="memberSearch">
      </div>
      
      <div id="searchResults" class="mb-4">
        <!-- Search results will appear here -->
      </div>
      
      <div class="form-group">
        <label class="form-label">Recent Check-ins</label>
        <div id="recentCheckins" class="space-y-2">
          <!-- Recent check-ins will appear here -->
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('checkInModal')">Close</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getEventStatusColor(status) {
  const colors = {
    'upcoming' => 'blue',
    'ongoing' => 'green',
    'completed' => 'amber',
    'cancelled' => 'red'
  };
  return colors[status] ?? 'blue';
}

function viewEvent(eventId) {
  fetch(`/events/${eventId}/show`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('eventDetails').innerHTML = html;
      document.getElementById('viewEventModal').classList.add('open');
    });
}

function checkInAttendees(eventId) {
  document.getElementById('checkInModal').classList.add('open');
  
  // Load recent check-ins for this event
  fetch(`/events/${eventId}/checkins`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayRecentCheckins(data.checkins);
      }
    });
}

function displayRecentCheckins(checkins) {
  const container = document.getElementById('recentCheckins');
  if (checkins.length === 0) {
    container.innerHTML = '<p class="text-muted text-sm">No check-ins yet</p>';
    return;
  }
  
  container.innerHTML = checkins.map(checkin => `
    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
      <div class="flex items-center gap-2">
        <div class="avatar text-xs">${checkin.member_name.substring(0, 2)}</div>
        <div>
          <div class="text-sm font-medium">${checkin.member_name}</div>
          <div class="text-xs text-muted">${checkin.checked_in_at}</div>
        </div>
      </div>
      <span class="badge green">Checked In</span>
    </div>
  `).join('');
}

// Member search for check-in
let searchTimeout;
document.getElementById('memberSearch').addEventListener('input', function(e) {
  clearTimeout(searchTimeout);
  const query = e.target.value;
  
  if (query.length < 2) {
    document.getElementById('searchResults').innerHTML = '';
    return;
  }
  
  searchTimeout = setTimeout(() => {
    fetch(`/members/search?q=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        displaySearchResults(data.members);
      });
  }, 500);
});

function displaySearchResults(members) {
  const container = document.getElementById('searchResults');
  if (members.length === 0) {
    container.innerHTML = '<p class="text-muted text-sm">No members found</p>';
    return;
  }
  
  container.innerHTML = members.map(member => `
    <div class="flex items-center justify-between p-3 border rounded mb-2">
      <div class="flex items-center gap-2">
        <div class="avatar">${member.full_name.substring(0, 2)}</div>
        <div>
          <div class="text-sm font-medium">${member.full_name}</div>
          <div class="text-xs text-muted">${member.registration_number}</div>
        </div>
      </div>
      <button class="btn btn-primary btn-sm" onclick="checkInMember(${member.id})">
        Check In
      </button>
    </div>
  `).join('');
}

function checkInMember(memberId) {
  // Get current event ID from the modal or store it globally
  const eventId = document.getElementById('checkInModal').dataset.eventId;
  
  fetch(`/events/${eventId}/checkin`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ member_id: memberId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast('Member checked in successfully', 'success');
      document.getElementById('memberSearch').value = '';
      document.getElementById('searchResults').innerHTML = '';
      // Refresh recent check-ins
      checkInAttendees(eventId);
    } else {
      showToast(data.message || 'Error checking in member', 'error');
    }
  });
}

function showCalendarView() {
  // In a real implementation, this would show a calendar view
  showToast('Calendar view coming soon', 'info');
}

function exportEvents() {
  window.open('/events/export', '_blank');
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('statusFilter').value = '';
  document.getElementById('monthFilter').value = '';
  location.href = '{{ route('events.index') }}';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const search = e.target.value;
  const url = new URL(window.location);
  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }
  window.location = url.toString();
});

// Filter functionality
['statusFilter', 'monthFilter'].forEach(id => {
  document.getElementById(id).addEventListener('change', function(e) {
    const value = e.target.value;
    const url = new URL(window.location);
    const param = id.replace('Filter', '');
    if (value) {
      url.searchParams.set(param, value);
    } else {
      url.searchParams.delete(param);
    }
    window.location = url.toString();
  });
});
</script>
@endpush

<?php
// Helper functions for the view
function getEventStatusColor($status) {
    $colors = [
        'upcoming' => 'blue',
        'ongoing' => 'green',
        'completed' => 'amber',
        'cancelled' => 'red'
    ];
    return $colors[$status] ?? 'blue';
}
?>
