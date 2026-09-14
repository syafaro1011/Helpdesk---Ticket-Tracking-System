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

<body class="font-sans text-slate-800 antialiased h-full bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">

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