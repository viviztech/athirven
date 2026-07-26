@php $photoUrl = $author->getFirstMediaUrl('photo'); @endphp
<x-frontend.layout :title="$author->pen_name">
    <div class="flex items-start gap-6 border-b border-hairline pb-10">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $author->pen_name }}" class="size-20 shrink-0 object-cover">
        @endif
        <div>
            <p class="font-meta text-xs tracking-wider text-slate uppercase">எழுத்தாளர்</p>
            <h1 class="mt-1 font-headline text-2xl font-bold text-ink">{{ $author->pen_name }}</h1>
            @if ($author->bio)
                <p class="mt-3 leading-relaxed text-ink">{{ $author->bio }}</p>
            @endif
        </div>
    </div>

    <section class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-slate">இன்னும் கட்டுரைகள் வெளியிடப்படவில்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
