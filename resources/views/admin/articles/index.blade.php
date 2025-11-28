<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Kelola Artikel & Berita') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Daftar semua tulisan yang ada di website kampus.
                </p>
            </div>

            <a href="{{ route('admin.articles.create') }}"
                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-brand-primary border border-transparent 
                      rounded-full font-bold text-sm text-white shadow-lg 
                      hover:bg-brand-primary/80 hover:shadow-xl focus:outline-none 
                      transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                + Buat Artikel Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div
                class="mb-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.articles.index') }}">
                    <div class="p-5">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ingin mencari artikel apa?
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search"
                                    placeholder="Ketik judul artikel atau nama penulis disini..."
                                    value="{{ request('search') }}"
                                    class="pl-4 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                              focus:border-brand-primary focus:ring-brand-primary 
                                              rounded-lg shadow-sm h-11">
                                <p class="mt-1 text-xs text-gray-500">Tekan tombol cari untuk menemukan artikel.</p>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 sm:flex-none justify-center inline-flex items-center px-6 py-2.5 bg-gray-800 dark:bg-gray-200 border border-transparent 
                                               rounded-lg font-semibold text-sm text-white dark:text-gray-800 tracking-wide 
                                               hover:bg-gray-700 dark:hover:bg-white focus:ring-2 focus:ring-gray-500 shadow-md transition">
                                    🔍 Cari
                                </button>

                                @if (request('search'))
                                    <a href="{{ route('admin.articles.index') }}"
                                        class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 
                                          rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700
                                          hover:bg-gray-100 transition"
                                        title="Kembali ke semua daftar">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div
                    class="mx-4 sm:mx-0 mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-800 dark:text-green-200 shadow-sm flex items-start gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 text-green-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div
                class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-3 bg-gray-100 dark:bg-gray-700 rounded-t-xl text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                <div class="col-span-5">Judul & Penulis</div>
                <div class="col-span-2 text-center">Status Tayang</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-3 text-right">Pilihan Aksi</div>
            </div>

            <div class="space-y-4 md:space-y-0 md:bg-white md:dark:bg-gray-800 md:shadow-sm md:rounded-b-xl">
                @forelse($articles as $article)
                    <div
                        class="group bg-white dark:bg-gray-800 shadow-sm md:shadow-none rounded-xl md:rounded-none p-5 md:p-0 
                            md:grid md:grid-cols-12 md:gap-4 md:items-center 
                            md:border-b md:border-gray-100 dark:md:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition duration-150">

                        <div class="md:col-span-5 md:px-6 md:py-5">
                            <div class="flex justify-between items-start md:hidden mb-2">
                                @php
                                    // Logika Bahasa Manusia untuk Status
                                    $statusConfig = match ($article->status) {
                                        App\Models\Article::STATUS_PUBLISHED => [
                                            'label' => '✅ Sudah Tayang',
                                            'class' => 'bg-green-100 text-green-800 border-green-200',
                                        ],
                                        App\Models\Article::STATUS_PENDING => [
                                            'label' => '⏳ Menunggu Review',
                                            'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        ],
                                        default => [
                                            'label' => '📝 Konsep (Draft)',
                                            'class' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        ],
                                    };
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>

                            <a href="{{ route('admin.articles.edit', $article) }}"
                                class="block text-lg md:text-base font-bold text-gray-800 dark:text-gray-100 hover:text-brand-primary transition mb-1">
                                {{ Str::limit($article->title, 60) }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <span>Ditulis oleh: <strong
                                        class="text-gray-700 dark:text-gray-300">{{ $article->user->name }}</strong></span>
                            </div>
                        </div>

                        <div class="hidden md:block md:col-span-2 md:px-6 md:py-4 text-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusConfig['class'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>

                        <div class="mt-3 md:mt-0 md:col-span-2 md:px-6 md:py-4">
                            <div class="flex items-center gap-2">
                                <span class="md:hidden text-xs text-gray-400">Kategori:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    📂 {{ $article->category->name }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 md:mt-0 md:col-span-3 md:px-6 md:py-4">
                            <div
                                class="flex items-center md:justify-end gap-3 pt-4 md:pt-0 border-t border-gray-100 md:border-t-0 dark:border-gray-700">
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="flex-1 md:flex-none justify-center inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 text-sm font-bold rounded-lg border border-indigo-100 dark:border-indigo-800 hover:bg-indigo-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Ubah
                                </a>

                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                    class="flex-1 md:flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm font-bold rounded-lg border border-red-100 dark:border-red-800 hover:bg-red-100 transition"
                                        onclick="return confirm('⚠️ Peringatan!\n\nApakah Anda yakin ingin menghapus artikel: \n\n&quot;{{ $article->title }}&quot;?\n\nData yang dihapus tidak bisa dikembalikan.')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center border border-dashed border-gray-300 dark:border-gray-700">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/30 mb-4">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Belum ada artikel disini</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            Website kampus masih kosong dari berita. Yuk, jadilah yang pertama menulis artikel!
                        </p>
                        <a href="{{ route('admin.articles.create') }}"
                            class="mt-6 inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-primary/80">
                            Mulai Menulis Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
