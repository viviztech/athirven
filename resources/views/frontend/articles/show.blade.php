@php $featuredImageUrl = $article->getFirstMediaUrl('featured_image'); @endphp
<x-frontend.layout :title="$article->title" :description="$article->meta_description ?? $article->excerpt">
    <x-frontend.json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified' => $article->updated_at->toIso8601String(),
        'author' => $article->authors->map(fn ($author) => ['@type' => 'Person', 'name' => $author->pen_name])->all(),
        'image' => $featuredImageUrl ? [$featuredImageUrl] : [],
        'publisher' => ['@type' => 'Organization', 'name' => 'அதிர்வெண்'],
        'mainEntityOfPage' => route('articles.show', $article),
    ]" />

    <article class="mx-auto max-w-2xl">
        <p class="font-meta text-xs tracking-wider text-ambedkar uppercase">
            {{ $article->type->getLabel() }}
            @if ($article->category)
                <span class="text-hairline">/</span>
                <a href="{{ route('categories.show', $article->category) }}" class="hover:text-ambedkar-ink">{{ $article->category->name_ta }}</a>
            @endif
            @if ($article->issue)
                <span class="text-hairline">/</span>
                <a href="{{ route('issues.show', $article->issue) }}" class="hover:text-ambedkar-ink">{{ $article->issue->title }}</a>
            @endif
        </p>

        <h1 class="mt-3 font-headline text-3xl leading-tight font-bold text-ink sm:text-4xl">{{ $article->title }}</h1>
        @if ($article->subtitle)
            <p class="mt-3 text-lg text-slate">{{ $article->subtitle }}</p>
        @endif

        <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-1 border-y border-hairline py-3 font-meta text-xs tracking-wider text-slate uppercase">
            @foreach ($article->authors as $author)
                <a href="{{ route('authors.show', $author) }}" class="text-ink hover:text-ambedkar">{{ $author->pen_name }}</a>{{ ! $loop->last ? ',' : '' }}
            @endforeach
            @if ($article->published_at)
                <span class="text-hairline">&bull;</span> {{ $article->published_at->translatedFormat('d F Y') }}
            @endif
            @if ($article->reading_time_minutes)
                <span class="text-hairline">&bull;</span> {{ $article->reading_time_minutes }} நிமிட வாசிப்பு
            @endif
        </div>

        @if ($featuredImageUrl)
            <img src="{{ $featuredImageUrl }}" alt="{{ $article->title }}" class="mt-8 w-full">
        @endif

        @foreach ($article->embeds as $embed)
            <div class="mt-8">
                @if ($embed->type === \App\Enums\ArticleEmbedType::Video)
                    <x-frontend.youtube-embed :video-id="$embed->youtubeVideoId()" :caption="$embed->caption" />
                @else
                    <x-frontend.audio-player :src="$embed->url" :caption="$embed->caption" />
                @endif
            </div>
        @endforeach

        <div class="mt-8">
            <x-frontend.paywall-gate :article="$article" :is-entitled="$isEntitled" />
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2 font-meta text-xs uppercase">
                @foreach ($article->tags as $tag)
                    <a
                        href="{{ route('tags.show', $tag) }}"
                        class="border border-hairline px-3 py-1 text-slate hover:border-ambedkar hover:text-ambedkar"
                    >
                        #{{ $tag->name_ta }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-8 border-t border-hairline pt-8">
            <x-frontend.share-buttons :url="route('articles.show', $article)" :title="$article->title" />
        </div>
    </article>

    @if ($article->allow_comments)
        <section class="mx-auto mt-12 max-w-2xl border-t border-hairline pt-8">
            <livewire:frontend.comments-section :article="$article" />
        </section>
    @endif
</x-frontend.layout>
