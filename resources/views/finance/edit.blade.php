@extends('layouts.app')

@section('title', 'Edit Contribution - TmcsSmart')
@section('page-title', 'Edit Contribution')
@section('breadcrumb', 'TmcsSmart / Finance / Edit')

@section('content')
<div class="animate-in">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit Contribution</div>
        </div>
        <div class="card-body">
            <form action="{{ route('finance.update', ['finance' => $contribution->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="member_id">Member</label>
                    <select name="member_id" id="member_id" class="form-control" required>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $contribution->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }} ({{ $member->registration_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contribution_type">Contribution Type</label>
                    <select name="contribution_type" id="contribution_type" class="form-control" required>
                        <option value="almsgiving" {{ $contribution->contribution_type == 'almsgiving' ? 'selected' : '' }}>Almsgiving/Zaka</option>
                        <option value="tithe" {{ $contribution->contribution_type == 'tithe' ? 'selected' : '' }}>Tithe</option>
                        <option value="offering" {{ $contribution->contribution_type == 'offering' ? 'selected' : '' }}>Offering</option>
                        <option value="special_donation" {{ $contribution->contribution_type == 'special_donation' ? 'selected' : '' }}>Special Donation</option>
                    </select>
                    @error('contribution_type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount (TZS)</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $contribution->amount) }}" required min="0">
                    @error('amount')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contribution_date">Contribution Date</label>
                    <input type="date" name="contribution_date" id="contribution_date" class="form-control" value="{{ old('contribution_date', $contribution->contribution_date->format('Y-m-d')) }}" required>
                    @error('contribution_date')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="cash" {{ $contribution->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ $contribution->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="mobile_money" {{ $contribution->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="stripe" {{ $contribution->payment_method == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="click_pesa" {{ $contribution->payment_method == 'click_pesa' ? 'selected' : '' }}>Click Pesa</option>
                    </select>
                    @error('payment_method')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $contribution->notes) }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('finance.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Contribution</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection