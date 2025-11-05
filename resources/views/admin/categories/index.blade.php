<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
                {{ __('Manajemen Kategori') }}
            </h2>
            
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
                      rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                      hover:bg-brand-primary/80 focus:outline-none 
                      focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                      dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Buat Kategori Baru
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
            @if(session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="hidden md:grid md:grid-cols-4 gap-4 px-6 py-3 bg-gray-50 dark:bg-gray-700 rounded-t-lg font-medium text-xs text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                <div class="col-span-1">{{ __('Nama') }}</div>
                <div class="col-span-1">{{ __('Slug') }}</div>
                <div class="col-span-1">{{ __('Jumlah Artikel') }}</div>
                <div class="col-span-1 text-right">{{ __('Aksi') }}</div>
            </div>

            <div class="space-y-4 md:space-y-0">
                @forelse($categories as $category)
                    <div class="bg-white dark:bg-gray-800 shadow-md md:shadow-none rounded-lg p-4 md:p-0 
                                md:grid md:grid-cols-4 md:gap-4 md:items-center 
                                md:border-b md:border-gray-200 dark:md:border-gray-700
                                md:rounded-none">

                        <div class="md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</div>
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="text-sm font-medium text-brand-primary dark:text-brand-accent hover:underline font-heading">
                                {{ $category->name }}
                            </a>
                        </div>

                        <div class="mt-2 md:mt-0 md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</div>
                            <div class="text-sm text-gray-900 dark:text-gray-300">{{ $category->slug }}</div>
                        </div>

                        <div class="mt-2 md:mt-0 md:col-span-1 md:px-6 md:py-4">
                            <div class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah Artikel</div>
                            <div class="text-sm text-gray-900 dark:text-gray-300">{{ $category->articles_count }}</div>
                        </div>

                        <div class="mt-4 md:mt-0 md:col-span-1 md:px-6 md:py-4 text-left md:text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="text-brand-primary dark:text-brand-accent hover:underline text-sm font-medium">
                                Edit
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 dark:text-red-400 hover:underline text-sm font-medium" 
                                        onclick="return confirm('Yakin ingin menghapus kategori ini? Ini tidak bisa dibatalkan.')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md md:shadow-none p-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada kategori.
                    </div>
                @endforelse
            </div>
            
            </div>
    </div>
</x-app-layout>