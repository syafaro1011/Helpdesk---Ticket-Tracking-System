<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                    {{ __('Kelola Tiket Masuk (IT Support)') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Pusat kontrol pemantauan dan penanganan tiket kendala IT pelapor</p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-2xs border border-gray-200 text-xs">
                <span class="text-gray-500">Role:</span>
                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 uppercase">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Notifications -->
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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-2xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-rose-800 text-xs uppercase tracking-wider">Terjadi Kesalahan</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tiket Masuk</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tickets->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Belum Ditangani (Open)</p>
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

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Dalam Pengerjaan</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 hover:shadow-md transition-shadow duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Selesai (Resolved)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $tickets->getCollection()->whereIn('status', ['resolved', 'closed'])->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Status Bar -->
            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Status:
                    </span>
                    
                    <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-gray-50 text-xs">
                        <a href="{{ route('tech.tickets.index') }}" 
                            class="px-3 py-1 rounded-md font-semibold transition {{ !request('status') ? 'bg-white text-indigo-600 shadow-2xs' : 'text-gray-600 hover:text-gray-900' }}">
                            Semua
                        </a>
                        <a href="{{ route('tech.tickets.index', ['status' => 'open']) }}" 
                            class="px-3 py-1 rounded-md font-semibold transition {{ request('status') == 'open' ? 'bg-blue-600 text-white shadow-2xs' : 'text-gray-600 hover:text-gray-900' }}">
                            OPEN
                        </a>
                        <a href="{{ route('tech.tickets.index', ['status' => 'in_progress']) }}" 
                            class="px-3 py-1 rounded-md font-semibold transition {{ request('status') == 'in_progress' ? 'bg-amber-500 text-white shadow-2xs' : 'text-gray-600 hover:text-gray-900' }}">
                            IN PROGRESS
                        </a>
                        <a href="{{ route('tech.tickets.index', ['status' => 'resolved']) }}" 
                            class="px-3 py-1 rounded-md font-semibold transition {{ request('status') == 'resolved' ? 'bg-emerald-600 text-white shadow-2xs' : 'text-gray-600 hover:text-gray-900' }}">
                            RESOLVED
                        </a>
                        <a href="{{ route('tech.tickets.index', ['status' => 'closed']) }}" 
                            class="px-3 py-1 rounded-md font-semibold transition {{ request('status') == 'closed' ? 'bg-gray-600 text-white shadow-2xs' : 'text-gray-600 hover:text-gray-900' }}">
                            CLOSED
                        </a>
                    </div>
                </div>

                @if(request('status'))
                    <a href="{{ route('tech.tickets.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reset Filter
                    </a>
                @endif
            </div>

            <!-- Tabel Daftar Tiket Masuk -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/80 text-gray-500 uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">Kode Tiket</th>
                                <th class="py-3.5 px-4 font-semibold">Pelapor</th>
                                <th class="py-3.5 px-4 font-semibold">Judul Kendala</th>
                                <th class="py-3.5 px-4 font-semibold">Kategori</th>
                                <th class="py-3.5 px-4 font-semibold">Urgensi</th>
                                <th class="py-3.5 px-4 font-semibold">Status</th>
                                <th class="py-3.5 px-4 font-semibold">Teknisi PJ</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">
                                    <!-- Kode Tiket -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        <a href="{{ route('tech.tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-100 transition">
                                            #{{ $ticket->ticket_code }}
                                        </a>
                                    </td>

                                    <!-- Nama Pelapor -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center font-bold text-xs border border-gray-200">
                                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $ticket->user->name }}</div>
                                                <div class="text-[11px] text-gray-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Judul Kendala (Truncated for Scannability) -->
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <a href="{{ route('tech.tickets.show', $ticket->id) }}" class="font-semibold text-gray-900 hover:text-indigo-600 truncate block transition" title="{{ $ticket->title }}">
                                            {{ $ticket->title }}
                                        </a>
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

                                    <!-- Teknisi Penanggung Jawab -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ticket->technician)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-800 rounded text-xs font-medium">
                                                <span>👤</span> {{ $ticket->technician->name }}
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-600 rounded text-xs font-medium italic">
                                                Belum Ada PJ
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Tombol Detail / Handling -->
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('tech.tickets.show', $ticket->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded shadow-2xs transition">
                                            <span>Detail & Handling</span>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 text-sm">Tidak Ada Tiket</h4>
                                            <p class="text-xs text-gray-500">Tidak ada tiket yang cocok dengan kriteria filter ini.</p>
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