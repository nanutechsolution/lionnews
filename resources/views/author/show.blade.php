<x-public-layout>
    
    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h1 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Berita oleh: {{ $author->name }}
        </h1>
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