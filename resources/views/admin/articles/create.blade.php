<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                ✍️ Tulis Artikel Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Buat konten berkualitas yang memberikan nilai pada pembaca.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data"
                  class="space-y-10">
                @csrf

                {{-- =============================== --}}
                {{-- SECTION: Informasi Dasar --}}
                {{-- =============================== --}}
                <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-xl p-8 border border-gray-200 dark:border-gray-700">

                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        🧩 Informasi Dasar
                    </h3>


                    {{-- Title --}}
                    <div class="mb-6">
                        <label for="title" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>

                        <input id="title" type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 rounded-lg border
                                   @error('title') border-red-500 dark:border-red-500
                                   @else border-gray-300 dark:border-gray-600 @enderror
                                   bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                   focus:ring-2 focus:ring-brand-accent focus:border-brand-accent" />

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Buat judul yang ringkas, punchy, dan clickable.
                        </p>

                        <x-input-error :messages="$errors->get('title')" class="mt-2 text-red-500" />
                    </div>


                    {{-- Category --}}
                    <div class="mb-6">
                        <label for="category_id" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Kategori
                        </label>

                        <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                   focus:ring-2 focus:ring-brand-accent">
                            <option value="">— Pilih kategori —</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <x-input-error :messages="$errors->get('category_id')" class="mt-2 text-red-500" />
                    </div>


                    {{-- Tags --}}
                    <div x-data="tomselect({ initialTags: [] })" x-init="init()" class="mb-6" wire:ignore>
                        <label for="tags" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Tags
                        </label>

                        <select id="tags" name="tags[]" multiple x-ref="tomselect" class="w-full"></select>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Pilih beberapa tag agar mudah ditemukan.
                        </p>

                        @error('tags')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </section>




                {{-- =============================== --}}
                {{-- SECTION: Konten Artikel --}}
                {{-- =============================== --}}
                <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-xl p-8 border border-gray-200 dark:border-gray-700">

                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        📝 Konten Artikel
                    </h3>

                    {{-- Excerpt --}}
                    <div class="mb-6">
                        <label for="excerpt" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Ringkasan (Lead)
                        </label>

                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                   focus:ring-2 focus:ring-brand-accent">{{ old('excerpt') }}</textarea>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Ringkasan 1–2 kalimat untuk menarik pembaca.
                        </p>
                    </div>

                    {{-- Body --}}
                    <div class="mb-2">
                        <label class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Isi Artikel <span class="text-red-500">*</span>
                        </label>

                        <x-quill-editor name="body" :value="old('body')" />

                        <x-input-error :messages="$errors->get('body')" class="mt-2 text-red-500" />
                    </div>

                </section>




                {{-- =============================== --}}
                {{-- SECTION: Gambar Utama --}}
                {{-- =============================== --}}
                <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-xl p-8 border border-gray-200 dark:border-gray-700">

                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        🖼️ Gambar Utama
                    </h3>

                    {{-- Upload --}}
                    <div class="mb-6">
                        <label for="featured_image" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Upload Gambar
                        </label>

                        <input type="file" id="featured_image" name="featured_image"
                            class="block w-full text-sm text-gray-700 dark:text-gray-300
                                   file:bg-brand-primary/10 dark:file:bg-brand-primary/20
                                   file:text-brand-primary dark:file:text-brand-accent
                                   file:px-4 file:py-2 file:rounded-lg"
                            onchange="previewImage(event)" />

                        <x-input-error :messages="$errors->get('featured_image')" class="mt-2 text-red-500" />

                        <img id="imagePreview" class="hidden rounded-md mt-4 max-h-72 border border-gray-300 dark:border-gray-600" />
                    </div>

                    {{-- Caption --}}
                    <div class="mb-2">
                        <label for="featured_image_caption" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                            Caption / Credit
                        </label>

                        <input type="text" id="featured_image_caption" name="featured_image_caption"
                            value="{{ old('featured_image_caption') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                   focus:ring-2 focus:ring-brand-accent" />
                    </div>

                </section>




                {{-- =============================== --}}
                {{-- SECTION: Status & Pin --}}
                {{-- =============================== --}}
                <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-xl p-8 border border-gray-200 dark:border-gray-700">

                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        🚦 Pengaturan Publikasi
                    </h3>

                    @can('publish-article')
                        <div class="mb-6">
                            <label for="status" class="block mb-2 font-semibold text-sm text-gray-700 dark:text-gray-300">
                                Status Artikel
                            </label>

                            <select id="status" name="status"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                       focus:ring-2 focus:ring-brand-accent">
                                <option value="{{ App\Models\Article::STATUS_DRAFT }}">Draft</option>
                                <option value="{{ App\Models\Article::STATUS_PENDING }}">Pending Review</option>
                                <option value="{{ App\Models\Article::STATUS_PUBLISHED }}">Published</option>
                            </select>
                        </div>
                    @endcan

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex gap-3 items-center cursor-pointer">
                            <input type="checkbox" name="is_hero_pinned" value="1"
                                class="h-5 w-5 rounded border-gray-300 text-brand-primary"
                                {{ old('is_hero_pinned') ? 'checked' : '' }}>
                            <span class="font-medium">Tampilkan di Hero Section</span>
                        </label>

                        <label class="flex gap-3 items-center cursor-pointer">
                            <input type="checkbox" name="is_editors_pick" value="1"
                                class="h-5 w-5 rounded border-gray-300 text-brand-primary"
                                {{ old('is_editors_pick') ? 'checked' : '' }}>
                            <span class="font-medium">Pilihan Editor</span>
                        </label>
                    </div>

                </section>




                {{-- =============================== --}}
                {{-- SUBMIT --}}
                {{-- =============================== --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-brand-primary text-white rounded-lg text-sm font-semibold shadow
                               hover:bg-brand-primary/85 transition focus:ring-2 focus:ring-brand-accent">

                        @can('publish-article')
                            Simpan Artikel
                        @else
                            Kirim untuk Review
                        @endcan
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- Preview Image Script --}}
    @push('scripts')
        <script>
            function previewImage(event) {
                const preview = document.getElementById('imagePreview');
                const file = event.target.files[0];
                if (!file) return preview.classList.add('hidden');

                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        </script>
    @endpush

</x-app-layout>
