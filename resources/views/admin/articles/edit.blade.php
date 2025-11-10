<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Edit Artikel: ') }} {{ Str::limit($article->title, 40) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($article->suggested_tags && auth()->user()->can('publish-article'))
            <div class="mb-4 p-4 rounded-md bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300">
                <h4 class="font-bold">Tag yang Disarankan</h4>
                <p class="text-sm">Jurnalis menyarankan tag berikut. Jika Anda setuju, silakan buat tag baru (di tab terpisah) lalu tambahkan ke artikel ini. Menyimpan artikel ini akan menghapus saran tersebut.</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($article->suggested_tags as $suggestedTag)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-200 dark:bg-yellow-800/50 text-yellow-900 dark:text-yellow-200">
                        {{ $suggestedTag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Judul') }}</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $article->title) }}" required autofocus class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kategori') }}</label>
                            <select name="category_id" id="category_id" required class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        @php
                        $selectedTags = $article->tags->map(fn($tag) => ['id' => $tag->id, 'name' => $tag->name]);
                        @endphp
                        <div class="mt-4" wire:ignore x-data="tomselect({
                                initialTags: @js($selectedTags),
                                canCreate: @js(auth()->user()->can('publish-article'))
                             })">
                            <label for="tags" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tags') }}</label>
                            <select id="tags" name="tags[]" multiple x-ref="tomselect" class="block mt-1 w-full" placeholder="Ketik untuk mencari tag...">
                                @foreach($article->tags as $tag)
                                <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="excerpt" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kutipan Singkat (Lead)') }}</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Isi Artikel') }}</label>
                            <x-quill-editor name="body" :value="old('body', $article->body)" class="mt-1" />
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="featured_image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Gambar Utama') }}</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <input type="file" name="featured_image" id="featured_image" class="flex-1 text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0 file:text-sm file:font-semibold
                                      file:bg-brand-primary/10 file:text-brand-primary
                                      dark:file:bg-brand-primary/20 dark:file:text-brand-accent
                                      hover:file:bg-brand-primary/20 dark:hover:file:bg-brand-primary/30" onchange="previewImage(event)">

                                {{-- <button type="button" @click="$dispatch('open-media-library', (selectedMedia) => {
                                            document.getElementById('imagePreview').src = selectedMedia.url;
                                            document.getElementById('imagePreview').classList.remove('hidden');
                                            document.getElementById('selected_media_id').value = selectedMedia.id;
                                            document.getElementById('featured_image_caption').value = selectedMedia.caption || '';
                                        })" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700 transition duration-150">
                                    {{ __('Pilih dari Pustaka') }}
                                </button> --}}
                            </div>

                            <input type="hidden" name="selected_media_id" id="selected_media_id" value="">

                            <div class="mt-2">
                                <img id="imagePreview" src="{{ $article->getFirstMediaUrl('featured', 'featured-thumbnail') ?: 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="Preview Gambar" class="max-h-64 rounded-md border border-gray-300 dark:border-gray-600 {{ $article->hasMedia('featured') ? '' : 'hidden' }}" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="featured_image_caption" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Keterangan Gambar Utama (Credit/Caption)') }}</label>
                            <input id="featured_image_caption" type="text" name="featured_image_caption" value="{{ old('featured_image_caption', $article->getFirstMedia('featured')?->getCustomProperty('caption')) }}" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('featured_image_caption')" class="mt-2" />
                        </div>

                        @can('publish-article')
                        <div class="mt-4">
                            <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                           focus:border-brand-accent dark:focus:border-brand-accent
                                           focus:ring-brand-accent dark:focus:ring-brand-accent
                                           rounded-md shadow-sm">
                                <option value="{{ App\Models\Article::STATUS_DRAFT }}" {{ old('status', $article->status) == App\Models\Article::STATUS_DRAFT ? 'selected' : '' }}>Draft</option>
                                <option value="{{ App\Models\Article::STATUS_PENDING }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PENDING ? 'selected' : '' }}>Pending Review</option>
                                <option value="{{ App\Models\Article::STATUS_PUBLISHED }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PUBLISHED ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="mt-4 space-y-2">
                            <label for="is_hero_pinned" class="flex items-center">
                                <input type="checkbox" id="is_hero_pinned" name="is_hero_pinned" value="1" @checked(old('is_hero_pinned', $article->is_hero_pinned))
                                class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-600
                                text-brand-primary dark:text-brand-primary
                                focus:ring-brand-accent dark:focus:ring-brand-accent">
                                <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Jadikan Hero Utama Homepage') }}</span>
                            </label>
                            <label for="is_editors_pick" class="flex items-center">
                                <input type="checkbox" id="is_editors_pick" name="is_editors_pick" value="1" @checked(old('is_editors_pick', $article->is_editors_pick))
                                class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-600
                                text-brand-primary dark:text-brand-primary
                                focus:ring-brand-accent dark:focus:ring-brand-accent">
                                <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Jadikan Pilihan Editor (Slider)') }}</span>
                            </label>
                        </div>
                        @endcan

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-brand-primary/80 focus:outline-none
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Perbarui Artikel') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
    @endpush
</x-app-layout>
