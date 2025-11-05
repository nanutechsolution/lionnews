@props(['name', 'value' => ''])

<div 
    x-data="quillEditor({
        name: '{{ $name }}',
        value: @js($value) 
    })" 
    wire:ignore
    class="quill-wrapper rounded-md shadow-sm"
    
    x-on:click="if (editor) editor.focus()"
    style="cursor: text;"
    >
    <div x-ref="editor" style="min-height: 250px;">
        </div>
    
    <input type="hidden" name="{{ $name }}" x-model="content">
</div>

<style>
    .ql-toolbar, .ql-container {
        border-color: #d1d5db !important; /* border-gray-300 */
    }
    
    .dark .ql-toolbar, .dark .ql-container {
        border-color: #4b5563 !important; /* dark:border-gray-600 */
    }

    .dark .ql-editor {
        background-color: #374151; /* dark:bg-gray-700 */
        color: #d1d5db; /* dark:text-gray-300 */
    }
    
    .dark .ql-toolbar .ql-stroke {
        stroke: #d1d5db; /* dark:text-gray-300 */
    }
    
    .dark .ql-toolbar .ql-picker-label {
        color: #d1d5db; /* dark:text-gray-300 */
    }
</style>