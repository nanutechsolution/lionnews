<x-public-layout>
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-brand-primary dark:text-brand-accent font-heading mb-6">
            {{ $page->title }}
        </h1>

        <div class="prose prose-lg max-w-none dark:prose">
            {!! $page->body !!}
        </div>
    </div>
</x-public-layout>
