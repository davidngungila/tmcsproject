@extends('layouts.app')

@section('title', 'Add New User - TmcsSmart')
@section('page-title', 'Create User Account')
@section('breadcrumb', 'TmcsSmart / Administration / Users / Create')

@section('content')
<div class="animate-in max-w-2xl mx-auto">
    <div class="card shadow-sm border-none overflow-hidden">
        <div class="card-header border-b p-6 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-gray-800 tracking-tight">User Details</h3>
                <p class="text-xs text-muted font-bold uppercase tracking-widest mt-1">Register a new system user</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm">Back to List</a>
        </div>
        <div class="card-body p-8">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter full name" required>
                    @error('name') <div class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Email Address *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
                        @error('email') <div class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0XXXXXXXXX">
                        @error('phone') <div class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">User Role *</label>
                    <select name="role" class="form-control select2 @error('role') is-invalid @enderror" required>
                        <option value="">Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Password *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('password') <div class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost px-8">Cancel</a>
                    <button type="submit" class="btn btn-primary px-10 py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
