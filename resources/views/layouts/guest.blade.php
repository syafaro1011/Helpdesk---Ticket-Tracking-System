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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Body font: Plus Jakarta Sans via tailwind.config.js (class font-sans) -->
</head>

<body class="font-sans text-slate-800 antialiased h-full bg-gradient-to-br from-slate-50 via-sky-50 to-indigo-50 selection:bg-sky-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">

        {{-- Dekorasi latar --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-sky-200/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-indigo-200/40 rounded-full blur-3xl"></div>
        </div>

        <!-- Form Card Container -->
        <div class="relative w-full sm:max-w-md bg-white shadow-xl shadow-slate-200/60 rounded-2xl border border-slate-200/80 p-6 sm:p-8">
            {{ $slot }}
        </div>

        <!-- Footer Copyright -->
        <div class="relative mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} IT Helpdesk System &bull; Ticket Tracking & Management
        </div>
    </div>
</body>

</html>