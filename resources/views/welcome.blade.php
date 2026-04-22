<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'LMS') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @php
            $redirectUrl = auth()->check()
                ? route('student.dashboard')
                : route('login');
        @endphp

        <meta http-equiv="refresh" content="0;url={{ $redirectUrl }}">
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <main class="min-h-screen flex items-center justify-center px-6">
            <div class="max-w-md text-center">
                <p class="text-sm text-slate-300">
                    Redirigiendo al portal de acceso…
                </p>
                <noscript>
                    <p class="mt-3 text-sm text-slate-300">
                        Si no redirige automáticamente, entra aquí:
                        <a class="underline underline-offset-4 text-white" href="{{ $redirectUrl }}">Ir al portal</a>
                    </p>
                </noscript>
            </div>
        </main>

        <script>
            window.location.replace(@js($redirectUrl));
        </script>
    </body>
</html>
