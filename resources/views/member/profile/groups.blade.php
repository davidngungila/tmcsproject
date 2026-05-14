@extends('layouts.app')

@section('title', 'My Groups - TmcsSmart')
@section('page-title', 'My Groups')
@section('breadcrumb', 'TmcsSmart / Member / Groups')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">My Fellowships & Groups</div>
      <div class="card-subtitle">Church associations and ministries you are involved in</div>
    </div>
    <div class="card-body">
      @if($groups->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($groups as $group)
            <div class="p-6 bg-light rounded-2xl border border-gray-100 flex items-start gap-5 hover:shadow-md transition-shadow">
              <div class="w-14 h-14 rounded-xl bg-white flex-center shadow-sm text-green-600">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              </div>
              <div>
                <div class="font-bold text-lg mb-1">{{ $group->name }}</div>
                <div class="text-xs text-muted mb-3">{{ $group->type }} • Joined: {{ $group->pivot->join_date ? \Carbon\Carbon::parse($group->pivot->join_date)->format('d M, Y') : 'N/A' }}</div>
                <span class="badge green text-[10px]">Active Member</span>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-16 text-muted">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" class="mx-auto mb-4 opacity-20"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <p class="text-sm">You are not a member of any fellowship or group yet.</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
