<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Default Title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <!-- Fix Date Picker Indicator Icon in Light Mode -->
        <style>
            html {
                color-scheme: light;
            }
            input[type="date"],
            input[type="datetime-local"] {
                color-scheme: light !important;
            }
            input[type="date"]::-webkit-calendar-picker-indicator,
            input[type="datetime-local"]::-webkit-calendar-picker-indicator,
            input[type="time"]::-webkit-calendar-picker-indicator,
            input[type="month"]::-webkit-calendar-picker-indicator {
                cursor: pointer !important;
                filter: invert(0) brightness(0.2) !important;
                opacity: 0.8 !important;
                transition: opacity 0.2s ease, transform 0.1s ease;
            }
            input[type="date"]::-webkit-calendar-picker-indicator:hover,
            input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover,
            input[type="time"]::-webkit-calendar-picker-indicator:hover,
            input[type="month"]::-webkit-calendar-picker-indicator:hover {
                opacity: 1 !important;
                filter: invert(0) brightness(0) !important;
                transform: scale(1.08);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{-- CTRL + / for commenting code --}}
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
    </body>
</html>
