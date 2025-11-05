<x-public-layout>
    
    @if($heroArticle)
        <div class="mb-6">
            <x-article-hero-card :article="$heroArticle" />
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-xl font-bold text-brand-primary dark:text-brand-accent font-heading mb-3">
            Sedang Populer
        </h2>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <ol class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($popularArticles as $index => $article)
                    <li class="p-4 flex items-center group">
                        <span class="text-2xl font-bold text-gray-300 dark:text-gray-600 mr-4">{{ $index + 1 }}</span>
                        
                        <a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}" class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-brand-primary dark:group-hover:text-brand-accent leading-tight line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $article->category->name }} | {{ views($article)->unique()->count() }} views
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="p-4 text-sm text-gray-500 dark:text-gray-400">Belum ada berita populer.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h2 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Pilihan Editor
        </h2>
    </div>
    
    <div class="flex overflow-x-auto gap-4 pb-4 horizontal-scrollbar">
        @foreach($topGridArticles as $article)
            <div class="flex-none w-80 md:w-1/4">
                <x-article-card :article="$article" />
            </div>
        @endforeach
    </div>

    <div class="mt-8 mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h2 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Terbaru Lainnya
        </h2>
    </div>

    <div class="space-y-4">
        @forelse($latestListArticles as $article)
            <x-article-list-item :article="$article" />
        @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Belum Ada Berita</h2>
                <p class="mt-2">Silakan cek kembali nanti.</p>
            </div>
        @endforelse
    </div>

</x-public-layout>