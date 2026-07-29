@extends('layouts.app')

@section('title', 'Event Attendance - TmcsSmart')
@section('page-title', 'Event Attendance')
@section('breadcrumb', 'TmcsSmart / Events / Attendance')

@section('content')
<div class="animate-in">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <div class="card-body">
                <div class="text-sm text-muted">Total Records</div>
                <div class="text-2xl font-bold">{{ $totalAttendances }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="text-sm text-muted">Attended</div>
                <div class="text-2xl font-bold text-green-600">{{ $attendedCount }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="text-sm text-muted">Absent</div>
                <div class="text-2xl font-bold text-red-600">{{ $absentCount }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="text-sm text-muted">Pending</div>
                <div class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Event Attendance Records</div>
            <div class="card-subtitle">Track and manage attendance for all church events</div>
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Back to Events</a>
        </div>
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Member</th>
                            <th>Status</th>
                            <th>Checked In At</th>
                            <th>Checked In By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $attendance->event->event_name }}</div>
                                <div class="text-xs text-muted">{{ $attendance->event->event_date->format('M j, Y') }}</div>
                            </td>
                            <td>{{ $attendance->member->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $attendance->status == 'attended' ? 'green' : ($attendance->status == 'absent' ? 'red' : 'amber') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>
                                @if($attendance->checked_in_at)
                                    {{ $attendance->checked_in_at->format('M j, Y g:i A') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->checkedInBy)
                                    {{ $attendance->checkedInBy->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <form action="{{ route('events.attendance.update', $attendance->id) }}" method="POST" class="inline">
                                        @method('PUT')
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="form-control text-xs py-1 px-2">
                                            <option value="pending" {{ $attendance->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="attended" {{ $attendance->status == 'attended' ? 'selected' : '' }}>Attended</option>
                                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-muted">
                                No attendance records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
