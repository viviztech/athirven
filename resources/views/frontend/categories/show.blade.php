<x-frontend.layout :title="$category->name_ta">
    <p class="text-sm text-gray-500 dark:text-gray-400">
        @if ($category->parent)
            <a href="{{ route('categories.show', $category->parent) }}" class="hover:underline">{{ $category->parent->name_ta }}</a> &rsaquo;
        @endif
    </p>
    <h1 class="mt-1 text-3xl font-semibold">{{ $category->name_ta }}</h1>

    <section class="mt-8 space-y-8">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-gray-500 dark:text-gray-400">இந்த பிரிவில் இன்னும் கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
