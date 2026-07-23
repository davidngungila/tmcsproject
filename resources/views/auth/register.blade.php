<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TmcsSmart</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Google Fonts: Sora (display) + DM Sans (body/UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine:    { 950:'#021c13', 900:'#042f1e', 800:'#064e3b', 700:'#065f46' },
                        emerald: { 600:'#047857', 500:'#059669', 400:'#10b981' },
                        gold:    { 400:'#e0b25c', 300:'#ecc988', 100:'#faf1de' },
                        ink:     { 900:'#0a1a12', 600:'#3d6b54', 400:'#7ecfa0' },
                        mist:    { 50:'#f5faf7', 100:'#ecfdf5' },
                    },
                    fontFamily: {
                        display: ['Sora', 'ui-sans-serif', 'sans-serif'],
                        sans: ['DM Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }

        @keyframes riseIn {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .rise-in { opacity: 0; animation: riseIn 0.7s cubic-bezier(.22,.61,.36,1) forwards; }

        @keyframes grainFloat {
            0%   { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
            10%  { opacity: .55; }
            85%  { opacity: .4; }
            100% { transform: translateY(-140px) translateX(var(--drift,10px)) rotate(45deg); opacity: 0; }
        }
        .grain { position: absolute; animation: grainFloat linear infinite; }

        @keyframes glowSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .glow-spin { animation: glowSpin 22s linear infinite; }

        @keyframes shimmer {
            0%, 100% { opacity: .5; }
            50% { opacity: 1; }
        }
        .shimmer { animation: shimmer 3.2s ease-in-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .rise-in, .grain, .glow-spin, .shimmer { animation: none !important; opacity: 1 !important; transform: none !important; }
        }

        input:-webkit-autofill { -webkit-box-shadow: 0 0 0 1000px #f5faf7 inset; -webkit-text-fill-color: #0a1a12; }
    </style>
</head>
<body class="h-full bg-white text-ink-900">
    <div class="h-full lg:grid lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">

        <!-- LEFT: Brand panel -->
        <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-pine-950 via-pine-900 to-pine-800 px-14 py-12 text-white">

            <!-- ambient glow -->
            <div class="pointer-events-none absolute -top-32 -left-24 h-[26rem] w-[26rem] rounded-full bg-emerald-500/20 blur-3xl glow-spin"></div>
            <div class="pointer-events-none absolute bottom-[-8rem] right-[-6rem] h-[22rem] w-[22rem] rounded-full bg-gold-400/10 blur-3xl"></div>

            <!-- drifting particles -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <i class="grain fa-solid fa-dove text-gold-300/60 text-sm" style="left:12%; bottom:-5%; --drift:18px; animation-duration:9s; animation-delay:0s;"></i>
                <i class="grain fa-solid fa-star text-gold-300/50 text-xs" style="left:28%; bottom:-8%; --drift:-14px; animation-duration:12s; animation-delay:1.4s;"></i>
                <i class="grain fa-solid fa-leaf text-emerald-400/40 text-sm" style="left:47%; bottom:-6%; --drift:10px; animation-duration:10.5s; animation-delay:3s;"></i>
                <i class="grain fa-solid fa-star text-gold-300/60 text-base" style="left:63%; bottom:-10%; --drift:-20px; animation-duration:13s; animation-delay:.7s;"></i>
                <i class="grain fa-solid fa-dove text-emerald-400/30 text-xs" style="left:78%; bottom:-4%; --drift:16px; animation-duration:11s; animation-delay:4.2s;"></i>
                <i class="grain fa-solid fa-star text-gold-300/40 text-sm" style="left:89%; bottom:-9%; --drift:-10px; animation-duration:9.5s; animation-delay:2.1s;"></i>
            </div>

            <!-- brand mark -->
            <div class="relative z-10 rise-in" style="animation-delay:.05s">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15 backdrop-blur-sm font-display font-extrabold text-lg text-gold-300">
                        TM
                    </div>
                    <div class="leading-tight">
                        <p class="text-[15px] font-bold tracking-wide">TmcsSmart</p>
                        <p class="text-[11px] uppercase tracking-[0.18em] text-emerald-300/80">Church Management System</p>
                    </div>
                </div>
            </div>

            <!-- headline block -->
            <div class="relative z-10 max-w-md">
                <p class="rise-in text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-300/90" style="animation-delay:.15s">Join our community</p>
                <h1 class="rise-in font-display mt-4 text-[2.6rem] leading-[1.1] font-semibold text-white" style="animation-delay:.25s">
                    Create your account<br> in seconds.
                </h1>
                <p class="rise-in mt-5 text-[15px] leading-relaxed text-mist-100/80" style="animation-delay:.4s">
                    Register with just your basic details. Complete your profile later to access all features.
                </p>

                <!-- feature rows -->
                <div class="rise-in mt-9 space-y-4" style="animation-delay:.55s">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300">
                            <i class="fa-solid fa-user-plus text-sm"></i>
                        </div>
                        <p class="text-sm text-mist-100/90">Quick registration process</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300">
                            <i class="fa-solid fa-sliders text-sm"></i>
                        </div>
                        <p class="text-sm text-mist-100/90">Complete profile at your convenience</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                        <p class="text-sm text-mist-100/90">Secure, session-based account access</p>
                    </div>
                </div>
            </div>

            <!-- footer stat -->
            <div class="relative z-10 rise-in flex items-center gap-6 border-t border-white/10 pt-6" style="animation-delay:.7s">
                <div>
                    <p class="font-display text-2xl font-semibold text-white">850<span class="text-gold-300">+</span></p>
                    <p class="text-[11px] uppercase tracking-wide text-mist-100/60">Churches onboard</p>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div>
                    <p class="font-display text-2xl font-semibold text-white">120k<span class="text-gold-300">+</span></p>
                    <p class="text-[11px] uppercase tracking-wide text-mist-100/60">Members managed</p>
                </div>
            </div>
        </aside>

        <!-- RIGHT: Register panel -->
        <main class="relative flex min-h-full items-center justify-center bg-white px-6 py-12 sm:px-10">

            <!-- soft mesh backdrop -->
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(5,150,105,0.06),_transparent_45%),radial-gradient(circle_at_bottom_left,_rgba(224,178,92,0.06),_transparent_40%)]"></div>

            <div x-data="{ 
                loading: false,
                step: 1,
                fullName: '{{ old('full_name') }}',
                email: '{{ old('email') }}',
                phone: '{{ old('phone') }}',
                password: '',
                passwordConfirmation: '',
                canProceed(step) {
                    if (step === 2) return this.fullName && this.fullName.length > 2;
                    if (step === 3) return this.email && this.email.includes('@');
                    if (step === 4) return this.phone && this.phone.length > 9;
                    if (step === 5) return this.password && this.password.length >= 8;
                    if (step === 6) return this.passwordConfirmation && this.passwordConfirmation === this.password;
                    return false;
                },
                nextStep() {
                    if (this.step < 6) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                }
            }" class="relative w-full max-w-sm">

                <!-- mobile-only brand mark -->
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-pine-900 font-display font-extrabold text-sm text-gold-300">
                        TM
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-bold text-pine-900">TmcsSmart</p>
                        <p class="text-[10px] uppercase tracking-[0.18em] text-ink-400">Church Management System</p>
                    </div>
                </div>

                <div class="rise-in" style="animation-delay:.1s">
                    <!-- Progress indicator -->
                    <div class="mb-6 flex items-center justify-center gap-2">
                        <template x-for="i in 5" :key="i">
                            <div class="h-1.5 w-8 rounded-full transition-all duration-300"
                                 :class="i <= step ? 'bg-emerald-500' : 'bg-mist-200'"></div>
                        </template>
                    </div>

                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Create account</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold text-pine-900">Register to get started</h2>
                    <p class="mt-2 text-sm text-ink-600">Enter your details to create your account.</p>
                </div>

                @if(session('success'))
                <div class="rise-in mt-6 flex items-start gap-2 rounded-xl border border-emerald-100 bg-mist-100 p-4 text-sm text-emerald-700" style="animation-delay:.15s">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('register.post') }}"
                    @submit="loading = true"
                    class="rise-in mt-8"
                    style="animation-delay:.2s"
                >
                    @csrf

                    <!-- Step 1: Full Name -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Full name</label>
                            <div class="group relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    name="full_name"
                                    x-model="fullName"
                                    required
                                    autofocus
                                    @keyup.enter="canProceed(2) && nextStep()"
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="Your full name"
                                >
                            </div>
                            @error('full_name')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <button
                            type="button"
                            @click="nextStep()"
                            :disabled="!canProceed(2)"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Continue
                        </button>
                    </div>

                    <!-- Step 2: Email -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Email address</label>
                            <div class="group relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    x-model="email"
                                    required
                                    @keyup.enter="canProceed(3) && nextStep()"
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="you@example.com"
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                @click="prevStep()"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-mist-200 py-3.5 font-semibold text-ink-600 transition-all hover:bg-mist-50 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98]"
                            >
                                Back
                            </button>
                            <button
                                type="button"
                                @click="nextStep()"
                                :disabled="!canProceed(3)"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Continue
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Phone -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Phone number</label>
                            <div class="group relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input
                                    type="text"
                                    name="phone"
                                    x-model="phone"
                                    required
                                    @keyup.enter="canProceed(4) && nextStep()"
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="e.g. 0712345678"
                                >
                            </div>
                            @error('phone')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                @click="prevStep()"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-mist-200 py-3.5 font-semibold text-ink-600 transition-all hover:bg-mist-50 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98]"
                            >
                                Back
                            </button>
                            <button
                                type="button"
                                @click="nextStep()"
                                :disabled="!canProceed(4)"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Continue
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Password -->
                    <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Password</label>
                            <div x-data="{ show: false }" class="group relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input
                                    :type="show ? 'text' : 'password'"
                                    name="password"
                                    x-model="password"
                                    required
                                    @keyup.enter="canProceed(5) && nextStep()"
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-12 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="Min 8 characters"
                                >
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400 transition-colors hover:text-emerald-600"
                                >
                                    <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                @click="prevStep()"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-mist-200 py-3.5 font-semibold text-ink-600 transition-all hover:bg-mist-50 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98]"
                            >
                                Back
                            </button>
                            <button
                                type="button"
                                @click="nextStep()"
                                :disabled="!canProceed(5)"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Continue
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Confirm Password -->
                    <div x-show="step === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                        <div class="mb-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Confirm password</label>
                            <div x-data="{ show: false }" class="group relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input
                                    :type="show ? 'text' : 'password'"
                                    name="password_confirmation"
                                    x-model="passwordConfirmation"
                                    required
                                    @keyup.enter="canProceed(6) && $root.loading = true"
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-12 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="Repeat password"
                                >
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400 transition-colors hover:text-emerald-600"
                                >
                                    <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                @click="prevStep()"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-mist-200 py-3.5 font-semibold text-ink-600 transition-all hover:bg-mist-50 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98]"
                            >
                                Back
                            </button>
                            <button
                                type="submit"
                                :disabled="!canProceed(6) || loading"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span x-show="!loading">Create account</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Creating&hellip;
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <p class="rise-in mt-8 text-center text-sm text-ink-600" style="animation-delay:.25s">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700">Sign in here</a>
                </p>

                <p class="rise-in mt-8 text-center text-xs text-ink-400" style="animation-delay:.3s">
                    &copy; {{ date('Y') }} TmcsSmart Church Management System. All rights reserved.
                </p>
            </div>
        </main>
    </div>
</body>
</html>