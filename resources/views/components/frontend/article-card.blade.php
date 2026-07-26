@props(['article'])

@php $image = $article->getFirstMediaUrl('featured_image'); @endphp

<article class="group">
    @if ($image)
        <a href="{{ route('articles.show', $article) }}" class="block aspect-3/2 overflow-hidden bg-hairline">
            <img src="{{ $image }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
        </a>
    @endif

    <div class="mt-4">
        <p class="font-meta text-[11px] tracking-wider text-slate uppercase">
            {{ $article->type->getLabel() }}
            @if ($article->category)
                <span class="text-hairline">/</span>
                <a href="{{ route('categories.show', $article->category) }}" class="hover:text-ambedkar">{{ $article->category->name_ta }}</a>
            @endif
            @if ($article->is_premium)
                <span class="ml-1 text-gold">&bull; சந்தா</span>
            @endif
        </p>
        <h3 class="mt-2 font-headline text-xl font-bold text-ink">
            <a href="{{ route('articles.show', $article) }}" class="hover:text-ambedkar">{{ $article->title }}</a>
        </h3>
        @if ($article->excerpt)
            <p class="mt-2 text-sm leading-relaxed text-slate">{{ Str::limit($article->excerpt, 120) }}</p>
        @endif
        <p class="mt-3 font-meta text-[11px] tracking-wider text-slate uppercase">
            @foreach ($article->authors as $author)
                <a href="{{ route('authors.show', $author) }}" class="hover:text-ambedkar">{{ $author->pen_name }}</a>{{ ! $loop->last ? ', ' : '' }}
            @endforeach
        </p>
    </div>
</article>
