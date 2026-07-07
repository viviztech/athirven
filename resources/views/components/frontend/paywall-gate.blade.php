@props(['article', 'isEntitled' => false])

@if ($article->is_premium && ! $isEntitled)
    <div class="prose prose-gray dark:prose-invert max-w-none">
        {{ Str::limit(strip_tags($article->body), 400) }}
    </div>

    <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-6 text-center dark:border-amber-900 dark:bg-amber-950/40">
        <p class="font-semibold text-amber-900 dark:text-amber-200">இது சந்தாதாரர்களுக்கான கட்டுரை</p>
        <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
            முழு கட்டுரையையும் படிக்க சந்தா பதிவு செய்யவும். (சந்தா முறை விரைவில் வரும்.)
        </p>
    </div>
@else
    <div class="prose prose-gray dark:prose-invert max-w-none">
        {!! $article->body !!}
    </div>
@endif
