@extends('layouts.app')

@section('title', 'Record Expense - TmcsSmart')
@section('page-title', 'Record New Expense')
@section('breadcrumb', 'TmcsSmart / Finance / Expenses / Create')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Expense Details</div>
      <div class="card-subtitle">Record a new church expenditure</div>
    </div>
    <div class="card-body">
      <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="expenseForm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label">Category *</label>
            <select name="category" class="form-control" required>
              <option value="">Select Category</option>
              <option value="Utilities">Utilities (Electricity/Water)</option>
              <option value="Salaries">Salaries & Allowances</option>
              <option value="Maintenance">Maintenance & Repairs</option>
              <option value="Charity">Charity & Donations</option>
              <option value="Events">Events & Liturgy</option>
              <option value="Office">Office Supplies</option>
              <option value="Other">Other Expenses</option>
            </select>
            @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Expense Date *</label>
            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
            @error('expense_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Description / Purpose *</label>
          <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="e.g. Payment for monthly electricity bill" required>
          @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label">Amount (TZS) *</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01" required>
            @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Payment Method *</label>
            <select name="payment_method" class="form-control" required>
              <option value="Cash">Cash</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Mobile Money">Mobile Money</option>
              <option value="Cheque">Cheque</option>
            </select>
            @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Reference Number (Optional)</label>
          <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="e.g. Check #, Transaction ID">
        </div>

        <div class="form-group">
          <label class="form-label">Attachment (Receipt/Invoice)</label>
          <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
          <p class="text-[10px] text-muted mt-1">PDF, JPG or PNG (Max 2MB)</p>
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('expenses.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1" id="submitBtn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" id="submitIcon"><path d="M5 13l4 4L19 7"/></svg>
            <span id="submitText">Save Expense</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Form submission with loading indicator
document.getElementById('expenseForm').addEventListener('submit', function(e) {
  const submitBtn = document.getElementById('submitBtn');
  const submitIcon = document.getElementById('submitIcon');
  const submitText = document.getElementById('submitText');
  
  // Disable button and show loading state
  submitBtn.disabled = true;
  submitIcon.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
  submitIcon.classList.add('animate-spin');
  submitText.textContent = 'Processing...';
});

// Check for session messages and show SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    Swal.fire({
      title: 'Success!',
      text: '{{ session('success') }}',
      icon: 'success',
      timer: 3000,
      showConfirmButton: false
    });
  @endif
  
  @if(session('error'))
    Swal.fire({
      title: 'Error!',
      text: '{{ session('error') }}',
      icon: 'error',
      confirmButtonColor: '#059669'
    });
  @endif
});
</script>
@endpush
