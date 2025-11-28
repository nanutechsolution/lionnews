<x-public-layout>
    <div class="max-w-7xl mx-auto  sm:px-6 lg:px-0">
        <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-brand-primary dark:hover:text-brand-accent">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('category.show', $article->category->slug) }}"
                class="font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
                {{ $article->category->name }}
            </a>
        </nav>

        <a href="{{ route('category.show', $article->category->slug) }}">
            <h3
                class="text-base font-semibold text-brand-primary dark:text-brand-accent uppercase hover:text-brand-primary/80 dark:hover:text-white font-heading">
                {{ $article->category->name }}
            </h3>
        </a>

        <h1
            class="mt-2 
           text-2xl           <!-- ukuran nyaman di hp -->
           sm:text-4xl        <!-- naik di tablet -->
           md:text-5xl        <!-- naik lagi di desktop -->
           font-bold 
           leading-snug       <!-- rapet tapi tetap enak dibaca di mobile -->
           tracking-tight 
           text-gray-900 
           dark:text-white 
           font-heading">
            {{ $article->title }}
        </h1>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            Oleh <a href="{{ route('author.show', $article->user) }}"
                class="font-medium text-brand-primary hover:text-brand-primary/80 dark:text-brand-accent dark:hover:text-white">
                {{ $article->user->name }}
            </a>
            <span class="mx-2">|</span>
            Diterbitkan pada {{ $article->published_at->format('d F Y, H:i') }}
        </div>

        <div class="mt-6 flex items-center gap-3">
            <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest">Bagikan:</span>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition"
                title="Bagikan ke Facebook">
                <x-lucide-facebook class="w-5 h-5" />
            </a>

            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                target="_blank"
                class="p-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-full hover:bg-gray-700 dark:hover:bg-gray-200 transition"
                title="Bagikan ke X (Twitter)">
                <x-lucide-twitter class="w-5 h-5" />
            </a>

            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . request()->url()) }}"
                target="_blank" class="p-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition"
                title="Bagikan ke WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
            </a>



            <div x-data="{ copied: false }">
                <button type="button" class="p-2 bg-gray-500 text-white rounded-full hover:bg-gray-600 transition"
                    x-on:click="navigator.clipboard.writeText(@js(request()->url()));
                          copied = true;
                          setTimeout(() => copied = false, 2000)"
                    x-bind:title="copied ? 'Link disalin!' : 'Salin link'">

                    <span x-show="!copied">
                        <x-lucide-copy class="w-5 h-5" />
                    </span>

                    <span x-show="copied" style="display: none;">
                        <x-lucide-check class="w-5 h-5 text-green-400" />
                    </span>
                </button>
            </div>
        </div>
        @if ($article->hasMedia('featured_image'))
            <figure class="mt-8">
                <img class="w-full rounded-lg object-cover"
                    src="{{ $article->getFirstMediaUrl('featured_image', 'featured-large') }}"
                    alt="{{ $article->title }}">

                @php
                    // Ambil caption dari custom properties media
                    $caption = $article->getFirstMedia('featured_image')->getCustomProperty('caption');
                @endphp

                @if ($caption)
                    <figcaption class="mt-2 text-sm text-center text-gray-600 dark:text-gray-400 italic">
                        {{ $caption }}
                    </figcaption>
                @endif
            </figure>
        @endif
        <div class="mt-8 prose prose-lg max-w-none dark:prose-invert">
            {!! $article->processed_body !!}
        </div>
        @if ($article->tags->count() > 0)
            <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 font-heading">Tag Terkait:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}"
                            class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium
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
    @if ($relatedArticles->count() > 0)
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-200 dark:border-gray-700  sm:px-6 lg:px-0">
            <h3 class="text-3xl font-bold text-brand-primary dark:text-white font-heading mb-6">
                Baca Juga
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedArticles as $relatedArticle)
                    <x-article-card :article="$relatedArticle" />
                @endforeach
            </div>
        </div>
    @endif
    <div class="max-w-3xl mx-auto mt-12 pt-8 border-t border-gray-200 dark:border-gray-700   sm:px-6 lg:px-0">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-heading mb-4">
            Komentar ({{ $article->comments->count() }})
        </h3>

        <div class="mb-6">
            @auth
                <form method="POST" action="{{ route('comments.store', $article) }}">
                    @csrf
                    <label for="body"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300 sr-only">{{ __('Tulis komentar') }}</label>
                    <textarea name="body" id="body" rows="4"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                     focus:border-brand-accent dark:focus:border-brand-accent
                                     focus:ring-brand-accent dark:focus:ring-brand-accent
                                     rounded-md shadow-sm"
                        placeholder="Tulis komentar Anda..."></textarea>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
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
                        <a href="{{ route('login') }}"
                            class="font-bold text-brand-primary dark:text-brand-accent hover:underline">Login</a>
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
