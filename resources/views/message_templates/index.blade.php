@extends('layouts.app')

@section('title', 'Message Templates - TmcsSmart')
@section('page-title', 'Message Templates')
@section('breadcrumb', 'TmcsSmart / Communications / Templates')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Message Templates</h2>
      <p class="text-sm text-muted mt-1">Manage reusable message content</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('communications.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Messages
      </a>
      <a href="{{ route('message-templates.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        New Template
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Template Name</th>
              <th>Type</th>
              <th>Subject</th>
              <th>Preview Content</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($templates as $template)
            <tr>
              <td>
                <div class="font-bold">{{ $template->name }}</div>
                <div class="text-[10px] text-muted uppercase tracking-wider">ID: #TMPL-{{ str_pad($template->id, 4, '0', STR_PAD_LEFT) }}</div>
              </td>
              <td>
                <span class="badge {{ $template->type === 'SMS' ? 'blue' : ($template->type === 'Email' ? 'gold' : 'purple') }}">
                  {{ $template->type }}
                </span>
              </td>
              <td class="text-sm italic text-muted">{{ $template->subject ?? 'N/A' }}</td>
              <td>
                <div class="text-xs max-w-xs truncate" title="{{ $template->content }}">
                  {{ Str::limit($template->content, 60) }}
                </div>
              </td>
              <td>
                <span class="badge {{ $template->is_active ? 'green' : 'red' }}">
                  {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <a href="{{ route('message-templates.edit', $template->id) }}" class="btn btn-secondary btn-sm p-1.5" title="Edit">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form action="{{ route('message-templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary btn-sm p-1.5 text-red-500 hover:bg-red-50" title="Delete">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-12">
                <div class="text-muted mb-4">No templates found</div>
                <a href="{{ route('message-templates.create') }}" class="btn btn-primary btn-sm">Create Your First Template</a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-4">
    {{ $templates->links() }}
  </div>
</div>
@endsection
