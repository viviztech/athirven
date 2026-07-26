<!DOCTYPE html>
<html lang="ta">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="font-family: sans-serif; color: #111827;">
        <h1 style="font-size: 20px;">அதிர்வெண் — புதிய கட்டுரைகள்</h1>

        @foreach ($articles as $article)
            <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="font-size: 16px; margin-bottom: 4px;">
                    <a href="{{ route('articles.show', $article) }}" style="color: #111827; text-decoration: none;">{{ $article->title }}</a>
                </h2>
                @if ($article->excerpt)
                    <p style="color: #4b5563; font-size: 14px;">{{ $article->excerpt }}</p>
                @endif
            </div>
        @endforeach

        <p style="font-size: 12px; color: #9ca3af; margin-top: 30px;">
            <a href="{{ route('newsletter.unsubscribe', $subscriber) }}" style="color: #9ca3af;">குழுசேர்வை நிறுத்தவும்</a>
        </p>
    </body>
</html>
