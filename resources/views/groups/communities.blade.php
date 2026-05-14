@extends('layouts.app')

@section('title', 'Small Communities - TmcsSmart')
@section('page-title', 'Small Christian Communities (SCC)')
@section('breadcrumb', 'TmcsSmart / Groups / Communities')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">Communities</h2>
      <p class="text-sm text-muted">Manage small community groups and assignments</p>
    </div>
    <a href="{{ route('groups.create') }}?type=Community" class="btn btn-primary">Add New Community</a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($groups as $group)
    <div class="card">
      <div class="card-body">
        <div class="flex items-center gap-4 mb-4">
          <div class="avatar avatar-lg">{{ substr($group->name, 0, 2) }}</div>
          <div>
            <h3 class="font-bold">{{ $group->name }}</h3>
            <p class="text-xs text-muted">Formed {{ $group->created_at->format('M Y') }}</p>
          </div>
        </div>
        
        <div class="space-y-2 mb-6">
          <div class="flex justify-between text-sm">
            <span class="text-muted">Members:</span>
            <span class="font-bold">{{ $group->members_count ?? $group->members->count() }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-muted">Meeting:</span>
            <span>{{ $group->meeting_day ?? 'TBD' }}</span>
          </div>
        </div>

        <div class="flex gap-2">
          <a href="{{ route('groups.show', $group->id) }}" class="btn btn-ghost btn-sm flex-1">View Details</a>
          <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-ghost btn-sm">Edit</a>
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-full card py-12 text-center">
      <p class="text-muted">No communities found.</p>
    </div>
    @endforelse
  </div>

  <div class="mt-6">
    {{ $groups->links() }}
  </div>
</div>
@endsection
