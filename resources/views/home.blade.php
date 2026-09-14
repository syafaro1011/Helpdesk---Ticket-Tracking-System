<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight">
                {{ __('Beranda') }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">Ringkasan aktivitas dan status tiket terkini</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu Sapaan Selamat Datang --}}
            <div
                class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-500 via-sky-600 to-blue-600 shadow-xl shadow-sky-500/10 text-white">
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
                    <div class="absolute -top-16 -left-16 w-56 h-56 bg-white/15 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-cyan-300/20 rounded-full blur-2xl"></div>
                </div>

                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 sm:p-8 z-10">
                    <div class="space-y-3 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md border border-white/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                                {{ $isTech ? 'Teknisi' : 'Pengguna' }}
                            </span> -->
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-sky-50 bg-black/15 px-3 py-1 rounded-full backdrop-blur-md border border-white/20">
                                <svg class="w-3.5 h-3.5 text-sky-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-sky-100">
                                Selamat datang kembali,
                            </p>
                            <h3
                                class="mt-1 font-extrabold text-white text-2xl sm:text-3xl tracking-tight leading-tight">
                                {{ Auth::user()->name }} 👋
                            </h3>
                        </div>

                        <p class="text-xs sm:text-sm text-sky-50 leading-relaxed font-normal">
                            {{ $isTech ? 'Pantau tiket masuk dan segera tangani laporan kendala yang membutuhkan perhatian.' : 'Laporkan kendala IT Anda dan pantau progres penanganannya dengan cepat dan mudah.' }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center gap-3">
                        <a href="{{ $isTech ? route('tech.tickets.index') : route('user.tickets.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-sky-50 text-sky-700 text-xs sm:text-sm font-bold rounded-xl shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            <span>{{ $isTech ? 'Kelola Tiket Masuk' : 'Buat Tiket Baru' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @if($isTech && ($stats['unassigned'] ?? 0) > 0)
                <div
                    class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg shadow-sm flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex items-start gap-3 flex-1">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-amber-800 text-xs uppercase tracking-wider">Perlu Perhatian</h4>
                            <p class="text-xs text-amber-700 mt-0.5">Ada {{ $stats['unassigned'] }} tiket aktif yang belum
                                ditugaskan ke teknisi.</p>
                        </div>
                    </div>
                    <a href="{{ route('tech.tickets.index', ['status' => 'open']) }}"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Lihat Tiket Open
                    </a>
                </div>
            @endif

            {{-- Ringkasan Statistik --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 truncate">
                            {{ $isTech ? 'Total Tiket Masuk' : 'Total Tiket Saya' }}
                        </p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">{{ $stats['total'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 truncate">Menunggu (Open)
                        </p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">{{ $stats['open'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600 truncate">Dalam Proses
                        </p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">
                            {{ $stats['in_progress'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 truncate">Selesai</p>
                        <p class="text-xl sm:text-2xl font-bold tabular-nums text-gray-800 mt-1">{{ $stats['done'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Tiket Terbaru --}}
                <div
                    class="lg:col-span-2 min-w-0 bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200/80 flex items-center justify-between bg-gray-50/50 gap-2">
                        <h3 class="font-bold text-gray-800 text-sm">Tiket Terbaru</h3>
                        <a href="{{ $isTech ? route('tech.tickets.index') : route('user.tickets.index') }}"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition shrink-0">
                            Lihat Semua
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="w-full {{ $isTech ? 'min-w-[760px]' : 'min-w-[640px]' }} text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-gray-200/80 bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider">
                                    <th class="py-3 px-4 font-semibold">Kode</th>
                                    <th class="py-3 px-4 font-semibold">Judul Kendala</th>
                                    @if($isTech)
                                        <th class="py-3 px-4 font-semibold">Pelapor</th>
                                    @endif
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                    <th class="py-3 px-4 font-semibold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentTickets as $ticket)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">
                                        <td class="py-3 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                            <a href="{{ $isTech ? route('tech.tickets.show', $ticket->id) : route('user.tickets.show', $ticket->id) }}"
                                                class="inline-flex items-center px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-100 transition">
                                                #{{ $ticket->ticket_code }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <a href="{{ $isTech ? route('tech.tickets.show', $ticket->id) : route('user.tickets.show', $ticket->id) }}"
                                                class="font-semibold text-gray-900 hover:text-indigo-600 truncate block transition"
                                                title="{{ $ticket->title }}">
                                                {{ $ticket->title }}
                                            </a>
                                        </td>
                                        @if($isTech)
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <span class="text-xs text-gray-800 font-medium">{{ $ticket->user->name }}</span>
                                            </td>
                                        @endif
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if($ticket->status == 'open')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200 font-medium">OPEN</span>
                                            @elseif($ticket->status == 'in_progress')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-amber-50 text-amber-700 border border-amber-200 font-medium">IN
                                                    PROGRESS</span>
                                            @elseif($ticket->status == 'resolved')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">RESOLVED</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600 border border-gray-200 font-medium">CLOSED</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-xs text-gray-500 whitespace-nowrap">
                                            {{ $ticket->created_at->format('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isTech ? 5 : 4 }}" class="py-10 px-4 text-center">
                                            <div class="max-w-xs mx-auto text-center space-y-2">
                                                <h4 class="font-semibold text-gray-800 text-sm">Belum Ada Tiket</h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $isTech ? 'Belum ada tiket masuk dari pelapor.' : 'Anda belum pernah melaporkan kendala IT.' }}
                                                </p>
                                                @if(!$isTech)
                                                    <a href="{{ route('user.tickets.create') }}"
                                                        class="inline-flex items-center px-3.5 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                                                        + Buat Tiket Sekarang
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Aksi Cepat --}}
                <div class="space-y-6 min-w-0">
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 space-y-3">
                        <h4 class="font-bold text-gray-800 text-sm border-b border-gray-100 pb-3">Aksi Cepat</h4>
                        @if(!$isTech)
                            <a href="{{ route('user.tickets.create') }}"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Tiket Baru
                            </a>
                            <a href="{{ route('user.tickets.index') }}"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                                Lihat Tiket Saya
                            </a>
                        @else
                            <a href="{{ route('tech.tickets.index') }}"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                Kelola Tiket Masuk
                            </a>
                            <a href="{{ route('tech.tickets.index', ['status' => 'open']) }}"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                                Tiket Belum Ditangani
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                            Pengaturan Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 text-xs font-semibold rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar (Log Out)
                            </button>
                        </form>
                    </div>

                    <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl text-xs text-indigo-900">
                        <span class="font-bold">Tips:</span>
                        <span class="text-indigo-800">
                            {{ $isTech ? 'Prioritaskan tiket berlabel URGENT dan yang belum memiliki teknisi penanggung jawab.' : 'Lampirkan foto/screenshot error agar teknisi lebih cepat menangani laporan Anda.' }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>