<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IT Helpdesk - Auth') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Body font: Plus Jakarta Sans via tailwind.config.js (class font-sans) -->
</head>

<body class="font-sans text-slate-800 antialiased h-full bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo Branding -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex flex-col items-center group">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 group-hover:bg-indigo-700 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/20 transition duration-200 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-extrabold text-slate-800 tracking-tight">IT HELPDESK</span>
                <span class="text-xs uppercase tracking-widest font-semibold text-indigo-600">Ticket Tracking System</span>
            </a>
        </div>

        <!-- Form Card Container -->
        <div class="w-full sm:max-w-md bg-white shadow-xl rounded-2xl border border-slate-200 p-6 sm:p-8 space-y-6">
            {{ $slot }}
        </div>

        <!-- Footer Copyright -->
        <div class="mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} IT Helpdesk System. Hak Cipta Dilindungi.
        </div>
    </div>
</body>

</html>

