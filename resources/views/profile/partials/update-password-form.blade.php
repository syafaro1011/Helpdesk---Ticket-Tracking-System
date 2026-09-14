<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
            </label>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400" autocomplete="current-password" placeholder="Masukkan kata sandi saat ini" />
            @error('current_password', 'updatePassword')
                <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="update_password_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400" autocomplete="new-password" placeholder="Minimal 8 karakter" />
                @error('password', 'updatePassword')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Konfirmasi Baru <span class="text-rose-500">*</span>
                </label>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400" autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            </div>
        </div>

        <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl flex items-start gap-3 text-xs text-indigo-800 leading-relaxed">
            <svg class="w-4 h-4 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p><span class="font-bold">Tips keamanan:</span> gunakan kombinasi huruf besar, angka, dan simbol agar akun lebih sulit dibobol.</p>
        </div>

        <div class="pt-5 mt-2 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center w-full sm:w-auto gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-xs transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                {{ __('Perbarui Kata Sandi') }}
            </button>

            @if (session('status') === 'password-updated')
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
