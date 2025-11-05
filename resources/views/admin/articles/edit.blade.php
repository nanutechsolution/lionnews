<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Artikel: ') }} {{ $article->title }} </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $article->title)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Kategori')" />

                            <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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

                        <div class="mt-4" wire:ignore x-data="tomselect({ initialTags: @js($selectedTags) })" x-init="init()">

                            <label for="tags" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tags') }}</label>
                            <select id="tags" name="tags[]" multiple x-ref="tomselect" class="block mt-1 w-full" placeholder="Ketik untuk mencari tag...">

                                @foreach($article->tags as $tag)
                                <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="excerpt" :value="__('Kutipan Singkat (Lead)')" />
                            <textarea name="excerpt" id="excerpt" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="body" :value="__('Isi Artikel')" />
                            <x-quill-editor name="body" :value="old('body', $article->body)" class="mt-1" />
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="featured_image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ganti Gambar Utama (Opsional)') }}</label>

                            <input type="file" name="featured_image" id="featured_image" class="block mt-1 w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0 file:text-sm file:font-semibold
                  file:bg-brand-primary/10 file:text-brand-primary
                  dark:file:bg-brand-primary/20 dark:file:text-brand-accent
                  hover:file:bg-brand-primary/20 dark:hover:file:bg-brand-primary/30" onchange="previewImage(event)">

                            <div class="mt-2">
                                <img id="imagePreview" src="{{ $article->getFirstMediaUrl('featured', 'featured-thumbnail') ?: 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="Preview Gambar" class="max-h-64 rounded-md border border-gray-300 dark:border-gray-600" />
                            </div>
                        </div>
                        @can('publish-article')
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="{{ App\Models\Article::STATUS_DRAFT }}" {{ old('status', $article->status) == App\Models\Article::STATUS_DRAFT ? 'selected' : '' }}>
                                    Draft
                                </option>
                                <option value="{{ App\Models\Article::STATUS_PENDING }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PENDING ? 'selected' : '' }}>
                                    Pending Review
                                </option>
                                <option value="{{ App\Models\Article::STATUS_PUBLISHED }}" {{ old('status', $article->status) == App\Models\Article::STATUS_PUBLISHED ? 'selected' : '' }}>
                                    Published
                                </option>
                            </select>
                        </div>
                        @endcan

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Perbarui Artikel') }}
                            </x-primary-button>
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
