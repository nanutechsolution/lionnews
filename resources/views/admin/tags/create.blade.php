<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-heading">
            Buat Tag Baru
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    {{-- FORM TAMBAH TAG --}}
                    <form method="POST" action="{{ route('admin.tags.store') }}">
                        @csrf

                        {{-- INPUT: NAMA TAG --}}
                        <div class="mb-6">
                            <label for="name"
                                class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Nama Tag
                            </label>

                            <input id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="block w-full px-3 py-2 border 
                                       @error('name') border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                                       rounded-md shadow-sm
                                       dark:bg-gray-700 dark:text-gray-200
                                       focus:ring-brand-accent focus:border-brand-accent
                                       transition duration-150 ease-in-out">

                            {{-- Helper text (biar user awam ngerti) --}}
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Masukkan nama tag yang ingin dibuat. Contoh: <strong>Teknologi, Kesehatan, Lifestyle.</strong>
                            </p>

                            {{-- ERROR --}}
                            @error('name')
                                <p class="mt-2 text-sm text-red-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 bg-brand-primary 
                                       rounded-md font-semibold text-xs text-white uppercase
                                       hover:bg-brand-primary/90 transition
                                       focus:outline-none focus:ring-2 focus:ring-brand-accent 
                                       focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
