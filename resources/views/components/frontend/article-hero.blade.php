@props(['article'])

@php $image = $article->getFirstMediaUrl('featured_image'); @endphp

<article class="grid gap-8 border-b border-hairline pb-12 sm:grid-cols-2 sm:items-center">
    @if ($image)
        <a href="{{ route('articles.show', $article) }}" class="order-1 block aspect-4/3 overflow-hidden bg-hairline sm:order-2">
            <img src="{{ $image }}" alt="" class="h-full w-full object-cover">
        </a>
    @endif

    <div class="{{ $image ? 'order-2 sm:order-1' : '' }}">
        <p class="font-meta text-xs tracking-wider text-ambedkar uppercase">
            {{ $article->type->getLabel() }}
            @if ($article->category)
                <span class="text-hairline">/</span> {{ $article->category->name_ta }}
            @endif
            @if ($article->is_premium)
                <span class="ml-1 text-gold">&bull; சந்தா</span>
            @endif
        </p>
        <h2 class="mt-3 font-headline text-3xl leading-tight font-bold text-ink sm:text-4xl">
            <a href="{{ route('articles.show', $article) }}" class="hover:text-ambedkar">{{ $article->title }}</a>
        </h2>
        @if ($article->subtitle)
            <p class="mt-3 text-lg text-slate">{{ $article->subtitle }}</p>
        @endif
        @if ($article->excerpt)
            <p class="mt-4 leading-relaxed text-ink">{{ $article->excerpt }}</p>
        @endif
        <p class="mt-5 font-meta text-xs tracking-wider text-slate uppercase">
            @foreach ($article->authors as $author)
                <a href="{{ route('authors.show', $author) }}" class="hover:text-ambedkar">{{ $author->pen_name }}</a>{{ ! $loop->last ? ', ' : '' }}
            @endforeach
        </p>
    </div>
</article>
