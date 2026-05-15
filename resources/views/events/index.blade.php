@extends('layouts.app')

@section('title', 'Events - TmcsSmart')
@section('page-title', 'Event Management')
@section('breadcrumb', 'TmcsSmart / Events')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<style>
  .fc-event { cursor: pointer; border: none; padding: 2px 4px; border-radius: 4px; }
  .fc-toolbar-title { font-family: 'Sora', sans-serif !important; font-weight: 800 !important; font-size: 1.25rem !important; }
  .fc-button-primary { background-color: var(--green-600) !important; border-color: var(--green-600) !important; font-weight: 600 !important; text-transform: uppercase !important; font-size: 0.75rem !important; letter-spacing: 0.05em !important; }
  .fc-button-primary:hover { background-color: var(--green-700) !important; border-color: var(--green-700) !important; }
  .fc-daygrid-day-number { font-weight: 700; color: var(--text-muted); font-size: 0.8rem; }
  .fc-col-header-cell-cushion { font-weight: 800; text-transform: uppercase; font-size: 0.7rem; color: var(--text-muted); letter-spacing: 0.1em; }
</style>
@endpush

@section('content')
<div class="animate-in space-y-6">
  <!-- TOP STATS -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="card p-6 border-none shadow-sm bg-gradient-to-br from-green-600 to-green-700 text-white">
      <div class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Total Events</div>
      <div class="text-2xl font-black">{{ $totalEvents }}</div>
      <div class="mt-2 text-[10px] font-bold opacity-70">Church lifecycle</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Upcoming</div>
      <div class="text-2xl font-black text-blue-600">{{ $upcomingEvents }}</div>
      <div class="mt-2 text-[10px] font-bold text-muted">Planned sessions</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Total Attendees</div>
      <div class="text-2xl font-black text-amber-600">{{ $totalAttendees }}</div>
      <div class="mt-2 text-[10px] font-bold text-muted">Overall engagement</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Past Events</div>
      <div class="text-2xl font-black text-gray-800">{{ $pastEvents }}</div>
      <div class="mt-2 text-[10px] font-bold text-muted">History archive</div>
    </div>
  </div>

  <!-- VIEW TOGGLE & ACTIONS -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex bg-gray-100 p-1 rounded-xl">
      <button onclick="switchView('calendar')" id="calendarToggle" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all bg-white shadow-sm text-green-600">Calendar View</button>
      <button onclick="switchView('list')" id="listToggle" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all text-gray-400">List View</button>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('events.create') }}" class="btn btn-primary bg-green-600 shadow-lg shadow-green-100 border-none px-6 py-3 rounded-xl font-black uppercase tracking-widest text-[10px]">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" class="mr-2"><path d="M12 4v16m8-8H4"/></svg>
        Schedule New Event
      </a>
    </div>
  </div>

  <!-- CALENDAR VIEW (DEFAULT) -->
  <div id="calendarView" class="card border-none shadow-sm p-6 animate-in fade-in slide-in-from-bottom-4">
    <div id="calendar"></div>
  </div>

  <!-- LIST VIEW (HIDDEN BY DEFAULT) -->
  <div id="listView" class="hidden animate-in fade-in slide-in-from-bottom-4">
    <div class="card border-none shadow-sm overflow-hidden">
      <div class="table-wrap">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50/50">
              <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Event Details</th>
              <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Time</th>
              <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Venue</th>
              <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
              <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50/50 transition-all">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex-center font-black text-xs">
                    {{ substr($event->event_name, 0, 2) }}
                  </div>
                  <div>
                    <div class="text-sm font-black text-gray-800">{{ $event->event_name }}</div>
                    <div class="text-[10px] text-muted font-bold">{{ Str::limit($event->description, 40) }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-xs font-black text-gray-700">{{ $event->event_date->format('M d, Y') }}</div>
                <div class="text-[10px] text-muted font-bold uppercase">{{ $event->event_time->format('h:i A') }}</div>
              </td>
              <td class="px-6 py-4 text-xs font-bold text-gray-600">
                <div class="flex items-center gap-1.5">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  {{ $event->venue }}
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="badge {{ getEventStatusColor($event->status) }} uppercase text-[9px] font-black px-3 py-1">{{ $event->status }}</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-1">
                  <a href="{{ route('events.show', $event->id) }}" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex-center hover:bg-green-600 hover:text-white transition-all">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                  <a href="{{ route('events.edit', $event->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex-center hover:bg-amber-600 hover:text-white transition-all">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="p-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-50 text-gray-300 flex-center mx-auto mb-4">
                  <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No events scheduled yet.</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($events->hasPages())
      <div class="p-6 border-t border-gray-50">
        {{ $events->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-12 text-center text-muted">
              <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <p>No events found</p>
              <a href="{{ route('events.create') }}" class="btn btn-primary mt-4 btn-sm">Create First Event</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- PAGINATION -->
  <div class="mb-8">
    {{ $events->links() }}
  </div>
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

@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($calendarEvents),
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            // Optional: add tooltips or extra info
        }
    });
    calendar.render();
    window.calendar = calendar; // Global ref for view switching
});

function switchView(view) {
    const calendarView = document.getElementById('calendarView');
    const listView = document.getElementById('listView');
    const calendarToggle = document.getElementById('calendarToggle');
    const listToggle = document.getElementById('listToggle');

    if (view === 'calendar') {
        calendarView.classList.remove('hidden');
        listView.classList.add('hidden');
        calendarToggle.classList.add('bg-white', 'shadow-sm', 'text-green-600');
        calendarToggle.classList.remove('text-gray-400');
        listToggle.classList.remove('bg-white', 'shadow-sm', 'text-green-600');
        listToggle.classList.add('text-gray-400');
        if (window.calendar) window.calendar.render();
    } else {
        calendarView.classList.add('hidden');
        listView.classList.remove('hidden');
        listToggle.classList.add('bg-white', 'shadow-sm', 'text-green-600');
        listToggle.classList.remove('text-gray-400');
        calendarToggle.classList.remove('bg-white', 'shadow-sm', 'text-green-600');
        calendarToggle.classList.add('text-gray-400');
    }
}
</script>
@endpush
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
