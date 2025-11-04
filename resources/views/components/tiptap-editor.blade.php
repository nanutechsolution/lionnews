@props(['name', 'value' => ''])

<div
    x-data="tiptapEditor({
        name: '{{ $name }}',
        value: '{{ $value }}'
    })"
    x-init="init()"
    wire:ignore
    {{ $attributes->merge(['class' => 'tiptap-wrapper']) }}
>
    <input type="hidden" name="{{ $name }}" x-model="content">

    <div class="tiptap-toolbar border border-gray-300 rounded-t-md p-2 flex flex-wrap gap-1">
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">H2</button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">H3</button>
        <button type="button" @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'is-active': editor.isActive('bold') }">Bold</button>
        <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'is-active': editor.isActive('italic') }">Italic</button>
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'is-active': editor.isActive('bulletList') }">Bullet</button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'is-active': editor.isActive('orderedList') }">Ordered</button>
    </div>

    <div x-ref="editor" class="tiptap-content border-x border-b border-gray-300 rounded-b-md p-3 min-h-[250px] focus:outline-none">
        </div>
</div>

<style>
.tiptap-toolbar button {
    @apply px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100;
}
.tiptap-toolbar button.is-active {
    @apply bg-blue-600 text-white border-blue-600;
}
/* Styling untuk konten editor */
.tiptap-content p { @apply my-2; }
.tiptap-content h2 { @apply text-2xl font-bold my-4; }
.tiptap-content h3 { @apply text-xl font-semibold my-3; }
.tiptap-content ul { @apply list-disc list-inside my-2; }
.tiptap-content ol { @apply list-decimal list-inside my-2; }
.tiptap-content:focus { @apply outline-none; } /* Dihandle oleh div wrapper */
</style>
