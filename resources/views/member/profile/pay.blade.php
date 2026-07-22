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
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Electronic Giving Portal</div>
          <div class="card-subtitle">Securely contribute to church funds using mobile money or card.</div>
        </div>
        <div class="card-body">
          <form id="paymentForm" action="{{ route('member.profile.process-payment') }}" method="POST">
            @csrf
            {{-- Form fields stay the same --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="form-group">
                <label class="form-label text-xs font-bold">Contribution Type *</label>
                <select name="contribution_type" class="form-control" required>
                  <option value="">Select Purpose</option>
                  <option value="Tithes">Tithes (1/10)</option>
                  <option value="Offerings">General Offering</option>
                  <option value="Building Fund">Building & Construction</option>
                  <option value="Community Fund">Small Christian Community</option>
                  <option value="Special Giving">Special Projects</option>
                  <option value="Almsgiving">Almsgiving (Charity)</option>
                </select>
                @error('contribution_type') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="form-label text-xs font-bold">Amount (TZS) *</label>
                <div class="relative">
                  <input type="number" name="amount" class="form-control pl-12" value="{{ old('amount', 1000) }}" min="500" required>
                  <div class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted border-r pr-2">TZS</div>
                </div>
                @error('amount') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group mb-8">
              <label class="form-label text-xs font-bold mb-4 block">Select Payment Channel *</label>
              <div class="grid grid-cols-1 gap-4">
                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="mobile_money" class="peer hidden" checked>
                  <div class="p-4 border-2 border-light rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex-center text-green-600 shadow-sm">
                      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                      <div class="text-xs font-bold">Mobile Money</div>
                      <div class="text-[9px] text-muted">M-Pesa, TigoPesa, AirtelMoney</div>
                    </div>
                    <div class="ml-auto opacity-0 peer-checked:opacity-100 text-green-500">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- DYNAMIC PHONE FIELD -->
            <div id="mobileMoneyFields" class="animate-in slide-in-from-top-4 duration-300">
                <div class="form-group mb-6">
                    <label class="form-label text-xs font-bold">Phone Number for USSD Prompt *</label>
                    <div class="relative">
                        <input type="text" name="phone_number" id="phoneNumber" class="form-control pl-12" value="{{ old('phone_number', $member->phone) }}" placeholder="e.g. 07XXXXXXXX">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted border-r pr-2">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.315l-.228 1.14a1 1 0 01-.779.79l-1.888.317a1 1 0 00-.76.94v2a1 1 0 00.76.94l1.888.317a1 1 0 01.779.79l.228 1.14a1 1 0 01-1.94.315H5a2 2 0 01-2-2V5z"/></svg>
                        </div>
                    </div>
                    <p class="text-[9px] text-muted mt-2">You can use your registered number or enter a different one.</p>
                </div>

                <div class="flex items-center gap-2 mb-8">
                    <input type="checkbox" name="save_method" id="saveMethod" value="1" class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="saveMethod" class="text-xs font-bold text-gray-700">Save this number for future use</label>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 mb-8">
              <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex-center flex-shrink-0">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <p class="text-[10px] text-blue-800 leading-relaxed">
                You will be redirected to the secure FeedTan payment gateway. For mobile money, a USSD prompt will be sent to <strong>{{ $member->phone }}</strong>.
              </p>
            </div>

            <div class="flex gap-3">
              <a href="{{ route('member.profile.index') }}" class="btn btn-secondary flex-1 py-3 text-sm font-bold">Cancel</a>
              <button type="submit" class="btn btn-primary flex-1 py-3 text-sm font-bold shadow-lg shadow-green-200">
                Confirm & Pay Now
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
                    {{ $c->is_verified ? 'Verified' : 'Pending' }}
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

      <!-- SAVED METHODS -->
      <div class="card">
        <div class="card-header border-b flex justify-between items-center">
          <div class="card-title text-sm">Saved Methods</div>
          <span class="px-2 py-0.5 bg-green-100 text-green-600 text-[8px] font-black uppercase rounded-full">New</span>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-50">
            @forelse($savedMethods as $method)
              <button type="button" onclick="useSavedMethod('{{ $method->identifier }}')" class="w-full p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors text-left group">
                <div class="w-10 h-10 rounded-xl bg-gray-100 flex-center text-gray-500 group-hover:bg-green-100 group-hover:text-green-600 transition-colors">
                  @if($method->type === 'mobile_money')
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                  @else
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                  @endif
                </div>
                <div>
                  <div class="text-xs font-bold text-gray-900">{{ $method->label ?? $method->provider }}</div>
                  <div class="text-[10px] text-muted mono">{{ $method->identifier }}</div>
                </div>
                <div class="ml-auto opacity-0 group-hover:opacity-100 text-green-500 transition-opacity">
                  <span class="text-[9px] font-bold">Use this</span>
                </div>
              </button>
            @empty
              <div class="p-8 text-center">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-2 opacity-20"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p class="text-[10px] text-muted">No saved methods yet.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function useSavedMethod(phone) {
    const phoneInput = document.getElementById('phoneNumber');
    
    phoneInput.value = phone;
    
    // Pulse animation to show it was updated
    phoneInput.classList.add('ring-2', 'ring-green-500');
    setTimeout(() => phoneInput.classList.remove('ring-2', 'ring-green-500'), 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin inline-block mr-2">⟳</span> Processing...';

            // Show SweetAlert loading
            Swal.fire({
                title: 'Initiating Payment',
                text: 'Validating transaction details...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(form);
            
            // Add timeout to fetch (3 minutes)
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 180000); // 3 minute timeout
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                signal: controller.signal
            })
            .then(async response => {
                clearTimeout(timeoutId);
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Payment failed.');
                
                const contributionId = data.contribution_id;
                const phoneNumber = document.getElementById('phoneNumber').value;

                // Update SweetAlert to show USSD sent with circular progress
                let countdown = 60;
                Swal.fire({
                    title: 'Check Your Phone',
                    html: `
                        <div class="text-center">
                            <p class="mb-4">USSD Push sent to ${phoneNumber}</p>
                            <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                                <svg class="progress-ring" width="120" height="120" style="transform: rotate(-90deg);">
                                    <circle class="progress-ring__circle-bg" stroke="#e5e7eb" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                    <circle class="progress-ring__circle" stroke="#059669" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                        style="stroke-dasharray: 326.726; stroke-dashoffset: 326.726; transition: stroke-dashoffset 1s linear;"/>
                                </svg>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 24px; font-weight: bold; color: #059669;">
                                    ${countdown}
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Redirecting in ${countdown} seconds...</p>
                        </div>
                    `,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: null
                });

                const circle = document.querySelector('.progress-ring__circle');
                const circumference = 326.726;

                // Countdown timer with circular progress (fills up as countdown decreases)
                const countdownInterval = setInterval(() => {
                    countdown--;
                    const offset = circumference - ((60 - countdown) / 60) * circumference;
                    circle.style.strokeDashoffset = offset;
                    
                    Swal.update({
                        html: `
                            <div class="text-center">
                                <p class="mb-4">USSD Push sent to ${phoneNumber}</p>
                                <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                                    <svg class="progress-ring" width="120" height="120" style="transform: rotate(-90deg);">
                                        <circle class="progress-ring__circle-bg" stroke="#e5e7eb" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                        <circle class="progress-ring__circle" stroke="#059669" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                            style="stroke-dasharray: 326.726; stroke-dashoffset: ${offset}; transition: stroke-dashoffset 1s linear;"/>
                                    </svg>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 24px; font-weight: bold; color: #059669;">
                                        ${countdown}
                                    </div>
                                </div>
                                <p class="mt-4 text-sm text-gray-500">Redirecting in ${countdown} seconds...</p>
                            </div>
                        `
                    });
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        window.location.href = "{{ route('member.contributions.index') }}";
                    }
                }, 1000);

                // Start polling for payment status in background
                let pollingInterval = setInterval(() => {
                    fetch(`/member/payment-status/${contributionId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.is_verified) {
                            clearInterval(countdownInterval);
                            clearInterval(pollingInterval);
                            Swal.fire({
                                title: 'Payment Successful!',
                                text: 'God bless you for your contribution.',
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                timer: 2000,
                                showConfirmButton: false
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
                clearTimeout(timeoutId);
                console.error('Payment error:', error);
                Swal.fire({
                    title: 'Error',
                    text: error.name === 'AbortError' ? 'Payment request timed out. Please try again.' : (error.message || 'Payment failed. Please try again.'),
                    icon: 'error',
                    confirmButtonColor: '#059669'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Confirm & Pay Now';
            });
        });
    }
});
</script>
@endpush
