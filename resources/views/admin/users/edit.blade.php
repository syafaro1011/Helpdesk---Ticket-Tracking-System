<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight">{{ __('Edit Pengguna') }}</h2>
            <a href="{{ route('admin.users.index') }}"
                class="shadow-2xs inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-rose-800 text-xs uppercase tracking-wider">Terjadi Kesalahan</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm sm:p-8">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('name')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('email')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Password Baru <span class="text-gray-400 font-medium normal-case">(opsional)</span></label>
                            <input type="password" name="password" id="password" minlength="8"
                                class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                                placeholder="Kosongkan jika tidak diubah">
                            @error('password')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                                class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div>
                        <label for="role" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Role <span class="text-rose-500">*</span></label>
                        <select name="role" id="role" required
                            class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User / Karyawan</option>
                            <option value="technician" {{ old('role', $user->role) == 'technician' ? 'selected' : '' }}>Teknisi</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                        @if($user->id === auth()->id())
                            <p class="mt-1 text-[11px] text-amber-600">Ini akun Anda sendiri — role admin tidak dapat dicabut dari sini.</p>
                        @endif
                    </div>

                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-xs text-gray-600 space-y-1">
                        <p><span class="font-semibold text-gray-700">Telegram:</span> {{ $user->telegram_chat_id ? 'Terhubung' : 'Belum terhubung' }}</p>
                        <p><span class="font-semibold text-gray-700">Tiket laporan:</span> {{ $user->tickets()->count() }} &bull; <span class="font-semibold text-gray-700">Tiket ditangani:</span> {{ $user->assignedTickets()->count() }}</p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.users.index') }}"
                            class="shadow-2xs inline-flex w-full justify-center rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 sm:w-auto">Batal</a>
                        <button type="submit"
                            class="shadow-xs inline-flex w-full items-center justify-center gap-2 rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white transition hover:bg-sky-700 sm:w-auto">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
