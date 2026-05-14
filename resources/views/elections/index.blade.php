@extends('layouts.app')

@section('title', 'All Elections - TmcsSmart')
@section('page-title', 'Election Management')
@section('breadcrumb', 'TmcsSmart / Elections / All')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($elections as $election)
          <tr>
            <td>{{ $election->title }}</td>
            <td>{{ $election->start_date->format('M d, Y') }}</td>
            <td>{{ $election->end_date->format('M d, Y') }}</td>
            <td><span class="badge {{ $election->status == 'Scheduled' ? 'blue' : 'green' }}">{{ $election->status }}</span></td>
            <td>
              <a href="{{ route('elections.vote', $election) }}" class="btn btn-ghost btn-sm">Vote</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-8 text-muted">No elections found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
