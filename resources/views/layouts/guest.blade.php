<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f766e">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="POS DSCM">
        <meta name="mobile-web-app-capable" content="yes">

        <title>{{ config('app.name', 'POS DSCM') }}</title>

        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="icon" href="{{ asset('icons/icon.svg') }}" type="image/svg+xml">
        <link rel="icon" href="{{ asset('icons/icon-192.png') }}" sizes="192x192" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <div id="pwaInstallBanner" class="hidden fixed inset-x-0 bottom-4 z-50 px-4">
                <div class="max-w-md mx-auto rounded-2xl border border-teal-200 bg-white/95 backdrop-blur px-4 py-3 shadow-lg">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Install POS DSCM</p>
                            <p class="text-xs text-slate-600">Akses lebih cepat langsung dari layar utama HP.</p>
                            <p id="pwaIosGuide" class="hidden text-xs text-slate-600 mt-1">iPhone: tekan Share lalu pilih Add to Home Screen.</p>
                        </div>
                        <button id="closeInstallBannerBtn" type="button" class="text-slate-400 hover:text-slate-600 text-sm">Tutup</button>
                    </div>
                    <button id="pwaInstallBtn" type="button" class="btn-primary w-full mt-3">Install App</button>
                </div>
            </div>
        </div>
    </body>
</html>
