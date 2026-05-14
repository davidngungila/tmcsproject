@extends('layouts.app')

@section('title', 'My Communities - TmcsSmart')
@section('page-title', 'My Communities')
@section('breadcrumb', 'TmcsSmart / Member / Communities')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">My Small Christian Communities</div>
      <div class="card-subtitle">Groups you belong to in your local area</div>
    </div>
    <div class="card-body">
      @if($communities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($communities as $community)
            <div class="p-6 bg-light rounded-2xl border border-gray-100 flex items-start gap-5 hover:shadow-md transition-shadow">
              <div class="w-14 h-14 rounded-xl bg-white flex-center shadow-sm text-blue-600">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div>
                <div class="font-bold text-lg mb-1">{{ $community->name }}</div>
                <div class="text-xs text-muted mb-3">Joined: {{ $community->pivot->join_date ? \Carbon\Carbon::parse($community->pivot->join_date)->format('d M, Y') : 'N/A' }}</div>
                <span class="badge blue text-[10px]">Active Community</span>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-16 text-muted">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" class="mx-auto mb-4 opacity-20"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <p class="text-sm">You are not registered in any community yet.</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
