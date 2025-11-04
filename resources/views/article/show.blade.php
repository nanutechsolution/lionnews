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
            Oleh <span class="font-medium">{{ $article->user->name }}</span>
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

    </div>
</x-public-layout>
