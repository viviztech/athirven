<!DOCTYPE html>
<html lang="ta">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>அதிர்வெண் — {{ config('app.name') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css'])
        @endif

        <style>
            /* Tamil font stack placeholder — self-hosted Noto Sans Tamil lands in Phase 3. */
            body { font-family: "Noto Sans Tamil", "Tamil Sangam MN", "Catamaran", ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-white text-gray-900 antialiased">
        <header class="border-b border-gray-200">
            <div class="mx-auto max-w-3xl px-6 py-6 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-semibold">அதிர்வெண்</a>
                <span class="text-sm text-gray-500">தலித் அரசியல் · பண்பாடு</span>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-6 py-10">
            @if ($latestIssue)
                <section class="mb-10">
                    <p class="text-sm text-gray-500 uppercase tracking-wider">சமீபத்திய இதழ்</p>
                    <h1 class="mt-1 text-3xl font-semibold">{{ $latestIssue->title }}</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $latestIssue->publish_date?->translatedFormat('F Y') }}
                    </p>
                </section>

                <section class="space-y-8">
                    @forelse ($recentArticles as $article)
                        <article class="border-b border-gray-100 pb-8">
                            <p class="text-xs uppercase tracking-wider text-gray-500">
                                {{ $article->type->getLabel() }}
                                @if ($article->category) &middot; {{ $article->category->name_ta }} @endif
                                @if ($article->is_premium)
                                    <span class="ml-2 rounded-sm bg-amber-100 px-2 py-0.5 text-amber-800">சந்தாதாரர்</span>
                                @endif
                            </p>
                            <h2 class="mt-1 text-xl font-semibold">{{ $article->title }}</h2>
                            @if ($article->subtitle)
                                <p class="mt-1 text-gray-600">{{ $article->subtitle }}</p>
                            @endif
                            @if ($article->excerpt)
                                <p class="mt-3 text-gray-700">{{ $article->excerpt }}</p>
                            @endif
                            <p class="mt-3 text-sm text-gray-500">
                                {{ $article->authors->pluck('pen_name')->join(', ') }}
                            </p>
                        </article>
                    @empty
                        <p class="text-gray-500">இந்த இதழில் இன்னும் வெளியிடப்பட்ட கட்டுரைகள் இல்லை.</p>
                    @endforelse
                </section>
            @else
                <p class="text-gray-500">இன்னும் எந்த இதழும் வெளியிடப்படவில்லை. விரைவில் வருகிறது.</p>
            @endif
        </main>

        <footer class="border-t border-gray-200 mt-16">
            <div class="mx-auto max-w-3xl px-6 py-8 text-sm text-gray-500">
                &copy; {{ now()->year }} அதிர்வெண். அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.
            </div>
        </footer>
    </body>
</html>
