<section class="space-y-5">
    <div class="p-4 bg-rose-50/70 border border-rose-100 rounded-xl text-xs text-rose-800 leading-relaxed flex items-start gap-3">
        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p>{{ __('Setelah akun dihapus, seluruh data dan tiket Anda akan dihapus permanen. Unduh data penting sebelum melanjutkan.') }}</p>
    </div>

    <div>
        <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-lg shadow-xs transition duration-150"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>{{ __('Hapus Akun') }}</button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4">
                <div class="p-2.5 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="min-w-0 space-y-1.5">
                    <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                        {{ __('Hapus akun secara permanen?') }}
                    </h2>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        {{ __('Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun.') }}
                    </p>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full rounded-lg border shadow-2xs text-sm py-2.5 px-3.5 placeholder:text-gray-400"
                    placeholder="{{ __('Masukkan kata sandi Anda') }}"
                />
                @error('password', 'userDeletion')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-5 py-2.5 rounded-lg border border-gray-300 font-semibold text-xs text-gray-700 hover:bg-gray-50 transition shadow-2xs">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-lg shadow-xs transition duration-150">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
