@extends('layouts.app')

@section('title', 'Group Activities - TmcsSmart')
@section('page-title', 'Group Activities & Events')
@section('breadcrumb', 'TmcsSmart / Groups / Activities')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header flex items-center justify-between">
      <h3 class="card-title">Recent Activities</h3>
      <button class="btn btn-primary btn-sm">Log Activity</button>
    </div>
    <div class="card-body py-12 text-center">
      <div class="flex-center mb-4">
        <svg width="48" height="48" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <h3 class="text-lg font-bold">No Recent Activities</h3>
      <p class="text-muted mt-1">Group-specific activities and meeting logs will appear here.</p>
    </div>
  </div>
</div>
@endsection
