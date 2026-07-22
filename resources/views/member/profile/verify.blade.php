@extends('layouts.app')

@section('title', 'Verify Phone Number')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 sm:p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Verify Your Phone Number</h1>
                <p class="mt-2 text-gray-600">Enter the 6-digit code sent to your phone number</p>
                <p class="mt-1 text-sm text-gray-500">{{ $user->phone }}</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('member.profile.verify.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input 
                        type="text" 
                        name="otp" 
                        id="otp" 
                        maxlength="6" 
                        pattern="[0-9]{6}"
                        required
                        class="w-full text-center text-2xl tracking-widest px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 uppercase"
                        placeholder="000000"
                        autofocus
                    >
                    <p class="mt-2 text-sm text-gray-500">The code expires in 10 minutes.</p>
                </div>

                <button type="submit" class="w-full bg-emerald-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Verify Phone Number
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Didn't receive the code?</p>
                <form action="{{ route('member.profile.resend-otp') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-medium">
                        Resend Code
                    </button>
                </form>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('member.profile.edit') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                    ← Back to Profile
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    
    // Auto-format OTP input
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
    
    // Auto-submit when 6 digits entered
    otpInput.addEventListener('keyup', function(e) {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
});
</script>
@endsection
