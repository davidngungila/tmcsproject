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
      <div class="card-title">Message Content</div>
      <div class="card-subtitle">Compose your message</div>
    </div>
    <div class="card-body space-y-4">
      <div class="form-group">
        <label class="form-label">Message Body</label>
        <textarea name="message_body" id="messageBody" class="form-control" rows="6" placeholder="Type your message here..." required>{{ old('message_body') }}</textarea>
        <div class="flex justify-between mt-2 text-sm">
          <span>Characters: <span id="charCount">0</span>/160</span>
          <span>SMS Parts: <span id="smsParts">1</span></span>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">Select Recipients</div>
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
      <div class="card-title">Member Filters</div>
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
    <div class="card-body">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <div class="card-title">Actions</div>
          <div class="card-subtitle">Send your message to selected recipients.</div>
        </div>
        <div class="flex flex-wrap gap-3">
          <button type="button" id="cancelBtn" class="btn btn-secondary" data-cancel-url="{{ route('communications.index') }}">Cancel</button>
          <button type="button" id="sendBtn" class="btn btn-primary">
            <span id="sendBtnText">Send SMS</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>

<!-- Sending Progress Modal -->
<div id="sendingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
  <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
    <div class="p-6">
      <h3 class="text-lg font-semibold mb-4">Sending SMS</h3>
      <div class="mb-4">
        <div class="flex justify-between text-sm mb-2">
          <span>Progress:</span>
          <span id="progressText">0 / 0</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div id="progressBar" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
        </div>
      </div>
      <div id="sendingStatus" class="text-sm text-gray-600 mb-4">Initializing...</div>
      <button type="button" id="closeSendingModal" class="w-full px-4 py-2 border rounded hidden">Close</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const smsForm = document.getElementById('smsForm');
  const formActionInput = document.getElementById('formActionInput');
  const messageBody = document.getElementById('messageBody');
  const sendBtn = document.getElementById('sendBtn');
  const sendBtnText = document.getElementById('sendBtnText');
  const cancelBtn = document.getElementById('cancelBtn');
  const sendingModal = document.getElementById('sendingModal');
  const progressBar = document.getElementById('progressBar');
  const progressText = document.getElementById('progressText');
  const sendingStatus = document.getElementById('sendingStatus');
  const closeSendingModal = document.getElementById('closeSendingModal');

  function updateCharCount() {
    if (!messageBody) return;
    const text = messageBody.value;
    const chars = text.length;
    const parts = Math.max(1, Math.ceil(chars / 160));

    const charCountEl = document.getElementById('charCount');
    const smsPartsEl = document.getElementById('smsParts');
    if (charCountEl) charCountEl.textContent = chars;
    if (smsPartsEl) smsPartsEl.textContent = parts;
  }

  function toggleRecipientFields() {
    const selectedValue = document.querySelector('input[name="recipient_option"]:checked')?.value;
    const cellGroupSelect = document.getElementById('cellGroupSelect');
    const customMemberSelect = document.getElementById('customMemberSelect');
    
    if (cellGroupSelect) cellGroupSelect.classList.add('hidden');
    if (customMemberSelect) customMemberSelect.classList.add('hidden');

    if (selectedValue === 'cell_group' && cellGroupSelect) {
      cellGroupSelect.classList.remove('hidden');
    } else if (selectedValue === 'custom' && customMemberSelect) {
      customMemberSelect.classList.remove('hidden');
    }
  }

  function showSendingModal(totalRecipients) {
    if (!sendingModal || !progressBar || !progressText || !sendingStatus || !closeSendingModal) return;
    sendingModal.style.display = 'flex';
    progressBar.style.width = '0%';
    progressText.textContent = `0 / ${totalRecipients}`;
    sendingStatus.textContent = 'Initializing...';
    closeSendingModal.classList.add('hidden');
  }

  function updateSendingProgress(current, total) {
    if (!progressBar || !progressText || !sendingStatus) return;
    const percentage = (current / total) * 100;
    progressBar.style.width = `${percentage}%`;
    progressText.textContent = `${current} / ${total}`;
    sendingStatus.textContent = `Sending to recipient ${current} of ${total}...`;
  }

  function hideSendingModal() {
    if (!closeSendingModal || !sendingStatus) return;
    closeSendingModal.classList.remove('hidden');
    sendingStatus.textContent = 'Sending completed!';
  }

  updateCharCount();
  toggleRecipientFields();

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

  if (messageBody) {
    messageBody.addEventListener('input', updateCharCount);
  }

  document.querySelectorAll('input[name="recipient_option"]').forEach(radio => {
    radio.addEventListener('change', toggleRecipientFields);
  });

  if (sendBtn) {
    sendBtn.addEventListener('click', function() {
      if (formActionInput) formActionInput.value = 'send';

      Swal.fire({
        title: 'Send SMS now?',
        text: 'This will send the message to filtered recipients sequentially.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, send SMS',
        cancelButtonText: 'Review Again',
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6b7280'
      }).then((result) => {
        if (result.isConfirmed) {
          if (sendBtn) sendBtn.disabled = true;
          if (sendBtnText) sendBtnText.textContent = 'Sending...';
          
          if (!smsForm) {
            Swal.fire({
              title: 'Error',
              text: 'Form not found',
              icon: 'error',
              confirmButtonColor: '#059669'
            });
            return;
          }
          
          const formData = new FormData(smsForm);
          showSendingModal(10);
          
          fetch('{{ route('communications.store') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
              'Accept': 'application/json'
            },
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              hideSendingModal();
              Swal.fire({
                title: 'Success!',
                text: 'SMS sent successfully to all recipients',
                icon: 'success',
                confirmButtonColor: '#059669'
              }).then(() => {
                window.location.href = '{{ route('communications.index') }}';
              });
            } else {
              if (sendingModal) sendingModal.style.display = 'none';
              if (sendBtn) sendBtn.disabled = false;
              if (sendBtnText) sendBtnText.textContent = 'Send SMS';
              Swal.fire({
                title: 'Error',
                text: data.message || 'Failed to send SMS',
                icon: 'error',
                confirmButtonColor: '#059669'
              });
            }
          })
          .catch(error => {
            if (sendingModal) sendingModal.style.display = 'none';
            if (sendBtn) sendBtn.disabled = false;
            if (sendBtnText) sendBtnText.textContent = 'Send SMS';
            Swal.fire({
              title: 'Error',
              text: 'An error occurred while sending',
              icon: 'error',
              confirmButtonColor: '#059669'
            });
          });
        }
      });
    });
  }

  if (cancelBtn) {
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
  }

  if (closeSendingModal) {
    closeSendingModal.addEventListener('click', function() {
      if (sendingModal) sendingModal.style.display = 'none';
      if (sendBtn) sendBtn.disabled = false;
      if (sendBtnText) sendBtnText.textContent = 'Send SMS';
    });
  }
});
</script>
@endpush
