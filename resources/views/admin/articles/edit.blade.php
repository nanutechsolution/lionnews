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
                        @method('PUT') <div>
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

                        <div class="mt-4">
                            <x-input-label for="excerpt" :value="__('Kutipan Singkat (Lead)')" />
                            <textarea name="excerpt" id="excerpt" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="body" :value="__('Isi Artikel')" />

                            <x-tiptap-editor name="body" :value="old('body', $article->body)" class="mt-1" />

                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="featured_image" :value="__('Ganti Gambar Utama (Opsional)')" />
                            <input type="file" name="featured_image" id="featured_image" class="block mt-1 w-full">
                            @if($article->featured_image_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $article->featured_image_path) }}" alt="Gambar Utama" class="w-48 h-auto rounded">
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Perbarui Artikel') }} </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
