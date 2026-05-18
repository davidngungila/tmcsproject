@extends('layouts.app')

@section('title', 'Add Account - TmcsSmart')
@section('page-title', 'Add Account')
@section('breadcrumb', 'TmcsSmart / Finance / Accounts / Add')

@section('content')
<div class="animate-in max-w-2xl">
    <div class="card shadow-sm border-none overflow-hidden">
        <div class="card-header border-b p-6 bg-white">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Account Information</h3>
        </div>
        <div class="card-body p-8">
            <form action="{{ route('accounts.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Account Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. CRDB Bank" required>
                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Account Code *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. 1100" required>
                    <p class="text-[10px] text-muted font-bold mt-1 uppercase tracking-widest">Unique identifier for this account</p>
                    @error('code') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Initial Balance (TZS) *</label>
                    <input type="number" name="balance" class="form-control" step="0.01" value="{{ old('balance', 0) }}" required>
                    @error('balance') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="text-xs font-black uppercase tracking-widest text-gray-400">Account is Active</span>
                    </label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="btn btn-primary px-10 py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">Create Account</button>
                    <a href="{{ route('finance.settings') }}" class="btn btn-ghost px-10 py-4 rounded-2xl font-black uppercase tracking-widest">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
