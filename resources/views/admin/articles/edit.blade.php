<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Edit Artikel: ') }} {{ Str::limit($article->title, 40) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Judul') }}</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $article->title) }}" required autofocus
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kategori') }}</label>
                            <select name="category_id" id="category_id" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
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
                            // Siapkan data tag yang sudah ada untuk Tom Select
                            $selectedTags = $article->tags->map(fn($tag) => [
                                'id' => $tag->id,
                                'name' => $tag->name
                            ]);
                        @endphp
                        <div class="mt-4" 
                             wire:ignore 
                             x-data="tomselect({ 
                                initialTags: @js($selectedTags), 
                                canCreate: @js(auth()->user()->can('publish-article')) 
                             })" 
                             x-init="init()">
                            <label for="tags" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tags') }}</label>
                            <select id="tags" name="tags[]" multiple x-ref="tomselect" class="block mt-1 w-full" placeholder="Ketik untuk mencari tag...">
                                @foreach($article->tags as $tag)
                                    <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mt-4">
                            <label for="excerpt" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kutipan Singkat (Lead)') }}</label>
                            <textarea name="excerpt" id="excerpt" rows="3" 
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Isi Artikel') }}</label>
                            <x-quill-editor name="body" :value="old('body', $article->body)" class="mt-1" />
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="featured_image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ganti Gambar Utama (Opsional)') }}</label>
                            <input type="file" name="featured_image" id="featured_image" 
                                   class="block mt-1 w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0 file:text-sm file:font-semibold
                                          file:bg-brand-primary/10 file:text-brand-primary
                                          dark:file:bg-brand-primary/20 dark:file:text-brand-accent
                                          hover:file:bg-brand-primary/20 dark:hover:file:bg-brand-primary/30"
                                   onchange="previewImage(event)">
                            
                            <div class="mt-2">
                                <img id="imagePreview" 
                                     src="{{ $article->getFirstMediaUrl('featured', 'featured-thumbnail') ?: 'https://via.placeholder.com/400x250?text=No+Image' }}" 
                                     alt="Preview Gambar" 
                                     class="max-h-64 rounded-md border border-gray-300 dark:border-gray-600" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="featured_image_caption" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Keterangan Gambar Utama (Credit/Caption)') }}</label>
                            <input id="featured_image_caption" type="text" name="featured_image_caption" 
                                   value="{{ old('featured_image_caption', $article->getFirstMedia('featured')?->getCustomProperty('caption')) }}" 
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                          focus:border-brand-accent dark:focus:border-brand-accent 
                                          focus:ring-brand-accent dark:focus:ring-brand-accent 
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('featured_image_caption')" class="mt-2" />
                        </div>

                        @can('publish-article')
                        <div class="mt-4">
                            <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" id="status" 
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                           focus:border-brand-accent dark:focus:border-brand-accent 
                                           focus:ring-brand-accent dark:focus:ring-brand-accent 
                                           rounded-md shadow-sm">
                                <option value="{{ App\Models\Article::STATUS_DRAFT }}" {{ old('status', $article->status) == App\Models\Article::STATUS_DRAFT ? 'selected' : '' }}>Draft</option>
                                <option value="{{ App\Models\Article::STATUS_PENDING }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PENDING ? 'selected' : '' }}>Pending Review</option>
                                <option value="{{ App\Models\Article::STATUS_PUBLISHED }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PUBLISHED ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        @endcan

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
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