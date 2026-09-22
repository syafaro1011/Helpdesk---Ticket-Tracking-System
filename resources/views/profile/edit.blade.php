<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Pengaturan Profil') }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">Kelola informasi akun, keamanan kata sandi, dan preferensi profil Anda</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan Profil --}}
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-2xl shrink-0 shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 space-y-1">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg truncate">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    <div class="flex flex-wrap items-center gap-2 pt-1.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 font-medium">
                            {{ ucfirst(Auth::user()->role ?? 'User') }}
                        </span>
                        <span class="text-[11px] text-gray-400">Bergabung {{ Auth::user()->created_at?->format('d M Y') }}</span>
                    </div>
                </div>
                @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                    <div class="flex items-start gap-2.5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-medium leading-relaxed shrink-0">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('status') === 'profile-updated' ? 'Profil diperbarui.' : 'Kata sandi diperbarui.' }}
                    </div>
                @endif
            </div>

            {{-- Informasi Profil --}}
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 sm:px-8 py-5 border-b border-gray-200/80 bg-gray-50/50 flex items-center gap-4">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 space-y-0.5">
                        <h3 class="font-bold text-gray-800 text-sm">Informasi Profil</h3>
                        <p class="text-xs text-gray-500">Perbarui nama dan alamat email akun Anda</p>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Integrasi Telegram --}}
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 sm:px-8 py-5 border-b border-gray-200/80 bg-gray-50/50 flex items-center gap-4">
                    <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.42-.88.03-.25.38-.51 1.07-.78 4.18-1.82 6.97-3.02 8.37-3.61 3.98-1.66 4.81-1.95 5.35-1.96.12 0 .38.03.55.17.14.12.18.28.2.45-.02.07-.02.19-.04.34z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 space-y-0.5">
                        <h3 class="font-bold text-gray-800 text-sm">Integrasi Bot Telegram</h3>
                        <p class="text-xs text-gray-500">Tautkan akun Telegram untuk pelaporan kendala via AI Telegram Bot</p>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-telegram-form')
                </div>
            </div>

            {{-- Keamanan Kata Sandi --}}
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 sm:px-8 py-5 border-b border-gray-200/80 bg-gray-50/50 flex items-center gap-4">
                    <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 space-y-0.5">
                        <h3 class="font-bold text-gray-800 text-sm">Keamanan Kata Sandi</h3>
                        <p class="text-xs text-gray-500">Gunakan kata sandi yang panjang dan acak agar tetap aman</p>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Zona Berbahaya --}}
            <div class="bg-white rounded-xl border border-rose-200/80 shadow-sm overflow-hidden">
                <div class="px-6 sm:px-8 py-5 border-b border-rose-100 bg-rose-50/50 flex items-center gap-4">
                    <div class="p-2.5 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 space-y-0.5">
                        <h3 class="font-bold text-rose-800 text-sm">Zona Berbahaya</h3>
                        <p class="text-xs text-rose-600/80">Tindakan permanen yang tidak dapat dibatalkan</p>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
