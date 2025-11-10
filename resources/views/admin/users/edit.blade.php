<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nama') }}</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <label for="bio" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Bio Penulis') }}</label>
                            <textarea name="bio" id="bio" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                             focus:border-brand-accent dark:focus:border-brand-accent
                                             focus:ring-brand-accent dark:focus:ring-brand-accent
                                             rounded-md shadow-sm">{{ old('bio', $user->bio) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Bio singkat ini akan muncul di halaman profil publik penulis.</p>
                        </div>

                        <div class="mt-4">
                            <label for="twitter_handle" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Twitter/X Handle (Opsional)') }}</label>
                            <input id="twitter_handle" type="text" name="twitter_handle" value="{{ old('twitter_handle', $user->twitter_handle) }}" placeholder="@username" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                          focus:border-brand-accent dark:focus:border-brand-accent
                                          focus:ring-brand-accent dark:focus:ring-brand-accent
                                          rounded-md shadow-sm">
                        </div>

                        <div class="mt-4">
                            <label for="avatar" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Foto Profil (Avatar)') }}</label>
                            <input type="file" name="avatar" id="avatar" class="block mt-1 w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0 file:text-sm file:font-semibold
                                          file:bg-brand-primary/10 file:text-brand-primary
                                          dark:file:bg-brand-primary/20 dark:file:text-brand-accent
                                          hover:file:bg-brand-primary/20 dark:hover:file:bg-brand-primary/30" onchange="previewImage(event)">

                            <div class="mt-2">
                                <img id="imagePreview" src="{{ $user->getFirstMediaUrl('avatar', 'avatar-thumb') ?: 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="Preview Avatar" class="w-32 h-32 rounded-full object-cover border border-gray-300 dark:border-gray-600" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Role') }}</label>
                            <select name="role" id="role" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                           focus:border-brand-accent dark:focus:border-brand-accent
                                           focus:ring-brand-accent dark:focus:ring-brand-accent
                                           rounded-md shadow-sm">
                                <option value="journalist" {{ old('role', $user->role) == 'journalist' ? 'selected' : '' }}>
                                    Journalist
                                </option>
                                <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>
                                    Editor
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-brand-primary/80 focus:outline-none
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Perbarui Pengguna') }}
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
            } else {}
        }

    </script>
    @endpush
</x-app-layout>
