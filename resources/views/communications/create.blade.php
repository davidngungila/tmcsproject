@extends('layouts.app')

@section('title', 'Send Message - TmcsSmart')
@section('page-title', 'Send Message')
@section('breadcrumb', 'TmcsSmart / Communications / Send')

@section('content')
<div class="animate-in">
  <form action="{{ route('communications.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- LEFT: MESSAGE CONTENT -->
      <div class="lg:col-span-2">
        <div class="card h-full">
          <div class="card-header">
            <div class="card-title">Message Content</div>
            <div class="card-subtitle">Compose your message</div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label class="form-label">Quick Templates</label>
              <select id="messageTemplate" class="form-control">
                <option value="">Select a template...</option>
                @foreach($templates as $template)
                <option value="{{ $template->content }}" data-subject="{{ $template->subject }}">{{ $template->name }}</option>
                @endforeach
              </select>
              <p class="text-[10px] text-muted mt-1">Selecting a template will populate the message field below.</p>
            </div>

            <div class="form-group">
              <label class="form-label">Subject *</label>
              <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required placeholder="e.g. Sunday Service Reminder">
              @error('subject') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Communication Channel *</label>
              <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="type" value="SMS" {{ old('type', 'SMS') == 'SMS' ? 'checked' : '' }}>
                  <span>SMS</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="type" value="Email" {{ old('type') == 'Email' ? 'selected' : '' }}>
                  <span>Email</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="type" value="WhatsApp" {{ old('type') == 'WhatsApp' ? 'selected' : '' }}>
                  <span>WhatsApp</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="type" value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>
                  <span class="badge green">Announcement</span>
                </label>
              </div>
              @error('type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Message *</label>
              <textarea name="message" class="form-control" rows="10" required placeholder="Type your message here...">{{ old('message') }}</textarea>
              <div class="flex justify-between mt-1">
                <span class="text-xs text-muted" id="charCount">0 characters</span>
                <span class="text-xs text-muted" id="smsCount">0 SMS units</span>
              </div>
              @error('message') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: RECIPIENTS -->
      <div class="lg:col-span-1">
        <div class="card h-full">
          <div class="card-header">
            <div class="card-title">Recipients</div>
            <div class="card-subtitle">Who will receive this message?</div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label class="form-label">Recipient Type *</label>
              <select name="recipient_type" id="recipientType" class="form-control" required>
                <option value="All" {{ old('recipient_type') == 'All' ? 'selected' : '' }}>All Members</option>
                <option value="Group" {{ old('recipient_type') == 'Group' ? 'selected' : '' }}>Specific Group</option>
                <option value="Individual" {{ old('recipient_type') == 'Individual' ? 'selected' : '' }}>Individual Member</option>
              </select>
            </div>

            <div id="groupSelect" class="form-group {{ old('recipient_type') == 'Group' ? '' : 'hidden' }}">
              <label class="form-label">Select Group *</label>
              <select name="group_id" class="form-control">
                <option value="">Select a group...</option>
                @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                  {{ $group->name }} ({{ $group->members->count() }} members)
                </option>
                @endforeach
              </select>
              @error('group_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div id="memberSelect" class="form-group {{ old('recipient_type') == 'Individual' ? '' : 'hidden' }}">
              <label class="form-label">Select Member *</label>
              <select name="member_id" class="form-control">
                <option value="">Select a member...</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                  {{ $member->full_name }} ({{ $member->phone ?? 'No phone' }})
                </option>
                @endforeach
              </select>
              @error('member_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mt-8 p-4 bg-light rounded-lg">
              <h4 class="text-sm font-bold mb-2">API Gateways Status</h4>
              @forelse($activeGateways as $gateway)
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green"></div>
                    <span class="text-xs font-medium">{{ $gateway->name }} ({{ $gateway->provider_type }})</span>
                  </div>
                  <span class="badge green" style="font-size: 8px;">Active</span>
                </div>
              @empty
                <div class="flex items-center gap-2 text-red-500">
                  <div class="w-2 h-2 rounded-full bg-red-500"></div>
                  <span class="text-xs">No Active Gateways</span>
                </div>
                <p class="text-[10px] text-muted mt-2">Please configure an API in the Api Config section.</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3 mt-6">
      <a href="{{ route('communications.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Send Message
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
const recipientType = document.getElementById('recipientType');
const groupSelect = document.getElementById('groupSelect');
const memberSelect = document.getElementById('memberSelect');
const messageArea = document.querySelector('textarea[name="message"]');
const charCount = document.getElementById('charCount');
const smsCount = document.getElementById('smsCount');
const templateSelect = document.getElementById('messageTemplate');
const subjectInput = document.querySelector('input[name="subject"]');

document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const groupId = urlParams.get('group_id');
  if (groupId) {
    recipientType.value = 'Group';
    groupSelect.classList.remove('hidden');
    const groupOption = groupSelect.querySelector(`option[value="${groupId}"]`);
    if (groupOption) {
      groupOption.selected = true;
    }
  }
});

templateSelect.addEventListener('change', function() {
  if (this.value) {
    messageArea.value = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const subject = selectedOption.getAttribute('data-subject');
    if (subject) {
      subjectInput.value = subject;
    }
    // Trigger input event to update char count
    messageArea.dispatchEvent(new Event('input'));
  }
});

recipientType.addEventListener('change', function() {
  groupSelect.classList.add('hidden');
  memberSelect.classList.add('hidden');
  
  if (this.value === 'Group') {
    groupSelect.classList.remove('hidden');
  } else if (this.value === 'Individual') {
    memberSelect.classList.remove('hidden');
  }
});

messageArea.addEventListener('input', function() {
  const chars = this.value.length;
  charCount.textContent = `${chars} characters`;
  
  // Basic SMS counting logic (160 chars per SMS)
  const units = Math.ceil(chars / 160) || 0;
  smsCount.textContent = `${units} SMS unit${units !== 1 ? 's' : ''}`;
});
</script>
@endpush
