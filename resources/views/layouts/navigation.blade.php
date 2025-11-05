<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-extrabold text-brand-primary dark:text-brand-accent hover:text-brand-primary/80 dark:hover:text-white transition duration-150">
                        LionNews
                    </a>
                </div>

                @php
                $linkClasses = "inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out font-heading";
                $activeClasses = "border-brand-primary dark:border-brand-accent text-brand-primary dark:text-brand-accent";
                $inactiveClasses = "border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-700 hover:text-gray-700 dark:hover:text-gray-300";
                @endphp

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('admin.dashboard') }}" class="{{ $linkClasses }} {{ request()->routeIs('admin.dashboard') ? $activeClasses : $inactiveClasses }}">
                        {{ __('Dashboard') }}
                    </a>
                </div>

                @can('access-admin-panel')
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('admin.articles.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('admin.articles.*') ? $activeClasses : $inactiveClasses }}">
                        {{ __('Artikel') }}
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('admin.categories.*') ? $activeClasses : $inactiveClasses }}">
                        {{ __('Kategori') }}
                    </a>
                    <a href="{{ route('admin.tags.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('admin.tags.*') ? $activeClasses : $inactiveClasses }}">
                        {{ __('Tag') }}
                    </a>
                    @can('manage-users')
                    <a href="{{ route('admin.users.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('admin.users.*') ? $activeClasses : $inactiveClasses }}">
                        {{ __('Pengguna') }}
                    </a>
                    @endcan
                </div>
                @endcan
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6" x-data="darkModeToggle()">

                <button @click="toggle()" class="p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
                    <svg x-show="!isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </button>

                <div x-data="{ open: false }" @click.away="open = false" class="relative ml-3">
                    <button @click="open = ! open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">...</svg></div>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0" @click="open = false" style="display: none;">
                        <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white dark:bg-gray-700">
                            <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden" x-data="darkModeToggle()">
                <button @click="toggle()" class="p-2 rounded-full text-gray-400 dark:text-gray-500 ...">
                    <svg x-show="!isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="isDark" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>

                </button>
                <button @click="open = !open" class="ml-2 inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 ...">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden dark:bg-gray-800">
        @php
        $respLinkClasses = "block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out font-heading";
        $respActiveClasses = "border-brand-primary dark:border-brand-accent bg-blue-50 dark:bg-brand-accent/10 text-brand-primary dark:text-brand-accent";
        $respInactiveClasses = "border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200";
        @endphp

        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ $respLinkClasses }} {{ request()->routeIs('admin.dashboard') ? $respActiveClasses : $respInactiveClasses }}">
                {{ __('Dashboard') }}
            </a>
            @can('access-admin-panel')
            <a href="{{ route('admin.articles.index') }}" class="{{ $respLinkClasses }} {{ request()->routeIs('admin.articles.*') ? $respActiveClasses : $respInactiveClasses }}">
                {{ __('Artikel') }}
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ $respLinkClasses }} {{ request()->routeIs('admin.categories.*') ? $respActiveClasses : $respInactiveClasses }}">
                {{ __('Kategori') }}
            </a>
            <a href="{{ route('admin.tags.index') }}" class="{{ $respLinkClasses }} {{ request()->routeIs('admin.tags.*') ? $respActiveClasses : $respInactiveClasses }}">
                {{ __('Tag') }}
            </a>
            @endcan
            @can('manage-users')
            <a href="{{ route('admin.users.index') }}" class="{{ $respLinkClasses }} {{ request()->routeIs('admin.users.*') ? $respActiveClasses : $respInactiveClasses }}">
                {{ __('Pengguna') }}
            </a>
            @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="{{ $respLinkClasses }} {{ $respInactiveClasses }}">
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="{{ $respLinkClasses }} {{ $respInactiveClasses }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
