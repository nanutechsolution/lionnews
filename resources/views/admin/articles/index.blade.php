<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
                {{ __('Manajemen Artikel') }}
            </h2>
            
            <a href="{{ route('admin.articles.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
                      rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                      hover:bg-brand-primary/80 focus:outline-none 
                      focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                      dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Tulis Artikel Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 rounded-md bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="hidden md:grid md:grid-cols-6 gap-4 px-6 py-3 bg-gray-50 dark:bg-gray-700 rounded-t-lg font-medium text-xs text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                <div class="col-span-2">{{ __('Judul') }}</div>
                <div class="col-span-1">{{ __('Status') }}</div>
                <div class="col-span-1">{{ __('Kategori') }}</div>
                <div class="col-span-1">{{ __('Penulis') }}</div>
                <div class="col-span-1 text-right">{{ __('Aksi') }}</div>
            </div>

            <div class="space-y-4 md:space-y-0">
                @forelse($articles as $article)
                    <div class="bg-white dark:bg-gray-800 shadow-md md:shadow-none rounded-lg p-4 md:p-0 
                                md:grid md:grid-cols-6 md:gap-4 md:items-center 
                                md:border-b md:border-gray-200 dark:md:border-gray-700
                                md:rounded-none">

                        <div class="md:col-span-2 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul</div>
                            <a href="{{ route('admin.articles.edit', $article) }}" 
                               class="text-sm font-medium text-brand-primary dark:text-brand-accent hover:underline font-heading">
                                {{ Str::limit($article->title, 50) }}
                            </a>
                        </div>

                        <div class="mt-2 md:mt-0 md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Status</div>
                            @php
                                $statusClass = '';
                                if ($article->status == App\Models\Article::STATUS_PUBLISHED) {
                                    $statusClass = 'bg-green-100 dark:bg-green-800/50 text-green-800 dark:text-green-300';
                                } elseif ($article->status == App\Models\Article::STATUS_PENDING) {
                                    $statusClass = 'bg-yellow-100 dark:bg-yellow-800/50 text-yellow-800 dark:text-yellow-300';
                                } else {
                                    $statusClass = 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                            </span>
                        </div>

                        <div class="mt-2 md:mt-0 md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kategori</div>
                            <div class="text-sm text-gray-900 dark:text-gray-300">{{ $article->category->name }}</div>
                        </div>

                        <div class="mt-2 md:mt-0 md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Penulis</div>
                            <div class="text-sm text-gray-900 dark:text-gray-300">{{ $article->user->name }}</div>
                        </div>

                        <div class="mt-4 md:mt-0 md:col-span-1 md:px-6 md:py-4 text-left md:text-right">
                            <a href="{{ route('admin.articles.edit', $article) }}" 
                               class="text-brand-primary dark:text-brand-accent hover:underline text-sm font-medium">
                                Edit
                            </a>

                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 dark:text-red-400 hover:underline text-sm font-medium" 
                                        onclick="return confirm('Anda yakin ingin menghapus artikel ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md md:shadow-none p-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada artikel.
                    </div>
                @endforelse
            </div>

            <div class="mt-4 p-4 bg-white dark:bg-gray-800 shadow-md md:shadow-none rounded-b-lg">
                {{ $articles->links() }}
            </div>

        </div>
    </div>
</x-app-layout>