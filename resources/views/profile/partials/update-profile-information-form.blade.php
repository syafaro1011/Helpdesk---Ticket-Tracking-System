<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Nama Lengkap <span class="text-rose-500">*</span>
            </label>
            <x-text-input id="name" name="name" type="text" class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            @error('name')
                <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <x-text-input id="email" name="email" type="email" class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400" :value="old('email', $user->email)" required autocomplete="username" placeholder="nama@email.com" />
            @error('email')
                <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="space-y-1">
                        <p class="text-xs text-amber-800 font-medium">Alamat email Anda belum terverifikasi.</p>
                        <button form="send-verification" class="text-xs font-semibold text-sky-600 hover:text-sky-800 underline underline-offset-2 transition">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="pt-1 text-xs font-medium text-emerald-600">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-5 mt-2 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center w-full sm:w-auto gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs rounded-lg shadow-xs transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
