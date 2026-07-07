<x-frontend.layout :title="'#' . $tag->name_ta">
    <h1 class="text-3xl font-semibold">#{{ $tag->name_ta }}</h1>

    <section class="mt-8 space-y-8">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-gray-500 dark:text-gray-400">இந்த குறிச்சொல்லில் இன்னும் கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
