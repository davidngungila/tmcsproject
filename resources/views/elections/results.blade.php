@extends('layouts.app')

@section('title', 'Election Results - TmcsSmart')
@section('page-title', 'Election Results')
@section('breadcrumb', 'TmcsSmart / Elections / Results')

@section('content')
<div class="animate-in">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Real-time Election Results</h3>
            <p class="card-subtitle">View vote counts and winners for active and past elections</p>
        </div>
        <div class="card-body py-12 text-center text-muted">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-4 opacity-20"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <p>Election results will be displayed here once voting periods conclude.</p>
        </div>
    </div>
</div>
@endsection
