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
      <a href="{{ route('expenses.create', ['event_id' => $event->id]) }}" class="btn btn-primary btn-sm">
        + Record Expense
      </a>
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
      <a href="{{ route('finance.create', ['event_id' => $event->id]) }}" class="btn btn-primary btn-sm">
        + Record Contribution
      </a>
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
@endsection
