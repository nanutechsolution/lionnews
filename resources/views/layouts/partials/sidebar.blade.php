<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold pb-2 border-b border-gray-200 dark:border-gray-700 text-brand-primary dark:text-brand-accent font-heading">
        Terbaru
    </h3>
    <ul class="mt-4 space-y-3">
        @forelse($trendingArticles as $article)
            <li>
                <a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}" 
                   class="group">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-brand-primary dark:group-hover:text-brand-accent font-heading">
                        {{ $article->title }}
                    </h4>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span class="font-medium text-brand-primary dark:text-brand-accent">{{ $article->category->name }}</span>
                        - {{ $article->published_at->diffForHumans() }}
                    </div>
                </a>
            </li>
        @empty
            <li class="text-sm text-gray-500 dark:text-gray-400">Belum ada berita.</li>
        @endforelse
    </ul>
</div>

<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold pb-2 border-b border-gray-200 dark:border-gray-700 text-brand-primary dark:text-brand-accent font-heading">
        Tag Populer
    </h3>
    <div class="mt-4 flex flex-wrap gap-2">
        @forelse($popularTags as $tag)
            <a href="{{ route('tag.show', $tag->slug) }}" 
               class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition-colors
                      hover:bg-brand-primary hover:text-white 
                      dark:bg-gray-700 dark:text-gray-200 
                      dark:hover:bg-brand-accent dark:hover:text-brand-primary">
                {{ $tag->name }} ({{ $tag->articles_count }})
            </a>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tag.</p>
        @endforelse
    </div>
</div>