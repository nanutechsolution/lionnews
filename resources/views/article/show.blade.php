<x-public-layout>
    <div class="max-w-3xl mx-auto">
        <nav class="mb-4 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('category.show', $article->category->slug) }}" class="text-gray-900 hover:text-blue-600">
                {{ $article->category->name }}
            </a>
        </nav>

        <h3 class="text-base font-semibold text-blue-600 uppercase">
            {{ $article->category->name }}
        </h3>

        <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
            {{ $article->title }}
        </h1>

        <div class="mt-4 text-sm text-gray-600">
            Oleh <a href="{{ route('author.show', $article->user) }}" class="font-medium hover:text-blue-600">
                {{ $article->user->name }}
            </a>
            <span class="mx-2">|</span>
            Diterbitkan pada {{ $article->published_at->format('d F Y, H:i') }}
        </div>

        @if($article->featured_image_path)
        <figure class="mt-8">
            <img class="w-full rounded-lg object-cover" src="{{ asset('storage/' . $article->featured_image_path) }}" alt="{{ $article->title }}">
        </figure>
        @endif

        <div class="mt-8 prose prose-lg max-w-none">

            {!! $article->body !!}

        </div>
        @if($article->tags->count() > 0)
        <div class="mt-8 pt-4 border-t border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Tag Terkait:</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-blue-600 hover:text-white">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-public-layout>
