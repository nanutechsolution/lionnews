<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Tulis Artikel Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="title"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Judul') }}</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required
                                autofocus
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="category_id"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kategori') }}</label>
                            <select name="category_id" id="category_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4" wire:ignore x-data="tomselect({ initialTags: [] })" x-init="init()">
                            <label for="tags"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tags') }}</label>
                            <select id="tags" name="tags[]" multiple class="block mt-1 w-full" x-ref="tomselect"
                                placeholder="Ketik untuk mencari tag..."></select>
                        </div>

                        <div class="mt-4">
                            <label for="excerpt"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kutipan Singkat (Lead)') }}</label>
                            <textarea name="excerpt" id="excerpt" rows="3"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="body"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Isi Artikel') }}</label>
                            <x-quill-editor name="body" :value="old('body')" class="mt-1" />
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>


                        <div class="mt-4">
                            <label for="featured_image"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Gambar Utama') }}</label>
                            <input type="file" name="featured_image" id="featured_image"
                                class="block mt-1 w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0 file:text-sm file:font-semibold
                                          file:bg-brand-primary/10 file:text-brand-primary
                                          dark:file:bg-brand-primary/20 dark:file:text-brand-accent
                                          hover:file:bg-brand-primary/20 dark:hover:file:bg-brand-primary/30"
                                onchange="previewImage(event)">
                            <x-input-error :messages="$errors->get('featured_image')" class="mt-2 text-red-500" />
                            <div class="mt-2">
                                <img id="imagePreview" src="#" alt="Preview Gambar"
                                    class="hidden max-h-64 rounded-md border border-gray-300 dark:border-gray-600" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="featured_image_caption"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Keterangan Gambar Utama (Credit/Caption)') }}
                            </label>
                            <input id="featured_image_caption" type="text" name="featured_image_caption"
                                value="{{ old('featured_image_caption') }}"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                  focus:border-brand-accent dark:focus:border-brand-accent 
                  focus:ring-brand-accent dark:focus:ring-brand-accent 
                  rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('featured_image_caption')" class="mt-2" />
                        </div>
                        @can('publish-article')
                            <div class="mt-4">
                                <label for="status"
                                    class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                                <select name="status" id="status"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-brand-accent dark:focus:border-brand-accent focus:ring-brand-accent dark:focus:ring-brand-accent rounded-md shadow-sm">
                                    <option value="{{ App\Models\Article::STATUS_DRAFT }}"
                                        {{ old('status') == App\Models\Article::STATUS_DRAFT ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="{{ App\Models\Article::STATUS_PENDING }}"
                                        {{ old('status') == App\Models\Article::STATUS_PENDING ? 'selected' : '' }}>Pending
                                        Review</option>
                                    <option value="{{ App\Models\Article::STATUS_PUBLISHED }}"
                                        {{ old('status') == App\Models\Article::STATUS_PUBLISHED ? 'selected' : '' }}>
                                        Published</option>
                                </select>
                            </div>
                        @endcan
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Pin to Hero -->
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_hero_pinned" value="1"
                                    class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                                    {{ old('is_hero_pinned') ? 'checked' : '' }}>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">
                                    Tampilkan di Hero Section
                                </span>
                            </label>

                            <!-- Editor's Pick -->
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_editors_pick" value="1"
                                    class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                                    {{ old('is_editors_pick') ? 'checked' : '' }}>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">
                                    Pilihan Editor
                                </span>
                            </label>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                           hover:bg-brand-primary/80 focus:outline-none 
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                @can('publish-article')
                                    {{ __('Simpan Artikel') }}
                                @else
                                    {{ __('Kirim untuk Review') }}
                                @endcan
                            </button>
                        </div>
                    </form>
                    @if ($errors->any())
                        <script>
                            document.addEventListener('alpine:init', () => {
                                @foreach ($errors->all() as $error)
                                    window.dispatchEvent(new CustomEvent('toast', {
                                        detail: "{{ $error }}"
                                    }));
                                @endforeach
                            });
                        </script>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
                // Restore simple inputs
                fields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el && localStorage.getItem(id)) {
                    if (!el) return;

                    const saved = localStorage.getItem('article_' + id);

                    if (saved !== null) {
                        if (el.type === 'checkbox') {
                            el.checked = saved === '1';
                        } else {
                            el.value = saved;
                        }
                    }
                });

                // ======== RESTORE QUILL ========
                const quillInterval = setInterval(() => {
                    const quillRoot = document.querySelector('.ql-editor');
                    const saved = localStorage.getItem('article_body');

                    if (quillRoot && saved) {
                        quillRoot.innerHTML = saved;
                        clearInterval(quillInterval);
                    }
                }, 300);

                // ======== AUTO SAVE ========
                setInterval(() => {
                    fields.forEach(id => {
                        const el = document.getElementById(id);
                        if (!el) return;

                        if (el.type === 'checkbox') {
                            localStorage.setItem('article_' + id, el.checked ? '1' : '0');
                        } else {
                            localStorage.setItem('article_' + id, el.value);
                        }
                    });

                    // Save body Quill
                    const body = document.querySelector('.ql-editor')?.innerHTML ?? '';
                    localStorage.setItem('article_body', body);

                }, 1500);

                // ======== CLEAR STORAGE on submit ========
                const form = document.querySelector('form');
                form.addEventListener('submit', () => {
                    fields.forEach(id => localStorage.removeItem('article_' + id));
                    localStorage.removeItem('article_body');
                });

            });
        </script>

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
                } else {
                    preview.src = '#';
                    preview.classList.add('hidden');
                }
            }
        </script>
    @endpush
</x-app-layout>
