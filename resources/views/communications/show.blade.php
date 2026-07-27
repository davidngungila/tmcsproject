@extends('layouts.app')

@section('title', 'Communication Details - TMCS Smart')
@section('page-title', 'Communication Details')
@section('breadcrumb', 'Home / Communications / Details')

@section('content')
<div class="animate-in space-y-6">
  <!-- COMMUNICATION DETAILS HEADER -->
  <div class="card">
    <div class="card-body">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-bold">{{ $communication->subject }}</h2>
          <p class="text-muted mt-1">{{ ucfirst($communication->type) }} Communication</p>
        </div>
        <div class="flex gap-2">
          <a href="{{ route('communications.index') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Communications
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="text-sm text-gray-500">Type</div>
          <div class="font-semibold text-lg">{{ ucfirst($communication->type) }}</div>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="text-sm text-gray-500">Status</div>
          <div class="font-semibold text-lg">
            <span class="badge {{ getCommunicationStatusColor($communication->status) }}">
              {{ ucfirst($communication->status) }}
            </span>
          </div>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="text-sm text-gray-500">Recipients</div>
          <div class="font-semibold text-lg">{{ count(json_decode($communication->recipients, true) ?? []) }}</div>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="text-sm text-gray-500">Sent By</div>
          <div class="font-semibold text-lg">{{ $communication->sentBy->name ?? 'N/A' }}</div>
        </div>
      </div>

      <!-- MESSAGE CONTENT -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Message Content</h3>
        <div class="bg-gray-50 p-4 rounded-lg border">
          <div class="whitespace-pre-wrap">{!! $communication->message !!}</div>
        </div>
      </div>

      <!-- ADDITIONAL DETAILS -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-lg font-semibold mb-3">Recipient Information</h3>
          <div class="space-y-2">
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Recipient Type</span>
              <span class="font-medium">
                @if($communication->recipient_type === 'all')
                  All Members
                @elseif($communication->recipient_type === 'group')
                  {{ $communication->group->name ?? 'Group' }}
                @elseif($communication->recipient_type === 'member')
                  {{ $communication->member->full_name ?? 'Member' }}
                @endif
              </span>
            </div>
            @if($communication->group_id)
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Group</span>
              <span class="font-medium">{{ $communication->group->name ?? 'N/A' }}</span>
            </div>
            @endif
            @if($communication->member_id)
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Member</span>
              <span class="font-medium">{{ $communication->member->full_name ?? 'N/A' }}</span>
            </div>
            @endif
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold mb-3">Timing Information</h3>
          <div class="space-y-2">
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Created At</span>
              <span class="font-medium">{{ $communication->created_at->format('M d, Y H:i') }}</span>
            </div>
            @if($communication->scheduled_at)
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Scheduled At</span>
              <span class="font-medium">{{ $communication->scheduled_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
            @if($communication->sent_at)
            <div class="flex justify-between py-2 border-b">
              <span class="text-gray-500">Sent At</span>
              <span class="font-medium">{{ $communication->sent_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- RECIPIENTS LIST -->
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Recipients List</h3>
        <div class="bg-gray-50 p-4 rounded-lg border max-h-64 overflow-y-auto">
          @php
            $recipients = json_decode($communication->recipients, true) ?? [];
          @endphp
          @if(!empty($recipients))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
              @foreach($recipients as $recipient)
                <div class="bg-white p-2 rounded border text-sm">
                  {{ $recipient }}
                </div>
              @endforeach
            </div>
          @else
            <div class="text-muted">No recipients found</div>
          @endif
        </div>
      </div>

      @if($communication->error_message)
      <!-- ERROR MESSAGE -->
      <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3 text-red-500">Error Information</h3>
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
          <div class="text-red-700">{{ $communication->error_message }}</div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function getCommunicationStatusColor(status) {
  const colors = {
    'pending': 'amber',
    'sent': 'green',
    'failed': 'red',
    'scheduled': 'blue',
    'draft': 'gray'
  };
  return colors[status] || 'blue';
}
</script>
@endpush
