<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-brand-base dark:bg-gray-900 dark:text-gray-200">

    <header class="bg-brand-primary shadow-md sticky top-0 z-50" x-data="{ open: false }">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-brand-accent hover:text-white transition duration-150">
                        LionNews
                    </a>
                </div>

                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">

                        @foreach($navigationCategories as $category)
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('category.show', $category->slug) }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 border-b-2 border-transparent 
                          hover:text-white hover:border-brand-accent 
                          font-heading flex items-center">
                                {{ $category->name }}

                                @if($category->children->count() > 0)
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                @endif
                            </a>

                            @if($category->children->count() > 0)
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute z-10 -ml-4 mt-3 p-2 w-screen max-w-xs" style="display: none;">
                                <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                                    <div class="relative grid gap-6 bg-white dark:bg-gray-800 px-5 py-6 sm:gap-8 sm:p-8">

                                        @foreach($category->children as $subCategory)
                                        <a href="{{ route('category.show', $subCategory->slug) }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <div class="ml-4">
                                                <p class="text-base font-medium text-brand-primary dark:text-brand-accent font-heading">
                                                    {{ $subCategory->name }}
                                                </p>
                                            </div>
                                        </a>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <a href="{{ route('categories.index.all') }}" class="px-3 py-2 rounded-md text-sm font-medium text-brand-accent 
                  border-b-2 border-transparent 
                  hover:text-white font-heading">
                            Lainnya »
                        </a>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6" x-data="darkModeToggle()">
                    @guest
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white font-heading">
                        Login
                    </a>
                    @else
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white font-heading">
                        Dashboard
                    </a>
                    @endguest



                    <form action="{{ route('search') }}" method="GET" class="ml-4">
                        <input type="text" name="q" placeholder="Cari berita..." class="px-3 py-2 bg-white/10 border border-transparent text-white rounded-md text-sm 
                                      focus:bg-white focus:text-gray-900 transition" value="{{ request('q') }}">
                    </form>

                    <button @click="toggle()" class="p-2 rounded-full text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none">
                        <svg x-show="!isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                </div>

                <div class="-me-2 flex items-center sm:hidden" x-data="darkModeToggle()">

                    <button @click="toggle()" class="p-2 rounded-full text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none">
                        <svg x-show="!isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <button @click="open = ! open" class="ml-2 inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-blue-900">

            <div class="pt-2 pb-3 space-y-1">

                @foreach($navigationCategories as $category)

                @if($category->children->count() > 0)

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center pl-3 pr-4 py-2 border-l-4 border-transparent 
                                   text-base font-medium text-gray-200 
                                   hover:text-white hover:bg-white/10 hover:border-brand-accent 
                                   font-heading">
                        <span>{{ $category->name }}</span>
                        <svg class="h-5 w-5 transform transition-transform duration-150" :class="{'rotate-90': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                        @foreach($category->children as $subCategory)
                        <a href="{{ route('category.show', $subCategory->slug) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-sm font-medium 
                                      text-gray-400 hover:text-white hover:bg-white/10">
                            {{ $subCategory->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                @else
                <a href="{{ route('category.show', $category->slug) }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium 
                          text-gray-200 hover:text-white hover:bg-white/10 
                          hover:border-brand-accent font-heading">
                    {{ $category->name }}
                </a>
                @endif

                @endforeach

                <a href="{{ route('categories.index.all') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium 
                  text-brand-accent hover:text-white hover:bg-white/10 
                  hover:border-brand-accent font-heading">
                    Lainnya »
                </a>
            </div>

            <div class="pt-4 pb-3 border-t border-blue-800">
                <div class="px-4">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" placeholder="Cari berita..." class="w-full px-3 py-2 bg-white/10 border border-transparent text-white rounded-md text-sm
                              focus:bg-white focus:text-gray-900 transition" value="{{ request('q') }}">
                    </form>
                </div>
            </div>

            <div class="pt-4 pb-3 border-t border-blue-800">
                @guest
                <div class="px-4">
                    <a href="{{ route('login') }}" class="block w-full px-3 py-2 text-left text-base font-medium text-gray-200 rounded-md hover:bg-white/10 font-heading">
                        Login
                    </a>
                </div>
                @else
                <div class="px-4 mb-3">
                    <div class="font-medium text-base text-white font-heading">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <div class="space-y-1 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="block w-full px-3 py-2 text-left text-base font-medium text-gray-200 rounded-md hover:bg-white/10 font-heading">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block w-full px-3 py-2 text-left text-base font-medium text-gray-200 rounded-md hover:bg-white/10">
                        Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="block w-full px-3 py-2 text-left text-base font-medium text-gray-200 rounded-md hover:bg-white/10" onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </a>
                    </form>
                </div>
                @endguest
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">

            <div class="md:col-span-2 lg:col-span-3">
                {{ $slot }}
            </div>

            <aside class="md:col-span-1 lg:col-span-1 space-y-6">
                @include('layouts.partials.sidebar')
            </aside>
        </div>
    </main>
    <footer class="bg-brand-primary text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">
                &copy; {{ date('Y') }} LionNews. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
