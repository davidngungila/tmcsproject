@extends('layouts.app')

@section('title', 'Edit Expense - TmcsSmart')
@section('page-title', 'Edit Expense Request')
@section('breadcrumb', 'Finance / Expenses / Edit')

@section('content')
<div class="animate-in max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Modify Expense Voucher</h3>
            <p class="card-subtitle">Voucher No: {{ $expense->voucher_number }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="Administrative" {{ $expense->category == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                            <option value="Maintenance" {{ $expense->category == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Utilities" {{ $expense->category == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="Events" {{ $expense->category == 'Events' ? 'selected' : '' }}>Events</option>
                            <option value="Charity" {{ $expense->category == 'Charity' ? 'selected' : '' }}>Charity</option>
                            <option value="Salaries" {{ $expense->category == 'Salaries' ? 'selected' : '' }}>Salaries</option>
                            <option value="Other" {{ $expense->category == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Amount (TZS)</label>
                        <input type="number" name="amount" value="{{ $expense->amount }}" class="form-control" required min="0">
                    </div>
                </div>

                <div>
                    <label class="form-label">Description / Purpose</label>
                    <textarea name="description" class="form-control" rows="3" required>{{ $expense->description }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Expense Date</label>
                        <input type="date" name="expense_date" value="{{ $expense->expense_date->format('Y-m-d') }}" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash" {{ $expense->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ $expense->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ $expense->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="cheque" {{ $expense->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Reference Number (Optional)</label>
                    <input type="text" name="reference_number" value="{{ $expense->reference_number }}" class="form-control" placeholder="Check No, Transaction ID, etc.">
                </div>

                <div>
                    <label class="form-label">Attachment (Receipt/Invoice)</label>
                    <input type="file" name="attachment" class="form-control">
                    @if($expense->attachment)
                        <p class="text-xs text-muted mt-1">Current: {{ basename($expense->attachment) }}</p>
                    @endif
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1">Update Voucher</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
