<div class="space-y-6">
  <!-- HEADER INFO -->
  <div class="flex items-center justify-between p-4 bg-light rounded-lg">
    <div>
      <div class="text-xs text-muted uppercase font-bold tracking-wider">Receipt Number</div>
      <div class="text-lg font-bold mono">{{ $contribution->receipt_number }}</div>
    </div>
    <div class="text-right">
      <div class="text-xs text-muted uppercase font-bold tracking-wider">Status</div>
      <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
        {{ $contribution->is_verified ? 'Verified' : 'Pending Verification' }}
      </span>
    </div>
  </div>

  <!-- TRANSACTION DETAILS -->
  <div class="grid grid-cols-2 gap-4">
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Member</div>
      <div class="font-bold">{{ $contribution->member->full_name ?? 'N/A' }}</div>
      <div class="text-xs text-muted">{{ $contribution->member->registration_number ?? 'N/A' }}</div>
    </div>
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Amount</div>
      <div class="font-bold text-green-600 text-lg">TZS {{ number_format($contribution->amount) }}</div>
    </div>
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Type</div>
      <div class="font-bold">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</div>
    </div>
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Date</div>
      <div class="font-bold">{{ $contribution->contribution_date ? $contribution->contribution_date->format('M d, Y') : 'N/A' }}</div>
    </div>
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Payment Method</div>
      <div class="font-bold">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</div>
    </div>
    <div class="p-3 border border-light rounded-lg">
      <div class="text-xs text-muted mb-1">Recorded By</div>
      <div class="font-bold text-xs">{{ $contribution->recorded_by ?? 'System' }}</div>
    </div>
  </div>

  <!-- NOTES / COMMENTS -->
  <div>
    <label class="text-xs text-muted uppercase font-bold mb-2 block">Internal Notes / Comments</label>
    <div class="p-3 bg-light rounded-lg text-sm italic min-h-[60px]">
      {{ $contribution->notes ?? 'No internal notes provided for this transaction.' }}
    </div>
  </div>

  <!-- ACTIONS -->
  <div class="space-y-3">
    <label class="text-xs text-muted uppercase font-bold block">Quick Actions</label>
    <div class="grid grid-cols-2 gap-2">
      <button class="btn btn-secondary flex items-center justify-center gap-2 py-3" onclick="generateReceipt({{ $contribution->id }})">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Download Receipt
      </button>
      <button class="btn btn-secondary flex items-center justify-center gap-2 py-3" onclick="sendContributionSms({{ $contribution->id }})">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        Send SMS
      </button>
      <button class="btn btn-secondary flex items-center justify-center gap-2 py-3" onclick="sendContributionEmail({{ $contribution->id }})">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Send Email
      </button>
      <button class="btn btn-secondary flex items-center justify-center gap-2 py-3" onclick="addComment({{ $contribution->id }})">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Add Comment
      </button>
    </div>
  </div>
</div>

<script>
function sendContributionSms(id) {
  if (confirm('Send receipt confirmation SMS to member?')) {
    showToast('Processing SMS request...', 'info');
    // AJAX call to send SMS
  }
}

function sendContributionEmail(id) {
  if (confirm('Send receipt confirmation Email to member?')) {
    showToast('Processing Email request...', 'info');
    // AJAX call to send Email
  }
}

function addComment(id) {
  const comment = prompt('Enter internal comment:');
  if (comment) {
    // AJAX call to update notes
    showToast('Comment added successfully', 'success');
  }
}
</script>
