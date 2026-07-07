@php $photoUrl = $author->getFirstMediaUrl('photo'); @endphp
<x-frontend.layout :title="$author->pen_name">
    <div class="flex items-start gap-5">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $author->pen_name }}" class="size-20 rounded-full object-cover">
        @endif
        <div>
            <h1 class="text-2xl font-semibold">{{ $author->pen_name }}</h1>
            @if ($author->bio)
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $author->bio }}</p>
            @endif
        </div>
    </div>

    <section class="mt-10 space-y-8">
        @forelse ($articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-gray-500 dark:text-gray-400">இன்னும் கட்டுரைகள் வெளியிடப்படவில்லை.</p>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</x-frontend.layout>
