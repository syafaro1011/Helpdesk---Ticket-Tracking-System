<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Tiket Kendala
                    </h2>
                    <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                        #{{ $ticket->ticket_code }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Perbarui detail laporan kendala atau lampiran foto sebelum ditangani oleh teknisi</p>
            </div>
            <a href="{{ route('user.tickets.show', $ticket->id) }}"
                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition gap-1">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal & Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Information Tip Banner -->
            <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-xl flex items-start gap-3 text-amber-900 shadow-sm">
                <div class="p-1.5 bg-amber-100 text-amber-700 rounded-lg shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xs">
                    <span class="font-bold text-amber-900">Catatan Pengeditan Tiket:</span>
                    <p class="mt-0.5 text-amber-800">
                        Tiket ini saat ini masih berstatus <span class="font-semibold uppercase text-blue-700">OPEN</span>. Selama teknisi belum mulai menangani tiket ini, Anda bebas memperbarui informasi atau melampirkan foto kendala baru.
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 sm:p-8">
                <form action="{{ route('user.tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" 
                    x-data="{ 
                        fileName: '',
                        previewUrl: '',
                        handleFile(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Judul Kendala & Kategori (Grid 2 Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Kendala -->
                        <div>
                            <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Judul Kendala <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $ticket->title) }}" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-3">
                            @error('title')
                                <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Masalah -->
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Kategori Masalah <span class="text-rose-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-3">
                                <option value="">-- Pilih Kategori Kendala --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Prioritas / Tingkat Urgensi -->
                    <div>
                        <label for="priority" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Tingkat Urgensi / Dampak <span class="text-rose-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-3">
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low - Masalah ringan (Masih bisa bekerja)</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium - Mengganggu alur kerja rutin</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High - Pekerjaan terhenti total</option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent - Darurat (Sistem / Server Down Kritis)</option>
                        </select>
                        @error('priority')
                            <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detail Deskripsi -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="5" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs p-3">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Lampiran Foto -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Foto / Bukti Kendala
                        </label>

                        @if($ticket->attachment)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Lampiran Foto" class="w-16 h-16 object-cover rounded-md border border-gray-200 shadow-sm">
                                <div>
                                    <span class="text-xs font-semibold text-gray-800">Lampiran Foto Saat Ini</span>
                                    <p class="text-[11px] text-gray-500">Unggah foto baru di bawah ini jika ingin mengganti lampiran saat ini.</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-indigo-400 transition-colors bg-gray-50/50">
                            <div class="space-y-2 text-center">
                                <template x-if="!previewUrl">
                                    <svg class="mx-auto h-9 w-9 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </template>

                                <template x-if="previewUrl">
                                    <div class="mb-2">
                                        <img :src="previewUrl" class="mx-auto h-24 w-auto rounded-lg shadow-sm border border-gray-200 object-cover">
                                    </div>
                                </template>

                                <div class="flex text-xs text-gray-600 justify-center items-center gap-1">
                                    <label for="attachment" class="relative cursor-pointer bg-white rounded font-semibold text-indigo-600 hover:text-indigo-500 border border-gray-300 px-2.5 py-1 shadow-sm">
                                        <span x-text="fileName ? 'Ganti File Baru' : '{{ $ticket->attachment ? 'Ganti Foto Lampiran' : 'Pilih File Gambar' }}'">Pilih File Gambar</span>
                                        <input id="attachment" name="attachment" type="file" @change="handleFile" accept="image/png, image/jpeg, image/jpg" class="sr-only">
                                    </label>
                                    <p class="text-gray-500">atau drag & drop</p>
                                </div>
                                <p class="text-[11px] text-gray-400" x-text="fileName ? 'Terpilih: ' + fileName : 'Format: PNG, JPG, JPEG (Maks. 2MB)'"></p>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                            class="inline-flex justify-center w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-300 font-semibold text-xs text-gray-700 hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center w-full sm:w-auto gap-2 px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs rounded-lg shadow-sm transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
