<section class="space-y-6">
    <header>
        <p class="text-xs text-gray-500">
            Hubungkan akun Telegram Anda untuk dapat mengajukan kendala IT secara otomatis via chat Telegram dengan kecerdasan buatan (AI).
        </p>
    </header>

    @if (Auth::user()->telegram_chat_id)
        {{-- Status Terhubung --}}
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        Akun Telegram Terhubung
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                            Aktif
                        </span>
                    </h4>
                    <p class="text-xs text-gray-600 mt-0.5">
                        ID Telegram: <code class="bg-white px-1.5 py-0.5 rounded border border-emerald-200 font-mono text-emerald-800">{{ Auth::user()->telegram_chat_id }}</code>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Anda sudah dapat mengirimkan aduan kendala IT secara langsung ke bot Telegram kami kapan saja.
                    </p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.telegram.unlink') }}">
                @csrf
                @method('delete')
                <x-danger-button onclick="return confirm('Apakah Anda yakin ingin memutus penautan akun Telegram?')">
                    {{ __('Putus Tautan') }}
                </x-danger-button>
            </form>
        </div>
    @else
        {{-- Status Belum Terhubung --}}
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                    Belum Terhubung
                </span>
            </div>

            @php
                $activeCode = session('telegram_code_generated') ?? Auth::user()->telegram_verification_code;
                $botUsername = config('services.telegram.bot_username', 'MyHelpdeskBot');
            @endphp

            @if ($activeCode)
                <div class="rounded-xl border border-indigo-200 bg-indigo-50/60 p-4 sm:p-5 space-y-3">
                    <h4 class="font-bold text-indigo-900 text-sm">Langkah Penautan Akun:</h4>
                    <ol class="list-decimal list-inside text-xs text-indigo-950 space-y-2 leading-relaxed">
                        <li>Buka aplikasi <strong>Telegram</strong> Anda.</li>
                        <li>Cari dan buka percakapan dengan Bot kami: 
                            <a href="https://t.me/{{ ltrim($botUsername, '@') }}" target="_blank" class="font-bold text-indigo-700 underline hover:text-indigo-900">
                                {{ $botUsername }}
                            </a>
                        </li>
                        <li>Kirimkan pesan perintah di bawah ini ke bot:</li>
                    </ol>

                    <div class="mt-2 p-3 bg-white rounded-lg border border-indigo-200 flex items-center justify-between gap-3">
                        <code class="font-mono font-bold text-sm text-indigo-700 select-all">/start {{ $activeCode }}</code>
                        <span class="text-[11px] text-gray-400">Salin kode ini</span>
                    </div>

                    <p class="text-[11px] text-indigo-700 italic">
                        * Bot akan otomatis membalas dan mengonfirmasi bahwa akun Telegram Anda sudah terhubung.
                    </p>
                </div>
            @endif

            <form method="post" action="{{ route('profile.telegram.code') }}">
                @csrf
                <x-primary-button>
                    {{ $activeCode ? __('Buat Ulang Kode Tautan') : __('Generate Kode Tautan Telegram') }}
                </x-primary-button>
            </form>
        </div>
    @endif
</section>
