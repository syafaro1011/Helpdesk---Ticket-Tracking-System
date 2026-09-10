<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IT Helpdesk - Ticket Tracking System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Body font: Plus Jakarta Sans via tailwind.config.js (class font-sans) -->
</head>

<body class="font-sans antialiased text-slate-700 bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen bg-slate-50">

        <!-- Sidebar Overlay (mobile only) -->
        <div x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false" x-cloak
            class="fixed inset-0 z-30 bg-slate-900/30 lg:hidden"></div>

        <!-- Sidebar (fixed on all breakpoints, width driven by sidebarCollapsed on desktop) -->
        @include('layouts.navigation')

        <!-- Main Content Area (padding follows sidebar width, so topbar never overlaps sidebar) -->
        <div :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'" class="flex flex-col min-h-screen transition-all duration-300">

            <!-- Top Bar -->
            <header class="sticky top-0 z-20 bg-white border-b border-slate-200">
                <div class="flex items-center gap-2 px-4 sm:px-6 lg:px-8 min-h-16 py-3">
                    <!-- Mobile: open sidebar -->
                    <button @click="sidebarOpen = true" type="button"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition shrink-0"
                        aria-label="Buka sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop: collapse / expand sidebar -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed" type="button"
                        class="hidden lg:inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition shrink-0"
                        :title="sidebarCollapsed ? 'Tampilkan sidebar' : 'Sembunyikan sidebar'"
                        :aria-label="sidebarCollapsed ? 'Tampilkan sidebar' : 'Sembunyikan sidebar'">
                        <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Page Heading -->
                    @if (isset($header))
                        <div class="flex-1 min-w-0">
                            {{ $header }}
                        </div>
                    @else
                        <div class="flex-1"></div>
                    @endif
                </div>
            </header>

            <!-- Breadcrumb (path halaman, otomatis mengikuti route aktif) -->
            @php
                $routeName = Route::currentRouteName();
                $homeUrl = route('home');
                $crumbs = [['label' => 'Beranda', 'url' => $homeUrl, 'home' => true]];
                switch ($routeName) {
                    case 'home':
                        break;
                    case 'user.tickets.index':
                        $crumbs[] = ['label' => 'Tiket Saya'];
                        break;
                    case 'user.tickets.create':
                        $crumbs[] = ['label' => 'Tiket Saya', 'url' => route('user.tickets.index')];
                        $crumbs[] = ['label' => 'Buat Tiket'];
                        break;
                    case 'user.tickets.show':
                        $crumbs[] = ['label' => 'Tiket Saya', 'url' => route('user.tickets.index')];
                        $crumbs[] = ['label' => 'Detail Tiket'];
                        break;
                    case 'tech.tickets.index':
                        $crumbs[] = ['label' => 'Kelola Tiket'];
                        break;
                    case 'tech.tickets.show':
                        $crumbs[] = ['label' => 'Kelola Tiket', 'url' => route('tech.tickets.index')];
                        $crumbs[] = ['label' => 'Detail Penanganan'];
                        break;
                    case 'profile.edit':
                        $crumbs[] = ['label' => 'Profil'];
                        break;
                    case 'dashboard':
                        $crumbs[] = ['label' => 'Dashboard'];
                        break;
                    default:
                        if ($routeName) {
                            $crumbs[] = ['label' => ucwords(str_replace(['.', '-', '_'], ' ', $routeName))];
                        }
                        break;
                }
            @endphp
            <nav aria-label="Breadcrumb" class="bg-white border-b border-slate-200">
                <ol class="flex items-center gap-1.5 px-4 sm:px-6 lg:px-8 py-2.5 text-xs overflow-x-auto whitespace-nowrap">
                    @foreach($crumbs as $crumb)
                        @if(!$loop->first)
                            <li aria-hidden="true" class="text-slate-300 shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </li>
                        @endif
                        <li class="flex items-center min-w-0 shrink-0">
                            @if(isset($crumb['url']))
                                <a href="{{ $crumb['url'] }}" class="flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 font-medium transition">
                                    @if(!empty($crumb['home']))
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                                        </svg>
                                    @endif
                                    {{ $crumb['label'] }}
                                </a>
                            @else
                                <span class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-none" aria-current="page">{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white py-5 mt-auto">
                <div class="px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span class="font-semibold text-slate-600">IT Helpdesk System</span> &bull; Ticket Tracking & Management
                    </div>
                    <div>
                        &copy; {{ date('Y') }} Support System. Hak Cipta Dilindungi.
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
