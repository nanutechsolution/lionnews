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
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 
              rounded-md font-semibold text-xs uppercase tracking-widest 
              hover:bg-gray-700 dark:hover:bg-gray-200 transition">
                Bagikan ke X
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white 
              rounded-md font-semibold text-xs uppercase tracking-widest 
              hover:bg-brand-primary/80 transition">
                Bagikan ke Facebook
            </a>

            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . request()->url()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white 
              rounded-md font-semibold text-xs uppercase tracking-widest 
              hover:bg-green-700 transition">
                Bagikan ke WhatsApp
            </a>
        </div>
        @if($article->hasMedia('featured'))
        <figure class="mt-8">
            <img class="w-full rounded-lg object-cover" src="{{ $article->getFirstMediaUrl('featured', 'featured-large') }}" alt="{{ $article->title }}">

            @php
            // Ambil caption dari custom properties media
            $caption = $article->getFirstMedia('featured')->getCustomProperty('caption');
            @endphp

            @if($caption)
            <figcaption class="mt-2 text-sm text-center text-gray-600 dark:text-gray-400 italic">
                {{ $caption }}
            </figcaption>
            @endif
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
    <div class="max-w-3xl mx-auto mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-heading mb-4">
            Komentar ({{ $article->comments->count() }})
        </h3>

        <div class="mb-6">
            @auth
            <form method="POST" action="{{ route('comments.store', $article) }}">
                @csrf
                <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300 sr-only">{{ __('Tulis komentar') }}</label>
                <textarea name="body" id="body" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                     focus:border-brand-accent dark:focus:border-brand-accent 
                                     focus:ring-brand-accent dark:focus:ring-brand-accent 
                                     rounded-md shadow-sm" placeholder="Tulis komentar Anda..."></textarea>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
                                       rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                       hover:bg-brand-primary/80 focus:outline-none 
                                       focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                                       dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Kirim Komentar
                    </button>
                </div>
            </form>
            @else
            <div class="text-center p-4 border border-gray-300 dark:border-gray-700 rounded-md">
                <p class="text-gray-700 dark:text-gray-300">
                    Silakan
                    <a href="{{ route('login') }}" class="font-bold text-brand-primary dark:text-brand-accent hover:underline">Login</a>
                    atau
                    <a href="{{ route('register') }}" class="font-bold text-brand-primary dark:text-brand-accent hover:underline">Register</a>
                    untuk berkomentar.
                </p>
            </div>
            @endauth
        </div>

        <div class="space-y-4">
            @forelse($article->comments as $comment)
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-brand-primary dark:text-brand-accent font-heading">
                        {{ $comment->user->name }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="mt-2 text-gray-800 dark:text-gray-300">
                    {{ $comment->body }}
                </p>
            </div>
            @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                <p>Belum ada komentar. Jadilah yang pertama!</p>
            </div>
            @endforelse
        </div>
    </div>
</x-public-layout>
