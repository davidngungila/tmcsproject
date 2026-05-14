@extends('layouts.app')

@section('title', 'Account & Security - TmcsSmart')
@section('page-title', 'Account & Security')
@section('breadcrumb', 'TmcsSmart / Profile / Security')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN: PROFILE CARD -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-green-600 to-green-400"></div>
                <div class="card-body -mt-12 text-center">
                    <div class="relative inline-block mb-4">
                        <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg mx-auto overflow-hidden">
                            @if(auth()->user()->profile_image)
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                            @else
                                <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <button class="absolute bottom-0 right-0 w-8 h-8 bg-green-600 text-white rounded-full border-2 border-white flex items-center justify-center hover:bg-green-700 transition-all">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                        </button>
                    </div>
                    <h3 class="text-lg font-bold">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-muted mb-4">{{ auth()->user()->roles->first()->display_name ?? 'User' }}</p>
                    
                    <div class="flex items-center justify-center gap-2">
                        <span class="badge green">Active</span>
                        <span class="text-xs text-muted">Member since {{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
                <div class="border-t border-light p-4">
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-muted">Email Address</span>
                            <span class="font-bold">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-muted">Phone Number</span>
                            <span class="font-bold">{{ auth()->user()->phone ?? 'Not set' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-muted">Last Login</span>
                            <span class="font-bold">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'First session' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Account Status</h4>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold">Verified Account</div>
                                <div class="text-[10px] text-muted">Identity verified via email</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold">2FA Disabled</div>
                                <div class="text-[10px] text-muted">Add extra layer of security</div>
                            </div>
                            <button class="text-xs text-green-600 font-bold">Enable</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: FORMS -->
        <div class="lg:col-span-2 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-medium animate-in slide-in-from-top duration-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- PROFILE SETTINGS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personal Information</h3>
                    <p class="card-subtitle">Update your basic account details</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                            <p class="text-[10px] text-muted mt-1">Recommended: Square image, max 2MB (JPG, PNG, WebP)</p>
                            @error('profile_image') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PASSWORD SETTINGS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Security & Password</h3>
                    <p class="card-subtitle">Keep your account safe by using a strong password</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.security.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                            @error('current_password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DANGER ZONE -->
            <div class="card border-red-100 bg-red-50/10">
                <div class="card-header border-red-100">
                    <h3 class="card-title text-red-600">Danger Zone</h3>
                    <p class="card-subtitle">Irreversible actions for your account</p>
                </div>
                <div class="card-body flex items-center justify-between">
                    <div>
                        <div class="text-sm font-bold">Delete Account</div>
                        <div class="text-xs text-muted">Once deleted, your account and all data will be permanently removed.</div>
                    </div>
                    <button class="btn border-red-500 text-red-600 hover:bg-red-600 hover:text-white transition-all">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
