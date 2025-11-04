<div class="bg-white p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold pb-2 border-b border-gray-200">
        Terbaru
    </h3>
    <ul class="mt-4 space-y-3">
        @forelse($trendingArticles as $article)
        <li>
            <a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}" class="group">
                <h4 class="font-semibold text-gray-800 group-hover:text-blue-600">
                    {{ $article->title }}
                </h4>
                <div class="text-sm text-gray-500 mt-1">
                    <span class="text-blue-500">{{ $article->category->name }}</span>
                    - {{ $article->published_at->diffForHumans() }}
                </div>
            </a>
        </li>
        @empty
        <li class="text-sm text-gray-500">Belum ada berita.</li>
        @endforelse
    </ul>
</div>

<div class="bg-white p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold pb-2 border-b border-gray-200">
        Tag Populer
    </h3>
    <div class="mt-4 flex flex-wrap gap-2">
        @forelse($popularTags as $tag)
        <a href="{{ route('tag.show', $tag->slug) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-blue-600 hover:text-white">
            {{ $tag->name }} ({{ $tag->articles_count }})
        </a>
        @empty
        <p class="text-sm text-gray-500">Belum ada tag.</p>
        @endforelse
    </div>
</div>
