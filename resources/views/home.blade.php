<x-public-layout>
    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700 px-4 sm:px-6 lg:px-0">
        <h1 class="text-3xl font-bold text-brand-primary dark:text-white">
            Berita Terbaru
        </h1>
    </div>
    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 px-4 sm:px-6 lg:px-0">
        @forelse($articles as $article)
            <x-article-card :article="$article" />
            
        @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Belum Ada Berita</h2>
                <p class="mt-2">Silakan cek kembali nanti.</p>
            </div>
        @endforelse
    </div>
</x-public-layout>
