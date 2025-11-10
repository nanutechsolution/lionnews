<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1E3A8A">
    <link rel="apple-touch-icon" href="{{ asset('logos/web-app-manifest-192x192.png') }}">
    <title>{{ config('app.name', 'LionNews') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=merriweather-sans:400,700" rel="stylesheet" />

    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen">

        @include('layouts.navigation')

        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow dark:shadow-none dark:border-b dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    <div x-data="mediaLibrary()" x-show="isOpen" @keydown.escape.window="isOpen = false" x-on:open-media-library.window="open($event.detail)" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="isOpen" x-transition.opacity.duration.300ms class="absolute inset-0 bg-gray-900/75"></div>

        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full h-[90vh] flex flex-col">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Pustaka Media') }}</h3>
                <button type="button" @click="isOpen = false" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-hidden flex flex-col">
                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                    <input x-model="searchQuery" @input.debounce.500ms="fetchMedia(1)" type="text" placeholder="Cari media..." class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                  focus:border-brand-accent focus:ring-brand-accent rounded-md shadow-sm mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400" x-show="totalMedia > 0">
                        {{ __('Total: ') }} <span x-text="totalMedia"></span>
                    </p>
                </div>

                <div x-ref="mediaGrid" @scroll="checkScroll()" class="flex-1 overflow-y-auto p-4 grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <template x-for="mediaItem in media" :key="mediaItem.id">
                        <div @click="selectMedia(mediaItem)" :class="{'ring-2 ring-brand-accent dark:ring-brand-accent': selectedMediaId === mediaItem.id}" class="group relative aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer
                                    hover:border-brand-primary dark:hover:border-brand-accent transition-all duration-200 flex items-center justify-center">
                            <img :src="mediaItem.thumb_url" :alt="mediaItem.alt_text" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-2 opacity-0 group-hover:opacity-100 transition">
                                <span class="text-white text-xs truncate" x-text="mediaItem.file_name"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="isLoading" class="col-span-full text-center py-4 text-gray-500 dark:text-gray-400">
                        {{ __('Memuat...') }}
                    </div>
                    <div x-show="!isLoading && media.length === 0" class="col-span-full text-center py-4 text-gray-500 dark:text-gray-400">
                        {{ __('Tidak ada media ditemukan.') }}
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button type="button" @click="isOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md
                               hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('Batal') }}
                </button>
                <button type="button" @click="confirmSelection()" :disabled="!selectedMediaId" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-md
                               hover:bg-brand-primary/80 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    {{ __('Pilih Gambar') }}
                </button>
            </div>

        </div>
    </div>
    @stack('scripts')
</body>
</html>
