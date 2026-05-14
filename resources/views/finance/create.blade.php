@extends('layouts.app')

@section('title', 'Record Contribution - TmcsSmart')
@section('page-title', 'Record Contribution')
@section('breadcrumb', 'TmcsSmart / Finance / Record Contribution')

@section('content')
<div class="animate-in">
  <form action="{{ route('finance.store') }}" method="POST">
    @csrf

    <!-- CONTRIBUTION DETAILS -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Contribution Details</div>
        <div class="card-subtitle">Enter contribution information</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Member *</label>
            <select name="member_id" class="form-control" required>
              <option value="">Select Member</option>
              @foreach($members as $member)
              <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                {{ $member->full_name }} - {{ $member->registration_number }}
              </option>
              @endforeach
            </select>
            @error('member_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Contribution Type *</label>
            <select name="contribution_type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="almsgiving" {{ old('contribution_type') == 'almsgiving' ? 'selected' : '' }}>Almsgiving/Zaka</option>
              <option value="offering" {{ old('contribution_type') == 'offering' ? 'selected' : '' }}>Offering</option>
              <option value="tithe" {{ old('contribution_type') == 'tithe' ? 'selected' : '' }}>Tithe</option>
              <option value="special_donation" {{ old('contribution_type') == 'special_donation' ? 'selected' : '' }}>Special Donation</option>
            </select>
            @error('contribution_type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Amount (TZS) *</label>
            <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount') }}" required>
            @error('amount') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Payment Method *</label>
            <select name="payment_method" class="form-control" required>
              <option value="">Select Method</option>
              <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
              <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
              <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            </select>
            @error('payment_method') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Contribution Date *</label>
            <input type="date" name="contribution_date" class="form-control" value="{{ old('contribution_date', now()->format('Y-m-d')) }}" required>
            @error('contribution_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Transaction Reference</label>
            <input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}" placeholder="e.g. MPESA12345">
            @error('transaction_reference') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this contribution">{{ old('notes') }}</textarea>
          @error('notes') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

    <!-- PAYMENT VERIFICATION -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Payment Verification</div>
        <div class="card-subtitle">Confirm payment details</div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_verified" class="rounded" {{ old('is_verified', true) ? 'checked' : '' }}>
            <span class="text-sm">Payment has been verified</span>
          </label>
          <p class="text-xs text-muted mt-1">Check this box if you have confirmed the payment was received</p>
        </div>

        <div class="form-group">
          <label class="form-label">Payment Receipt (Optional)</label>
          <div class="upload-box" onclick="document.getElementById('receipt').click()">
            <svg width="32" height="32" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
              <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Click to upload receipt</p>
            <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">PDF, JPG or PNG (MAX. 5MB)</p>
            <input type="file" id="receipt" name="receipt" accept=".pdf,.jpg,.jpeg,.png" style="display:none;">
          </div>
          @error('receipt') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

    <!-- AUTO-GENERATED RECEIPT NUMBER -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="font-bold">Receipt Number</h4>
            <p class="text-sm text-muted">This will be automatically generated when saved</p>
          </div>
          <div class="text-lg font-mono text-green" id="receiptPreview">
            REC-{{ date('Y') }}-{{ str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT) }}
          </div>
        </div>
      </div>
    </div>

    <!-- FORM ACTIONS -->
    <div class="flex gap-3">
      <a href="{{ route('finance.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Record Contribution
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
// Receipt preview
function generateReceiptNumber() {
  const year = new Date().getFullYear();
  const random = Math.floor(Math.random() * 90000) + 10000;
  return `REC-${year}-${random}`;
}

// Update receipt preview periodically
setInterval(() => {
  document.getElementById('receiptPreview').textContent = generateReceiptNumber();
}, 10000);

// Receipt upload preview
document.getElementById('receipt').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const uploadBox = document.querySelector('.upload-box');
    const fileName = file.name;
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    
    uploadBox.innerHTML = `
      <svg width="32" height="32" fill="none" stroke="var(--green-600)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p style="font-size:14px;font-weight:600;color:var(--green-600);">Receipt uploaded</p>
      <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">${fileName} (${fileSize} MB)</p>
      <button type="button" onclick="removeReceipt()" class="btn btn-secondary btn-sm mt-2">Remove Receipt</button>
    `;
  }
});

function removeReceipt() {
  document.getElementById('receipt').value = '';
  const uploadBox = document.querySelector('.upload-box');
  uploadBox.innerHTML = `
    <svg width="32" height="32" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
      <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Click to upload receipt</p>
    <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">PDF, JPG or PNG (MAX. 5MB)</p>
  `;
  uploadBox.onclick = function() { document.getElementById('receipt').click(); };
}

// Auto-calculate member statistics
document.querySelector('select[name="member_id"]').addEventListener('change', function(e) {
  const memberId = e.target.value;
  if (memberId) {
    // You could fetch member statistics here if needed
    console.log('Selected member:', memberId);
  }
});

// Amount formatting
document.querySelector('input[name="amount"]').addEventListener('input', function(e) {
  const value = parseFloat(e.target.value);
  if (!isNaN(value) && value > 0) {
    // You could show formatted amount here
    console.log('Amount:', value);
  }
});
</script>
@endpush
