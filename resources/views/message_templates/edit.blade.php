@extends('layouts.app')

@section('title', 'Edit Template - TmcsSmart')
@section('page-title', 'Edit Template')
@section('breadcrumb', 'TmcsSmart / Communications / Templates / Edit')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Template Form -->
    <div class="lg:col-span-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Edit Template</div>
          <div class="card-subtitle">Modify reusable message content with placeholders</div>
        </div>
        <div class="card-body">
          <form action="{{ route('message-templates.update', $messageTemplate->id) }}" method="POST" id="templateForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="form-group">
                <label class="form-label">Template Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $messageTemplate->name) }}" required placeholder="e.g. Sunday Service Reminder">
                @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="form-label">Template Type *</label>
                <select name="type" class="form-control" required id="templateType">
                  <option value="SMS" {{ old('type', $messageTemplate->type) == 'SMS' ? 'selected' : '' }}>SMS</option>
                  <option value="Email" {{ old('type', $messageTemplate->type) == 'Email' ? 'selected' : '' }}>Email</option>
                  <option value="WhatsApp" {{ old('type', $messageTemplate->type) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
                @error('type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group" id="subjectGroup" style="{{ old('type', $messageTemplate->type) == 'Email' ? '' : 'display:none;' }}">
              <label class="form-label">Default Subject</label>
              <input type="text" name="subject" class="form-control" value="{{ old('subject', $messageTemplate->subject) }}" placeholder="Subject line for the email">
              @error('subject') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <div class="flex justify-between items-center mb-1">
                <label class="form-label mb-0">Template Content *</label>
                <div class="flex gap-2">
                  <button type="button" class="px-2 py-0.5 bg-light text-[10px] rounded hover:bg-gray-200" onclick="insertPlaceholder('[Name]')">[Name]</button>
                  <button type="button" class="px-2 py-0.5 bg-light text-[10px] rounded hover:bg-gray-200" onclick="insertPlaceholder('[Group]')">[Group]</button>
                  <button type="button" class="px-2 py-0.5 bg-light text-[10px] rounded hover:bg-gray-200" onclick="insertPlaceholder('[Date]')">[Date]</button>
                </div>
              </div>
              <textarea name="content" class="form-control" rows="10" required placeholder="Type the reusable content here...">{{ old('content', $messageTemplate->content) }}</textarea>
              <div class="flex justify-between mt-1">
                <span class="text-[10px] text-muted">Characters: <span id="charCount">0</span></span>
                <span class="text-[10px] text-muted">Estimated SMS segments: <span id="smsSegments">0</span></span>
              </div>
              @error('content') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $messageTemplate->is_active) ? 'checked' : '' }}>
                <span class="text-sm font-medium">Active Template</span>
              </label>
            </div>

            <div class="flex gap-3 mt-8">
              <a href="{{ route('message-templates.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
              <button type="submit" class="btn btn-primary flex-1">Update Template</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Right Column: Quick Test -->
    <div class="lg:col-span-1">
      <div class="card mb-6">
        <div class="card-header">
          <div class="card-title">Test Template</div>
          <div class="card-subtitle">Send a quick test message</div>
        </div>
        <div class="card-body">
          <form action="{{ route('message-templates.test') }}" method="POST" id="testForm">
            @csrf
            <input type="hidden" name="type" id="testType" value="{{ $messageTemplate->type }}">
            <input type="hidden" name="subject" id="testSubject" value="{{ $messageTemplate->subject }}">
            <input type="hidden" name="content" id="testContent" value="{{ $messageTemplate->content }}">

            <div class="form-group">
              <label class="form-label">Recipient (Phone or Email) *</label>
              <input type="text" name="test_recipient" class="form-control" placeholder="07xxxxxxxx or email@example.com" required>
              <p class="text-[10px] text-muted mt-1">Use your own contact to verify delivery.</p>
            </div>

            <button type="button" onclick="runQuickTest()" class="btn btn-dark w-full">
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

@push('scripts')
<script>
const contentArea = document.querySelector('textarea[name="content"]');
const charCount = document.getElementById('charCount');
const smsSegments = document.getElementById('smsSegments');
const templateType = document.getElementById('templateType');
const subjectGroup = document.getElementById('subjectGroup');

function updateCounts() {
  const len = contentArea.value.length;
  charCount.textContent = len;
  smsSegments.textContent = Math.ceil(len / 160) || 0;
}

contentArea.addEventListener('input', updateCounts);

templateType.addEventListener('change', function() {
  if (this.value === 'Email') {
    subjectGroup.style.display = 'block';
  } else {
    subjectGroup.style.display = 'none';
  }
});

function insertPlaceholder(placeholder) {
  const start = contentArea.selectionStart;
  const end = contentArea.selectionEnd;
  const text = contentArea.value;
  contentArea.value = text.substring(0, start) + placeholder + text.substring(end);
  contentArea.focus();
  contentArea.setSelectionRange(start + placeholder.length, start + placeholder.length);
  updateCounts();
}

function runQuickTest() {
  document.getElementById('testType').value = templateType.value;
  document.getElementById('testSubject').value = document.querySelector('input[name="subject"]').value;
  document.getElementById('testContent').value = contentArea.value;
  
  if (!contentArea.value) {
    alert('Please enter template content first.');
    return;
  }
  
  document.getElementById('testForm').submit();
}

// Initial count
updateCounts();
</script>
@endpush
