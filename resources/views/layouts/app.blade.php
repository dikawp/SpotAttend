<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="mainState" :class="{ 'dark': isDarkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'HRBT'))</title>

    <link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased overflow-y-hidden">
    <div class="flex h-dvh bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

        <!-- Desktop sidebar -->
        @include('layouts.components.sidebar')

        <!-- Mobile sidebar backdrop -->
        <div x-show="isSideMenuOpen"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
            aria-hidden="true">
        </div>

        <!-- Mobile sidebar -->
        <aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
            x-show="isSideMenuOpen" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
            @include('layouts.components.sidebar-content')
        </aside>

        <div class="flex flex-col flex-1 w-full overflow-hidden">
            <!-- Navbar -->
            @include('layouts.components.navbar')

            <!-- Page Content -->
            <main class="h-full overflow-y-auto pb-16 flex-1">
                <div class="container grid px-6 mx-auto">
                    <!-- Page Heading -->
                    @hasSection('header')
                        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                            @yield('header')
                        </h2>
                    @endif
                    
                    @include('sweetalert::alert')
                    <!-- Main Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
