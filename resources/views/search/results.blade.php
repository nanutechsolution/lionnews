<x-public-layout>

    <div class="mb-6 pb-4 border-b border-gray-300">
        <h1 class="text-3xl font-bold text-gray-900">
            Hasil Pencarian: "{{ $query }}"
        </h1>
        <p class="mt-2 text-lg text-gray-600">
            Menampilkan {{ $articles->total() }} hasil yang cocok.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($articles as $article)

        <x-article-card :article="$article" />

        @empty
        <div class="col-span-full text-center text-gray-500 py-10">
            <h2 class="text-2xl font-semibold">Tidak Ada Hasil</h2>
            <p class="mt-2">Maaf, tidak ada artikel yang cocok dengan kata kunci "{{ $query }}".</p>
            <p class="mt-1">Silakan coba kata kunci lain.</p>
        </div>
        @endforelse

    </div>

    <div class="mt-8">
        {{ $articles->appends(['q' => $query])->links() }}
    </div>

</x-public-layout>
