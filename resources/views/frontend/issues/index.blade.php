<x-frontend.layout title="இதழ்கள்">
    <h1 class="text-3xl font-semibold">இதழ்கள்</h1>

    <div class="mt-8 grid gap-6 sm:grid-cols-2">
        @forelse ($issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="block rounded-lg border border-gray-200 p-5 hover:border-gray-400 dark:border-gray-800 dark:hover:border-gray-600">
                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">இதழ் {{ $issue->issue_number }}</p>
                <h2 class="mt-1 text-lg font-semibold">{{ $issue->title }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $issue->publish_date?->translatedFormat('F Y') }}
                </p>
            </a>
        @empty
            <p class="text-gray-500 dark:text-gray-400">இன்னும் எந்த இதழும் வெளியிடப்படவில்லை.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $issues->links() }}
    </div>
</x-frontend.layout>
