@extends('layouts.app')

@section('title', 'Make Payment - TMCS Smart')
@section('page-title', 'Make Payment')
@section('breadcrumb', 'Home / Member / Pay')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- PAYMENT FORM -->
    <div class="lg:col-span-2">
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Mobile Money Payment</div>
          <div class="card-subtitle">Securely contribute using M-Pesa, Tigo Pesa, or Airtel Money</div>
        </div>
        <div class="card-body">
          <form id="paymentForm" action="{{ route('member.profile.process-payment') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="form-group">
                <label class="form-label text-xs font-bold">Contribution Type *</label>
                <select name="contribution_type" class="form-control" required>
                  <option value="">Select Purpose</option>
                  <option value="Tithe">Tithe (1/10)</option>
                  <option value="Offering">General Offering</option>
                  <option value="Special">Special Projects</option>
                  <option value="Harvest">Harvest Thanksgiving</option>
                </select>
                @error('contribution_type') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="form-label text-xs font-bold">Amount (TZS) *</label>
                <div class="relative">
                  <input type="number" name="amount" id="amount" class="form-control pl-12" value="{{ old('amount', 1000) }}" min="908" max="3000000" required>
                  <div class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted border-r pr-2">TZS</div>
                </div>
                @error('amount') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
                <p class="text-[9px] text-muted mt-1">Minimum: 908 TZS | Maximum: 3,000,000 TZS</p>
              </div>
            </div>

            <div class="form-group mb-6">
              <label class="form-label text-xs font-bold mb-4 block">Payment Method *</label>
              <div class="grid grid-cols-1 gap-4">
                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="mobile_money" class="peer hidden" checked>
                  <div class="p-4 border-2 border-light rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex-center text-green-600 shadow-sm">
                      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                      <div class="text-xs font-bold">Mobile Money</div>
                      <div class="text-[9px] text-muted">M-Pesa, Tigo Pesa, Airtel Money</div>
                    </div>
                    <div class="ml-auto opacity-0 peer-checked:opacity-100 text-green-500">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- PHONE NUMBER -->
            <div class="form-group mb-6">
              <label class="form-label text-xs font-bold">Phone Number *</label>
              <div class="relative">
                <input type="text" name="phone_number" id="phoneNumber" class="form-control pl-12" value="{{ old('phone_number', $member->phone) }}" placeholder="e.g. 07XXXXXXXX" required>
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted border-r pr-2">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.315l-.228 1.14a1 1 0 01-.779.79l-1.888.317a1 1 0 00-.76.94v2a1 1 0 00.76.94l1.888.317a1 1 0 01.779.79l.228 1.14a1 1 0 01-1.94.315H5a2 2 0 01-2-2V5z"/></svg>
                </div>
              </div>
              <p class="text-[9px] text-muted mt-2">USSD prompt will be sent to this number</p>
              @error('phone_number') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- INFO BOX -->
            <div class="flex items-center gap-3 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 mb-8">
              <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex-center flex-shrink-0">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <p class="text-[10px] text-blue-800 leading-relaxed">
                After clicking "Pay Now", you will receive a USSD prompt on your phone. Enter your PIN to complete the payment.
              </p>
            </div>

            <div class="flex gap-3">
              <a href="{{ route('member.contributions.index') }}" class="btn btn-secondary flex-1 py-3 text-sm font-bold">Cancel</a>
              <button type="submit" id="submitBtn" class="btn btn-primary flex-1 py-3 text-sm font-bold shadow-lg shadow-green-200">
                Pay Now
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- RECENT HISTORY SIDEBAR -->
    <div class="lg:col-span-1 space-y-6">
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm">Recent Payments</div>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-50">
            @forelse($member->contributions()->latest()->limit(5)->get() as $c)
              <div class="p-4 flex items-center justify-between">
                <div>
                  <div class="text-xs font-bold">{{ $c->contribution_type }}</div>
                  <div class="text-[10px] text-muted">{{ $c->contribution_date->format('d M, Y') }}</div>
                </div>
                <div class="text-xs font-bold text-right">
                  <div>{{ number_format($c->amount) }}</div>
                  <div class="text-[9px] {{ $c->is_verified ? 'text-green-500' : 'text-amber-500' }}">
                    {{ $c->is_verified ? 'Verified' : ($c->feedtan_status ?? 'Pending') }}
                  </div>
                </div>
              </div>
            @empty
              <div class="p-8 text-center text-xs text-muted">No recent payments.</div>
            @endforelse
          </div>
        </div>
        <div class="card-footer p-3 bg-light/30 border-t text-center">
          <a href="{{ route('member.contributions.index') }}" class="text-[10px] font-bold text-blue-500 hover:underline">View Full History</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate amount
            const amount = parseFloat(document.getElementById('amount').value);
            if (amount < 908) {
                Swal.fire({
                    title: 'Invalid Amount',
                    text: 'Minimum payment amount is 908 TZS',
                    icon: 'error',
                    confirmButtonColor: '#059669'
                });
                return;
            }

            if (amount > 3000000) {
                Swal.fire({
                    title: 'Invalid Amount',
                    text: 'Maximum payment amount is 3,000,000 TZS',
                    icon: 'error',
                    confirmButtonColor: '#059669'
                });
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin inline-block mr-2">⟳</span> Processing...';

            // Show SweetAlert loading
            Swal.fire({
                title: 'Initiating Payment',
                text: 'Please wait while we process your payment...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Payment failed.');
                
                const contributionId = data.contribution_id;
                const phoneNumber = document.getElementById('phoneNumber').value;

                // Show USSD sent with countdown and progress stages
                let countdown = 60;
                let currentStage = 2; // Start at stage 2 (USSD sent)
                
                const stages = [
                    { id: 1, name: 'Initiating Payment', icon: 'fa-spinner fa-spin', completed: true },
                    { id: 2, name: 'USSD Push Sent', icon: 'fa-paper-plane', completed: true },
                    { id: 3, name: 'Waiting for PIN', icon: 'fa-mobile-screen', completed: false },
                    { id: 4, name: 'Verifying Payment', icon: 'fa-circle-check', completed: false },
                    { id: 5, name: 'Payment Complete', icon: 'fa-check-double', completed: false }
                ];
                
                function getStagesHtml(stage) {
                    return stages.map(s => {
                        const isCurrent = s.id === stage;
                        const isCompleted = s.completed || s.id < stage;
                        const color = isCompleted ? 'text-green-500' : (isCurrent ? 'text-blue-500' : 'text-gray-300');
                        const bg = isCompleted ? 'bg-green-100' : (isCurrent ? 'bg-blue-100' : 'bg-gray-100');
                        
                        return `
                            <div class="flex items-center gap-3 ${isCurrent ? 'opacity-100' : 'opacity-60'}">
                                <div class="w-8 h-8 rounded-full ${bg} ${color} flex items-center justify-center">
                                    <i class="fa-solid ${s.icon} text-sm"></i>
                                </div>
                                <span class="text-sm font-medium ${isCompleted ? 'text-green-700' : (isCurrent ? 'text-blue-700' : 'text-gray-500')}">${s.name}</span>
                            </div>
                        `;
                    }).join('');
                }
                
                Swal.fire({
                    title: 'Payment Initiated',
                    html: `
                        <div class="text-left">
                            <div class="mb-6 space-y-3">
                                ${getStagesHtml(currentStage)}
                            </div>
                            
                            <div class="text-center mt-6 pt-6 border-t border-gray-200">
                                <p class="mb-2 text-sm font-medium text-gray-700">USSD Push sent to ${phoneNumber}</p>
                                <p class="mb-4 text-xs text-gray-500">Please enter your PIN on your phone to complete the payment</p>
                                
                                <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                                    <svg class="progress-ring" width="120" height="120" style="transform: rotate(-90deg);">
                                        <circle class="progress-ring__circle-bg" stroke="#e5e7eb" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                        <circle class="progress-ring__circle" stroke="#059669" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                            style="stroke-dasharray: 326.726; stroke-dashoffset: 326.726; transition: stroke-dashoffset 1s linear;"/>
                                    </svg>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 28px; font-weight: bold; color: #059669;">
                                        ${countdown}
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-center gap-2 mt-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-gray-600">Checking payment status...</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Auto-redirecting in ${countdown} seconds</p>
                            </div>
                        </div>
                    `,
                    icon: null,
                    showConfirmButton: false,
                    timer: null,
                    allowOutsideClick: false,
                    width: '450px'
                });

                const circle = document.querySelector('.progress-ring__circle');
                const circumference = 326.726;

                // Countdown timer with circular progress
                const countdownInterval = setInterval(() => {
                    countdown--;
                    const offset = circumference - ((60 - countdown) / 60) * circumference;
                    circle.style.strokeDashoffset = offset;
                    
                    // Update stages based on countdown
                    if (countdown <= 45 && currentStage === 2) {
                        currentStage = 3;
                        stages[2].completed = true;
                    } else if (countdown <= 30 && currentStage === 3) {
                        currentStage = 4;
                        stages[3].completed = true;
                    }
                    
                    Swal.update({
                        html: `
                            <div class="text-left">
                                <div class="mb-6 space-y-3">
                                    ${getStagesHtml(currentStage)}
                                </div>
                                
                                <div class="text-center mt-6 pt-6 border-t border-gray-200">
                                    <p class="mb-2 text-sm font-medium text-gray-700">USSD Push sent to ${phoneNumber}</p>
                                    <p class="mb-4 text-xs text-gray-500">Please enter your PIN on your phone to complete the payment</p>
                                    
                                    <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                                        <svg class="progress-ring" width="120" height="120" style="transform: rotate(-90deg);">
                                            <circle class="progress-ring__circle-bg" stroke="#e5e7eb" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                            <circle class="progress-ring__circle" stroke="#059669" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                                style="stroke-dasharray: 326.726; stroke-dashoffset: ${offset}; transition: stroke-dashoffset 1s linear;"/>
                                        </svg>
                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 28px; font-weight: bold; color: #059669;">
                                            ${countdown}
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-center gap-2 mt-3">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-gray-600">Checking payment status...</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Auto-redirecting in ${countdown} seconds</p>
                                </div>
                            </div>
                        `
                    });
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        clearInterval(pollingInterval);
                        currentStage = 5;
                        stages[4].completed = true;
                        
                        Swal.fire({
                            title: 'Payment Pending',
                            html: `
                                <div class="text-left">
                                    <div class="mb-6 space-y-3">
                                        ${getStagesHtml(currentStage)}
                                    </div>
                                    <p class="text-sm text-gray-600 text-center mt-4">Your payment is being processed. You can check the status in your payment history.</p>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonColor: '#059669',
                            width: '450px'
                        }).then(() => {
                            window.location.href = "{{ route('member.contributions.index') }}";
                        });
                    }
                }, 1000);

                // Poll for payment status
                let pollingInterval = setInterval(() => {
                    fetch(`/member/payment-status/${contributionId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.is_verified) {
                            clearInterval(countdownInterval);
                            clearInterval(pollingInterval);
                            currentStage = 5;
                            stages[4].completed = true;
                            
                            Swal.fire({
                                title: 'Payment Successful!',
                                html: `
                                    <div class="text-left">
                                        <div class="mb-6 space-y-3">
                                            ${getStagesHtml(currentStage)}
                                        </div>
                                        <p class="text-sm text-gray-600 text-center mt-4">Thank you for your contribution. God bless you!</p>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                timer: 2000,
                                showConfirmButton: false,
                                width: '450px'
                            }).then(() => {
                                window.location.href = "{{ route('member.contributions.show', ['contribution' => ':id']) }}".replace(':id', contributionId);
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Polling error:', err);
                    });
                }, 3000);

            })
            .catch(error => {
                console.error('Payment error:', error);
                Swal.fire({
                    title: 'Payment Failed',
                    text: error.message || 'Payment failed. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#059669'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Pay Now';
            });
        });
    }
});
</script>
@endpush
