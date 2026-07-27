@extends('layouts.app')

@section('title', 'Event Details - TmcsSmart')
@section('page-title', 'Event Details')
@section('breadcrumb', 'TmcsSmart / Events / Details')

@section('content')
<div class="animate-in">
  <!-- Event Information -->
  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">{{ $event->event_name }}</div>
      <div class="card-subtitle">{{ $event->event_date->format('F j, Y') }} at {{ $event->event_time->format('g:i A') }}</div>
    </div>
    <div class="card-body">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm text-muted">Venue</label>
          <p class="font-medium">{{ $event->venue }}</p>
        </div>
        <div>
          <label class="text-sm text-muted">Date</label>
          <p class="font-medium">{{ $event->event_date->format('F j, Y') }}</p>
        </div>
        <div>
          <label class="text-sm text-muted">Time</label>
          <p class="font-medium">{{ $event->event_time->format('g:i A') }}</p>
        </div>
        <div>
          <label class="text-sm text-muted">Status</label>
          <p class="font-medium">
            <span class="badge {{ $event->status == 'upcoming' ? 'blue' : ($event->status == 'completed' ? 'green' : 'red') }}">
              {{ ucfirst($event->status) }}
            </span>
          </p>
        </div>
      </div>
      
      <div class="mt-4">
        <label class="text-sm text-muted">Description</label>
        <p class="text-gray-600">{{ $event->description ?? 'No description provided' }}</p>
      </div>
      
      <div class="flex gap-3 mt-6 pt-6 border-t">
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary">Edit Event</a>
      </div>
    </div>
  </div>

  <!-- Financial Summary -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card">
      <div class="card-body">
        <div class="text-sm text-muted">Total Expenses</div>
        <div class="text-2xl font-bold text-red-600">TZS {{ number_format($totalExpenses, 2) }}</div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="text-sm text-muted">Total Contributions</div>
        <div class="text-2xl font-bold text-green-600">TZS {{ number_format($totalContributions, 2) }}</div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="text-sm text-muted">Balance</div>
        <div class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">TZS {{ number_format($balance, 2) }}</div>
      </div>
    </div>
  </div>

  <!-- Expenses Section -->
  <div class="card mb-6">
    <div class="card-header">
      <div class="card-title">Event Expenses</div>
      <div class="card-subtitle">Manage expenses for this event</div>
      <button onclick="openExpenseModal()" class="btn btn-primary btn-sm">
        + Record Expense
      </button>
    </div>
    <div class="card-body">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Voucher #</th>
              <th>Category</th>
              <th>Description</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($event->expenses as $expense)
            <tr>
              <td>{{ $expense->voucher_number }}</td>
              <td>{{ $expense->category }}</td>
              <td>{{ $expense->description }}</td>
              <td>TZS {{ number_format($expense->amount, 2) }}</td>
              <td>{{ $expense->expense_date->format('M j, Y') }}</td>
              <td>
                <span class="badge {{ $expense->status == 'Approved' ? 'green' : ($expense->status == 'Rejected' ? 'red' : 'amber') }}">
                  {{ $expense->status }}
                </span>
              </td>
              <td>
                <a href="#" class="text-blue-600 hover:underline text-sm">View</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-8 text-muted">
                No expenses recorded for this event
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Contributions Section -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Event Contributions</div>
      <div class="card-subtitle">Manage contributions for this event</div>
      <button onclick="openContributionModal()" class="btn btn-primary btn-sm">
        + Record Contribution
      </button>
    </div>
    <div class="card-body">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Receipt #</th>
              <th>Member</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($event->contributions as $contribution)
            <tr>
              <td>{{ $contribution->receipt_number }}</td>
              <td>{{ $contribution->member->full_name ?? 'N/A' }}</td>
              <td>{{ $contribution->contribution_type }}</td>
              <td>TZS {{ number_format($contribution->amount, 2) }}</td>
              <td>{{ $contribution->contribution_date->format('M j, Y') }}</td>
              <td>
                <span class="badge {{ $contribution->is_verified ? 'green' : 'amber' }}">
                  {{ $contribution->is_verified ? 'Verified' : 'Pending' }}
                </span>
              </td>
              <td>
                <a href="#" class="text-blue-600 hover:underline text-sm">View</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-8 text-muted">
                No contributions recorded for this event
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Expense Modal -->
<div id="expenseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
  <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
    <div class="p-6">
      <h3 class="text-lg font-semibold mb-4">Record Expense</h3>
      <form id="expenseForm">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Category *</label>
            <select name="category" class="w-full border rounded px-3 py-2" required>
              <option value="Events" selected>Events</option>
              <option value="Utilities">Utilities (Electricity/Water)</option>
              <option value="Salaries">Salaries & Allowances</option>
              <option value="Maintenance">Maintenance & Repairs</option>
              <option value="Charity">Charity & Donations</option>
              <option value="Office">Office Supplies</option>
              <option value="Other">Other Expenses</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Amount (TZS) *</label>
            <input type="number" name="amount" class="w-full border rounded px-3 py-2" step="0.01" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Expense Date *</label>
            <input type="date" name="expense_date" class="w-full border rounded px-3 py-2" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Payment Method *</label>
            <select name="payment_method" class="w-full border rounded px-3 py-2" required>
              <option value="Cash">Cash</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Mobile Money">Mobile Money</option>
              <option value="Card">Card</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Description *</label>
            <input type="text" name="description" class="w-full border rounded px-3 py-2" required>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Reference Number</label>
            <input type="text" name="reference_number" class="w-full border rounded px-3 py-2">
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button type="button" onclick="closeExpenseModal()" class="flex-1 px-4 py-2 border rounded">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded">Save Expense</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Contribution Modal -->
<div id="contributionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
  <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
    <div class="p-6">
      <h3 class="text-lg font-semibold mb-4">Record Contribution</h3>
      <form id="contributionForm">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Member *</label>
            <select name="member_id" class="w-full border rounded px-3 py-2" required>
              <option value="">Select Member</option>
              @foreach(\App\Models\Member::where('is_active', true)->get() as $member)
              <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->registration_number }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Contribution Type *</label>
            <select name="contribution_type" class="w-full border rounded px-3 py-2" required>
              <option value="Event Contribution" selected>Event Contribution</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Amount (TZS) *</label>
            <input type="number" name="amount" class="w-full border rounded px-3 py-2" step="0.01" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Contribution Date *</label>
            <input type="date" name="contribution_date" class="w-full border rounded px-3 py-2" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Payment Method *</label>
            <select name="payment_method" class="w-full border rounded px-3 py-2" required>
              <option value="cash">Cash</option>
              <option value="mobile_money">Mobile Money</option>
              <option value="card">Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2" rows="2"></textarea>
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button type="button" onclick="closeContributionModal()" class="flex-1 px-4 py-2 border rounded">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded">Save Contribution</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openExpenseModal() {
  document.getElementById('expenseModal').style.display = 'flex';
}

function closeExpenseModal() {
  document.getElementById('expenseModal').style.display = 'none';
}

function openContributionModal() {
  document.getElementById('contributionModal').style.display = 'flex';
}

function closeContributionModal() {
  document.getElementById('contributionModal').style.display = 'none';
}

// Expense form submission
document.addEventListener('DOMContentLoaded', function() {
  const expenseForm = document.getElementById('expenseForm');
  if (expenseForm) {
    let isSubmitting = false;
    
    expenseForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Prevent multiple submissions
      if (isSubmitting) {
        return;
      }
      
      isSubmitting = true;
      
      const formData = new FormData(this);
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      const submitBtn = this.querySelector('button[type="submit"]');
      
      if (!csrfToken) {
        Swal.fire({
          title: 'Error!',
          text: 'CSRF token not found',
          icon: 'error'
        });
        isSubmitting = false;
        return;
      }
      
      // Disable submit button to prevent double submission
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving...';
      
      fetch('{{ route('events.store-expense', $event->id) }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: 'Success!',
            text: 'Expense recorded successfully',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: data.message || 'Failed to record expense',
            icon: 'error'
          });
          submitBtn.disabled = false;
          submitBtn.textContent = 'Save Expense';
          isSubmitting = false;
        }
      })
      .catch(error => {
        Swal.fire({
          title: 'Error!',
          text: 'An error occurred',
          icon: 'error'
        });
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Expense';
        isSubmitting = false;
      });
    });
  }

  // Contribution form submission
  const contributionForm = document.getElementById('contributionForm');
  if (contributionForm) {
    let isSubmitting = false;
    
    contributionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Prevent multiple submissions
      if (isSubmitting) {
        return;
      }
      
      isSubmitting = true;
      
      const formData = new FormData(this);
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      const submitBtn = this.querySelector('button[type="submit"]');
      
      if (!csrfToken) {
        Swal.fire({
          title: 'Error!',
          text: 'CSRF token not found',
          icon: 'error'
        });
        isSubmitting = false;
        return;
      }
      
      // Disable submit button to prevent double submission
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving...';
      
      fetch('{{ route('events.store-contribution', $event->id) }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: 'Success!',
            text: 'Contribution recorded successfully',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: data.message || 'Failed to record contribution',
            icon: 'error'
          });
          submitBtn.disabled = false;
          submitBtn.textContent = 'Save Contribution';
          isSubmitting = false;
        }
      })
      .catch(error => {
        Swal.fire({
          title: 'Error!',
          text: 'An error occurred',
          icon: 'error'
        });
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Contribution';
        isSubmitting = false;
      });
    });
  }
});
</script>
@endpush
@endsection
