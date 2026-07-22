@extends('layouts.app')

@section('title', 'Announcements - TmcsSmart')
@section('page-title', 'Announcements Management')
@section('breadcrumb', 'TmcsSmart / Announcements')

@section('content')
<div class="animate-in">
  <div class="card mb-4">
    <div class="card-header">
      <div class="card-title">All Announcements</div>
      <div class="card-subtitle">Manage church announcements and notices</div>
    </div>
    <div class="card-body">
      <div class="flex gap-3 mb-4">
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
          New Announcement
        </a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b">
              <th class="text-left p-3 font-semibold text-sm">Title</th>
              <th class="text-left p-3 font-semibold text-sm">Type</th>
              <th class="text-left p-3 font-semibold text-sm">Target Audience</th>
              <th class="text-left p-3 font-semibold text-sm">Status</th>
              <th class="text-left p-3 font-semibold text-sm">Expiry Date</th>
              <th class="text-left p-3 font-semibold text-sm">Created At</th>
              <th class="text-left p-3 font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($announcements as $announcement)
            <tr class="border-b hover:bg-gray-50">
              <td class="p-3">
                <div class="font-medium">{{ $announcement->title }}</div>
                <div class="text-xs text-muted truncate max-w-xs">{{ Str::limit($announcement->content, 80) }}</div>
              </td>
              <td class="p-3">
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">{{ $announcement->type }}</span>
              </td>
              <td class="p-3 text-sm">{{ $announcement->target_audience }}</td>
              <td class="p-3">
                @if($announcement->is_active)
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                @else
                  <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Inactive</span>
                @endif
              </td>
              <td class="p-3 text-sm">{{ $announcement->expiry_date ? $announcement->expiry_date->format('M d, Y') : '-' }}</td>
              <td class="p-3 text-sm">{{ $announcement->created_at->format('M d, Y') }}</td>
              <td class="p-3">
                <div class="flex gap-2">
                  <a href="{{ route('announcements.show', $announcement->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                  <a href="{{ route('announcements.edit', $announcement->id) }}" class="text-green-600 hover:text-green-800 text-sm">Edit</a>
                  <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-muted">
                <div class="mb-2">
                  <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto text-gray-400"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <p>No announcements found. Create your first announcement.</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($announcements->hasPages())
      <div class="flex justify-center mt-4">
        {{ $announcements->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
