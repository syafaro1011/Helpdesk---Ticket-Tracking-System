<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('tech.tickets.index') }}"
                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                            Detail Tiket
                        </h2>
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                            #{{ $ticket->ticket_code }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Penanganan & Pemantauan Progres Kendala IT</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($ticket->status == 'open')
                    <span class="px-3 py-1.5 rounded-lg bg-sky-100 text-sky-800 font-bold text-xs border border-sky-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                        STATUS: OPEN
                    </span>
                @elseif($ticket->status == 'in_progress')
                    <span class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 font-bold text-xs border border-amber-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        STATUS: IN PROGRESS
                    </span>
                @elseif($ticket->status == 'resolved')
                    <span class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        STATUS: RESOLVED
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

            <!-- Notifications -->
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

            @if(session('error'))
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-800 text-sm">Terjadi Kesalahan!</h4>
                        <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Detail Tiket & Chat Log (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Kartu Informasi Kendala Utama -->
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
                                        🚨 URGENT
                                    </span>
                                @elseif($ticket->priority == 'high')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        ⚠️ HIGH
                                    </span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        ⚡ MEDIUM
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                        🔵 LOW
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Pelapor & Tanggal -->
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-bold text-gray-800">{{ $ticket->user->name }}</span>
                                <span class="text-gray-400">({{ $ticket->user->email }})</span>
                                <div class="text-gray-400 mt-0.5">Diajukan pada {{ $ticket->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Deskripsi Rinci Kendala</h4>
                            <div class="p-4 bg-slate-50 rounded-xl text-sm text-gray-700 leading-relaxed border border-slate-100">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>

                        <!-- Lampiran foto jika ada -->
                        @if($ticket->attachment)
                            <div class="pt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Lampiran Foto Bukti</h4>
                                <div class="inline-block relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm max-w-sm">
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Bukti Kendala"
                                        class="w-full h-auto max-h-64 object-cover group-hover:scale-105 transition-transform duration-200">
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-semibold text-xs gap-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Penanganan / Ticket Logs -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 class="font-bold text-gray-800 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                Catatan Perbaikan & Aktivitas
                            </h4>
                            <span class="text-xs text-gray-400 font-medium">{{ $ticket->logs->count() }} Catatan</span>
                        </div>

                        <!-- Timeline Items -->
                        <div class="space-y-4">
                            @forelse($ticket->logs as $log)
                                <div class="p-4 rounded-xl text-sm transition border {{ $log->user->role == 'user' ? 'bg-slate-50 border-slate-100' : 'bg-indigo-50/60 border-indigo-100' }}">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-800">{{ $log->user->name }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->user->role == 'user' ? 'bg-gray-200 text-gray-700' : ($log->user->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-indigo-100 text-indigo-700') }}">
                                                {{ $log->user->role }}
                                            </span>
                                        </div>
                                        <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $log->message }}</p>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    Belum ada catatan aktivitas atau diskusikan pada tiket ini.
                                </div>
                            @endforelse
                        </div>

                        <!-- Form Tambah Catatan / Update Status -->
                        <form action="{{ route('tech.tickets.update-status', $ticket->id) }}" method="POST" class="pt-4 border-t border-gray-100 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Tambah Catatan / Progres Pekerjaan
                                </label>
                                <textarea name="message" id="message" rows="3"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3"
                                    placeholder="Tuliskan catatan hasil pengecekan, solusi perbaikan, atau instruksi selanjutnya..."></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50 p-3.5 rounded-xl border border-gray-200/80">
                                <div class="flex items-center gap-2">
                                    <label for="status_select" class="text-xs font-bold text-gray-700 uppercase">Ubah Status Tiket:</label>
                                    <select name="status" id="status_select" class="rounded-lg border-gray-300 text-xs font-semibold focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-3">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>OPEN</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>IN PROGRESS</option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>RESOLVED</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>CLOSED</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Catatan & Status
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Kolom Kanan: Informasi Status & Assignment (1/3) -->
                <div class="space-y-6">

                    <!-- Kartu Ringkasan Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h4 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Tiket
                        </h4>

                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Kode Tiket</span>
                                <span class="font-mono font-bold text-indigo-600">{{ $ticket->ticket_code }}</span>
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Kategori</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->category->name }}</span>
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Tingkat Urgensi</span>
                                @if($ticket->priority == 'urgent')
                                    <span class="font-bold text-red-600">🚨 URGENT</span>
                                @elseif($ticket->priority == 'high')
                                    <span class="font-bold text-orange-600">⚠️ HIGH</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="font-bold text-amber-600">⚡ MEDIUM</span>
                                @else
                                    <span class="font-semibold text-slate-600">🔵 LOW</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Status</span>
                                <span class="font-bold text-indigo-700 uppercase bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 font-medium">Teknisi Penanggung Jawab</span>
                                <span class="font-bold text-gray-800">
                                    @if($ticket->technician)
                                        👤 {{ $ticket->technician->name }}
                                    @else
                                        <span class="text-red-500 italic">Belum Ditugaskan</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Penugasan Teknisi (Khusus Role Admin) -->
                    @if(auth()->user()->role === 'admin')
                        <div class="bg-white rounded-xl shadow-sm border-2 border-indigo-100 p-6 space-y-4">
                            <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2 text-indigo-900">
                                <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                Penugasan Teknisi (Admin)
                            </h4>

                            <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" class="space-y-3.5">
                                @csrf
                                @method('PATCH')

                                <!-- Select Teknisi -->
                                <div>
                                    <label for="technician_id" class="block text-xs font-semibold text-gray-700 mb-1">
                                        Pilih Teknisi Penanggung Jawab:
                                    </label>
                                    <select name="technician_id" id="technician_id" required
                                        class="w-full rounded-lg border-gray-300 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 py-2">
                                        <option value="">-- Pilih Teknisi --</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                                {{ $tech->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Catatan Penugasan (Opsional) -->
                                <div>
                                    <label for="notes" class="block text-xs font-semibold text-gray-700 mb-1">
                                        Catatan / Instruksi Tambahan:
                                    </label>
                                    <input type="text" name="notes" id="notes"
                                        placeholder="Misal: Tolong prioritaskan pengecekan printer..."
                                        class="w-full rounded-lg border-gray-300 text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                                </div>

                                <button type="submit"
                                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition duration-150 flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Tugaskan Teknisi
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>