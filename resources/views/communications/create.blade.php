@extends('layouts.app')

@section('title', 'Send Bulk SMS - TmcsSmart')
@section('page-title', 'Send Bulk SMS')
@section('breadcrumb', 'TmcsSmart / Communications / Send SMS')

@section('content')
<div class="animate-in">
  <form action="{{ route('communications.store') }}" method="POST" enctype="multipart/form-data" id="smsForm">
    @csrf
    <input type="hidden" name="type" value="SMS">
    <input type="hidden" name="form_action" id="formActionInput" value="send">
  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">1. SMS Information</div>
      <div class="card-subtitle">Basic SMS details</div>
    </div>
    <div class="card-body space-y-4">
      <div class="form-group">
        <label class="form-label">SMS Category</label>
        <select name="sms_category" class="form-control">
          <option value="">Select SMS Type</option>
          <option value="church_announcement" {{ old('sms_category') == 'church_announcement' ? 'selected' : '' }}>Church Announcement</option>
          <option value="sunday_service_reminder" {{ old('sms_category') == 'sunday_service_reminder' ? 'selected' : '' }}>Sunday Service Reminder</option>
          <option value="prayer_meeting_reminder" {{ old('sms_category') == 'prayer_meeting_reminder' ? 'selected' : '' }}>Prayer Meeting Reminder</option>
          <option value="event_invitation" {{ old('sms_category') == 'event_invitation' ? 'selected' : '' }}>Event Invitation</option>
          <option value="birthday_wishes" {{ old('sms_category') == 'birthday_wishes' ? 'selected' : '' }}>Birthday Wishes</option>
          <option value="wedding_announcement" {{ old('sms_category') == 'wedding_announcement' ? 'selected' : '' }}>Wedding Announcement</option>
          <option value="funeral_announcement" {{ old('sms_category') == 'funeral_announcement' ? 'selected' : '' }}>Funeral Announcement</option>
          <option value="giving_tithe_reminder" {{ old('sms_category') == 'giving_tithe_reminder' ? 'selected' : '' }}>Giving/Tithe Reminder</option>
          <option value="cell_group_communication" {{ old('sms_category') == 'cell_group_communication' ? 'selected' : '' }}>Cell Group Communication</option>
          <option value="emergency_message" {{ old('sms_category') == 'emergency_message' ? 'selected' : '' }}>Emergency Message</option>
          <option value="general_message" {{ old('sms_category') == 'general_message' ? 'selected' : '' }}>General Message</option>
        </select>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">2. Message Content</div>
      <div class="card-subtitle">Compose your message</div>
    </div>
    <div class="card-body space-y-4">
      <div class="form-group">
        <label class="form-label">Message Title</label>
        <input type="text" name="message_title" id="messageTitle" class="form-control" value="{{ old('message_title') }}" placeholder="Sunday Service Reminder">
      </div>
      <div class="form-group">
        <label class="form-label">Message Body</label>
        <textarea name="message_body" id="messageBody" class="form-control" rows="6" placeholder="Dear Church Member, 

You are warmly invited to join our Sunday Worship Service this Sunday at 8:00 AM. 

Venue: Main Church Hall. 

God bless you.
TMCSmart Church">{{ old('message_body') }}</textarea>
        <div class="flex justify-between mt-2 text-sm">
          <span>Characters: <span id="charCount">0</span>/160</span>
          <span>SMS Parts: <span id="smsParts">1</span></span>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">3. Select Recipients</div>
      <div class="card-subtitle">Choose who to send to</div>
    </div>
    <div class="card-body space-y-4">
      <div class="flex flex-wrap gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="recipient_option" id="recipientAll" value="all" {{ old('recipient_option', 'all') == 'all' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>All Members</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="recipient_option" id="recipientCellGroup" value="cell_group" {{ old('recipient_option') == 'cell_group' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>Cell Group / Fellowship</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="recipient_option" id="recipientVisitors" value="visitors" {{ old('recipient_option') == 'visitors' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>Visitors</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="recipient_option" id="recipientCustom" value="custom" {{ old('recipient_option') == 'custom' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>Custom Members</span>
        </label>
      </div>

      <div id="cellGroupSelect" class="mt-4 {{ old('recipient_option') == 'cell_group' ? '' : 'hidden' }}">
        <div class="form-group">
          <label class="form-label">Select Cell Group</label>
          <select name="cell_group" class="form-control">
            <option value="">Choose a cell group...</option>
            @foreach($groups as $group)
            <option value="{{ $group->id }}" {{ old('cell_group') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div id="customMemberSelect" class="mt-4 {{ old('recipient_option') == 'custom' ? '' : 'hidden' }}">
        <div class="form-group">
          <label class="form-label">Select Members</label>
          <select name="custom_members[]" class="form-control" multiple size="5">
            @foreach($members as $member)
            <option value="{{ $member->id }}" {{ (old('custom_members') && in_array($member->id, old('custom_members'))) ? 'selected' : '' }}>{{ $member->full_name }} ({{ $member->phone ?? $member->email }})</option>
            @endforeach
          </select>
          <div class="text-xs text-muted mt-1">Hold Ctrl/Cmd to select multiple</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">4. Member Filters</div>
      <div class="card-subtitle">Filter recipients further</div>
    </div>
    <div class="card-body">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-control">
            <option value="all">All</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Age Group</label>
          <select name="age_group" class="form-control">
            <option value="all">All</option>
            <option value="0-17">0-17</option>
            <option value="18-30">18-30</option>
            <option value="31-50">31-50</option>
            <option value="51+">51+</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Membership Status</label>
          <select name="membership_status" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="all">All</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-control">
            <option value="all">All</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
          </select>
        </div>
        @if($contributionTypes->count() > 0)
        <div class="form-group">
          <label class="form-label">Contribution Type</label>
          <select name="contribution_type_id" class="form-control">
            <option value="">All Types</option>
            @foreach($contributionTypes as $type)
            <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        @endif
        <div class="form-group">
          <label class="form-label">Registration Date (Start)</label>
          <input type="date" name="reg_start_date" class="form-control" value="{{ old('reg_start_date') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Registration Date (End)</label>
          <input type="date" name="reg_end_date" class="form-control" value="{{ old('reg_end_date') }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">5. Manual Phone Numbers</div>
      <div class="card-subtitle">Add external contacts</div>
    </div>
    <div class="card-body">
      <div class="form-group">
        <label class="form-label">Enter phone numbers (one per line)</label>
        <textarea name="manual_phones" class="form-control" rows="3" placeholder="+255712345678
+255754321987">{{ old('manual_phones') }}</textarea>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">6. SMS Preview</div>
      <div class="card-subtitle">Preview before sending</div>
    </div>
    <div class="card-body">
      <div class="border rounded-lg p-4 bg-gray-50">
        <p class="mb-2"><strong>Sender:</strong> <span id="previewSender">{{ $defaultSenderName }}</span></p>
        <p class="mb-2"><strong>Recipients:</strong> <span id="previewRecipients">0 Members</span></p>
        <p class="mb-2"><strong>Message:</strong></p>
        <p id="previewMessage" class="whitespace-pre-line border-t border-gray-200 pt-2">Your message will appear here</p>
      </div>
      <p class="text-sm mt-4"><strong>SMS Required:</strong> <span id="previewSmsRequired">0</span></p>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">7. Schedule SMS</div>
      <div class="card-subtitle">Choose when to send</div>
    </div>
    <div class="card-body space-y-4">
      <div class="flex gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="send_option" value="now" {{ old('send_option', 'now') == 'now' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>Send Now</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="send_option" value="schedule" {{ old('send_option') == 'schedule' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
          <span>Schedule</span>
        </label>
      </div>
      <div id="scheduleFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('send_option') == 'schedule' ? '' : 'hidden' }}">
        <div class="form-group">
          <label class="form-label">Date</label>
          <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Time</label>
          <input type="time" name="scheduled_time" class="form-control" value="{{ old('scheduled_time') }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-body">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <div class="card-title">Actions</div>
          <div class="card-subtitle">Preview, save, or send your SMS with confirmation prompts.</div>
        </div>
        <div class="flex flex-wrap gap-3">
          <button type="button" id="previewBtn" class="btn btn-secondary">Preview SMS</button>
          <button type="button" id="saveDraftBtn" class="btn btn-gold">Save Draft</button>
          <button type="button" id="cancelBtn" class="btn btn-secondary" data-cancel-url="{{ route('communications.index') }}">Cancel</button>
          <button type="button" id="sendBtn" class="btn btn-primary">
            <span id="sendBtnText">Send SMS</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">8. SMS History</div>
      <div class="card-subtitle">Your recent SMS</div>
    </div>
    <div class="card-body">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b">
              <th class="text-left py-2">Date</th>
              <th class="text-left py-2">Message</th>
              <th class="text-left py-2">Group</th>
              <th class="text-left py-2">Recipients</th>
              <th class="text-left py-2">Status</th>
              <th class="text-left py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @if($communications->count() > 0)
              @foreach($communications->take(5) as $comm)
                <tr class="border-b">
                  <td class="py-2">{{ $comm->created_at->format('d/m/Y') }}</td>
                  <td class="py-2">{{ Str::limit($comm->subject, 30) }}</td>
                  <td class="py-2">{{ $comm->recipient_type == 'all' ? 'All Members' : ($comm->group ? $comm->group->name : 'N/A') }}</td>
                  <td class="py-2">{{ count(json_decode($comm->recipients, true) ?? []) }}</td>
                  <td class="py-2"><span class="badge {{ $comm->status == 'sent' ? 'green' : 'amber' }}">{{ ucfirst($comm->status) }}</span></td>
                  <td class="py-2">
                    <div class="flex gap-2">
                      <button type="button" class="text-xs text-green-600 hover:underline" onclick="viewCommunication({{ $comm->id }})">View</button>
                      <button type="button" class="text-xs text-blue-600 hover:underline" onclick="resendCommunication({{ $comm->id }})">Resend</button>
                      <button type="button" class="text-xs text-gray-600 hover:underline">Download Report</button>
                    </div>
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="6" class="text-center py-4 text-muted">No SMS history yet</td></tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">Recommended Church SMS Templates</div>
    </div>
    <div class="card-body space-y-3">
      <div class="p-3 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-blue-50" onclick="useTemplate('Welcome New Member', 'Welcome to TMCSmart Church family. We are happy to have you with us. God bless you.')">
        <h5 class="font-semibold text-sm mb-1">Welcome New Member</h5>
        <p class="text-xs text-gray-600">Welcome to TMCSmart Church family. We are happy to have you with us. God bless you.</p>
      </div>
      <div class="p-3 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-blue-50" onclick="useTemplate('Birthday Message', 'Happy Birthday [Name]! May God bless you with joy, health and success. From TMCSmart Church.')">
        <h5 class="font-semibold text-sm mb-1">Birthday Message</h5>
        <p class="text-xs text-gray-600">Happy Birthday John! May God bless you with joy, health and success. From TMCSmart Church.</p>
      </div>
      <div class="p-3 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-blue-50" onclick="useTemplate('Service Reminder', 'Reminder: Sunday Worship Service starts at 8:00 AM. We look forward to worshiping with you.')">
        <h5 class="font-semibold text-sm mb-1">Service Reminder</h5>
        <p class="text-xs text-gray-600">Reminder: Sunday Worship Service starts at 8:00 AM. We look forward to worshiping with you.</p>
      </div>
      <div class="p-3 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-blue-50" onclick="useTemplate('Contribution/Giving Reminder', 'Dear Member, thank you for your faithfulness. Remember your weekly contribution. God bless you.')">
        <h5 class="font-semibold text-sm mb-1">Contribution/Giving Reminder</h5>
        <p class="text-xs text-gray-600">Dear Member, thank you for your faithfulness. Remember your weekly contribution. God bless you.</p>
      </div>
      <div class="p-3 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-blue-50" onclick="useTemplate('Emergency Announcement', 'Important announcement: Due to weather conditions, today\'s meeting has been postponed.')">
        <h5 class="font-semibold text-sm mb-1">Emergency Announcement</h5>
        <p class="text-xs text-gray-600">Important announcement: Due to weather conditions, today's meeting has been postponed.</p>
      </div>
    </div>
  </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
const smsForm = document.getElementById('smsForm');
const formActionInput = document.getElementById('formActionInput');
const messageTitle = document.getElementById('messageTitle');
const messageBody = document.getElementById('messageBody');
const previewMessage = document.getElementById('previewMessage');
const previewRecipients = document.getElementById('previewRecipients');
const previewSmsRequired = document.getElementById('previewSmsRequired');
const sendBtn = document.getElementById('sendBtn');
const sendBtnText = document.getElementById('sendBtnText');
const saveDraftBtn = document.getElementById('saveDraftBtn');
const previewBtn = document.getElementById('previewBtn');
const cancelBtn = document.getElementById('cancelBtn');

function countManualPhones() {
  const manualPhonesField = document.querySelector('textarea[name="manual_phones"]');
  if (!manualPhonesField || !manualPhonesField.value.trim()) {
    return 0;
  }

  return manualPhonesField.value
    .split('\n')
    .map(phone => phone.trim())
    .filter(Boolean)
    .length;
}

function getRecipientSummary() {
  const selectedRecipient = document.querySelector('input[name="recipient_option"]:checked')?.value || 'all';
  const manualCount = countManualPhones();
  let label = 'All Members';
  let estimatedCount = manualCount;

  if (selectedRecipient === 'cell_group') {
    const groupSelect = document.querySelector('select[name="cell_group"]');
    label = groupSelect?.options[groupSelect.selectedIndex]?.text || 'Selected Cell Group';
    estimatedCount += groupSelect?.value ? 1 : 0;
  } else if (selectedRecipient === 'custom') {
    const selectedMembers = Array.from(document.querySelector('select[name="custom_members[]"]')?.selectedOptions || []);
    label = selectedMembers.length ? `Custom Members (${selectedMembers.length})` : 'Custom Members';
    estimatedCount += selectedMembers.length;
  } else if (selectedRecipient === 'visitors') {
    label = 'Visitors';
    estimatedCount += 1;
  } else {
    estimatedCount += 1;
  }

  return {
    label,
    estimatedCount,
  };
}

function updateCharCount() {
  const text = messageBody.value;
  const chars = text.length;
  const parts = Math.max(1, Math.ceil(chars / 160));
  const recipientSummary = getRecipientSummary();

  document.getElementById('charCount').textContent = chars;
  document.getElementById('smsParts').textContent = parts;
  previewMessage.textContent = text || 'Your message will appear here';
  previewRecipients.textContent = `${recipientSummary.estimatedCount} recipient(s) estimated`;
  previewSmsRequired.textContent = Math.max(1, parts * Math.max(1, recipientSummary.estimatedCount));
}

function toggleRecipientFields() {
  const selectedValue = document.querySelector('input[name="recipient_option"]:checked')?.value;
  document.getElementById('cellGroupSelect').classList.add('hidden');
  document.getElementById('customMemberSelect').classList.add('hidden');

  if (selectedValue === 'cell_group') {
    document.getElementById('cellGroupSelect').classList.remove('hidden');
  } else if (selectedValue === 'custom') {
    document.getElementById('customMemberSelect').classList.remove('hidden');
  }

  updateCharCount();
}

function toggleScheduleFields() {
  const sendOption = document.querySelector('input[name="send_option"]:checked')?.value;
  document.getElementById('scheduleFields').classList.toggle('hidden', sendOption !== 'schedule');
}

function useTemplate(title, body) {
  messageTitle.value = title;
  messageBody.value = body;
  updateCharCount();
}

function setSubmittingState(buttonLabel) {
  sendBtn.disabled = true;
  saveDraftBtn.disabled = true;
  previewBtn.disabled = true;
  cancelBtn.disabled = true;
  sendBtnText.textContent = buttonLabel;
}

function getPreviewHtml() {
  const chars = messageBody.value.length;
  const parts = Math.max(1, Math.ceil(chars / 160));
  const recipientSummary = getRecipientSummary();
  const message = (messageBody.value || 'No message content yet.').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
  const title = (messageTitle.value || 'Untitled SMS').replace(/</g, '&lt;').replace(/>/g, '&gt;');

  return `
    <div style="text-align:left">
      <div style="margin-bottom:12px;"><strong>Sender:</strong> {{ $defaultSenderName }}</div>
      <div style="margin-bottom:12px;"><strong>Title:</strong> ${title}</div>
      <div style="margin-bottom:12px;"><strong>Recipients:</strong> ${recipientSummary.label}</div>
      <div style="margin-bottom:12px;"><strong>Estimated count:</strong> ${recipientSummary.estimatedCount}</div>
      <div style="margin-bottom:12px;"><strong>SMS units:</strong> ${parts}</div>
      <div style="padding:12px; border:1px solid #d1fae5; border-radius:12px; background:#f0fdf4;">
        ${message}
      </div>
    </div>
  `;
}

document.addEventListener('DOMContentLoaded', function() {
  updateCharCount();
  toggleRecipientFields();
  toggleScheduleFields();

  @if(session('success'))
    Swal.fire({
      title: 'Success',
      text: '{{ session('success') }}',
      icon: 'success',
      timer: 3000,
      showConfirmButton: false
    });
  @endif

  @if(session('error'))
    Swal.fire({
      title: 'Error',
      text: '{{ session('error') }}',
      icon: 'error',
      confirmButtonColor: '#059669'
    });
  @endif

  @if($errors->any())
    Swal.fire({
      title: 'Validation Error',
      html: `{!! collect($errors->all())->map(fn ($error) => '<div style="margin-bottom:6px;">' . e($error) . '</div>')->implode('') !!}`,
      icon: 'error',
      confirmButtonColor: '#059669'
    });
  @endif
});

messageBody.addEventListener('input', updateCharCount);
messageBody.addEventListener('change', updateCharCount);
messageTitle.addEventListener('input', updateCharCount);
document.querySelector('textarea[name="manual_phones"]').addEventListener('input', updateCharCount);
document.querySelector('select[name="cell_group"]').addEventListener('change', updateCharCount);
document.querySelector('select[name="custom_members[]"]').addEventListener('change', updateCharCount);

document.querySelectorAll('input[name="recipient_option"]').forEach(radio => {
  radio.addEventListener('change', toggleRecipientFields);
});

document.querySelectorAll('input[name="send_option"]').forEach(radio => {
  radio.addEventListener('change', toggleScheduleFields);
});

previewBtn.addEventListener('click', function() {
  Swal.fire({
    title: 'SMS Preview',
    html: getPreviewHtml(),
    width: 720,
    confirmButtonText: 'Close',
    confirmButtonColor: '#059669'
  });
});

sendBtn.addEventListener('click', function() {
  formActionInput.value = 'send';

  Swal.fire({
    title: 'Send SMS now?',
    text: 'This will submit the form and send the message using the configured SMS gateway.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, send SMS',
    cancelButtonText: 'Review Again',
    confirmButtonColor: '#059669',
    cancelButtonColor: '#6b7280'
  }).then((result) => {
    if (result.isConfirmed) {
      setSubmittingState('Sending...');
      smsForm.submit();
    }
  });
});

saveDraftBtn.addEventListener('click', function() {
  formActionInput.value = 'save_draft';

  Swal.fire({
    title: 'Save as draft?',
    text: 'Your current SMS setup will be saved without sending.',
    icon: 'info',
    showCancelButton: true,
    confirmButtonText: 'Save Draft',
    cancelButtonText: 'Continue Editing',
    confirmButtonColor: '#d97706',
    cancelButtonColor: '#6b7280'
  }).then((result) => {
    if (result.isConfirmed) {
      setSubmittingState('Saving Draft...');
      smsForm.submit();
    }
  });
});

cancelBtn.addEventListener('click', function() {
  const cancelUrl = this.dataset.cancelUrl;

  Swal.fire({
    title: 'Discard changes?',
    text: 'Unsaved edits on this SMS form will be lost.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, leave page',
    cancelButtonText: 'Stay Here',
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = cancelUrl;
    }
  });
});
</script>
@endpush
