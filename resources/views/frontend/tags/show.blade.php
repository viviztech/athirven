<x-frontend.layout :title="'#' . $tag->name_ta">
    <p class="font-meta text-xs tracking-wider text-slate uppercase">குறிச்சொல்</p>
    <h1 class="mt-2 font-headline text-3xl font-bold text-ink">#{{ $tag->name_ta }}</h1>

    <section class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-slate">இந்த குறிச்சொல்லில் இன்னும் கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
