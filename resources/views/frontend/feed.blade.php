<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>அதிர்வெண்</title>
        <link>{{ route('home') }}</link>
        <description>தலித் அரசியல் மற்றும் பண்பாட்டு மாத இதழ்</description>
        <language>ta</language>
@foreach ($articles as $article)
        <item>
            <title>{{ $article->title }}</title>
            <link>{{ route('articles.show', $article) }}</link>
            <guid>{{ route('articles.show', $article) }}</guid>
            <pubDate>{{ $article->published_at->toRfc2822String() }}</pubDate>
            @if ($article->excerpt)
            <description>{{ $article->excerpt }}</description>
            @endif
        </item>
@endforeach
    </channel>
</rss>
