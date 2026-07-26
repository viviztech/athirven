<x-frontend.layout :title="$category->name_ta">
    <p class="font-meta text-xs tracking-wider text-slate uppercase">
        @if ($category->parent)
            <a href="{{ route('categories.show', $category->parent) }}" class="hover:text-ambedkar">{{ $category->parent->name_ta }}</a> &rsaquo;
        @endif
        பிரிவு
    </p>
    <h1 class="mt-2 font-headline text-3xl font-bold text-ink sm:text-4xl">{{ $category->name_ta }}</h1>

    <x-frontend.waveform class="waveform-divider mt-8" />

    <section class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-slate">இந்த பிரிவில் இன்னும் கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
