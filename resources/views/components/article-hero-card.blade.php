@props(['article'])

<a href="{{ route('article.show', [$article->category->slug, $article->slug]) }}" class="flex md:block bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden 
          transition-shadow duration-300 hover:shadow-xl">

    <div class="flex-none w-1/3 md:w-full">
        <img class="w-full h-full md:h-80 object-cover" src="{{ $article->getFirstMediaUrl('featured_image', 'featured-large') ?: 'https://via.placeholder.com/1200x675?text=LionNews' }}" alt="{{ $article->title }}">
    </div>

    <div class="flex-grow w-2/3 md:w-full p-4 md:p-6 flex flex-col justify-center">
        <span class="text-sm font-medium text-brand-primary dark:text-brand-accent font-heading">
            {{ $article->category->name }}
        </span>

        <h2 class="mt-1 md:mt-2 text-lg md:text-3xl font-semibold text-gray-900 dark:text-white 
                   hover:text-brand-primary dark:hover:text-gray-300 font-heading 
                   line-clamp-3 md:line-clamp-none">
            {{ $article->title }}
        </h2>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden md:block">
            {{ Str::limit($article->excerpt, 150) }}
        </p>

        <div class="mt-2 md:mt-4 text-xs text-gray-500 dark:text-gray-400">
            <span>Oleh:
                <span class="font-medium text-brand-primary dark:text-brand-accent">
                    {{ $article->user->name }}
                </span>
            </span>
            <span class="hidden sm:inline"> | {{ $article->published_at->diffForHumans() }}</span>
        </div>
    </div>
</a>
