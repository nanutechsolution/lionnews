<x-public-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-0">
        <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-brand-primary dark:hover:text-brand-accent">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('category.show', $article->category->slug) }}" class="font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
                {{ $article->category->name }}
            </a>
        </nav>

        <a href="{{ route('category.show', $article->category->slug) }}">
            <h3 class="text-base font-semibold text-brand-primary dark:text-brand-accent uppercase hover:text-brand-primary/80 dark:hover:text-white font-heading">
                {{ $article->category->name }}
            </h3>
        </a>

        <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl font-heading">
            {{ $article->title }}
        </h1>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            Oleh <a href="{{ route('author.show', $article->user) }}" class="font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
                {{ $article->user->name }}
            </a>
            <span class="mx-2">|</span>
            Diterbitkan pada {{ $article->published_at->format('d F Y, H:i') }}
        </div>

        @if($article->hasMedia('featured')) <figure class="mt-8">
            <img class="w-full rounded-lg object-cover" src="{{ $article->getFirstMediaUrl('featured', 'featured-large') }}" alt="{{ $article->title }}">
        </figure>
        @endif
        <div class="mt-8 prose prose-lg max-w-none dark:prose-invert">
            {!! $article->body !!}
        </div>
        @if($article->tags->count() > 0)
        <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 font-heading">Tag Terkait:</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium 
                              hover:bg-brand-primary hover:text-white 
                              dark:bg-gray-700 dark:text-gray-200 
                              dark:hover:bg-brand-accent dark:hover:text-brand-primary">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-public-layout>
