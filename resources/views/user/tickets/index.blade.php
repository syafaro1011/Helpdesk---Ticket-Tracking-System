<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    {{ __('Daftar Tiket Saya') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau status penanganan kendala IT dan pengajuan fasilitas Anda</p>
            </div>
            <a href="{{ route('user.tickets.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-150 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Tiket Baru
            </a>
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

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tiket</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tickets->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-500">Menunggu (Open)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'open')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-500">Dalam Proses</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">Selesai / Closed</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->whereIn('status', ['resolved', 'closed'])->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Tiket Saya -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-base">Riwayat Kendala Lapangan</h3>
                    <span class="text-xs text-gray-500 font-medium">Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">Kode Tiket</th>
                                <th class="py-3.5 px-4 font-semibold">Judul Kendala</th>
                                <th class="py-3.5 px-4 font-semibold">Kategori</th>
                                <th class="py-3.5 px-4 font-semibold">Urgensi</th>
                                <th class="py-3.5 px-4 font-semibold">Status</th>
                                <th class="py-3.5 px-4 font-semibold">Teknisi PJ</th>
                                <th class="py-3.5 px-4 font-semibold">Tanggal Diajukan</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50/80 transition-colors duration-150 text-sm">
                                    <!-- Kode Tiket -->
                                    <td class="py-4 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 rounded-md border border-indigo-100 transition">
                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                            {{ $ticket->ticket_code }}
                                        </a>
                                    </td>

                                    <!-- Judul -->
                                    <td class="py-4 px-4">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}" class="font-semibold text-gray-900 hover:text-indigo-600 line-clamp-1 transition" title="{{ $ticket->title }}">
                                            {{ $ticket->title }}
                                        </a>
                                        <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                            {{ Str::limit($ticket->description, 60) }}
                                        </div>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md">
                                            📁 {{ $ticket->category->name }}
                                        </span>
                                    </td>

                                    <!-- Prioritas -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($ticket->priority == 'urgent')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                🚨 SANGAT MENDESAK
                                            </span>
                                        @elseif($ticket->priority == 'high')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                ⚠️ TINGGI
                                            </span>
                                        @elseif($ticket->priority == 'medium')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                                ⚡ SEDANG
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                                🔵 RENDAH
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($ticket->status == 'open')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-sky-100 text-sky-800 border border-sky-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                                MENUNGGU
                                            </span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                DIPROSES
                                            </span>
                                        @elseif($ticket->status == 'resolved')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                SELESAI
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                CLOSED
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Teknisi PJ -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($ticket->technician)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                                    {{ strtoupper(substr($ticket->technician->name, 0, 1)) }}
                                                </div>
                                                <span class="text-xs font-medium text-gray-800">{{ $ticket->technician->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-4 px-4 text-xs text-gray-500 whitespace-nowrap">
                                        {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </td>

                                    <!-- Tombol Detail -->
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold rounded-lg transition border border-indigo-100">
                                            <span>Lihat Detail</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 text-base">Belum Ada Tiket</h4>
                                            <p class="text-xs text-gray-500">Anda belum pernah membuat atau melaporkan kendala IT.</p>
                                            <a href="{{ route('user.tickets.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow-sm transition">
                                                + Buat Tiket Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($tickets->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>