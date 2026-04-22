<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SEIA') }} - @yield('title', 'Dashboard')</title>

    @php
        /** @var \App\Support\Branding\BrandingRepository $branding */
        $branding = app(\App\Support\Branding\BrandingRepository::class);
        $brandingSettings = $branding->get();
        $faviconUrl = $brandingSettings->favicon_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($brandingSettings->favicon_path)
            : null;
    @endphp

    @if ($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Prevent flash of unstyled content -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface dark:bg-secondary antialiased transition-colors duration-200">
    <x-student-navigation />

    <main class="min-h-[calc(100vh-4rem)]">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>

