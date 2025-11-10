<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Pengaturan Situs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-medium text-brand-primary dark:text-brand-accent font-heading">Breaking News</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Aktifkan ini untuk menampilkan banner darurat di atas semua halaman.</p>

                        <div class="mt-4">
                            <label for="breaking_news_active" class="flex items-center">
                                <input type="checkbox" id="breaking_news_active" name="breaking_news_active" value="1" @checked(old('breaking_news_active', $settings->breaking_news_active))
                                class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-600
                                text-brand-primary dark:text-brand-primary
                                focus:ring-brand-accent dark:focus:ring-brand-accent">
                                <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Aktifkan Banner Breaking News') }}</span>
                            </label>
                        </div>

                        <div class="mt-4">
                            <label for="breaking_news_text" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Teks Banner') }}</label>
                            <input id="breaking_news_text" type="text" name="breaking_news_text" value="{{ old('breaking_news_text', $settings->breaking_news_text) }}" placeholder="BREAKING: Gempa Guncang Sumba" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                        </div>

                        <div class="mt-4">
                            <label for="breaking_news_link" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Link Banner') }}</label>
                            <input id="breaking_news_link" type="text" name="breaking_news_link" value="{{ old('breaking_news_link', $settings->breaking_news_link) }}" placeholder="/search?q=Kata Kunci" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                  focus:border-brand-accent dark:focus:border-brand-accent
                  focus:ring-brand-accent dark:focus:ring-brand-accent
                  rounded-md shadow-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tips: Arahkan ke pencarian (cth: /search?q=Gempa) agar link otomatis terisi saat berita terbit.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-brand-primary/80 focus:outline-none
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Simpan Pengaturan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
