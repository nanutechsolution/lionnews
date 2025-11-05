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
                    
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nama') }}</label>
                            
                            <input id="name" type="text" name="name" 
                                   value="{{ old('name', $user->name) }}" required autofocus
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                          focus:border-brand-accent dark:focus:border-brand-accent 
                                          focus:ring-brand-accent dark:focus:ring-brand-accent 
                                          rounded-md shadow-sm">
                                          
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input id="email" type="email" name="email" 
                                   value="{{ old('email', $user->email) }}" required
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                          focus:border-brand-accent dark:focus:border-brand-accent 
                                          focus:ring-brand-accent dark:focus:ring-brand-accent 
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Role') }}</label>
                            <select name="role" id="role" 
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
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
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
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
</x-app-layout>