<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-2xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user() && in_array(Auth::user()->role, ['admin', 'technician']) ? route('tech.tickets.index') : route('user.tickets.index') }}" 
                        class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 group-hover:bg-indigo-700 text-white flex items-center justify-center font-bold shadow-sm transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="font-extrabold text-lg tracking-tight text-slate-900 group-hover:text-indigo-600 transition">
                                IT HELPDESK
                            </span>
                            <span class="block text-[10px] uppercase tracking-widest font-semibold text-slate-400 -mt-1">
                                Ticket Tracking System
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links Sesuai Role -->
                <div class="hidden space-x-2 sm:-my-px sm:flex items-center">

                    {{-- MENU UNTUK ROLE: USER / KARYAWAN --}}
                    @if(auth()->user()->role === 'user')
                        <x-nav-link :href="route('user.tickets.index')" :active="request()->routeIs('user.tickets.index')" class="text-sm font-semibold py-2 px-3 rounded-lg hover:bg-slate-50">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-500 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            {{ __('Tiket Saya') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.tickets.create')" :active="request()->routeIs('user.tickets.create')" class="text-sm font-semibold py-2 px-3 rounded-lg hover:bg-slate-50">
                            <svg class="w-4 h-4 mr-1.5 text-emerald-500 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('+ Buat Tiket') }}
                        </x-nav-link>
                    @endif

                    {{-- MENU UNTUK ROLE: TEKNISI & ADMIN --}}
                    @if(in_array(auth()->user()->role, ['technician', 'admin']))
                        <x-nav-link :href="route('tech.tickets.index')" :active="request()->routeIs('tech.tickets.*')" class="text-sm font-semibold py-2 px-3 rounded-lg hover:bg-slate-50">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-500 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                            </svg>
                            {{ __('Kelola Tiket Support') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Settings Dropdown (Profil & Logout) -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-1.5 border border-slate-200 rounded-full bg-white hover:bg-slate-50 focus:outline-none transition shadow-2xs">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left pr-1">
                                <span class="font-bold text-xs text-slate-800 block leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] font-bold tracking-wider text-indigo-600 uppercase block">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70">
                            <p class="text-xs text-slate-400 font-semibold uppercase">Akun Terhubung</p>
                            <p class="text-xs font-bold text-slate-800 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Profile Link -->
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-xs">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Pengaturan Profil') }}
                        </x-dropdown-link>

                        <!-- Authentication / Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="flex items-center gap-2 text-xs text-red-600 hover:text-red-700 hover:bg-red-50">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Tampilan Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'user')
                <x-responsive-nav-link :href="route('user.tickets.index')" :active="request()->routeIs('user.tickets.index')">
                    {{ __('Tiket Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.tickets.create')" :active="request()->routeIs('user.tickets.create')">
                    {{ __('+ Buat Tiket') }}
                </x-responsive-nav-link>
            @endif

            @if(in_array(auth()->user()->role, ['technician', 'admin']))
                <x-responsive-nav-link :href="route('tech.tickets.index')" :active="request()->routeIs('tech.tickets.*')">
                    {{ __('Kelola Tiket Support') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-200 bg-slate-50/50">
            <div class="px-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-sm text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-slate-500">{{ Auth::user()->email }} ({{ strtoupper(Auth::user()->role) }})</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Pengaturan Profil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>