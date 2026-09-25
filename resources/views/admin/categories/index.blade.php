<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.586 5.586a1 1 0 01-1.414 0L5.293 10.293A1 1 0 015 9.586V4a1 1 0 011-1z"></path>
                    </svg>
                    {{ __('Kelola Kategori') }}
                </h2>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow-xs transition duration-150 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-rose-800 text-xs uppercase tracking-wider">Terjadi Kesalahan</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Pencarian -->
            <form method="GET" action="{{ route('admin.categories.index') }}" id="category-search-form" class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="q" id="category-search" value="{{ request('q') }}" placeholder="Cari nama kategori..." autocomplete="off"
                        class="block w-full rounded-lg border-gray-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 pl-9 pr-9">
                    <span id="category-search-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-indigo-500">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center flex-1 sm:flex-none px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">Cari</button>
                    @if(request('q'))
                        <a href="{{ route('admin.categories.index') }}" id="category-search-reset"
                            class="inline-flex items-center justify-center flex-1 sm:flex-none px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-2xs transition">Reset</a>
                    @endif
                </div>
            </form>

            <!-- Tabel Kategori -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/80 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm">Daftar Kategori Kendala</h3>
                    <span id="category-page-info" class="text-xs text-gray-500 font-medium">Halaman {{ $categories->currentPage() }} dari {{ $categories->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table id="category-table" class="w-full min-w-[640px] text-left border-collapse transition-opacity duration-150">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider">
                                <th class="py-3.5 px-4 font-semibold">Nama Kategori</th>
                                <th class="py-3.5 px-4 font-semibold">Slug</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Jumlah Tiket</th>
                                <th class="py-3.5 px-4 font-semibold">Dibuat</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="category-rows" class="divide-y divide-gray-100">
                            @forelse($categories as $c)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center text-xs font-semibold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $c->name }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-xs text-gray-500">{{ $c->slug }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold tabular-nums text-gray-700">{{ $c->tickets_count }}</td>
                                    <td class="py-3.5 px-4 text-xs text-gray-500 whitespace-nowrap">{{ $c->created_at->format('d M Y, H:i') }}</td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.categories.edit', $c->id) }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white text-xs font-semibold rounded transition border border-amber-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                <span>Edit</span>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus kategori {{ $c->name }}?');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white text-xs font-semibold rounded transition border border-rose-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <h4 class="font-semibold text-gray-800 text-sm">{{ request('q') ? 'Pencarian Tidak Ditemukan' : 'Belum Ada Kategori' }}</h4>
                                            <p class="text-xs text-gray-500">{{ request('q') ? 'Tidak ada kategori yang cocok dengan pencarian "' . request('q') . '".' : 'Belum ada data kategori kendala.' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="category-pagination" class="px-6 py-4 border-t border-gray-200/80 bg-gray-50/50" @if(!$categories->hasPages()) style="display: none;" @endif>
                    {{ $categories->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
    (function () {
        const form = document.getElementById('category-search-form');
        if (!form) return;
        const input = document.getElementById('category-search');
        const rows = document.getElementById('category-rows');
        const table = document.getElementById('category-table');
        const pagination = document.getElementById('category-pagination');
        const pageInfo = document.getElementById('category-page-info');
        const loading = document.getElementById('category-search-loading');
        const csrfToken = "{{ csrf_token() }}";
        const baseIndexUrl = "{{ route('admin.categories.index') }}";
        let timer = null;

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        function rowHtml(c) {
            return '<tr class="hover:bg-gray-50/50 transition-colors duration-150 text-xs">'
                + '<td class="py-3.5 px-4"><span class="inline-flex items-center text-xs font-semibold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg">' + esc(c.name) + '</span></td>'
                + '<td class="py-3.5 px-4 font-mono text-xs text-gray-500">' + esc(c.slug) + '</td>'
                + '<td class="py-3.5 px-4 text-center font-bold tabular-nums text-gray-700">' + c.tickets_count + '</td>'
                + '<td class="py-3.5 px-4 text-xs text-gray-500 whitespace-nowrap">' + esc(c.created_at) + '</td>'
                + '<td class="py-3.5 px-4 text-center whitespace-nowrap"><div class="flex items-center justify-center gap-1.5">'
                + '<a href="' + c.edit_url + '" class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white text-xs font-semibold rounded transition border border-amber-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg><span>Edit</span></a>'
                + '<form action="' + c.destroy_url + '" method="POST" onsubmit="return confirm(\'Hapus kategori ' + esc(c.name) + '?\');" class="inline-block">'
                + '<input type="hidden" name="_token" value="' + csrfToken + '">'
                + '<input type="hidden" name="_method" value="DELETE">'
                + '<button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white text-xs font-semibold rounded transition border border-rose-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg><span>Hapus</span></button></form>'
                + '</div></td></tr>';
        }

        function emptyHtml(q) {
            return '<tr><td colspan="5" class="py-12 px-4 text-center"><div class="max-w-xs mx-auto text-center space-y-3">'
                + '<h4 class="font-semibold text-gray-800 text-sm">' + (q ? 'Pencarian Tidak Ditemukan' : 'Belum Ada Kategori') + '</h4>'
                + '<p class="text-xs text-gray-500">' + (q ? 'Tidak ada kategori yang cocok dengan pencarian &quot;' + esc(q) + '&quot;.' : 'Belum ada data kategori kendala.') + '</p>'
                + '</div></td></tr>';
        }

        function toggleReset(q) {
            let resetBtn = document.getElementById('category-search-reset');
            if (q && !resetBtn) {
                resetBtn = document.createElement('a');
                resetBtn.id = 'category-search-reset';
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

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { fetchPage(1); }, 350);
        });

        pagination.addEventListener('click', function (e) {
            const a = e.target.closest('a');
            if (!a) return;
            e.preventDefault();
            fetchPage(new URL(a.href).searchParams.get('page') || 1);
        });
    })();
    </script>
</x-app-layout>
