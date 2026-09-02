<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Buat Tiket Kendala Baru') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Laporkan masalah perangkat keras, jaringan, atau sistem aplikasi yang Anda alami</p>
            </div>
            <a href="{{ route('user.tickets.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 hover:bg-gray-50 shadow-sm transition gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Information Tip Banner -->
            <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl flex items-start gap-3 text-indigo-900 shadow-sm">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xs sm:text-sm">
                    <span class="font-bold">Tips Pelaporan Tiket:</span>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-indigo-800">
                        <li>Jelaskan kendala selengkap mungkin (lokasi ruangan, kronologi masalah, pesan error).</li>
                        <li>Lampirkan foto/screenshot pesan kesalahan agar teknisi lebih cepat memverifikasi.</li>
                    </ul>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Judul Kendala -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">
                            Judul Kendala <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                            placeholder="Contoh: PC Ruang 201 Tidak Bisa Konek Wi-Fi Kantor">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Urgensi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori Masalah -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-800 mb-1">
                                Kategori Masalah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category_id" id="category_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                                    <option value="">-- Pilih Kategori Kendala --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <label for="priority" class="block text-sm font-semibold text-gray-800 mb-1">
                                Tingkat Urgensi <span class="text-red-500">*</span>
                            </label>
                            <select name="priority" id="priority" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Rendah (Masalah kecil / Masih bisa bekerja)</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Sedang (Mengganggu alur kerja rutin)</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 Tinggi (Pekerjaan terhenti total)</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Darurat / Urgent (Server Down / Sistem Kritis)</option>
                            </select>
                            @error('priority')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Detail Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-800 mb-1">
                            Deskripsi Rinci Kendala <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="5" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3.5"
                            placeholder="Tuliskan kronologi singkat, nomor aset (jika ada), serta pesan kesalahan yang muncul pada layar..."></textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Lampiran Foto -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">
                            Foto / Bukti Kendala (Opsional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-indigo-400 transition-colors bg-gray-50/50">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-0.5 border">
                                        <span>Pilih file gambar</span>
                                        <input id="attachment" name="attachment" type="file" accept="image/png, image/jpeg, image/jpg" class="sr-only">
                                    </label>
                                    <p class="pl-1 pt-0.5 text-xs text-gray-500">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-400">Format: PNG, JPG, JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('user.tickets.index') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 font-semibold text-xs text-gray-700 hover:bg-gray-50 transition shadow-xs">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-xs transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Tiket Kendala
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>