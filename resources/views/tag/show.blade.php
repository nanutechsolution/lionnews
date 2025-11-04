<x-public-layout>

    <div class="mb-6 pb-4 border-b border-gray-300">
        <h1 class="text-3xl font-bold text-gray-900">
            Tag: {{ $tag->name }}
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($articles as $article)
        <x-article-card :article="$article" />

        @empty
        <div class="col-span-full text-center text-gray-500 py-10">
            <h2 class="text-2xl font-semibold">Belum Ada Berita</h2>
            <p class="mt-2">Belum ada artikel yang ditandai dengan tag ini.</p>
        </div>
        @endforelse

    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</x-public-layout>
