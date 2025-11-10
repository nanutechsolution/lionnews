<x-public-layout>

    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md flex flex-col md:flex-row items-center gap-6">

        <div class="flex-shrink-0">
            <img class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-brand-accent" src="{{ $author->getFirstMediaUrl('avatar', 'avatar-thumb') ?: 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=random&size=200' }}" alt="{{ $author->name }}">
        </div>

        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
                {{ $author->name }}
            </h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                {{ $author->bio ?? 'Jurnalis di LionNews' }}
            </p>

            @if($author->twitter_handle)
            <div class="mt-4">
                <a href="https://x.com/{{ str_replace('@', '', $author->twitter_handle) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-brand-primary dark:hover:text-brand-accent">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                    {{ str_replace('@', '', $author->twitter_handle) }}
                </a>
            </div>
            @endif
        </div>
    </div>
    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 font-heading">
            Semua Artikel oleh {{ $author->name }}
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($articles as $article)
        <x-article-card :article="$article" />

        @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white font-heading">
                Belum Ada Berita
            </h2>
            <p class="mt-2">Penulis ini belum mempublikasikan artikel.</p>
        </div>
        @endforelse

    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</x-public-layout>
