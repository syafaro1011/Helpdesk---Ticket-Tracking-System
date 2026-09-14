{{-- Sidebar Navigation (fixed; visibility & width driven by parent Alpine state in app.blade.php) --}}
<aside :class="{ '-translate-x-full': !sidebarOpen, 'lg:w-20': sidebarCollapsed, 'lg:w-64': !sidebarCollapsed }"
    class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 bg-white border-r border-slate-200 transition-all duration-300 lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-200 shrink-0 overflow-hidden">
        <a href="{{ route('home') }}"
            class="flex items-center gap-2.5 group flex-shrink-0 min-w-0">
            <img src="{{ asset('images/fix_logo.webp') }}" alt="Logo IT Helpdesk"
                class="w-8 h-8 rounded-lg object-cover shadow-sm shrink-0 group-hover:opacity-90 transition">
            <div class="overflow-hidden transition-all duration-300 ease-in-out"
                :class="sidebarCollapsed ? 'lg:w-0 lg:opacity-0' : 'lg:w-auto lg:opacity-100'">
                <span class="font-extrabold text-sm tracking-tight text-slate-800 group-hover:text-sky-600 transition whitespace-nowrap">IT HELPDESK</span>
                <span class="block text-[10px] uppercase tracking-wider font-semibold text-slate-400 -mt-0.5 whitespace-nowrap">Ticket Tracking</span>
            </div>
        </a>

        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false" type="button"
            class="lg:hidden ml-auto p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition flex-shrink-0"
            aria-label="Tutup sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation Section Label --}}
    <div class="px-4 pt-6 pb-2 overflow-hidden transition-all duration-300 ease-in-out"
        :class="sidebarCollapsed ? 'lg:h-0 lg:py-0 lg:opacity-0' : 'lg:h-auto lg:opacity-100'">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Menu</p>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
        <a href="{{ route('home') }}" @click="sidebarOpen = false"
            class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                {{ request()->routeIs('home') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            title="Beranda" :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('home') ? 'text-sky-600' : 'text-slate-400' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            <span class="transition-all duration-300 ease-in-out whitespace-nowrap"
                :class="sidebarCollapsed ? 'lg:opacity-0 lg:w-0 lg:invisible' : 'lg:opacity-100 lg:w-auto lg:visible'">Beranda</span>
        </a>

        @if(auth()->user()->role === 'user')
            <a href="{{ route('user.tickets.index') }}" @click="sidebarOpen = false"
                class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                    {{ request()->routeIs('user.tickets.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                title="Tiket Saya" :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('user.tickets.index') ? 'text-sky-600' : 'text-slate-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="transition-all duration-300 ease-in-out whitespace-nowrap"
                    :class="sidebarCollapsed ? 'lg:opacity-0 lg:w-0 lg:invisible' : 'lg:opacity-100 lg:w-auto lg:visible'">Tiket Saya</span>
            </a>

            <a href="{{ route('user.tickets.create') }}" @click="sidebarOpen = false"
                class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                    {{ request()->routeIs('user.tickets.create') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                title="Buat Tiket" :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('user.tickets.create') ? 'text-sky-600' : 'text-slate-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="transition-all duration-300 ease-in-out whitespace-nowrap"
                    :class="sidebarCollapsed ? 'lg:opacity-0 lg:w-0 lg:invisible' : 'lg:opacity-100 lg:w-auto lg:visible'">Buat Tiket</span>
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['technician', 'admin']))
            <a href="{{ route('tech.tickets.index') }}" @click="sidebarOpen = false"
                class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                    {{ request()->routeIs('tech.tickets.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                title="Kelola Tiket" :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('tech.tickets.*') ? 'text-sky-600' : 'text-slate-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                <span class="transition-all duration-300 ease-in-out whitespace-nowrap"
                    :class="sidebarCollapsed ? 'lg:opacity-0 lg:w-0 lg:invisible' : 'lg:opacity-100 lg:w-auto lg:visible'">Kelola Tiket</span>
            </a>
        @endif
    </nav>

    {{-- User Profile Section --}}
    <div class="border-t border-slate-200 p-3 shrink-0 transition-all duration-300 ease-in-out"
        :class="sidebarCollapsed ? 'lg:px-2' : 'lg:px-3'">
        <x-dropdown align="top" width="56">
            <x-slot name="trigger">
                <button type="button"
                    class="relative flex items-center gap-3 w-full px-2 py-2.5 rounded-lg hover:bg-slate-100 transition-all duration-150 text-left"
                    :class="sidebarCollapsed ? 'lg:justify-center' : ''"
                    title="{{ Auth::user()->name }} ({{ Auth::user()->role }})">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="sidebarCollapsed ? 'lg:w-0 lg:opacity-0 lg:invisible' : 'lg:w-auto lg:opacity-100 lg:visible'">
                        <span class="block text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->name }}</span>
                        <span class="block text-[10px] font-bold tracking-wider text-sky-600 uppercase truncate">{{ Auth::user()->role }}</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-all duration-300 ease-in-out"
                        :class="sidebarCollapsed ? 'lg:opacity-0 lg:w-0 lg:invisible' : 'lg:opacity-100 lg:w-auto lg:visible'" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2.5 border-b border-slate-200 bg-slate-50">
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Akun Terhubung</p>
                    <p class="text-xs font-bold text-slate-700 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-xs">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Pengaturan Profil') }}
                </x-dropdown-link>

                <x-dropdown-link href="#" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-logout')"
                    class="flex items-center gap-2 text-xs text-rose-600 hover:bg-rose-50">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Keluar (Log Out)') }}
                    </x-dropdown-link>
            </x-slot>
        </x-dropdown>
    </div>
</aside>
