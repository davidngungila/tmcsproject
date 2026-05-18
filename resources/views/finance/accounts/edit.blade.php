@extends('layouts.app')

@section('title', 'Edit Account - TmcsSmart')
@section('page-title', 'Edit Account')
@section('breadcrumb', 'TmcsSmart / Finance / Accounts / Edit')

@section('content')
<div class="animate-in max-w-2xl">
    <div class="card shadow-sm border-none overflow-hidden">
        <div class="card-header border-b p-6 bg-white flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Update Account: {{ $account->name }}</h3>
            <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 transition-all">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
        <div class="card-body p-8">
            <form action="{{ route('accounts.update', $account->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $account->type }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Account Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}" placeholder="e.g. Church Main Account" required>
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Account Code *</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $account->code) }}" placeholder="e.g. 1100" required>
                        @error('code') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $account->bank_name) }}" placeholder="e.g. CRDB Bank">
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $account->account_number) }}" placeholder="e.g. 015XXXXXXXX">
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Branch Code</label>
                        <input type="text" name="branch_code" class="form-control" value="{{ old('branch_code', $account->branch_code) }}" placeholder="e.g. 011">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Current Balance (TZS) *</label>
                    <input type="number" name="balance" class="form-control" step="0.01" value="{{ old('balance', $account->balance) }}" required>
                    @error('balance') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                        <span class="text-xs font-black uppercase tracking-widest text-gray-400">Account is Active</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_default_income" value="0">
                        <input type="checkbox" name="is_default_income" value="1" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_default_income', $account->is_default_income) ? 'checked' : '' }}>
                        <span class="text-xs font-black uppercase tracking-widest text-gray-400">Default Income Account</span>
                    </label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="btn btn-primary px-10 py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">Update Account</button>
                    <a href="{{ route('finance.settings') }}" class="btn btn-ghost px-10 py-4 rounded-2xl font-black uppercase tracking-widest">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
