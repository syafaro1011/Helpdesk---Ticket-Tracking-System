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
        <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8">

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
                        <li>Butuh lapor beberapa kendala sekaligus? Klik <span class="font-semibold">"+ Tambah Tiket Lain"</span> di bawah form.</li>
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

                            @error('tickets')
                                <p class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror

                            <!-- Repeater: daftar tiket (jquery.repeater) -->
                            @php
                                $oldTickets = old('tickets');
                                if (!is_array($oldTickets) || count($oldTickets) === 0) {
                                    $oldTickets = [['title' => '', 'category_id' => '', 'priority' => 'medium', 'description' => '']];
                                }
                            @endphp
                            <div id="ticket-repeater">
                                <div data-repeater-list="tickets" class="space-y-4">
                                    @foreach($oldTickets as $i => $raw)
                                        @php $t = is_array($raw) ? $raw : []; @endphp
                                        <div data-repeater-item class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5">
                                            <div class="mb-4 flex items-center justify-between gap-3">
                                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700">
                                                    Tiket <span class="repeater-number">#{{ $i + 1 }}</span>
                                                </h4>
                                                <button data-repeater-delete type="button"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600 transition hover:bg-rose-600 hover:text-white">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>

                                            <!-- Judul Kendala -->
                                            <div class="mb-4">
                                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                                    Judul Kendala <span class="text-rose-500">*</span>
                                                </label>
                                                <input type="text" name="title" value="{{ $t['title'] ?? '' }}" required
                                                    class="ticket-title shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                                                    placeholder="Contoh: PC Ruang 201 Tidak Bisa Konek Wi-Fi Kantor">
                                                @error('tickets.' . $i . '.title')
                                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Kategori & Prioritas -->
                                            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                                        Kategori Masalah <span class="text-rose-500">*</span>
                                                    </label>
                                                    <select name="category_id" required
                                                        class="ticket-category shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                                                        <option value="">-- Pilih Kategori Kendala --</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ ($t['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('tickets.' . $i . '.category_id')
                                                        <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                                        Tingkat Urgensi <span class="text-rose-500">*</span>
                                                    </label>
                                                    <select name="priority" required
                                                        class="ticket-priority shadow-2xs block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                                                        <option value="low" {{ ($t['priority'] ?? 'medium') == 'low' ? 'selected' : '' }}>Low - Masalah ringan</option>
                                                        <option value="medium" {{ ($t['priority'] ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium - Mengganggu alur kerja</option>
                                                        <option value="high" {{ ($t['priority'] ?? 'medium') == 'high' ? 'selected' : '' }}>High - Pekerjaan terhenti total</option>
                                                        <option value="urgent" {{ ($t['priority'] ?? 'medium') == 'urgent' ? 'selected' : '' }}>Urgent - Darurat / sistem down</option>
                                                    </select>
                                                    @error('tickets.' . $i . '.priority')
                                                        <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Detail Deskripsi -->
                                            <div class="mb-4">
                                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                                    Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                                                </label>
                                                <textarea name="description" rows="4" required
                                                    class="shadow-2xs block w-full rounded-lg border-gray-300 p-3 text-sm focus:border-sky-500 focus:ring-sky-500"
                                                    placeholder="Tuliskan kronologi singkat, nomor aset (jika ada), serta pesan kesalahan yang muncul pada layar...">{{ $t['description'] ?? '' }}</textarea>
                                                @error('tickets.' . $i . '.description')
                                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Upload Lampiran Foto per tiket -->
                                            <div>
                                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                                    Foto / Bukti Kendala (Opsional)
                                                </label>
                                                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-white px-4 py-4 transition-colors hover:border-sky-400">
                                                    <div class="flex flex-col items-center gap-2 text-center sm:flex-row sm:text-left">
                                                        <img src="" alt="Preview lampiran"
                                                            class="attachment-preview hidden h-16 w-auto shrink-0 rounded-lg border border-gray-200 object-cover shadow-xs">
                                                        <div class="min-w-0 flex-1">
                                                            <label class="shadow-2xs inline-block cursor-pointer rounded border border-gray-300 bg-white px-2.5 py-1 text-xs font-semibold text-sky-600 hover:text-sky-700">
                                                                Pilih file gambar
                                                                <input name="attachment" type="file"
                                                                    accept="image/png, image/jpeg, image/jpg"
                                                                    class="attachment-input sr-only">
                                                            </label>
                                                            <p class="attachment-name mt-1 truncate text-[11px] text-gray-400">
                                                                Format: PNG, JPG, JPEG (Maks. 2MB)
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('tickets.' . $i . '.attachment')
                                                    <p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Tombol tambah baris tiket -->
                                <button data-repeater-create type="button"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-sky-300 bg-sky-50/50 px-4 py-2.5 text-xs font-bold text-sky-700 transition hover:border-sky-500 hover:bg-sky-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Tiket Lain
                                </button>
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

                    <!-- Ringkasan pengajuan (di-update via jQuery repeater) -->
                    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Ringkasan Pengajuan</h3>
                        <dl class="mt-3 space-y-3 text-xs">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-gray-500">Jumlah tiket</dt>
                                <dd id="summary-count" class="font-semibold text-gray-800">1 tiket</dd>
                            </div>
                        </dl>
                        <ul id="summary-list" class="mt-3 space-y-1.5 border-t border-gray-100 pt-3 text-xs text-gray-600">
                            <li class="italic text-gray-400">Belum ada judul tiket.</li>
                        </ul>
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

    {{-- jquery.repeater: tambah/hapus baris tiket dinamis (di-bundle via Vite, tanpa CDN) --}}
    @vite('resources/js/ticket-repeater.js')
</x-app-layout>
