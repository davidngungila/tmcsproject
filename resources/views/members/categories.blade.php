@extends('layouts.app')

@section('title', 'Member Categories - TmcsSmart')
@section('page-title', 'Member Categories')
@section('breadcrumb', 'TmcsSmart / Members / Categories')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">All Categories</h3>
    </div>
    <div class="card-body">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <div class="stat-card blue">
          <div class="stat-value">{{ $category }}</div>
          <div class="stat-label">Member Category</div>
          <div class="mt-4">
            <button class="btn btn-ghost btn-sm">View Members</button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
