<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IT Helpdesk') }} - Masuk</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-white">

        <!-- Panel kiri: branding / ilustrasi -->
        <div
            class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 p-12 text-white lg:flex">
            {{-- dekorasi lingkaran --}}
            <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-32 -right-16 h-96 w-96 rounded-full bg-white/10"></div>
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.15)_1px,transparent_0)] [background-size:24px_24px] opacity-30">
            </div>

            <a href="/" class="relative z-10 inline-flex items-center gap-3">
                <img src="{{ asset('images/fix_logo.webp') }}" alt="Logo IT Helpdesk"
                    class="h-11 w-11 rounded-xl bg-white object-contain p-1.5 shadow-sm">
                <div class="leading-tight">
                    <p class="text-base font-extrabold tracking-tight">IT HELPDESK</p>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-sky-200">Ticket Tracking System
                    </p>
                </div>
            </a>

            <div class="relative z-10 max-w-md">
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight">
                    Kelola dan pantau setiap tiket kendala dengan mudah.
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-sky-100">
                    Satu portal untuk melaporkan masalah, melacak progres, dan berkoordinasi dengan tim IT
                    secara real-time.
                </p>

                <div class="mt-8 flex items-center gap-6">
                    <div>
                        <p class="text-2xl font-extrabold">24/7</p>
                        <p class="text-xs text-sky-200">Dukungan aktif</p>
                    </div>
                    <div class="h-8 w-px bg-white/20"></div>
                    <div>
                        <p class="text-2xl font-extrabold">100%</p>
                        <p class="text-xs text-sky-200">Terlacak digital</p>
                    </div>
                </div>
            </div>

            <p class="relative z-10 text-xs text-sky-200">
                &copy; {{ date('Y') }} {{ config('app.name', 'IT Helpdesk') }}. Seluruh hak cipta dilindungi.
            </p>
        </div>

        <!-- Panel kanan: form login -->
        <div class="flex w-full flex-col justify-center px-6 py-12 sm:px-12 lg:w-1/2 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm">

                {{-- logo untuk mobile (panel kiri tersembunyi) --}}
                <a href="/" class="mb-8 inline-flex items-center gap-3 lg:hidden">
                    <img src="{{ asset('images/fix_logo.webp') }}" alt="Logo IT Helpdesk"
                        class="h-11 w-11 rounded-xl border border-slate-200 bg-white object-contain p-1.5 shadow-sm">
                    <div class="leading-tight">
                        <p class="text-base font-extrabold tracking-tight text-slate-900">IT HELPDESK</p>
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-sky-600">Ticket Tracking
                            System</p>
                    </div>
                </a>

                {{-- Status sesi (mis. tautan reset terkirim) --}}
                @if (session('status'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium leading-relaxed text-emerald-700">{{ session('status') }}</p>
                    </div>
                @endif

                <h1 class="text-2xl font-bold text-slate-900">Selamat datang kembali</h1>
                <p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola dan memantau tiket kendala Anda.</p>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')"
                            class="text-xs font-bold uppercase tracking-wider text-slate-600" />
                        <div class="relative mt-2">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" placeholder="nama@contoh.com"
                                class="block w-full rounded-xl border-slate-200 py-2.5 pl-10 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Kata Sandi -->
                    <div x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Kata Sandi')"
                            class="text-xs font-bold uppercase tracking-wider text-slate-600" />
                        <div class="relative mt-2">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <x-text-input id="password" :type="'password'" name="password" required
                                autocomplete="current-password" placeholder="Masukkan kata sandi"
                                x-bind:type="show ? 'text' : 'password'"
                                class="block w-full rounded-xl border-slate-200 py-2.5 pl-10 pr-11 text-sm placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500" />
                            <button type="button" x-on:click="show = !show"
                                :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-md p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.948 9.948 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Ingat saya & Lupa kata sandi -->
                    <div class="flex items-center justify-between gap-3">
                        <label for="remember_me" class="inline-flex cursor-pointer select-none items-center gap-2">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500">
                            <span class="text-sm text-slate-600">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-semibold text-sky-600 transition hover:text-sky-800"
                                href="{{ route('password.request') }}">
                                {{ __('Lupa kata sandi?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-3 text-sm font-bold text-white shadow-sm shadow-sky-600/20 transition duration-150 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 active:bg-sky-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Masuk') }}
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-slate-400 lg:hidden">
                    &copy; {{ date('Y') }} {{ config('app.name', 'IT Helpdesk') }}
                </p>
            </div>
        </div>
    </div>
</body>

</html>