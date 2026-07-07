@php $featuredImageUrl = $article->getFirstMediaUrl('featured_image'); @endphp
<x-frontend.layout :title="$article->title" :description="$article->meta_description ?? $article->excerpt">
    <article>
        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ $article->type->getLabel() }}
            @if ($article->category)
                &middot; <a href="{{ route('categories.show', $article->category) }}" class="hover:underline">{{ $article->category->name_ta }}</a>
            @endif
            @if ($article->issue)
                &middot; <a href="{{ route('issues.show', $article->issue) }}" class="hover:underline">{{ $article->issue->title }}</a>
            @endif
        </p>

        <h1 class="mt-2 text-3xl font-semibold">{{ $article->title }}</h1>
        @if ($article->subtitle)
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">{{ $article->subtitle }}</p>
        @endif

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            @foreach ($article->authors as $author)
                <a href="{{ route('authors.show', $author) }}" class="hover:underline">{{ $author->pen_name }}</a>{{ ! $loop->last ? ', ' : '' }}
            @endforeach
            @if ($article->published_at)
                &middot; {{ $article->published_at->translatedFormat('d F Y') }}
            @endif
            @if ($article->reading_time_minutes)
                &middot; {{ $article->reading_time_minutes }} நிமிட வாசிப்பு
            @endif
        </p>

        @if ($featuredImageUrl)
            <img src="{{ $featuredImageUrl }}" alt="{{ $article->title }}" class="mt-6 w-full rounded-lg">
        @endif

        @foreach ($article->embeds as $embed)
            @if ($embed->type === \App\Enums\ArticleEmbedType::Video)
                <x-frontend.youtube-embed :video-id="$embed->youtubeVideoId()" :caption="$embed->caption" />
            @else
                <x-frontend.audio-player :src="$embed->url" :caption="$embed->caption" />
            @endif
        @endforeach

        <div class="mt-6">
            <x-frontend.paywall-gate :article="$article" :is-entitled="$isEntitled" />
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($article->tags as $tag)
                    <a
                        href="{{ route('tags.show', $tag) }}"
                        class="rounded-full border border-gray-300 px-3 py-1 text-xs text-gray-600 hover:border-gray-500 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-500"
                    >
                        #{{ $tag->name_ta }}
                    </a>
                @endforeach
            </div>
        @endif
    </article>

    @if ($article->allow_comments)
        <section class="mt-12 border-t border-gray-200 pt-8 dark:border-gray-800">
            <livewire:frontend.comments-section :article="$article" />
        </section>
    @endif
</x-frontend.layout>
