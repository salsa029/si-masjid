<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - Masjid An-Nur</title>

    <!-- Vite Asset -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-700 to-emerald-500 p-4 md:p-8">
        <!-- Decorative elements -->
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="absolute left-1/2 top-1/2 h-36 w-36 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/10">
        </div>

        <div class="relative z-10 w-full max-w-md">

            <!-- Card -->
            <div class="rounded-2xl p-6 md:p-8">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-xs text-green-200">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
