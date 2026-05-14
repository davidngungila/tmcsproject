@extends('layouts.app')

@section('title', 'Event Attendance - TmcsSmart')
@section('page-title', 'Event Attendance')
@section('breadcrumb', 'TmcsSmart / Events / Attendance')

@section('content')
<div class="animate-in">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Event Attendance Records</h3>
            <p class="card-subtitle">Track and manage attendance for all church events</p>
        </div>
        <div class="card-body py-12 text-center text-muted">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-4 opacity-20"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <p>Attendance tracking module is currently being finalized.</p>
        </div>
    </div>
</div>
@endsection
