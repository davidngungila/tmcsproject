@extends('layouts.app')

@section('title', 'Create Group - TmcsSmart')
@section('page-title', 'Create New Group')
@section('breadcrumb', 'TmcsSmart / Groups / Create')

@section('content')
<div class="animate-in">
  <form action="{{ route('groups.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Group Details</div>
        <div class="card-subtitle">Basic information about the group</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Group Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Group Type *</label>
            <select name="type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Fellowship" {{ old('type') == 'Fellowship' ? 'selected' : '' }}>Fellowship</option>
              <option value="Community" {{ old('type') == 'Community' ? 'selected' : '' }}>Small Christian Community</option>
              <option value="Ministry" {{ old('type') == 'Ministry' ? 'selected' : '' }}>Ministry/Department</option>
              <option value="Choir" {{ old('type') == 'Choir' ? 'selected' : '' }}>Choir</option>
            </select>
            @error('type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          @error('description') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Meeting Day</label>
            <select name="meeting_day" class="form-control">
              <option value="">Select Day</option>
              @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
              <option value="{{ $day }}" {{ old('meeting_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Regular Contribution Amount</label>
            <div class="input-group">
              <span class="input-group-text">KES</span>
              <input type="number" step="0.01" name="regular_contribution_amount" class="form-control" value="{{ old('regular_contribution_amount', 0) }}">
            </div>
            <p class="text-xs text-muted mt-1">Default amount per member per meeting</p>
          </div>
        </div>

        <div class="border-t pt-4 mt-4">
          <h4 class="text-sm font-bold mb-4 uppercase text-muted">Leadership Assignment</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="form-group">
              <label class="form-label">Chairperson</label>
              <select name="chairperson_id" class="form-control select2">
                <option value="">Select Chairperson</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('chairperson_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Secretary</label>
              <select name="secretary_id" class="form-control select2">
                <option value="">Select Secretary</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('secretary_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Accountant</label>
              <select name="accountant_id" class="form-control select2">
                <option value="">Select Accountant</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('accountant_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div id="criteria-section" class="{{ old('type') == 'Community' ? '' : 'hidden' }} border-t pt-4 mt-4">
          <h4 class="text-sm font-bold mb-4 uppercase text-muted">Community Assignment Criteria</h4>
          <p class="text-xs text-muted mb-4 italic">Members matching these criteria will be automatically assigned to this community.</p>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="form-group">
              <label class="form-label">Member Category</label>
              <select name="criteria[category_id]" id="criteria_category_id" class="form-control">
                <option value="">Any Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" data-name="{{ $category->name }}" {{ old('criteria.category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group" id="criteria_program_group">
              <label class="form-label">Academic Programmes (Multi-select)</label>
              <select name="criteria[program_ids][]" class="form-control select2" multiple data-placeholder="Select one or more programmes">
                @foreach($programs as $program)
                <option value="{{ $program->id }}" {{ (is_array(old('criteria.program_ids')) && in_array($program->id, old('criteria.program_ids'))) ? 'selected' : '' }}>[{{ $program->code }}] {{ $program->name }}</option>
                @endforeach
              </select>
              <p class="text-[10px] text-muted mt-1 italic">Note: A programme can only be assigned to one community.</p>
            </div>
            <div class="form-group">
              <label class="form-label">Parish</label>
              <input type="text" name="criteria[parish]" class="form-control" value="{{ old('criteria.parish') }}" placeholder="e.g. St. Jude">
            </div>
            <div class="form-group">
              <label class="form-label">Diocese</label>
              <input type="text" name="criteria[diocese]" class="form-control" value="{{ old('criteria.diocese') }}" placeholder="e.g. Moshi">
            </div>
            <div class="form-group">
              <label class="form-label">Region</label>
              <input type="text" name="criteria[region]" class="form-control" value="{{ old('criteria.region') }}" placeholder="e.g. Kilimanjaro">
            </div>
          </div>
          <div class="form-group mt-3">
            <label class="form-label">Address Keyword (Partial Match)</label>
            <input type="text" name="criteria[address]" class="form-control" value="{{ old('criteria.address') }}" placeholder="e.g. Kibosho">
            <p class="text-[10px] text-muted mt-1">System will check if member's address contains this word.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Create Group
      </button>
    </div>
  </form>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const criteriaSection = document.getElementById('criteria-section');
    const criteriaCategorySelect = document.getElementById('criteria_category_id');
    const criteriaProgramGroup = document.getElementById('criteria_program_group');

    function toggleCriteriaSection() {
      if (typeSelect.value === 'Community') {
        criteriaSection.classList.remove('hidden');
      } else {
        criteriaSection.classList.add('hidden');
      }
    }

    function toggleCriteriaProgram() {
      const selectedOption = criteriaCategorySelect.options[criteriaCategorySelect.selectedIndex];
      const categoryName = selectedOption ? selectedOption.getAttribute('data-name') : '';
      
      if (['Undergraduate', 'Postgraduate'].includes(categoryName)) {
        criteriaProgramGroup.style.display = 'block';
      } else {
        criteriaProgramGroup.style.display = 'none';
      }
    }

    typeSelect.addEventListener('change', toggleCriteriaSection);
    criteriaCategorySelect.addEventListener('change', toggleCriteriaProgram);

    // Initial state
    toggleCriteriaSection();
    toggleCriteriaProgram();
  });
</script>
@endpush
@endsection
