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

        @keyframes glowSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .glow-spin { animation: glowSpin 22s linear infinite; }

        @media (prefers-reduced-motion: reduce) {
            .rise-in, .glow-spin { animation: none !important; opacity: 1 !important; transform: none !important; }
        }

        input:-webkit-autofill, select:-webkit-autofill { -webkit-box-shadow: 0 0 0 1000px #f5faf7 inset; -webkit-text-fill-color: #0a1a12; }

        .group-selection::-webkit-scrollbar { width: 6px; }
        .group-selection::-webkit-scrollbar-thumb { background: #c6e8d7; border-radius: 999px; }
    </style>
</head>
<body class="min-h-full bg-mist-50 text-ink-900">

    <!-- top brand strip -->
    <header class="relative overflow-hidden bg-gradient-to-br from-pine-950 via-pine-900 to-pine-800 px-6 py-10 text-white sm:px-10">
        <div class="pointer-events-none absolute -top-24 -left-16 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl glow-spin"></div>
        <div class="pointer-events-none absolute -bottom-20 right-[-4rem] h-64 w-64 rounded-full bg-gold-400/10 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="rise-in flex items-center gap-3" style="animation-delay:.05s">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15 backdrop-blur-sm font-display font-extrabold text-base text-gold-300">
                    TM
                </div>
                <div class="leading-tight">
                    <p class="text-[15px] font-bold tracking-wide">TmcsSmart</p>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-emerald-300/80">Church Management System</p>
                </div>
            </div>

            <p class="rise-in mt-8 text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-300/90" style="animation-delay:.15s">Become a member</p>
            <h1 class="rise-in font-display mt-2 text-3xl font-semibold text-white sm:text-4xl" style="animation-delay:.25s">Join TmcsSmart</h1>
            <p class="rise-in mt-3 max-w-lg text-[15px] leading-relaxed text-mist-100/80" style="animation-delay:.35s">
                Register yourself to our church management system to stay connected with your community, groups, and services.
            </p>
        </div>
    </header>

    <!-- registration card -->
    <main class="relative flex justify-center px-4 py-10 sm:px-6">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(5,150,105,0.05),_transparent_45%),radial-gradient(circle_at_bottom_left,_rgba(224,178,92,0.05),_transparent_40%)]"></div>

        <div class="relative w-full max-w-3xl">
            <div class="rise-in overflow-hidden rounded-2xl border border-emerald-100 bg-white shadow-lg shadow-pine-900/5" style="animation-delay:.2s">
                <div class="p-6 sm:p-10">
                    <form method="POST" action="{{ route('register.post') }}" x-data="{ loading: false }" @submit="loading = true">
                        @csrf

                        <!-- Section: Personal details -->
                        <p class="mb-5 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-600">
                            <i class="fa-solid fa-id-card"></i> Personal details
                        </p>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Full name</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="Your full name">
                                </div>
                                @error('full_name') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Email address</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="email@example.com">
                                </div>
                                @error('email') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Phone number</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="e.g. 0712345678">
                                </div>
                                @error('phone') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Category</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </span>
                                    <select name="category_id" required
                                        class="w-full appearance-none rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-10 text-pine-900 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                          <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </span>
                                </div>
                                @error('category_id') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Gender</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-venus-mars"></i>
                                    </span>
                                    <select name="gender" required
                                        class="w-full appearance-none rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-10 text-pine-900 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                        <option value="">Select gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </span>
                                </div>
                                @error('gender') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Date of birth</label>
                                <div class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-cake-candles"></i>
                                    </span>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                </div>
                                @error('date_of_birth') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="mb-2 block text-sm font-semibold text-pine-900">Home address</label>
                            <div class="group relative">
                                <span class="absolute left-0 top-3 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <textarea name="address" rows="2" required
                                    class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-4 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="Street, Ward, District...">{{ old('address') }}</textarea>
                            </div>
                            @error('address') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                        </div>

                        <!-- Section: Community -->
                        <p class="mb-4 mt-9 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-600">
                            <i class="fa-solid fa-people-group"></i> Community
                        </p>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-pine-900">Join communities / groups</label>
                            <p class="mb-3 text-xs text-ink-600">Select any existing community you wish to join.</p>
                            <div class="group-selection max-h-48 overflow-y-auto rounded-xl border-2 border-mist-100 bg-mist-50 p-2">
                                @foreach($groups as $group)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 transition-colors hover:bg-white">
                                    <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                        {{ is_array(old('groups')) && in_array($group->id, old('groups')) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-2 border-mist-100 text-emerald-600 focus:ring-emerald-500/30">
                                    <div>
                                        <p class="text-sm font-semibold text-pine-900">{{ $group->name }}</p>
                                        <p class="text-xs text-ink-600">{{ $group->type }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section: Security -->
                        <p class="mb-5 mt-9 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-600">
                            <i class="fa-solid fa-shield-halved"></i> Account security
                        </p>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Password</label>
                                <div x-data="{ show: false }" class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input :type="show ? 'text' : 'password'" name="password" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-12 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="Min 8 characters">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400 transition-colors hover:text-emerald-600">
                                        <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password') <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-pine-900">Confirm password</label>
                                <div x-data="{ show: false }" class="group relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-ink-400 transition-colors group-focus-within:text-emerald-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                        class="w-full rounded-xl border-2 border-mist-100 bg-mist-50 py-3 pl-12 pr-12 text-pine-900 placeholder-ink-400 transition-all focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="Repeat password">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ink-400 transition-colors hover:text-emerald-600">
                                        <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" :disabled="loading"
                            class="mt-9 flex w-full items-center justify-center gap-2 rounded-xl bg-pine-900 py-3.5 font-semibold text-white shadow-lg shadow-pine-900/10 transition-all hover:bg-emerald-600 hover:shadow-xl hover:shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-70">
                            <span x-show="!loading">Register my account</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin"></i> Creating account&hellip;
                            </span>
                        </button>
                    </form>
                </div>

                <div class="border-t border-mist-100 bg-mist-50 px-6 py-5 text-center sm:px-10">
                    <p class="text-sm text-ink-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700">Sign in here</a>
                    </p>
                </div>
            </div>

            <p class="rise-in mt-8 text-center text-xs text-ink-400" style="animation-delay:.3s">
                &copy; {{ date('Y') }} TmcsSmart Church Management System. All rights reserved.
            </p>
        </div>
    </main>
</body>
</html>