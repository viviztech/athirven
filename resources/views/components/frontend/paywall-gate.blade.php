@props(['article', 'isEntitled' => false])

@if ($article->is_premium && ! $isEntitled)
    <div class="article-body">
        {{ Str::limit(strip_tags($article->body), 400) }}
    </div>

    <div class="mt-6 border border-gold/40 bg-gold/5 p-6 text-center">
        <p class="font-meta text-xs tracking-wider text-gold uppercase">சந்தாதாரர்களுக்கான கட்டுரை</p>
        <p class="mt-2 text-ink">
            முழு கட்டுரையையும் படிக்க சந்தா பதிவு செய்யவும்.
        </p>
        <a href="{{ route('subscriptions.index') }}"
           class="mt-4 inline-block bg-ambedkar px-5 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
            சந்தா செய்யுங்கள்
        </a>
    </div>
@else
    <div class="article-body">
        {!! $article->body !!}
    </div>
@endif
