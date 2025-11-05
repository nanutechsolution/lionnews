<x-public-layout>

    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Kategori: {{ $category->name }}
        </h1>

        @if($category->description)
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            {{ $category->description }}
        </p>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <x-article-card :article="$article" />
        @empty
        <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Belum Ada Berita di Kategori Ini
            </h2>
            <p class="mt-2">Silakan cek kembali nanti.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8 sm:px-6 lg:px-0">
        {{ $articles->links('pagination::tailwind') }}
    </div>

</x-public-layout>
