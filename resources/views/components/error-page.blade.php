@props(['code', 'heading', 'message'])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <title>{{ $code }} — {{ $heading }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-primary-gradient flex min-h-screen items-center justify-center px-4 font-sans">
    <div class="pattern-geometric pointer-events-none fixed inset-0"></div>

    <div class="relative w-full max-w-md text-center text-white" data-reveal>
        <a href="{{ route('public.home') }}" class="mb-8 inline-flex items-center gap-2.5">
            <span class="rounded-arch flex h-11 w-11 items-center justify-center bg-white/15">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                    <path d="M12 2 3 7v2h18V7L12 2Zm-7 8v9h4v-6h6v6h4v-9H5Z" />
                </svg>
            </span>
            <span class="font-display text-lg font-bold">{{ config('app.name', 'SI-MASJID') }}</span>
        </a>

        <p class="font-display text-gold-300 text-7xl font-extrabold sm:text-8xl">{{ $code }}</p>
        <h1 class="font-display mt-3 text-xl font-bold sm:text-2xl">{{ $heading }}</h1>
        <p class="text-primary-50/80 mx-auto mt-3 max-w-sm text-sm leading-relaxed">{{ $message }}</p>

        <a href="{{ route('public.home') }}" class="btn-gold mt-8 inline-flex">Kembali ke Beranda</a>
    </div>
</body>

</html>
