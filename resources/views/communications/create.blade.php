@extends('layouts.app')

@section('title', 'Schedule Message - TmcsSmart')
@section('page-title', 'Schedule Message')
@section('breadcrumb', 'TmcsSmart / Communications / Schedule')

@section('content')
<div class="animate-in">
  <form action="{{ route('communications.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- LEFT COLUMN: CHANNEL & CONTENT -->
      <div class="lg:col-span-8 space-y-6">
        <!-- CHANNEL SELECTION CARD -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Select Communication Channel
            </div>
            <div class="card-subtitle">Choose how you want to send your message</div>
          </div>
          <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <label class="flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:bg-green-50">
                <input type="radio" name="type" value="SMS" {{ old('type', 'SMS') == 'SMS' ? 'checked' : '' }} class="hidden peer">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-3 peer-checked:bg-blue-500 transition-colors">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="peer-checked:text-white text-blue-700">
                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
                <span class="font-semibold">SMS</span>
              </label>

              <label class="flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:bg-green-50">
                <input type="radio" name="type" value="Email" {{ old('type') == 'Email' ? 'checked' : '' }} class="hidden peer">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-3 peer-checked:bg-amber-500 transition-colors">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="peer-checked:text-white text-amber-700">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <span class="font-semibold">Email</span>
              </label>

              <label class="flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:bg-green-50">
                <input type="radio" name="type" value="WhatsApp" {{ old('type') == 'WhatsApp' ? 'checked' : '' }} class="hidden peer">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3 peer-checked:bg-purple-500 transition-colors">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="peer-checked:text-white text-purple-700">
                    <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                </div>
                <span class="font-semibold">WhatsApp</span>
              </label>

              <label class="flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:bg-green-50">
                <input type="radio" name="type" value="Announcement" {{ old('type') == 'Announcement' ? 'checked' : '' }} class="hidden peer">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3 peer-checked:bg-green-500 transition-colors">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="peer-checked:text-white text-green-700">
                    <path d="M11 5.882V19.24a1.76 1.76 0 003.417.592l2.147-6.158a1.76 1.76 0 011.545-1.101H20V5.882z"/>
                    <path d="M11 3h-7a1 1 0 00-1 1v10a1 1 0 001 1h7"/>
                  </svg>
                </div>
                <span class="font-semibold">Announcement</span>
              </label>
            </div>
            @error('type') <div class="text-red text-xs mt-3">{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- MESSAGE CONTENT CARD -->
        <div class="card">
          <div class="card-header flex items-center justify-between">
            <div>
              <div class="card-title flex items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Message Content
              </div>
              <div class="card-subtitle">Craft your perfect message</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <select id="messageTemplate" class="form-control text-sm" onchange="useTemplate()">
                  <option value="">Load Template...</option>
                  @foreach($templates as $template)
                  <option value="{{ $template->content }}" data-subject="{{ $template->subject }}" data-type="{{ $template->type }}">{{ $template->name }} ({{ $template->type }})</option>
                  @endforeach
                </select>
              </div>
              <div class="flex gap-2">
                <button type="button" class="btn btn-secondary flex-1" onclick="resetTemplate()">
                  Reset
                </button>
              </div>
            </div>
            <div id="templatePreview" class="mt-4 hidden p-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
              <h4 class="text-sm font-semibold mb-2 text-gray-600">Template Preview</h4>
              <div id="templatePreviewContent" class="text-sm text-gray-800"></div>
            </div>
          </div>
          <div class="card-body space-y-4">
            <div class="form-group">
              <label class="form-label">Subject <span class="text-red-500">*</span></label>
              <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required placeholder="e.g., Sunday Service Reminder">
              @error('subject') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
              <label class="form-label">Message <span class="text-red-500">*</span></label>
              <textarea name="message" id="messageArea" class="form-control hidden" rows="4" required placeholder="Type your message here...">{{ old('message') }}</textarea>
              <div id="quillEditor" class="rounded-lg"></div>
              <div class="flex justify-between mt-2">
                <span class="text-xs text-muted" id="charCount">0 characters</span>
                <span class="text-xs text-muted" id="smsCount">0 SMS units</span>
              </div>
              <div class="p-3 bg-blue-50 rounded-lg border border-blue-100 mt-3">
                <h5 class="text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">Available Placeholders</h5>
                <div class="flex flex-wrap gap-2">
                  <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Name')">[Name]</button>
                  <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Group')">[Group]</button>
                  <button type="button" class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-100 transition" onclick="insertPlaceholder('Date')">[Date]</button>
                </div>
              </div>
              @error('message') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: RECIPIENTS & SCHEDULING -->
      <div class="lg:col-span-4 space-y-6">
        <!-- RECIPIENTS CARD -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Recipients
            </div>
            <div class="card-subtitle">Who will receive this message?</div>
          </div>
          <div class="card-body space-y-4">
            <div class="form-group">
              <select name="recipient_type" id="recipientType" class="form-control" required>
                <option value="All" {{ old('recipient_type') == 'All' ? 'selected' : '' }}>All Members</option>
                <option value="Group" {{ old('recipient_type') == 'Group' ? 'selected' : '' }}>Specific Group</option>
                <option value="Individual" {{ old('recipient_type') == 'Individual' ? 'selected' : '' }}>Individual Member</option>
                <option value="Advanced" {{ old('recipient_type') == 'Advanced' ? 'selected' : '' }}>Advanced Criteria</option>
              </select>
            </div>

            <div id="groupSelect" class="space-y-3 {{ old('recipient_type') == 'Group' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Select Group <span class="text-red-500">*</span></label>
                <select name="group_id" class="form-control">
                  <option value="">Choose a group...</option>
                  @foreach($groups as $group)
                  <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }} ({{ $group->members->count() }} members)</option>
                  @endforeach
                </select>
                @error('group_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div id="memberSelect" class="space-y-3 {{ old('recipient_type') == 'Individual' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Select Member <span class="text-red-500">*</span></label>
                <select name="member_id" class="form-control">
                  <option value="">Choose a member...</option>
                  @foreach($members as $member)
                  <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }} ({{ $member->phone ?? $member->email }})</option>
                  @endforeach
                </select>
                @error('member_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div id="advancedCriteria" class="space-y-4 {{ old('recipient_type') == 'Advanced' ? '' : 'hidden' }}">
              <div class="form-group mb-0">
                <label class="form-label">Member Categories</label>
                <select name="criteria[category_ids][]" class="form-control" multiple>
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ in_array($cat->id, old('criteria.category_ids', [])) ? 'selected' : '' }}>{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mb-0">
                <label class="form-label">Programs</label>
                <select name="criteria[program_ids][]" class="form-control" multiple>
                  @foreach($programs as $prog)
                  <option value="{{ $prog->id }}" {{ in_array($prog->id, old('criteria.program_ids', [])) ? 'selected' : '' }}>{{ $prog->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mb-0">
                <label class="form-label">Communities</label>
                <select name="criteria[community_ids][]" class="form-control" multiple>
                  @foreach($groups->where('type', 'Community') as $com)
                  <option value="{{ $com->id }}" {{ in_array($com->id, old('criteria.community_ids', [])) ? 'selected' : '' }}>{{ $com->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="grid grid-cols-2 gap-3">
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
                  <option value="1" {{ old('criteria.is_active') === '1' ? 'selected' : '' }}>Only Active</option>
                  <option value="0" {{ old('criteria.is_active') === '0' ? 'selected' : '' }}>Only Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- SCHEDULING CARD -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Timing
            </div>
            <div class="card-subtitle">When should this be sent?</div>
          </div>
          <div class="card-body space-y-4">
            <div class="flex gap-4 items-center">
              <label class="flex items-center gap-2 cursor-pointer flex-1">
                <input type="radio" name="send_option" value="now" checked onchange="toggleCreateScheduleField()" class="w-4 h-4 text-green-600">
                <span class="font-medium">Send Now</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer flex-1">
                <input type="radio" name="send_option" value="schedule" onchange="toggleCreateScheduleField()" class="w-4 h-4 text-green-600">
                <span class="font-medium">Schedule Later</span>
              </label>
            </div>
            <div id="createScheduleField" class="space-y-2 hidden">
              <input type="datetime-local" name="scheduled_at" class="form-control">
              <span class="text-xs text-muted block">Enter date and time in your local timezone</span>
            </div>
          </div>
        </div>

        <!-- API GATEWAY STATUS CARD -->
        <div class="card">
          <div class="card-header">
            <div class="card-title flex items-center gap-2">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              API Gateways
            </div>
            <div class="card-subtitle">Status of your messaging services</div>
          </div>
          <div class="card-body">
            @forelse($activeGateways as $gateway)
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-sm font-medium">{{ $gateway->name }}</span>
              </div>
              <span class="badge green text-xs">{{ $gateway->provider_type }}</span>
            </div>
            @empty
            <div class="text-center py-4">
              <div class="flex items-center justify-center gap-2 text-red-500 mb-2">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <span class="text-sm font-medium">No Active Gateways</span>
              </div>
              <p class="text-xs text-muted">Please configure an API in Api Config section</p>
            </div>
            @endforelse
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-3">
          <a href="{{ route('communications.index') }}" class="btn btn-secondary flex-1">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2">
              <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span id="createSubmitText">Send Message</span>
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
let quill;
const recipientType = document.getElementById('recipientType');
const groupSelect = document.getElementById('groupSelect');
const memberSelect = document.getElementById('memberSelect');
const advancedCriteria = document.getElementById('advancedCriteria');
const templateSelect = document.getElementById('messageTemplate');
const subjectInput = document.querySelector('input[name="subject"]');
const communicationTypes = document.querySelectorAll('input[name="type"]');
const charCountEl = document.getElementById('charCount');
const smsCountEl = document.getElementById('smsCount');
const messageArea = document.getElementById('messageArea');

document.addEventListener('DOMContentLoaded', function() {
  // Initialize Quill
  quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Type your message here...',
    modules: {
      toolbar: [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'image'],
        ['clean']
      ]
    }
  });

  // Load initial content
  if (messageArea.value) {
    quill.root.innerHTML = messageArea.value;
  }

  // Sync Quill content to textarea
  quill.on('text-change', function() {
    const html = quill.root.innerHTML;
    messageArea.value = html === '<p><br></p>' ? '' : html;
    updateCharCount();
  });

  // Handle URL params
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

  // Initial count
  updateCharCount();
});

function updateCharCount() {
  const text = quill.getText().trim();
  const chars = text.length;
  charCountEl.textContent = `${chars} characters`;
  
  const units = Math.ceil(chars / 160) || 0;
  smsCountEl.textContent = `${units} SMS unit${units !== 1 ? 's' : ''}`;
}

function useTemplate() {
  if (templateSelect.value) {
    const selectedOption = templateSelect.options[templateSelect.selectedIndex];
    const content = templateSelect.value;
    const subject = selectedOption.getAttribute('data-subject');
    const type = selectedOption.getAttribute('data-type');

    quill.root.innerHTML = content;
    if (subject) subjectInput.value = subject;
    
    // Auto-select channel
    if (type) {
      communicationTypes.forEach(radio => {
        if (radio.value.toUpperCase() === type.toUpperCase()) {
          radio.checked = true;
        }
      });
    }
    
    // Show preview
    document.getElementById('templatePreview').classList.remove('hidden');
    document.getElementById('templatePreviewContent').innerHTML = `
      <p class="font-semibold mb-1">${subject}</p>
      <div class="whitespace-pre-wrap">${content}</div>
    `;
    
    updateCharCount();
  } else {
    document.getElementById('templatePreview').classList.add('hidden');
  }
}

function resetTemplate() {
  if (confirm('Are you sure you want to clear the message and subject?')) {
    quill.setText('');
    subjectInput.value = '';
    templateSelect.value = '';
    document.getElementById('templatePreview').classList.add('hidden');
    updateCharCount();
  }
}

function insertPlaceholder(placeholder) {
  const range = quill.getSelection();
  if (range) {
    quill.insertText(range.index, `[${placeholder}]`);
    quill.setSelection(range.index + placeholder.length + 2);
    updateCharCount();
  }
}

recipientType.addEventListener('change', function() {
  groupSelect.classList.add('hidden');
  memberSelect.classList.add('hidden');
  advancedCriteria.classList.add('hidden');
  
  if (this.value === 'Group') {
    groupSelect.classList.remove('hidden');
  } else if (this.value === 'Individual') {
    memberSelect.classList.remove('hidden');
  } else if (this.value === 'Advanced') {
    advancedCriteria.classList.remove('hidden');
  }
});

function toggleCreateScheduleField() {
  const sendOption = document.querySelector('input[name="send_option"]:checked')?.value;
  const submitText = document.getElementById('createSubmitText');
  
  if (sendOption === 'schedule') {
    document.getElementById('createScheduleField').classList.remove('hidden');
    submitText.textContent = 'Schedule Message';
  } else {
    document.getElementById('createScheduleField').classList.add('hidden');
    submitText.textContent = 'Send Message';
  }
}
</script>
@endpush