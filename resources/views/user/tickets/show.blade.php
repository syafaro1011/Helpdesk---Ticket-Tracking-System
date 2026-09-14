<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.tickets.index') }}"
                    class="shadow-2xs rounded-lg border border-gray-300 bg-white p-1.5 text-gray-600 transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold tracking-tight text-gray-800">
                            Detail Tiket Saya
                        </h2>
                        <span
                            class="rounded border border-sky-100 bg-sky-50 px-2 py-0.5 font-mono text-xs font-bold text-sky-600">
                            #{{ $ticket->ticket_code }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-gray-500">Pantau status pengerjaan dan tanggapan teknisi IT Support
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if ($ticket->status == 'open')
                    <a href="{{ route('user.tickets.edit', $ticket->id) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800 shadow-sm transition hover:bg-amber-100">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Tiket
                    </a>
                    <form action="{{ route('user.tickets.destroy', $ticket->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket #{{ $ticket->ticket_code }} ini? Data yang dihapus tidak dapat dikembalikan.');"
                        class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Tiket
                        </button>
                    </form>
                    <span
                        class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        STATUS: OPEN
                    </span>
                @elseif ($ticket->status == 'in_progress')
                    <span
                        class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        STATUS: IN PROGRESS
                    </span>
                @elseif ($ticket->status == 'resolved')
                    <span
                        class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        STATUS: RESOLVED
                    </span>
                @else
                    <span
                        class="rounded-full border border-gray-200 bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                        STATUS: CLOSED
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            <!-- Flash Notification -->
            @if (session('success'))
                <div class="shadow-2xs flex items-start gap-3 rounded-r-lg border-l-4 border-emerald-500 bg-emerald-50 p-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-emerald-800">Berhasil</h4>
                        <p class="mt-0.5 text-xs text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="shadow-2xs flex items-start gap-3 rounded-r-lg border-l-4 border-rose-500 bg-rose-50 p-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-rose-800">Perhatian</h4>
                        <p class="mt-0.5 text-xs text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Progress Stepper Status -->
            @php
                $steps = ['open' => 'Diajukan', 'in_progress' => 'Dikerjakan', 'resolved' => 'Terselesaikan', 'closed' => 'Ditutup'];
                $stepKeys = array_keys($steps);
                $currentIndex = array_search($ticket->status, $stepKeys);
                $currentIndex = $currentIndex === false ? 0 : $currentIndex;
            @endphp
            <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
                <ol class="flex items-center">
                    @foreach ($steps as $key => $label)
                        <li class="flex flex-1 items-center {{ !$loop->last ? '' : 'flex-none' }}">
                            <div class="flex flex-col items-center gap-1.5">
                                <div
                                    class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-bold
                                            {{ $loop->index < $currentIndex ? 'bg-sky-600 text-white' : ($loop->index == $currentIndex ? 'bg-sky-600 text-white ring-4 ring-sky-100' : 'bg-gray-100 text-gray-400') }}">
                                    @if ($loop->index < $currentIndex)
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        {{ $loop->index + 1 }}
                                    @endif
                                </div>
                                <span
                                    class="whitespace-nowrap text-[11px] font-semibold {{ $loop->index <= $currentIndex ? 'text-gray-800' : 'text-gray-400' }}">
                                    {{ $label }}
                                </span>
                            </div>
                            @if (!$loop->last)
                                <div
                                    class="mx-2 h-0.5 flex-1 rounded {{ $loop->index < $currentIndex ? 'bg-sky-600' : 'bg-gray-100' }}">
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Kolom Kiri: Detail Tiket & Chat Log (2/3 Lebar) -->
                <div class="min-w-0 space-y-6 lg:col-span-2">

                    <!-- Kartu Detail Kendala Utama -->
                    <div class="space-y-4 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <span
                                    class="rounded border border-sky-100 bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-600">
                                    {{ $ticket->category->name }}
                                </span>
                                <h3 class="mt-2 break-words text-lg font-bold text-gray-900">{{ $ticket->title }}</h3>
                            </div>
                            <div>
                                @if ($ticket->priority == 'urgent')
                                    <span
                                        class="inline-flex animate-pulse items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                        🚨 URGENT
                                    </span>
                                @elseif ($ticket->priority == 'high')
                                    <span
                                        class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-700">
                                        HIGH
                                    </span>
                                @elseif ($ticket->priority == 'medium')
                                    <span
                                        class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700">
                                        MEDIUM
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                        LOW
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-xs text-gray-500">
                            Diajukan pada: <span
                                class="font-semibold text-gray-700">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div>
                            <h4 class="mb-1 text-xs font-bold uppercase tracking-wider text-gray-400">Deskripsi
                                Masalah</h4>
                            <div
                                class="rounded-lg border border-gray-100 bg-gray-50 p-4 text-xs leading-relaxed text-gray-700">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>

                        @if ($ticket->attachment)
                            <div class="pt-2">
                                <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">Lampiran
                                    Foto Bukti</h4>
                                <div
                                    class="shadow-2xs group relative inline-block max-w-sm overflow-hidden rounded-lg border border-gray-200">
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Bukti Kendala"
                                        class="h-auto max-h-56 w-full object-cover transition-transform duration-200 group-hover:scale-105">
                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                        class="absolute inset-0 flex items-center justify-center gap-1 bg-black/40 text-xs font-semibold text-white opacity-0 transition-opacity group-hover:opacity-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline & Catatan Diskusi -->
                    <div class="space-y-6 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 class="flex items-center gap-2 text-sm font-bold text-gray-800">
                                <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                    </path>
                                </svg>
                                Tanggapan Teknisi & Catatan Aktivitas
                            </h4>
                            <span class="text-xs font-medium text-gray-400">{{ $ticket->logs->count() }} Catatan</span>
                        </div>

                        <div class="space-y-0">
                            @forelse ($ticket->logs as $log)
                                <div
                                    class="relative flex gap-3 pb-5 {{ !$loop->last ? 'border-l border-gray-100 ml-3.5' : 'ml-3.5' }}">
                                    <div
                                        class="absolute -left-[7px] top-0.5 h-3.5 w-3.5 rounded-full border-2 border-white {{ $log->user->role == 'user' ? 'bg-gray-300' : 'bg-sky-500' }} shadow">
                                    </div>
                                    <div class="w-full pl-5">
                                        <div
                                            class="rounded-lg border p-3.5 text-xs {{ $log->user->role == 'user' ? 'border-gray-200 bg-gray-50' : 'border-sky-100 bg-sky-50/60' }}">
                                            <div class="mb-1.5 flex items-center justify-between text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-800">{{ $log->user->name }}</span>
                                                    <span
                                                        class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $log->user->role == 'user' ? 'bg-gray-200 text-gray-700' : 'bg-sky-100 text-sky-700' }}">
                                                        {{ $log->user->role == 'user' ? 'Pelapor (Anda)' : strtoupper($log->user->role) }}
                                                    </span>
                                                </div>
                                                <span
                                                    class="text-[11px] text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="leading-relaxed text-gray-700">{{ $log->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="rounded-lg border border-dashed border-gray-200 bg-gray-50 py-6 text-center text-xs italic text-gray-400">
                                    Belum ada tanggapan atau catatan aktivitas pada tiket ini.
                                </div>
                            @endforelse
                        </div>

                        @if ($ticket->status != 'closed')
                            <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST"
                                class="space-y-3 border-t border-gray-100 pt-4">
                                @csrf

                                <div>
                                    <label for="message"
                                        class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Kirim Balasan / Pertanyaan Tambahan
                                    </label>
                                    <textarea name="message" id="message" rows="3" required
                                        class="shadow-2xs w-full rounded-lg border-gray-300 p-3 text-xs focus:border-sky-500 focus:ring-sky-500"
                                        placeholder="Tuliskan pesan balasan atau informasi tambahan untuk teknisi..."></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="shadow-xs inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-sky-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>

                <!-- Kolom Kanan: Action Sidebar (1/3 Lebar) -->
                <div class="min-w-0 space-y-6 lg:sticky lg:top-6 lg:self-start">

                    <!-- Status Summary Sidebar Card -->
                    <div class="space-y-4 rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
                        <h4
                            class="flex items-center gap-2 border-b border-gray-100 pb-3 text-sm font-bold text-gray-800">
                            <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Pengerjaan
                        </h4>

                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                <span class="font-medium text-gray-500">Status Tiket</span>
                                @if ($ticket->status == 'open')
                                    <span
                                        class="inline-flex items-center rounded border border-blue-200 bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">OPEN</span>
                                @elseif ($ticket->status == 'in_progress')
                                    <span
                                        class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">IN
                                        PROGRESS</span>
                                @elseif ($ticket->status == 'resolved')
                                    <span
                                        class="inline-flex items-center rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">RESOLVED</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded border border-gray-200 bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">CLOSED</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                <span class="font-medium text-gray-500">Teknisi PJ</span>
                                @if ($ticket->technician)
                                    <span class="font-bold text-gray-800">{{ $ticket->technician->name }}</span>
                                @else
                                    <span
                                        class="rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-600">
                                        Menunggu Penunjukan
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                                <span class="font-medium text-gray-500">Kategori</span>
                                <span class="font-semibold text-gray-800">{{ $ticket->category->name }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-500">Urgensi</span>
                                @if ($ticket->priority == 'urgent')
                                    <span class="font-bold text-rose-600">URGENT</span>
                                @elseif ($ticket->priority == 'high')
                                    <span class="font-semibold text-orange-600">HIGH</span>
                                @elseif ($ticket->priority == 'medium')
                                    <span class="font-semibold text-sky-600">MEDIUM</span>
                                @else
                                    <span class="text-slate-600">LOW</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($ticket->status == 'resolved')
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 text-xs text-emerald-800">
                            <p class="flex items-center gap-2 font-bold uppercase tracking-wider">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tiket Terselesaikan
                            </p>
                            <p class="mt-2 leading-relaxed">
                                Jika masalah masih berlanjut, kirim balasan di atas atau ajukan tiket baru bila
                                kendalanya berbeda.
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>