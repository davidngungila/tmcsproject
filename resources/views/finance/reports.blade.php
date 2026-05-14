@extends('layouts.app')

@section('title', 'Financial Reports - TmcsSmart')
@section('page-title', 'Financial Reports')
@section('breadcrumb', 'TmcsSmart / Finance / Reports')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Income Summary</h3>
      </div>
      <div class="card-body">
        <p class="text-muted">Financial report charts and summaries will be displayed here.</p>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Expense Summary</h3>
      </div>
      <div class="card-body">
        <p class="text-muted">Expense tracking and reporting data.</p>
      </div>
    </div>
  </div>
</div>
@endsection
