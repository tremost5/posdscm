<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f766e">
        <meta name="description" content="POS DSCM - Sistem kasir cepat, ringan, dan responsif.">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="POS DSCM">
        <meta name="mobile-web-app-capable" content="yes">

        <title>{{ config('app.name', 'POS DSCM') }}</title>

        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="icon" href="{{ asset('icons/icon.svg') }}" type="image/svg+xml">
        <link rel="icon" href="{{ asset('icons/icon-192.png') }}" sizes="192x192" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-ui antialiased bg-slate-100 text-slate-800">
        <div class="min-h-screen bg-app-pattern">
            @include('layouts.navigation')

            @isset($header)
                <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="rounded-2xl bg-white/90 backdrop-blur border border-slate-200 shadow-sm px-6 py-5">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">
                @if (session('success'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-900 px-4 py-3 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{ $slot }}
            </main>

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
