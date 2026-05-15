@extends('layouts.app')

@section('title', 'Users - TmcsSmart')
@section('page-title', 'User Management')
@section('breadcrumb', 'TmcsSmart / Administration / Users')

@section('content')
<div class="animate-in">
  <!-- PAGE HEADER -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">User Management</h2>
      <p class="text-sm text-muted mt-1">Manage system users and their roles</p>
    </div>
    @if(auth()->user()->hasPermission('users.create'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
      Add New User
    </a>
    @endif
  </div>

  <!-- USERS TABLE -->
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">System Users</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>
              <div class="flex items-center gap-2">
                <div class="avatar overflow-hidden">
                  @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                  @else
                    {{ substr($user->name, 0, 2) }}
                  @endif
                </div>
                <div style="font-weight:600;font-size:13px;">{{ $user->name }}</div>
              </div>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? 'N/A' }}</td>
            <td>
              @foreach($user->roles as $role)
                <span class="badge bg-blue-100 text-blue-700">{{ $role->display_name }}</span>
              @endforeach
            </td>
            <td>
              <span class="badge {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <div class="flex gap-2">
                @if(auth()->user()->hasPermission('users.edit'))
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                @endif
                @if(auth()->user()->hasPermission('users.delete'))
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-secondary btn-sm text-red-600">Delete</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-10 text-muted">No users found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer border-t">
      {{ $users->links() }}
    </div>
    @endif
  </div>
</div>
@endsection
