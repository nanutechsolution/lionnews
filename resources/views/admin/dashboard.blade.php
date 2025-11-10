<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
                    {{ $greeting }}, {{ $userName }}!
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Selamat datang kembali di pusat kendali LionNews.
                </p>
            </div>

            <a href="{{ route('admin.articles.create') }}"
               class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
                      rounded-md font-semibold text-xs text-white uppercase tracking-widest
                      hover:bg-brand-primary/80 focus:outline-none
                      focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                      dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Tulis Artikel Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg
                            border-l-4 border-yellow-400 dark:border-brand-accent">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800/50">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-brand-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Menunggu Review</h3>
                            <p class="mt-1 text-3xl font-bold">{{ $pendingCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-800/50">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Dipublikasi</h3>
                            <p class="mt-1 text-3xl font-bold">{{ $publishedCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-gray-100 dark:bg-gray-700">
                             <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Draft</h3>
                            <p class="mt-1 text-3xl font-bold">{{ $draftCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800/50">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM8.25 21a2.25 2.25 0 01-2.25-2.25V18c0-1.13.9-2.036 2.036-2.036h3.928c1.13 0 2.036.906 2.036 2.036v.75A2.25 2.25 0 0112.75 21H8.25z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Total Pengguna</h3>
                            <p class="mt-1 text-3xl font-bold">{{ $userCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4 font-heading dark:text-white">
                        Daftar Tugas Anda
                    </h3>

                    @if($pendingArticles->count() > 0)
                        <h4 class="text-lg font-semibold text-brand-primary dark:text-brand-accent mb-2">Perlu Persetujuan ({{ $pendingCount }})</h4>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pendingArticles as $article)
                                <div class="py-3 flex justify-between items-center">
                                    <div>
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                           class="font-semibold text-gray-800 dark:text-gray-200 hover:underline">
                                            {{ $article->title }}
                                        </a>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            Oleh: {{ $article->user->name }} | Kategori: {{ $article->category->name }}
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="text-sm text-brand-primary dark:text-brand-accent hover:underline ml-4 flex-shrink-0">
                                        Review Sekarang
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($suggestedArticles->count() > 0)
                        <h4 class="text-lg font-semibold text-yellow-500 dark:text-yellow-400 mb-2 mt-6">
                            Tag Menunggu Persetujuan
                        </h4>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($suggestedArticles as $article)
                                <div class="py-3">
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="font-semibold text-gray-800 dark:text-gray-200 hover:underline">
                                        {{ $article->title }}
                                    </a>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Oleh: {{ $article->user->name }}
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span class="text-sm text-gray-500">Saran:</span>
                                        @foreach($article->suggested_tags as $suggestedTag)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 dark:bg-yellow-800/50 text-yellow-800 dark:text-yellow-300">
                                            {{ $suggestedTag }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($pendingArticles->count() == 0 && $suggestedArticles->count() == 0)
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada tugas mendesak. Kerja bagus!</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
