<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="flex items-center gap-2 text-xl font-bold tracking-tight text-gray-800 sm:text-2xl">
                    <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    {{ __('Buat Tiket Kendala Baru') }}
                </h2>
            </div>
            <a href="{{ route('user.tickets.index') }}"
                class="shadow-2xs inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8"
            x-data="{
                fileName: '',
                previewUrl: '',
                title: '{{ old('title', '') }}',
                priority: '{{ old('priority', 'medium') }}',
                handleFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            }">
            
            <!-- Tips pelaporan -->
            <div class="shadow-2xs flex items-start gap-3 rounded-2xl border border-sky-100 bg-sky-50/70 p-5 text-sky-900">
                <div class="mt-0.5 shrink-0 rounded-lg bg-sky-100 p-1.5 text-sky-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xs">
                    <span class="font-bold">Tips Pelaporan Kendala</span>
                    <ul class="mt-1.5 list-inside list-disc space-y-1 text-sky-800">
                        <li>Jelaskan kendala selengkap mungkin (lokasi ruangan, kronologi masalah, pesan
                            error).</li>
                        <li>Lampirkan foto/screenshot pesan kesalahan agar teknisi lebih cepat memverifikasi.
                        </li>
                        <li>Pilih tingkat urgensi sesuai dampak nyata, bukan sekadar rasa terburu-buru.</li>
                    </ul>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">

                <!-- Kolom kiri: form utama -->
                <div class="space-y-6 lg:col-span-2">


                    <!-- Form Card -->
                    <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm sm:p-8">
                        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf

                            <!-- Judul Kendala -->
                            <div>
                                <label for="title"
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Judul Kendala <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" x-model="title" required
                                    class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                                    placeholder="Contoh: PC Ruang 201 Tidak Bisa Konek Wi-Fi Kantor">
                                @error('title')
                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori & Prioritas -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="category_id"
                                        class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Kategori Masalah <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="category_id" id="category_id" required
                                        class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="">-- Pilih Kategori Kendala --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="priority"
                                        class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Tingkat Urgensi <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="priority" id="priority" x-model="priority" required
                                        class="shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="low">Low - Masalah ringan</option>
                                        <option value="medium">Medium - Mengganggu alur kerja</option>
                                        <option value="high">High - Pekerjaan terhenti total</option>
                                        <option value="urgent">Urgent - Darurat / sistem down</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Detail Deskripsi -->
                            <div>
                                <label for="description"
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="5" required
                                    class="shadow-2xs block w-full rounded-lg border-gray-300 p-3 text-sm focus:border-sky-500 focus:ring-sky-500"
                                    placeholder="Tuliskan kronologi singkat, nomor aset (jika ada), serta pesan kesalahan yang muncul pada layar...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Lampiran Foto dengan Preview File -->
                            <div>
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Foto / Bukti Kendala (Opsional)
                                </label>
                                <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/50 px-6 pb-6 pt-5 transition-colors hover:border-sky-400">
                                    <div class="space-y-2 text-center">
                                        <template x-if="!previewUrl">
                                            <svg class="mx-auto h-9 w-9 text-gray-400" stroke="currentColor" fill="none"
                                                viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </template>

                                        <template x-if="previewUrl">
                                            <div class="mb-2">
                                                <img :src="previewUrl"
                                                    class="shadow-xs mx-auto h-24 w-auto rounded-lg border border-gray-200 object-cover">
                                            </div>
                                        </template>

                                        <div class="flex items-center justify-center gap-1 text-xs text-gray-600">
                                            <label for="attachment"
                                                class="shadow-2xs relative cursor-pointer rounded border border-gray-300 bg-white px-2.5 py-1 font-semibold text-sky-600 hover:text-sky-700">
                                                <span x-text="fileName ? 'Ganti File' : 'Pilih file gambar'">Pilih file
                                                    gambar</span>
                                                <input id="attachment" name="attachment" type="file" @change="handleFile"
                                                    accept="image/png, image/jpeg, image/jpg" class="sr-only">
                                            </label>
                                            <p class="text-gray-500">atau drag & drop</p>
                                        </div>
                                        <p class="text-[11px] text-gray-400"
                                            x-text="fileName ? 'Terpilih: ' + fileName : 'Format: PNG, JPG, JPEG (Maks. 2MB)'">
                                        </p>
                                    </div>
                                </div>
                                @error('attachment')
                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-end">
                                <a href="{{ route('user.tickets.index') }}"
                                    class="shadow-2xs inline-flex w-full justify-center rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 sm:w-auto">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="shadow-xs inline-flex w-full items-center justify-center gap-2 rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white transition duration-150 hover:bg-sky-700 sm:w-auto">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Kirim Tiket Kendala
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Kolom kanan: sidebar bantuan -->
                <div class="space-y-6 lg:sticky lg:top-6">
                    
                    <!-- Ringkasan tiket -->
                    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Ringkasan</h3>
                        <dl class="mt-3 space-y-3 text-xs">
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-gray-500">Judul</dt>
                                <dd class="text-right font-semibold text-gray-800"
                                    x-text="title ? title : '—'"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-gray-500">Prioritas</dt>
                                <dd>
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold"
                                        :class="{
                                            'bg-emerald-100 text-emerald-700': priority === 'low',
                                            'bg-amber-100 text-amber-700': priority === 'medium',
                                            'bg-orange-100 text-orange-700': priority === 'high',
                                            'bg-rose-100 text-rose-700': priority === 'urgent',
                                        }"
                                        x-text="priority.charAt(0).toUpperCase() + priority.slice(1)"></span>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-gray-500">Lampiran</dt>
                                <dd class="font-semibold text-gray-800"
                                    x-text="fileName ? 'Terlampir' : 'Belum ada'"></dd>
                            </div>
                        </dl>
                    </div>


                    <!-- Panduan prioritas -->
                    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Panduan Urgensi</h3>
                        <ul class="mt-3 space-y-2.5 text-xs text-gray-600">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                                <span><span class="font-semibold text-gray-800">Low</span> — masih bisa bekerja
                                    seperti biasa.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
                                <span><span class="font-semibold text-gray-800">Medium</span> — mengganggu alur kerja
                                    rutin.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-orange-500"></span>
                                <span><span class="font-semibold text-gray-800">High</span> — pekerjaan terhenti
                                    total.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-rose-500"></span>
                                <span><span class="font-semibold text-gray-800">Urgent</span> — sistem/server down,
                                    darurat.</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>