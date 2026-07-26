<x-frontend.layout title="இதழ்கள்">
    <h1 class="font-headline text-3xl font-bold text-ink">இதழ்கள்</h1>

    <div class="mt-10 grid gap-x-8 gap-y-10 sm:grid-cols-2">
        @forelse ($issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="group block border-t-2 border-ink pt-4 hover:border-ambedkar">
                <p class="font-meta text-xs tracking-wider text-slate uppercase">இதழ் {{ $issue->issue_number }}</p>
                <h2 class="mt-2 font-headline text-xl font-bold text-ink group-hover:text-ambedkar">{{ $issue->title }}</h2>
                <p class="mt-2 font-meta text-xs tracking-wider text-slate uppercase">
                    {{ $issue->publish_date?->translatedFormat('F Y') }}
                </p>
            </a>
        @empty
            <p class="text-slate">இன்னும் எந்த இதழும் வெளியிடப்படவில்லை.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $issues->links() }}
    </div>
</x-frontend.layout>
