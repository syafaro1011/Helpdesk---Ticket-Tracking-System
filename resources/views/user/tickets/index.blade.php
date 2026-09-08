<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    {{ __('Daftar Tiket Saya') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Pantau status penanganan kendala IT dan pengajuan fasilitas Anda</p>
            </div>
            <a href="{{ route('user.tickets.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition duration-150 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-emerald-800 text-xs uppercase tracking-wider">Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tiket</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tickets->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Menunggu (Open)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'open')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Dalam Proses</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Selesai / Closed</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->whereIn('status', ['resolved', 'closed'])->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Tiket Saya -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/80 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm">Riwayat Kendala Lapangan</h3>
                    <span class="text-xs text-gray-500 font-medium">Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider">
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
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">
                                    <!-- Kode Tiket -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-100 transition">
                                            #{{ $ticket->ticket_code }}
                                        </a>
                                    </td>

                                    <!-- Judul Kendala (Scannable & Truncated) -->
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}" class="font-semibold text-gray-900 hover:text-indigo-600 truncate block transition" title="{{ $ticket->title }}">
                                            {{ $ticket->title }}
                                        </a>
                                        <p class="text-gray-400 truncate text-[11px] mt-0.5" title="{{ $ticket->description }}">
                                            {{ $ticket->description }}
                                        </p>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center text-xs font-medium text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded">
                                            {{ $ticket->category->name }}
                                        </span>
                                    </td>

                                    <!-- Prioritas (Skill.md Rules) -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
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
                                    </td>

                                    <!-- Status (Skill.md Rules) -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->status == 'open')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                                                OPEN
                                            </span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">
                                                IN PROGRESS
                                            </span>
                                        @elseif($ticket->status == 'resolved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                                                RESOLVED
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">
                                                CLOSED
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Teknisi PJ -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->technician)
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($ticket->technician->name, 0, 1)) }}
                                                </div>
                                                <span class="text-xs text-gray-800 font-medium">{{ $ticket->technician->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-3.5 px-4 text-xs text-gray-500 whitespace-nowrap">
                                        {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </td>

                                    <!-- Tombol Detail -->
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold rounded transition border border-indigo-100">
                                            <span>Detail</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 text-sm">Belum Ada Tiket</h4>
                                            <p class="text-xs text-gray-500">Anda belum pernah membuat atau melaporkan kendala IT.</p>
                                            <a href="{{ route('user.tickets.create') }}"
                                                class="inline-flex items-center px-3.5 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                                                + Buat Tiket Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200/80 bg-gray-50/50">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>