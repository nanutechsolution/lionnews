<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
                {{ __('Manajemen Artikel') }}
            </h2>

            <a href="{{ route('admin.articles.create') }}"
                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-3 sm:py-2 bg-brand-primary border border-transparent 
                      rounded-lg font-semibold text-sm text-white uppercase tracking-widest 
                      shadow-md hover:bg-brand-primary/80 focus:outline-none 
                      focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                      dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tulis Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <form method="GET" action="{{ route('admin.articles.index') }}">
                    <div class="p-4 sm:p-6">
                        <div class="relative flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" placeholder="Cari judul..."
                                    value="{{ request('search') }}"
                                    class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                              focus:border-brand-accent dark:focus:border-brand-accent 
                                              focus:ring-brand-accent dark:focus:ring-brand-accent 
                                              rounded-lg shadow-sm h-12 sm:h-10">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 sm:flex-none justify-center inline-flex items-center px-6 py-3 sm:py-2 bg-brand-primary border border-transparent 
                                               rounded-lg font-semibold text-xs text-white uppercase tracking-widest 
                                               hover:bg-brand-primary/80 focus:ring-2 focus:ring-brand-accent shadow-sm transition">
                                    Cari
                                </button>

                                @if (request('search'))
                                    <a href="{{ route('admin.articles.index') }}"
                                        class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 sm:py-2 border border-gray-300 dark:border-gray-600 
                                          rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest 
                                          hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div
                    class="mx-4 sm:mx-0 mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div
                class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-3 bg-gray-100 dark:bg-gray-700 rounded-t-lg font-bold text-xs text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                <div class="col-span-5">{{ __('Artikel Info') }}</div>
                <div class="col-span-2 text-center">{{ __('Status') }}</div>
                <div class="col-span-2">{{ __('Kategori') }}</div>
                <div class="col-span-3 text-right">{{ __('Aksi') }}</div>
            </div>

            <div
                class="space-y-4 md:space-y-0 bg-transparent md:bg-white md:dark:bg-gray-800 md:shadow-sm md:rounded-b-lg">
                @forelse($articles as $article)
                    <div
                        class="group bg-white dark:bg-gray-800 shadow-sm md:shadow-none rounded-xl md:rounded-none p-5 md:p-0 
                            md:grid md:grid-cols-12 md:gap-4 md:items-center 
                            md:border-b md:border-gray-100 dark:md:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">

                        <div class="md:col-span-5 md:px-6 md:py-4">
                            <div class="flex justify-between items-start md:hidden mb-2">
                                @php
                                    $statusClass = match ($article->status) {
                                        App\Models\Article::STATUS_PUBLISHED
                                            => 'bg-green-100 text-green-700 border-green-200',
                                        App\Models\Article::STATUS_PENDING
                                            => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                                </span>
                            </div>

                            <a href="{{ route('admin.articles.edit', $article) }}"
                                class="block text-lg md:text-sm font-bold text-gray-900 dark:text-gray-100 leading-tight hover:text-brand-primary transition">
                                {{ Str::limit($article->title, 60) }}
                            </a>

                            <div class="mt-1 md:mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Oleh: <span
                                        class="font-medium text-gray-700 dark:text-gray-300">{{ $article->user->name }}</span></span>
                            </div>
                        </div>

                        <div class="hidden md:block md:col-span-2 md:px-6 md:py-4 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                            </span>
                        </div>

                        <div class="mt-3 md:mt-0 md:col-span-2 md:px-6 md:py-4 flex items-center gap-2">
                            <span
                                class="md:hidden text-xs font-semibold text-gray-400 uppercase tracking-wide">Kategori:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">
                                {{ $article->category->name }}
                            </span>
                        </div>

                        <div class="mt-5 md:mt-0 md:col-span-3 md:px-6 md:py-4">
                            <div
                                class="flex items-center md:justify-end gap-3 border-t md:border-t-0 border-gray-100 dark:border-gray-700 pt-4 md:pt-0">
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="flex-1 md:flex-none text-center justify-center inline-flex items-center px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                    class="flex-1 md:flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-3 py-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-100 transition"
                                        onclick="return confirm('Anda yakin ingin menghapus artikel ini?')">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada artikel</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulailah dengan membuat artikel baru.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
