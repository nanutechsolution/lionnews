@props(['article'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col transition-shadow duration-300 hover:shadow-xl">
    <a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}">
        <img class="h-48 w-full object-cover" 
             src="{{ $article->featured_image_path ? asset('storage/' . $article->featured_image_path) : 'https://via.placeholder.com/400x250?text=LionNews' }}" 
             alt="{{ $article->title }}">
    </a>

    <div class="p-6 flex flex-col flex-grow">
        <a href="{{ route('category.show', $article->category->slug) }}" 
           class="text-sm font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
            {{ $article->category->name }}
        </a>

        <a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}">
            <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white hover:text-brand-primary dark:hover:text-gray-300 font-heading">
                {{ $article->title }}
            </h2>
        </a>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-grow">
            {{ Str::limit($article->excerpt, 100) }}
        </p>

        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            <span>Oleh:
                <a href="{{ route('author.show', $article->user) }}" 
                   class="font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
                    {{ $article->user->name }}
                </a>
            </span> |
            <span>{{ $article->published_at->format('d M Y') }}</span>
        </div>
    </div>
</div>