@props(['article'])

<article class="border-b border-gray-100 pb-8 dark:border-gray-800">
    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
        {{ $article->type->getLabel() }}
        @if ($article->category)
            &middot; <a href="{{ route('categories.show', $article->category) }}" class="hover:underline">{{ $article->category->name_ta }}</a>
        @endif
        @if ($article->is_premium)
            <span class="ml-2 rounded-sm bg-amber-100 px-2 py-0.5 text-amber-800 dark:bg-amber-950 dark:text-amber-300">சந்தாதாரர்</span>
        @endif
    </p>
    <h2 class="mt-1 text-xl font-semibold">
        <a href="{{ route('articles.show', $article) }}" class="hover:underline">{{ $article->title }}</a>
    </h2>
    @if ($article->subtitle)
        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $article->subtitle }}</p>
    @endif
    @if ($article->excerpt)
        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $article->excerpt }}</p>
    @endif
    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
        @foreach ($article->authors as $author)
            <a href="{{ route('authors.show', $author) }}" class="hover:underline">{{ $author->pen_name }}</a>{{ ! $loop->last ? ', ' : '' }}
        @endforeach
    </p>
</article>
