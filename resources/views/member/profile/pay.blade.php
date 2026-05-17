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
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="mobile_money" class="peer hidden" checked onchange="togglePhoneField(true)">
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

                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="card" class="peer hidden" onchange="togglePhoneField(false)">
                  <div class="p-4 border-2 border-light rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex-center text-blue-600 shadow-sm">
                      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                      <div class="text-xs font-bold">Bank Card</div>
                      <div class="text-[9px] text-muted">Visa, Mastercard</div>
                    </div>
                    <div class="ml-auto opacity-0 peer-checked:opacity-100 text-blue-500">
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
                        <input type="text" name="phone_number" id="phoneNumber" class="form-control pl-12" value="{{ $member->phone }}" placeholder="e.g. 07XXXXXXXX">
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
                You will be redirected to the secure Snipe payment gateway. For mobile money, a USSD prompt will be sent to <strong>{{ $member->phone }}</strong>.
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

<!-- PAYMENT SPLASH OVERLAY (Centered Modal with Blur) -->
<div id="paymentSplash" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-md transition-all duration-300" style="display: none !important;">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 text-center animate-in zoom-in duration-300 border border-white/20">
        <div id="progressWrapper" class="mb-8 relative">
            <svg class="w-32 h-32 mx-auto transform -rotate-90">
                <circle cx="64" cy="64" r="60" stroke="#f3f4f6" stroke-width="8" fill="transparent" />
                <circle id="progressCircle" cx="64" cy="64" r="60" stroke="#059669" stroke-width="10" fill="transparent"
                    stroke-dasharray="376.99" stroke-dashoffset="376.99" stroke-linecap="round" style="transition: stroke-dashoffset 0.5s cubic-bezier(0.4, 0, 0.2, 1);" />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <span id="progressText" class="text-3xl font-black text-green-600 tracking-tighter">0%</span>
            </div>
        </div>

        <div id="successIcon" class="mb-8 hidden">
            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

        <h2 id="statusTitle" class="text-2xl font-black text-gray-900 mb-2">Initiating Payment</h2>
        <p id="statusDesc" class="text-sm text-gray-500 mb-8 font-medium">Connecting to secure gateway...</p>

        <div class="space-y-4 max-w-xs mx-auto">
            <div class="step-item flex items-center gap-4 text-left">
                <div id="step1" class="w-8 h-8 rounded-full border-2 border-green-500 bg-green-500 flex items-center justify-center text-white shadow-lg shadow-green-100 transition-all">
                    <span class="text-xs font-black">1</span>
                </div>
                <span id="step1Text" class="text-xs font-bold text-gray-900">Validating transaction details</span>
            </div>
            <div id="step2Wrapper" class="step-item flex items-center gap-4 text-left opacity-30">
                <div id="step2" class="w-8 h-8 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 transition-all">
                    <span class="text-xs font-black">2</span>
                </div>
                <span id="step2Text" class="text-xs font-medium text-gray-400">Requesting USSD Session</span>
            </div>
            <div id="step3Wrapper" class="step-item flex items-center gap-4 text-left opacity-30">
                <div id="step3" class="w-8 h-8 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 transition-all">
                    <span class="text-xs font-black">3</span>
                </div>
                <span id="step3Text" class="text-xs font-medium text-gray-400">Waiting for confirmation</span>
            </div>
        </div>

        <div id="countdownArea" class="mt-10 p-4 bg-red-50 rounded-2xl border border-red-100" style="display: none;">
            <div class="text-[10px] uppercase font-black text-red-400 mb-1 tracking-widest">Action Required</div>
            <div class="text-xs text-red-600 font-bold mb-2">Enter PIN on your phone now</div>
            <div id="timer" class="text-3xl font-mono font-black text-red-500">01:00</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePhoneField(show) {
    const fields = document.getElementById('mobileMoneyFields');
    if (show) {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}

function useSavedMethod(phone) {
    const phoneInput = document.getElementById('phoneNumber');
    const mobileRadio = document.querySelector('input[name="payment_method"][value="mobile_money"]');
    
    mobileRadio.checked = true;
    togglePhoneField(true);
    phoneInput.value = phone;
    
    // Pulse animation to show it was updated
    phoneInput.classList.add('ring-2', 'ring-green-500');
    setTimeout(() => phoneInput.classList.remove('ring-2', 'ring-green-500'), 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    const splash = document.getElementById('paymentSplash');
    const form = document.getElementById('paymentForm');
    
    // Ensure splash is hidden on load
    splash.classList.add('hidden');
    splash.style.display = 'none';

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const circle = document.getElementById('progressCircle');
            const text = document.getElementById('progressText');
            const title = document.getElementById('statusTitle');
            const desc = document.getElementById('statusDesc');
            const step2 = document.getElementById('step2');
            const step2Wrapper = document.getElementById('step2Wrapper');
            const step3 = document.getElementById('step3');
            const step3Wrapper = document.getElementById('step3Wrapper');
            const step2Text = document.getElementById('step2Text');
            const step3Text = document.getElementById('step3Text');
            const countdownArea = document.getElementById('countdownArea');
            const timerDisplay = document.getElementById('timer');
            const progressWrapper = document.getElementById('progressWrapper');
            const successIcon = document.getElementById('successIcon');

            // Show Splash
            splash.classList.remove('hidden');
            splash.style.display = 'flex';
            
            let progress = 0;
            const circumference = 376.99;
            let contributionId = null;
            let pollingInterval = null;
            
            const updateProgress = (val) => {
                const offset = circumference - (val / 100 * circumference);
                circle.style.strokeDashoffset = offset;
                text.textContent = Math.round(val) + '%';
            };

            const markStepComplete = (stepEl, textEl) => {
                stepEl.classList.replace('border-gray-300', 'border-green-500');
                stepEl.classList.add('bg-green-500', 'text-white');
                stepEl.classList.remove('text-gray-400');
                textEl.classList.replace('text-gray-400', 'text-gray-900');
                textEl.classList.add('font-bold');
            };

            // PHASE 1: Validating
            const phase1 = setInterval(() => {
                progress += 2;
                updateProgress(progress);
                if (progress >= 30) {
                    clearInterval(phase1);
                    
                    // PHASE 2: Gateway
                    title.textContent = "Connecting Gateway";
                    desc.textContent = "Requesting secure payment channel...";
                    step2Wrapper.classList.remove('opacity-40');
                    markStepComplete(step2, step2Text);

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.error || 'Payment failed.');
                        
                        contributionId = data.contribution_id;

                        if (data.checkout_url) {
                            updateProgress(100);
                            window.location.href = data.checkout_url;
                            return;
                        }

                        // PHASE 3: USSD Push
                        const finishPhase2 = setInterval(() => {
                            progress += 2;
                            updateProgress(progress);
                            if (progress >= 70) {
                                clearInterval(finishPhase2);
                                title.textContent = "Check Your Phone";
                                desc.textContent = "USSD Push sent to {{ $member->phone }}";
                                step3Wrapper.classList.remove('opacity-40');
                                markStepComplete(step3, step3Text);
                                countdownArea.style.display = 'block';

                                // Start Polling & Countdown
                                startPolling(contributionId);
                                startCountdown();
                            }
                        }, 50);
                    })
                    .catch(error => {
                        alert(error.message);
                        splash.style.display = 'none';
                        splash.classList.add('hidden');
                    });
                }
            }, 50);

            function startPolling(id) {
                pollingInterval = setInterval(() => {
                    fetch(`/member/payment-status/${id}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.is_verified) {
                            handleSuccess();
                        }
                    });
                }, 3000);
            }

            function handleSuccess() {
                if (pollingInterval) clearInterval(pollingInterval);
                updateProgress(100);
                progressWrapper.classList.add('hidden');
                successIcon.classList.remove('hidden');
                title.textContent = "Payment Successful!";
                desc.textContent = "God bless you for your contribution.";
                countdownArea.style.display = 'none';
                
                setTimeout(() => {
                    window.location.href = "{{ route('member.contributions.index') }}";
                }, 3000);
            }

            function startCountdown() {
                let seconds = 60;
                const countdown = setInterval(() => {
                    seconds--;
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    
                    if (progress < 95) {
                        progress += 0.4;
                        updateProgress(progress);
                    }

                    if (seconds <= 0) {
                        clearInterval(countdown);
                        if (pollingInterval) clearInterval(pollingInterval);
                        
                        title.textContent = "Processing...";
                        desc.textContent = "Taking you to your contributions history...";
                        setTimeout(() => {
                            window.location.href = "{{ route('member.contributions.index') }}";
                        }, 2000);
                    }
                }, 1000);
            }
        });
    }
});
</script>
@endpush
