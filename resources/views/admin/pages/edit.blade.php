<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Edit Halaman: ') }} {{ $page->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Judul Halaman') }}</LabeL>
                            <input id="title" type="text" name="title"
                                   value="{{ old('title', $page->title) }}" required autofocus
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                             <label for="slug" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('URL (Slug)') }}</LabeL>
                             <input id="slug" type="text"
                                    value="{{ $page->slug }}" readonly
                                    class="block mt-1 w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600
                                           text-gray-500 dark:text-gray-400 rounded-md shadow-sm">
                             <p class="mt-1 text-xs text-gray-500">Slug akan di-update otomatis jika Anda mengubah judul.</p>
                        </div>

                        <div class="mt-4">
                            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Isi Halaman') }}</label>
                            <x-quill-editor name="body" :value="old('body', $page->body)" class="mt-1" />
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="is_published" class="flex items-center">
                                <input type="checkbox" id="is_published" name="is_published" value="1"
                                       @checked(old('is_published', $page->is_published))
                                       class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-600
                                              text-brand-primary dark:text-brand-primary
                                              focus:ring-brand-accent dark:focus:ring-brand-accent">
                                <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Publikasikan Halaman') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-brand-primary/80 focus:outline-none
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Perbarui Halaman') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
