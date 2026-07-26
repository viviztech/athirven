@php $pdfUrl = $issue->getFirstMediaUrl('issue_pdf'); @endphp
<x-frontend.layout :title="$issue->title">
    <p class="font-meta text-xs tracking-wider text-ambedkar uppercase">இதழ் {{ $issue->issue_number }}</p>
    <h1 class="mt-2 font-headline text-3xl font-bold text-ink sm:text-4xl">{{ $issue->title }}</h1>
    <p class="mt-2 font-meta text-xs tracking-wider text-slate uppercase">
        {{ $issue->publish_date?->translatedFormat('F Y') }}
    </p>

    @if ($pdfUrl)
        <a
            href="{{ $pdfUrl }}"
            class="mt-5 inline-flex items-center gap-2 border border-hairline px-4 py-2 font-meta text-xs tracking-wider text-ink uppercase hover:border-ambedkar hover:text-ambedkar"
        >
            முழு இதழையும் PDF ஆக பதிவிறக்கவும்
        </a>
    @endif

    <x-frontend.waveform class="waveform-divider mt-10" />

    <section class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2">
        @forelse ($issue->articles as $article)
            <x-frontend.article-card :article="$article" />
        @empty
            <p class="text-slate">இந்த இதழில் இன்னும் வெளியிடப்பட்ட கட்டுரைகள் இல்லை.</p>
        @endforelse
    </section>
</x-frontend.layout>
