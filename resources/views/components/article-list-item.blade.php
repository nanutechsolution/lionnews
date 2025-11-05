@props(['article'])

<a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}" 
   class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm 
          hover:bg-gray-50 dark:hover:bg-gray-700">
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
            <img class="w-20 h-16 rounded object-cover" 
                 src="{{ $article->getFirstMediaUrl('featured', 'featured-thumbnail') ?: 'https://via.placeholder.com/400x250?text=LionNews' }}" 
                 alt="{{ $article->title }}">
        </div>
        
        <div class="flex-1 min-w-0">
            <span class="text-sm font-medium text-brand-primary dark:text-brand-accent font-heading">
                {{ $article->category->name }}
            </span>
            <h3 class_={{ "mt-1 text-lg font-semibold text-gray-900 dark:text-white leading-tight truncate" }}>
                {{ $article->title }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $article->published_at->diffForHumans() }}
            </p>
        </div>
    </div>
</a>