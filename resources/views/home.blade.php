<x-public-layout>

    @if($heroArticle)
    <div class="mb-6">
        <x-article-hero-card :article="$heroArticle" />
    </div>
    @endif

    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h2 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Berita Terpopuler
        </h2>
    </div>

    <div class="flex overflow-x-auto gap-4 pb-4 horizontal-scrollbar">

        @foreach($topGridArticles as $article)
        <div class="flex-none w-80 md:w-1/4">
            <x-article-card :article="$article" />
        </div>
        @endforeach
    </div>

    <div class="mt-8 mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h2 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Terbaru Lainnya
        </h2>
    </div>

    <div class="space-y-4">
        @forelse($latestListArticles as $article)
        <x-article-list-item :article="$article" />
        @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Belum Ada Berita</h2>
            <p class="mt-2">Silakan cek kembali nanti.</p>
        </div>
        @endforelse
    </div>

</x-public-layout>
