<x-public-layout>
    <div class="mb-6 pb-4 border-b border-gray-300 dark:border-gray-700">
        <h1 class="text-3xl font-bold text-brand-primary dark:text-white font-heading">
            Semua Kategori
        </h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" 
               class="block p-4 bg-white dark:bg-gray-800 rounded shadow 
                      font-semibold text-brand-primary dark:text-brand-accent 
                      hover:bg-brand-primary hover:text-white 
                      dark:hover:bg-brand-accent dark:hover:text-brand-primary">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</x-public-layout>