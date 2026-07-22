@extends('layouts.app')

@section('title', 'View Template - TmcsSmart')
@section('page-title', 'View Template')
@section('breadcrumb', 'TmcsSmart / Communications / Templates / View')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-4">
    <a href="{{ route('message-templates.index') }}" class="btn btn-secondary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Templates
    </a>
    <div class="flex gap-2">
      <a href="{{ route('message-templates.edit', $messageTemplate->id) }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Template
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Template Details -->
    <div class="lg:col-span-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ $messageTemplate->name }}</div>
          <div class="card-subtitle">
            <span class="badge {{ $messageTemplate->type === 'SMS' ? 'blue' : ($messageTemplate->type === 'Email' ? 'gold' : 'purple') }}">
              {{ $messageTemplate->type }}
            </span>
            <span class="badge {{ $messageTemplate->is_active ? 'green' : 'red' }} ml-2">
              {{ $messageTemplate->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <div class="card-body">
          @if($messageTemplate->subject)
          <div class="mb-4">
            <label class="form-label font-bold">Subject:</label>
            <p class="text-sm">{{ $messageTemplate->subject }}</p>
          </div>
          @endif
          
          <div class="mb-4">
            <label class="form-label font-bold">Content:</label>
            <div class="border rounded-lg p-4 bg-light">
              {!! $messageTemplate->content !!}
            </div>
          </div>
          
          <div class="text-xs text-muted">
            Created: {{ $messageTemplate->created_at->format('M d, Y H:i') }}
            @if($messageTemplate->updated_at != $messageTemplate->created_at)
            | Last updated: {{ $messageTemplate->updated_at->format('M d, Y H:i') }}
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Preview & Placeholders -->
    <div class="lg:col-span-1">
      <div class="card mb-6">
        <div class="card-header">
          <div class="card-title">Quick Test</div>
          <div class="card-subtitle">Send a test message</div>
        </div>
        <div class="card-body">
          <form action="{{ route('message-templates.test') }}" method="POST" id="testForm">
            @csrf
            <input type="hidden" name="type" value="{{ $messageTemplate->type }}">
            <input type="hidden" name="subject" value="{{ $messageTemplate->subject }}">
            <input type="hidden" name="content" value="{{ $messageTemplate->content }}">

            <div class="form-group">
              <label class="form-label">Recipient (Phone or Email) *</label>
              <input type="text" name="test_recipient" class="form-control" placeholder="07xxxxxxxx or email@example.com" required>
              <p class="text-[10px] text-muted mt-1">Use your own contact to verify delivery.</p>
            </div>

            <button type="submit" class="btn btn-dark w-full">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              Run Live Test
            </button>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Placeholders</div>
        </div>
        <div class="card-body">
          <div class="space-y-4">
            <div class="p-3 bg-light rounded-lg">
              <div class="font-bold text-xs mb-1">[Name]</div>
              <p class="text-[10px] text-muted">Recipient's full name</p>
            </div>
            <div class="p-3 bg-light rounded-lg">
              <div class="font-bold text-xs mb-1">[Group]</div>
              <p class="text-[10px] text-muted">Primary group of the member</p>
            </div>
            <div class="p-3 bg-light rounded-lg">
              <div class="font-bold text-xs mb-1">[Date]</div>
              <p class="text-[10px] text-muted">Today's current date</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
