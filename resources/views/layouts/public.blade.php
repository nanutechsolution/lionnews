<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LionNews') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                        LionNews
                    </a>
                </div>

                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">Politik</a>
                        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">Olahraga</a>
                    </div>
                </div>

                <div class="ml-4">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" placeholder="Cari berita..." class="px-3 py-2 border border-gray-300 rounded-md text-sm" value="{{ request('q') }}"> </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">

            <div class="md:col-span-2 lg:col-span-3">
                {{ $slot }} </div>

            <aside class="md:col-span-1 lg:col-span-1 space-y-6">

                @include('layouts.partials.sidebar')

            </aside>
        </div>
    </main>
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} LionNews. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
