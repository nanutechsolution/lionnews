@props(['name', 'value' => ''])

<div 
    x-data="quillEditor({
        name: '{{ $name }}',
        value: @js($value) 
    })" 
    wire:ignore
    class="quill-wrapper rounded-md shadow-sm"
>
    <div x-ref="editor" style="min-height: 250px;">
        </div>
    
    <input type="hidden" name="{{ $name }}" x-model="content">
</div>

<style>
    /* PERBAIKAN: 
      Membuat toolbar dan container menyatu dengan form
    */
    .ql-toolbar {
        border-top-left-radius: 0.375rem; /* rounded-md */
        border-top-right-radius: 0.375rem; /* rounded-md */
        border-color: #d1d5db !important; /* border-gray-300 */
        background-color: #f9fafb; /* bg-gray-50 */
    }
    .ql-container {
        border-bottom-left-radius: 0.375rem; /* rounded-md */
        border-bottom-right-radius: 0.375rem; /* rounded-md */
        border-color: #d1d5db !important; /* border-gray-300 */
    }
    
    /* === DARK MODE === */
    
    .dark .ql-toolbar {
        border-color: #4b5563 !important; /* dark:border-gray-600 */
        background-color: #374151; /* dark:bg-gray-700 */
    }
    .dark .ql-container {
        border-color: #4b5563 !important; /* dark:border-gray-600 */
    }

    .dark .ql-editor {
        /* PERBAIKAN KUNCI:
          Latar belakang editor dibuat lebih gelap (sesuai bg halaman)
          untuk kontras yang jelas.
        */
        background-color: #111827 !important; /* dark:bg-gray-900 */
        color: #d1d5db; /* dark:text-gray-300 */
    }
    
    .dark .ql-toolbar .ql-stroke {
        stroke: #d1d5db; /* dark:text-gray-300 */
    }
    
    .dark .ql-toolbar .ql-picker-label {
        color: #d1d5db; /* dark:text-gray-300 */
    }
    .dark .ql-toolbar .ql-picker-item {
        color: #374151; /* dark:bg-gray-700 */
    }
</style>