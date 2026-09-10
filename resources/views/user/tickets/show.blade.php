<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.tickets.index') }}"
                    class="p-1.5 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight">
                            Detail Tiket Saya
                        </h2>
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                            #{{ $ticket->ticket_code }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau status pengerjaan dan tanggapan teknisi IT Support</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($ticket->status == 'open')
                    <span class="px-2.5 py-1 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                        STATUS: OPEN
                    </span>
                @elseif($ticket->status == 'in_progress')
                    <span class="px-2.5 py-1 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">
                        STATUS: IN PROGRESS
                    </span>
                @elseif($ticket->status == 'resolved')
                    <span class="px-2.5 py-1 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                        STATUS: RESOLVED
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">
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
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-2xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-emerald-800 text-xs uppercase tracking-wider">Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Detail Tiket & Chat Log (2/3 Lebar) -->
                <div class="lg:col-span-2 space-y-6 min-w-0">

                    <!-- Kartu Detail Kendala Utama -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded border border-indigo-100">
                                    {{ $ticket->category->name }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 mt-2 break-words">{{ $ticket->title }}</h3>
                            </div>
                            <div>
                                @if($ticket->priority == 'urgent')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-rose-100 text-rose-700 font-bold animate-pulse">
                                        🚨 URGENT
                                    </span>
                                @elseif($ticket->priority == 'high')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700 font-semibold">
                                        HIGH
                                    </span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-sky-100 text-sky-700">
                                        MEDIUM
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600">
                                        LOW
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-xs text-gray-500">
                            Diajukan pada: <span class="font-semibold text-gray-700">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Deskripsi Masalah</h4>
                            <div class="p-4 bg-gray-50 rounded-lg text-xs text-gray-700 leading-relaxed border border-gray-100">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>

                        @if($ticket->attachment)
                            <div class="pt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Lampiran Foto Bukti</h4>
                                <div class="inline-block relative group rounded-lg overflow-hidden border border-gray-200 shadow-2xs max-w-sm">
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Bukti Kendala"
                                        class="w-full h-auto max-h-56 object-cover group-hover:scale-105 transition-transform duration-200">
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-semibold text-xs gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline & Catatan Diskusi -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                Tanggapan Teknisi & Catatan Aktivitas
                            </h4>
                            <span class="text-xs text-gray-400 font-medium">{{ $ticket->logs->count() }} Catatan</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($ticket->logs as $log)
                                <div class="p-3.5 rounded-lg text-xs border {{ $log->user->role == 'user' ? 'bg-gray-50 border-gray-200' : 'bg-indigo-50/50 border-indigo-100' }}">
                                    <div class="flex items-center justify-between text-gray-500 mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-800">{{ $log->user->name }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->user->role == 'user' ? 'bg-gray-200 text-gray-700' : 'bg-indigo-100 text-indigo-700' }}">
                                                {{ $log->user->role == 'user' ? 'Pelapor (Anda)' : strtoupper($log->user->role) }}
                                            </span>
                                        </div>
                                        <span class="text-gray-400 text-[11px]">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $log->message }}</p>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs italic bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                    Belum ada tanggapan atau catatan aktivitas pada tiket ini.
                                </div>
                            @endforelse
                        </div>

                        @if($ticket->status != 'closed')
                            <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST" class="pt-4 border-t border-gray-100 space-y-3">
                                @csrf

                                <div>
                                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                        Kirim Balasan / Pertanyaan Tambahan
                                    </label>
                                    <textarea name="message" id="message" rows="3" required
                                        class="w-full rounded-lg border-gray-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-xs p-3"
                                        placeholder="Tuliskan pesan balasan atau informasi tambahan untuk teknisi..."></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>

                <!-- Kolom Kanan: Action Sidebar (1/3 Lebar) -->
                <div class="space-y-6 min-w-0">

                    <!-- Status Summary Sidebar Card -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 space-y-4">
                        <h4 class="font-bold text-gray-800 text-sm border-b border-gray-100 pb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Pengerjaan
                        </h4>

                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Status Tiket</span>
                                @if($ticket->status == 'open')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">OPEN</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">IN PROGRESS</span>
                                @elseif($ticket->status == 'resolved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">RESOLVED</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">CLOSED</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Teknisi PJ</span>
                                @if($ticket->technician)
                                    <span class="font-bold text-gray-800">{{ $ticket->technician->name }}</span>
                                @else
                                    <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-medium text-[11px]">
                                        Menunggu Penunjukan
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pb-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Kategori</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->category->name }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 font-medium">Urgensi</span>
                                @if($ticket->priority == 'urgent')
                                    <span class="font-bold text-rose-600">URGENT</span>
                                @elseif($ticket->priority == 'high')
                                    <span class="font-semibold text-orange-600">HIGH</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="font-semibold text-sky-600">MEDIUM</span>
                                @else
                                    <span class="text-slate-600">LOW</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
