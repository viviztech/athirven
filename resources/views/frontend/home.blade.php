<x-frontend.layout description="தலித் அரசியல் மற்றும் பண்பாட்டு மாத இதழ்">
    @if ($latestIssue)
        <section class="mb-10">
            <p class="text-sm text-gray-500 uppercase tracking-wider dark:text-gray-400">சமீபத்திய இதழ்</p>
            <h1 class="mt-1 text-3xl font-semibold">
                <a href="{{ route('issues.show', $latestIssue) }}" class="hover:underline">{{ $latestIssue->title }}</a>
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $latestIssue->publish_date?->translatedFormat('F Y') }}
            </p>
        </section>

        <section class="space-y-8">
            @forelse ($recentArticles as $article)
                <x-frontend.article-card :article="$article" />
            @empty
                <p class="text-gray-500 dark:text-gray-400">இந்த இதழில் இன்னும் வெளியிடப்பட்ட கட்டுரைகள் இல்லை.</p>
            @endforelse
        </section>
    @else
        <p class="text-gray-500 dark:text-gray-400">இன்னும் எந்த இதழும் வெளியிடப்படவில்லை. விரைவில் வருகிறது.</p>
    @endif
</x-frontend.layout>
