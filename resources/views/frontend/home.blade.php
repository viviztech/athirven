<x-frontend.layout description="தலித் அரசியல் மற்றும் பண்பாட்டு மாத இதழ்">
    @if ($latestIssue)
        <div class="mb-10 flex items-center justify-between font-meta text-xs tracking-wider uppercase">
            <a href="{{ route('issues.show', $latestIssue) }}" class="text-slate hover:text-ambedkar">
                இதழ் {{ $latestIssue->issue_number }} — {{ $latestIssue->publish_date?->translatedFormat('F Y') }}
            </a>
            <a href="{{ route('issues.index') }}" class="text-slate hover:text-ambedkar">
                பழைய இதழ்கள் &rarr;
            </a>
        </div>

        @php
            $hero = $recentArticles->first();
            $rest = $recentArticles->slice(1);
        @endphp

        @if ($hero)
            <x-frontend.article-hero :article="$hero" />
        @endif

        @if ($rest->isNotEmpty())
            <section class="mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2">
                @foreach ($rest as $article)
                    <x-frontend.article-card :article="$article" />
                @endforeach
            </section>
        @endif

        @if ($recentArticles->isEmpty())
            <p class="text-slate">இந்த இதழில் இன்னும் வெளியிடப்பட்ட கட்டுரைகள் இல்லை.</p>
        @endif
    @else
        <p class="text-slate">இன்னும் எந்த இதழும் வெளியிடப்படவில்லை. விரைவில் வருகிறது.</p>
    @endif
</x-frontend.layout>
