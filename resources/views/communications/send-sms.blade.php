@extends('layouts.app')

@section('title', 'Send SMS - TmcsSmart')
@section('page-title', 'Send SMS')
@section('breadcrumb', 'TmcsSmart / Communications / Send SMS')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">Send SMS</h2>
      <p class="text-sm text-muted mt-1">Compose and send SMS messages to members</p>
    </div>
    <a href="{{ route('communications.index') }}" class="btn btn-secondary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Communications
    </a>
  </div>

  <form action="{{ route('communications.store') }}" method="POST">
    @csrf
    <input type="hidden" name="type" value="SMS">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left Column (Content & Templates) -->
      <div class="lg:col-span-8 space-y-6">
        <!-- Message Templates Card -->
        <div class="card">
          <div class="card-header flex items-center justify-between">
            <div>
              <div class="card-title flex items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Message Templates
              </div>
              <div class="card-subtitle">Use a pre-defined template</div>
            </div>
          </div>
          <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <select id="smsTemplateSelect" class="form-control" onchange="useSmsTemplate()">
                  <option value="">Select a template...</option>
                  @foreach($templates as $template)
                    @if($template->type === 'SMS')
                      <option value="{{ $template->id }}" data-subject="{{ $template->subject }}" data-content="{{ $template->content }}">
                        {{ $template->name }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="flex gap-2">
                <button type="button" class="btn btn-secondary" onclick="resetSmsMessage()">
                  Reset
                </button>
              </div>
            </div>
            <div id="templatePreview" class="mt-4 hidden p-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
              <h4 class="text-sm font-semibold mb-2 text-gray-600">Template Preview</h4>
              <div id="templatePreviewContent" class="text-sm text-gray-800"></div>
            </div>
          </div>
        </div>

        <!-- Message Content Card -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
              Message Content
            </div>
            <div class="card-subtitle">Compose your SMS message</div>
          </div>
          <div class="card-body space-y-4">
            <div class="form-group">
              <label class="form-label">Subject</label>
              <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="Enter message subject" required>
            </div>
            <div class="form-group">
              <label class="form-label">Message</label>
              <textarea name="message" id="smsMessage" class="form-control" rows="8" placeholder="Enter your SMS message here..." required>{{ old('message') }}</textarea>
              <div class="flex justify-between mt-2 text-xs text-muted">
                <span id="smsCharCount">0 characters</span>
                <span id="smsUnitCount">0 SMS units</span>
              </div>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
              <h5 class="text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">Available Placeholders</h5>
              <div class="flex flex-wrap gap-2">
                <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Name')">[Name]</button>
                <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Group')">[Group]</button>
                <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Date')">[Date]</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column (Recipients, Schedule, Gateway) -->
      <div class="lg:col-span-4 space-y-6">
        <!-- Recipients Card -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              Recipients
            </div>
            <div class="card-subtitle">Select who will receive the SMS</div>
          </div>
          <div class="card-body space-y-4">
            <div class="form-group">
              <select name="recipient_type" id="smsRecipientType" class="form-control" required onchange="toggleSmsRecipientFields()">
                <option value="All" {{ old('recipient_type') == 'All' ? 'selected' : '' }}>All Members</option>
                <option value="Group" {{ old('recipient_type') == 'Group' ? 'selected' : '' }}>Specific Group</option>
                <option value="Individual" {{ old('recipient_type') == 'Individual' ? 'selected' : '' }}>Individual Member</option>
                <option value="Advanced" {{ old('recipient_type') == 'Advanced' ? 'selected' : '' }}>Advanced Criteria</option>
              </select>
            </div>

            <div id="smsGroupField" class="space-y-3 {{ old('recipient_type') == 'Group' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Select Group</label>
                <select name="group_id" class="form-control">
                  <option value="">Select a group</option>
                  @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }} ({{ $group->members->count() }} members)</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div id="smsIndividualField" class="space-y-3 {{ old('recipient_type') == 'Individual' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Select Member</label>
                <select name="member_id" class="form-control">
                  <option value="">Select a member</option>
                  @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }} ({{ $member->phone ?? 'No phone' }})</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div id="smsAdvancedFields" class="space-y-4 {{ old('recipient_type') == 'Advanced' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Member Categories</label>
                <select name="criteria[category_ids][]" class="form-control" multiple>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, old('criteria.category_ids', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
                <span class="text-xs text-muted">Hold Ctrl/Cmd to select multiple</span>
              </div>
              <div class="form-group mb-0">
                <label class="form-label">Programs</label>
                <select name="criteria[program_ids][]" class="form-control" multiple>
                  @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ in_array($program->id, old('criteria.program_ids', [])) ? 'selected' : '' }}>{{ $program->name }}</option>
                  @endforeach
                </select>
                <span class="text-xs text-muted">Hold Ctrl/Cmd to select multiple</span>
              </div>
              <div class="form-group mb-0">
                <label class="form-label">Communities</label>
                <select name="criteria[community_ids][]" class="form-control" multiple>
                  @foreach($groups->where('type', 'Community') as $community)
                    <option value="{{ $community->id }}" {{ in_array($community->id, old('criteria.community_ids', [])) ? 'selected' : '' }}>{{ $community->name }}</option>
                  @endforeach
                </select>
                <span class="text-xs text-muted">Hold Ctrl/Cmd to select multiple</span>
              </div>
              <div class="grid grid-cols-2 gap-2">
                <div class="form-group mb-0">
                  <label class="form-label">Contribution Min</label>
                  <input type="number" name="criteria[contribution_min]" class="form-control" value="{{ old('criteria.contribution_min') }}" placeholder="Min">
                </div>
                <div class="form-group mb-0">
                  <label class="form-label">Contribution Max</label>
                  <input type="number" name="criteria[contribution_max]" class="form-control" value="{{ old('criteria.contribution_max') }}" placeholder="Max">
                </div>
              </div>
              <div class="form-group mb-0">
                <label class="form-label">Active Status</label>
                <select name="criteria[is_active]" class="form-control">
                  <option value="">All</option>
                  <option value="1" {{ old('criteria.is_active') === '1' ? 'selected' : '' }}>Active Only</option>
                  <option value="0" {{ old('criteria.is_active') === '0' ? 'selected' : '' }}>Inactive Only</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Schedule Card -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Schedule
            </div>
            <div class="card-subtitle">Schedule when to send this SMS</div>
          </div>
          <div class="card-body space-y-4">
            <div class="flex gap-4 items-center">
              <label class="flex items-center gap-2 cursor-pointer flex-1">
                <input type="radio" name="send_option" value="now" checked onchange="toggleSmsScheduleField()">
                <span class="font-medium">Send Now</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer flex-1">
                <input type="radio" name="send_option" value="schedule" onchange="toggleSmsScheduleField()">
                <span class="font-medium">Schedule Later</span>
              </label>
            </div>
            <div id="smsScheduleField" class="space-y-2 hidden">
              <input type="datetime-local" name="scheduled_at" class="form-control">
              <span class="text-xs text-muted">Enter date and time in your local timezone</span>
            </div>
          </div>
        </div>

        <!-- Gateway Status Card -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              API Gateway
            </div>
            <div class="card-subtitle">Status of your messaging gateway</div>
          </div>
          <div class="card-body">
            @forelse($activeGateways->where('type', 'SMS') as $gateway)
              <div class="flex items-center justify-between py-2">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 rounded-full bg-green-500"></div>
                  <span class="text-sm font-medium">{{ $gateway->name }}</span>
                </div>
                <span class="badge green text-xs">Active</span>
              </div>
            @empty
              <div class="text-center py-4">
                <div class="flex items-center justify-center gap-2 text-red-500 mb-2">
                  <div class="w-2 h-2 rounded-full bg-red-500"></div>
                  <span class="text-sm font-medium">No active gateway</span>
                </div>
                <p class="text-xs text-muted">Please configure an API in API Settings</p>
              </div>
            @endforelse
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <button type="button" onclick="window.history.back()" class="btn btn-secondary flex-1">Cancel</button>
          <button type="submit" class="btn btn-primary flex-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span id="smsSubmitText">Send SMS Now</span>
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
let quillSms;

function toggleSmsRecipientFields() {
  const type = document.getElementById('smsRecipientType').value;
  document.getElementById('smsGroupField').classList.add('hidden');
  document.getElementById('smsIndividualField').classList.add('hidden');
  document.getElementById('smsAdvancedFields').classList.add('hidden');

  if (type === 'Group') {
    document.getElementById('smsGroupField').classList.remove('hidden');
  } else if (type === 'Individual') {
    document.getElementById('smsIndividualField').classList.remove('hidden');
  } else if (type === 'Advanced') {
    document.getElementById('smsAdvancedFields').classList.remove('hidden');
  }
}

function toggleSmsScheduleField() {
  const sendOption = document.querySelector('input[name="send_option"]:checked')?.value;
  if (sendOption === 'schedule') {
    document.getElementById('smsScheduleField').classList.remove('hidden');
    document.getElementById('smsSubmitText').textContent = 'Schedule SMS';
  } else {
    document.getElementById('smsScheduleField').classList.add('hidden');
    document.getElementById('smsSubmitText').textContent = 'Send SMS Now';
  }
}

function useSmsTemplate() {
  const select = document.getElementById('smsTemplateSelect');
  const selectedOption = select.options[select.selectedIndex];
  
  if (selectedOption.value) {
    const subject = selectedOption.getAttribute('data-subject');
    const content = selectedOption.getAttribute('data-content');
    
    // Set form inputs
    document.querySelector('input[name="subject"]').value = subject;
    document.getElementById('smsMessage').value = content;
    
    // Show preview
    document.getElementById('templatePreview').classList.remove('hidden');
    document.getElementById('templatePreviewContent').innerHTML = `
      <p class="font-semibold mb-1">${subject}</p>
      <p class="whitespace-pre-wrap">${content}</p>
    `;
    
    updateSmsCount();
  } else {
    document.getElementById('templatePreview').classList.add('hidden');
  }
}

function resetSmsMessage() {
  if (confirm('Are you sure you want to clear the subject and message?')) {
    document.querySelector('input[name="subject"]').value = '';
    document.getElementById('smsMessage').value = '';
    document.getElementById('smsTemplateSelect').value = '';
    document.getElementById('templatePreview').classList.add('hidden');
    updateSmsCount();
  }
}

function insertPlaceholder(placeholder) {
  const textarea = document.getElementById('smsMessage');
  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const text = textarea.value;
  textarea.value = text.substring(0, start) + `[${placeholder}]` + text.substring(end);
  textarea.focus();
  textarea.setSelectionRange(start + placeholder.length + 2, start + placeholder.length + 2);
  updateSmsCount();
}

function updateSmsCount() {
  const msg = document.getElementById('smsMessage').value;
  const charCount = document.getElementById('smsCharCount');
  const unitCount = document.getElementById('smsUnitCount');
  
  const length = msg.length;
  const units = Math.ceil(length / 160) || 0;
  
  charCount.textContent = `${length} characters`;
  unitCount.textContent = `${units} SMS unit${units !== 1 ? 's' : ''}`;
}

document.getElementById('smsMessage').addEventListener('input', updateSmsCount);
document.addEventListener('DOMContentLoaded', function() {
  toggleSmsRecipientFields();
  updateSmsCount();
});
</script>
@endpush
