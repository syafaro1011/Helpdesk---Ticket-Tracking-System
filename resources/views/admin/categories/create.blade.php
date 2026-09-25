<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight">{{ __('Tambah Kategori') }}</h2>
            <a href="{{ route('admin.categories.index') }}"
                class="shadow-2xs inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm sm:p-8">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255"
                            class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Contoh: Jaringan & Internet">
                        <p class="mt-1 text-[11px] text-gray-400">Slug URL dibuat otomatis dari nama kategori.</p>
                        @error('name')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.categories.index') }}"
                            class="shadow-2xs inline-flex w-full justify-center rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 sm:w-auto">Batal</a>
                        <button type="submit"
                            class="shadow-xs inline-flex w-full items-center justify-center gap-2 rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white transition hover:bg-sky-700 sm:w-auto">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
