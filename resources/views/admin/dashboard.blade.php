<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            
            <a href="{{ route('admin.articles.create') }}" 
               class="px-4 py-2 bg-brand-primary text-white rounded-md text-sm font-medium hover:bg-brand-primary/80">
                Tulis Artikel Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-yellow-500">Menunggu Review</h3>
                        <p class="mt-1 text-3xl font-bold">{{ $pendingCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-green-500">Dipublikasi</h3>
                        <p class="mt-1 text-3xl font-bold">{{ $publishedCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-500">Draft</h3>
                        <p class="mt-1 text-3xl font-bold">{{ $draftCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-blue-500">Total Pengguna</h3>
                        <p class="mt-1 text-3xl font-bold">{{ $userCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class_={{ "text-xl font-bold mb-4 font-heading" }}>
                        Perlu Persetujuan ({{ $pendingCount }})
                    </h3>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pendingArticles as $article)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('admin.articles.edit', $article) }}" 
                                       class="font-semibold text-brand-primary dark:text-brand-accent hover:underline">
                                        {{ $article->title }}
                                    </a>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Oleh: {{ $article->user->name }} | Kategori: {{ $article->category->name }}
                                    </div>
                                </div>
                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Edit
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada artikel yang menunggu review. Kerja bagus!</p>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>