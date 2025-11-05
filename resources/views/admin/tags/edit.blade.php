<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            {{ __('Edit Tag') }}: {{ $tag->name }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nama Tag') }}</Ulabel>
                            <input id="name" type="text" name="name" 
                                   value="{{ old('name', $tag->name) }}" required autofocus
                                   class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                          focus:border-brand-accent dark:focus:border-brand-accent 
                                          focus:ring-brand-accent dark:focus:ring-brand-accent 
                                          rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent 
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                           hover:bg-brand-primary/80 focus:outline-none 
                                           focus:ring-2 focus:ring-brand-accent focus:ring-offset-2 
                                           dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Perbarui') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>