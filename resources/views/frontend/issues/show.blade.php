@php $pdfUrl = $issue->getFirstMediaUrl('issue_pdf'); @endphp
<x-frontend.layout :title="$issue->title">
    <p class="text-sm text-gray-500 uppercase tracking-wider dark:text-gray-400">இதழ் {{ $issue->issue_number }}</p>
    <h1 class="mt-1 text-3xl font-semibold">{{ $issue->title }}</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{ $issue->publish_date?->translatedFormat('F Y') }}
    </p>

    @if ($pdfUrl)
        <a
            href="{{ $pdfUrl }}"
            class="mt-4 inline-flex items-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-medium hover:border-gray-500 dark:border-gray-700 dark:hover:border-gray-500"
        >
            முழு இதழையும் PDF ஆக பதிவிறக்கவும்
        </a>
    @endif

    <section class="mt-10 space-y-8">
        @forelse ($issue->articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-gray-500 dark:text-gray-400">இந்த இதழில் இன்னும் வெளியிடப்பட்ட கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>
</x-frontend.layout>
