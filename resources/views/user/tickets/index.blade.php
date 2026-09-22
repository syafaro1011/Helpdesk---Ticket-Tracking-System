<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <!-- <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                        </path>
                    </svg> -->
                    {{ __('Daftar Tiket Saya') }}
                </h2>
               
            </div>
            <a href="{{ route('user.tickets.create') }}"
                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow-xs transition duration-150 gap-2">
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
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-emerald-800 text-xs uppercase tracking-wider">Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tiket</p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">{{ $tickets->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Menunggu (Open)</p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'open')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Dalam Proses</p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-xs p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Selesai / Closed</p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">
                            {{ $tickets->getCollection()->whereIn('status', ['resolved', 'closed'])->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pencarian Tiket -->
            <form method="GET" action="{{ route('user.tickets.index') }}" id="ticket-search-form" class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="q" id="ticket-search" value="{{ request('q') }}" placeholder="Cari kode tiket atau judul kendala..." autocomplete="off"
                        {{ request('q') ? 'autofocus' : '' }}
                        class="block w-full rounded-lg border-gray-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 pl-9 pr-9">
                    <span id="ticket-search-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-indigo-500">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center flex-1 sm:flex-none px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                        Cari
                    </button>
                    @if(request('q'))
                        <a href="{{ route('user.tickets.index') }}" id="ticket-search-reset"
                            class="inline-flex items-center justify-center flex-1 sm:flex-none px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-2xs transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabel Daftar Tiket Saya -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/80 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm">Riwayat Kendala Lapangan</h3>
                    <span id="ticket-page-info" class="text-xs text-gray-500 font-medium">Halaman {{ $tickets->currentPage() }} dari
                        {{ $tickets->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table id="ticket-table" class="w-full min-w-[880px] text-left border-collapse transition-opacity duration-150">
                        <thead>
                            <tr
                                class="border-b border-gray-200/80 bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider">
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
                        <tbody id="ticket-rows" class="divide-y divide-gray-100">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">
                                    <!-- Kode Tiket -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-100 transition">
                                            #{{ $ticket->ticket_code }}
                                        </a>
                                    </td>

                                    <!-- Judul Kendala (Scannable & Truncated) -->
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                            class="font-semibold text-gray-900 hover:text-sky-600 truncate block transition"
                                            title="{{ $ticket->title }}">
                                            {{ $ticket->title }}
                                        </a>
                                        <p class="text-gray-400 truncate text-[11px] mt-0.5"
                                            title="{{ $ticket->description }}">
                                            {{ $ticket->description }}
                                        </p>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center text-xs font-medium text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded">
                                            {{ $ticket->category->name }}
                                        </span>
                                    </td>

                                    <!-- Prioritas (Skill.md Rules) -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->priority == 'urgent')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-rose-100 text-rose-700 font-bold animate-pulse">
                                                🚨 URGENT
                                            </span>
                                        @elseif($ticket->priority == 'high')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700 font-semibold">
                                                HIGH
                                            </span>
                                        @elseif($ticket->priority == 'medium')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-sky-100 text-sky-700">
                                                MEDIUM
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600">
                                                LOW
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status (Skill.md Rules) -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->status == 'open')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                                                OPEN
                                            </span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">
                                                IN PROGRESS
                                            </span>
                                        @elseif($ticket->status == 'resolved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                                                RESOLVED
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">
                                                CLOSED
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Teknisi PJ -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->technician)
                                            <div class="flex items-center gap-1.5">
                                                <div
                                                    class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($ticket->technician->name, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-xs text-gray-800 font-medium">{{ $ticket->technician->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-3.5 px-4 text-xs text-gray-500 whitespace-nowrap">
                                        {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </td>

                                    <!-- Tombol Detail & Edit -->
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($ticket->status == 'open')
                                                <a href="{{ route('user.tickets.edit', $ticket->id) }}"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white text-xs font-semibold rounded transition border border-amber-200"
                                                    title="Edit Tiket">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>
                                                <form action="{{ route('user.tickets.destroy', $ticket->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket #{{ $ticket->ticket_code }} ini?');"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white text-xs font-semibold rounded transition border border-rose-200"
                                                        title="Hapus Tiket">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold rounded transition border border-indigo-100">
                                                <span>Detail</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div
                                                class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 text-sm">{{ request('q') ? 'Pencarian Tidak Ditemukan' : 'Belum Ada Tiket' }}</h4>
                                            <p class="text-xs text-gray-500">
                                                {{ request('q') ? 'Tidak ada tiket yang cocok dengan pencarian "' . request('q') . '".' : 'Anda belum pernah membuat atau melaporkan kendala IT.' }}
                                            </p>
                                            <a href="{{ route('user.tickets.create') }}"
                                                class="inline-flex items-center px-3.5 py-1.5 bg-sky-600 text-white text-xs font-semibold rounded-lg hover:bg-sky-700 transition">
                                                + Buat Tiket Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="ticket-pagination" class="px-6 py-4 border-t border-gray-200/80 bg-gray-50/50" @if(!$tickets->hasPages()) style="display: none;" @endif>
                    {{ $tickets->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
    (function () {
        const form = document.getElementById('ticket-search-form');
        if (!form) return;
        const input = document.getElementById('ticket-search');
        const rows = document.getElementById('ticket-rows');
        const table = document.getElementById('ticket-table');
        const pagination = document.getElementById('ticket-pagination');
        const pageInfo = document.getElementById('ticket-page-info');
        const loading = document.getElementById('ticket-search-loading');
        const csrfToken = "{{ csrf_token() }}";
        const baseIndexUrl = "{{ route('user.tickets.index') }}";
        const createUrl = "{{ route('user.tickets.create') }}";
        let timer = null;

        // Pindahkan kursor ke akhir teks saat autofocus (biar enak lanjut ngetik)
        if (document.activeElement === input) {
            const len = input.value.length;
            try { input.setSelectionRange(len, len); } catch (e) {}
        }

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        function priorityBadge(p) {
            if (p === 'urgent') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-rose-100 text-rose-700 font-bold animate-pulse">🚨 URGENT</span>';
            if (p === 'high') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700 font-semibold">HIGH</span>';
            if (p === 'medium') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-sky-100 text-sky-700">MEDIUM</span>';
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600">LOW</span>';
        }

        function statusBadge(s) {
            if (s === 'open') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">OPEN</span>';
            if (s === 'in_progress') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">IN PROGRESS</span>';
            if (s === 'resolved') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">RESOLVED</span>';
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">CLOSED</span>';
        }

        function techCell(name) {
            if (name) {
                const initial = esc(String(name).charAt(0).toUpperCase());
                return '<div class="flex items-center gap-1.5"><div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">' + initial + '</div><span class="text-xs text-gray-800 font-medium">' + esc(name) + '</span></div>';
            }
            return '<span class="text-xs text-gray-400 italic">Belum ditugaskan</span>';
        }

        function actionCell(t) {
            let html = '<div class="flex items-center justify-center gap-1.5">';
            if (t.can_edit) {
                html += '<a href="' + t.edit_url + '" class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white text-xs font-semibold rounded transition border border-amber-200" title="Edit Tiket">'
                    + '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'
                    + '<span>Edit</span></a>';
                html += '<form action="' + t.destroy_url + '" method="POST" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus tiket #' + esc(t.ticket_code) + ' ini?\');" class="inline-block">'
                    + '<input type="hidden" name="_token" value="' + csrfToken + '">'
                    + '<input type="hidden" name="_method" value="DELETE">'
                    + '<button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white text-xs font-semibold rounded transition border border-rose-200" title="Hapus Tiket">'
                    + '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
                    + '<span>Hapus</span></button></form>';
            }
            html += '<a href="' + t.show_url + '" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold rounded transition border border-indigo-100">'
                + '<span>Detail</span>'
                + '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>';
            return html + '</div>';
        }

        function rowHtml(t) {
            return '<tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">'
                + '<td class="py-3.5 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap"><a href="' + t.show_url + '" class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-100 transition">#' + esc(t.ticket_code) + '</a></td>'
                + '<td class="py-3.5 px-4 max-w-xs"><a href="' + t.show_url + '" class="font-semibold text-gray-900 hover:text-sky-600 truncate block transition" title="' + esc(t.title) + '">' + esc(t.title) + '</a>'
                + '<p class="text-gray-400 truncate text-[11px] mt-0.5" title="' + esc(t.description) + '">' + esc(t.description) + '</p></td>'
                + '<td class="py-3.5 px-4 whitespace-nowrap"><span class="inline-flex items-center text-xs font-medium text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded">' + esc(t.category_name) + '</span></td>'
                + '<td class="py-3.5 px-4 whitespace-nowrap">' + priorityBadge(t.priority) + '</td>'
                + '<td class="py-3.5 px-4 whitespace-nowrap">' + statusBadge(t.status) + '</td>'
                + '<td class="py-3.5 px-4 whitespace-nowrap">' + techCell(t.technician_name) + '</td>'
                + '<td class="py-3.5 px-4 text-xs text-gray-500 whitespace-nowrap">' + esc(t.created_at) + '</td>'
                + '<td class="py-3.5 px-4 text-center whitespace-nowrap">' + actionCell(t) + '</td>'
                + '</tr>';
        }

        function emptyHtml(q) {
            const title = q ? 'Pencarian Tidak Ditemukan' : 'Belum Ada Tiket';
            const desc = q ? 'Tidak ada tiket yang cocok dengan pencarian &quot;' + esc(q) + '&quot;.' : 'Anda belum pernah membuat atau melaporkan kendala IT.';
            return '<tr><td colspan="8" class="py-12 px-4 text-center"><div class="max-w-xs mx-auto text-center space-y-3">'
                + '<div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>'
                + '<h4 class="font-semibold text-gray-800 text-sm">' + title + '</h4>'
                + '<p class="text-xs text-gray-500">' + desc + '</p>'
                + '<a href="' + createUrl + '" class="inline-flex items-center px-3.5 py-1.5 bg-sky-600 text-white text-xs font-semibold rounded-lg hover:bg-sky-700 transition">+ Buat Tiket Sekarang</a>'
                + '</div></td></tr>';
        }

        function toggleReset(q) {
            let resetBtn = document.getElementById('ticket-search-reset');
            if (q && !resetBtn) {
                resetBtn = document.createElement('a');
                resetBtn.id = 'ticket-search-reset';
                resetBtn.href = baseIndexUrl;
                resetBtn.className = 'inline-flex items-center justify-center flex-1 sm:flex-none px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-2xs transition';
                resetBtn.textContent = 'Reset';
                form.querySelector('.flex.gap-2').appendChild(resetBtn);
            } else if (!q && resetBtn) {
                resetBtn.remove();
            }
        }

        async function fetchPage(page) {
            const params = new URLSearchParams(new FormData(form));
            params.set('ajax', '1');
            if (page && Number(page) > 1) { params.set('page', page); } else { params.delete('page'); }
            loading.classList.remove('hidden');
            table.classList.add('opacity-50');
            try {
                const res = await fetch(form.action + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('request failed');
                const json = await res.json();
                const q = params.get('q') || '';
                rows.innerHTML = json.data.length ? json.data.map(rowHtml).join('') : emptyHtml(q);
                pagination.innerHTML = json.pagination || '';
                pagination.style.display = json.last_page > 1 ? '' : 'none';
                if (pageInfo) pageInfo.textContent = 'Halaman ' + json.current_page + ' dari ' + json.last_page;
                toggleReset(q);
                const clean = new URLSearchParams(params);
                clean.delete('ajax'); clean.delete('page');
                if (json.current_page > 1) clean.set('page', json.current_page);
                history.replaceState(null, '', form.action + (clean.toString() ? '?' + clean.toString() : ''));
            } catch (e) {
                form.submit();
            } finally {
                loading.classList.add('hidden');
                table.classList.remove('opacity-50');
            }
        }

        // Cari langsung tiap huruf (debounce 350ms) — tanpa perlu tekan tombol Cari
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { fetchPage(1); }, 350);
        });

        // Pagination tetap jalan via AJAX agar query pencarian tidak hilang
        pagination.addEventListener('click', function (e) {
            const a = e.target.closest('a');
            if (!a) return;
            e.preventDefault();
            fetchPage(new URL(a.href).searchParams.get('page') || 1);
        });
    })();
    </script>
</x-app-layout>