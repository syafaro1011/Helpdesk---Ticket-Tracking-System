<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.tickets.index') }}"
                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                            Detail Tiket Saya
                        </h2>
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                            #{{ $ticket->ticket_code }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Pantau status pengerjaan dan tanggapan teknisi IT Support</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($ticket->status == 'open')
                    <span class="px-3 py-1.5 rounded-lg bg-sky-100 text-sky-800 font-bold text-xs border border-sky-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                        STATUS: MENUNGGU
                    </span>
                @elseif($ticket->status == 'in_progress')
                    <span class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 font-bold text-xs border border-amber-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        STATUS: SEDANG DIPROSES
                    </span>
                @elseif($ticket->status == 'resolved')
                    <span class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        STATUS: SELESAI
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        STATUS: CLOSED
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Notification -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-emerald-800 text-sm">Berhasil!</h4>
                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Detail Tiket & Chat Log (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Detail Tiket Kendala -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                                    📁 {{ $ticket->category->name }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2.5">{{ $ticket->title }}</h3>
                            </div>
                            <div>
                                @if($ticket->priority == 'urgent')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 animate-pulse">
                                        🚨 SANGAT MENDESAK
                                    </span>
                                @elseif($ticket->priority == 'high')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        ⚠️ TINGGI
                                    </span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        ⚡ SEDANG
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                        🔵 RENDAH
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Info waktu -->
                        <div class="text-xs text-gray-500">
                            Diajukan pada: <span class="font-semibold text-gray-700">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Deskripsi Masalah</h4>
                            <div class="p-4 bg-slate-50 rounded-xl text-sm text-gray-700 leading-relaxed border border-slate-100">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>

                        <!-- Lampiran foto jika ada -->
                        @if($ticket->attachment)
                            <div class="pt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Foto / Bukti Kendala</h4>
                                <div class="inline-block relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm max-w-sm">
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Bukti Kendala"
                                        class="w-full h-auto max-h-64 object-cover group-hover:scale-105 transition-transform duration-200">
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-semibold text-xs gap-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Gambar Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat & Catatan Diskusi -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 class="font-bold text-gray-800 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                Tanggapan Teknisi & Diskusi
                            </h4>
                            <span class="text-xs text-gray-400 font-medium">{{ $ticket->logs->count() }} Catatan</span>
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-4">
                            @forelse($ticket->logs as $log)
                                <div class="p-4 rounded-xl text-sm transition border {{ $log->user->role == 'user' ? 'bg-slate-50 border-slate-100' : 'bg-indigo-50/60 border-indigo-100' }}">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-800">{{ $log->user->name }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->user->role == 'user' ? 'bg-gray-200 text-gray-700' : 'bg-indigo-100 text-indigo-700' }}">
                                                {{ $log->user->role == 'user' ? 'Pelapor (Anda)' : strtoupper($log->user->role) }}
                                            </span>
                                        </div>
                                        <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $log->message }}</p>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    Belum ada tanggapan atau catatan dari teknisi.
                                </div>
                            @endforelse
                        </div>

                        <!-- Form Balas Pesan -->
                        @if($ticket->status != 'closed')
                            <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST" class="pt-4 border-t border-gray-100 space-y-4">
                                @csrf

                                <div>
                                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                        Kirim Balasan / Pertanyaan Tambahan
                                    </label>
                                    <textarea name="message" id="message" rows="3" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3"
                                        placeholder="Tuliskan pesan balasan atau informasi tambahan untuk teknisi..."></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>

                <!-- Kolom Kanan: Status & Teknisi PJ (1/3) -->
                <div class="space-y-6">

                    <!-- Ringkasan Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h4 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Pengerjaan
                        </h4>

                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Status Tiket</span>
                                <span class="font-bold text-indigo-700 uppercase bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Teknisi PJ</span>
                                @if($ticket->technician)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($ticket->technician->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-gray-800">{{ $ticket->technician->name }}</span>
                                    </div>
                                @else
                                    <span class="font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100">
                                        ⏳ Menunggu Penunjukan
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Kategori Kendala</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->category->name }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 font-medium">Tingkat Urgensi</span>
                                @if($ticket->priority == 'urgent')
                                    <span class="font-bold text-red-600">🚨 SANGAT MENDESAK</span>
                                @elseif($ticket->priority == 'high')
                                    <span class="font-bold text-orange-600">⚠️ TINGGI</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="font-bold text-amber-600">⚡ SEDANG</span>
                                @else
                                    <span class="font-semibold text-slate-600">🔵 RENDAH</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Bantuan Tambahan -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-sm p-6 text-white space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-bold">
                            🎧
                        </div>
                        <h4 class="font-bold text-base">Butuh Bantuan Mendesak?</h4>
                        <p class="text-xs text-indigo-100 leading-relaxed">
                            Jika kendala Anda berdampak kritis pada operasional, hubungi Helpdesk Desk IT melalui nomor ekstensi internal kantor.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
