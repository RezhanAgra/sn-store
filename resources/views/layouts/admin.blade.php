<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' | ' : '' }}Admin {{ config('app.name', 'SN Store') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
            <x-admin.sidebar :menu="$menu" />

            <div class="flex min-h-screen flex-col lg:pl-64">
                <x-admin.topbar :title="$title ?? null" />

                <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-7xl">
                        {{ $slot }}
                    </div>
                </main>

                <footer class="border-t border-gray-100 bg-white px-4 py-4 text-center text-xs text-gray-500 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} {{ config('app.name', 'SN Store') }}. Panel Admin.
                </footer>
            </div>
        </div>
    </body>
</html>
