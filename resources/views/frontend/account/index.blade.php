<x-frontend.layout title="என் கணக்கு">
    <h1 class="text-2xl font-semibold">என் கணக்கு</h1>

    @if (session('status'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif

    <section class="mt-8">
        <h2 class="text-lg font-semibold">சந்தா</h2>

        @if ($subscription)
            <div class="mt-3 rounded-lg border border-gray-200 p-5 dark:border-gray-800">
                <p class="font-medium">{{ $subscription->plan->name_en }}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    நிலை: {{ $subscription->status->getLabel() }}
                    @if ($subscription->current_period_ends_at)
                        &middot; {{ $subscription->current_period_ends_at->translatedFormat('d M Y') }} வரை
                    @endif
                </p>

                @if (in_array($subscription->status->value, ['active', 'past_due']))
                    <form method="POST" action="{{ route('account.subscriptions.cancel', $subscription) }}" class="mt-4"
                          onsubmit="return confirm('சந்தாவை ரத்து செய்ய விரும்புகிறீர்களா?');">
                        @csrf
                        <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950/40">
                            சந்தாவை ரத்து செய்யவும்
                        </button>
                    </form>
                @endif
            </div>
        @else
            <p class="mt-3 text-gray-600 dark:text-gray-400">
                உங்களிடம் சந்தா இல்லை. <a href="{{ route('subscriptions.index') }}" class="underline hover:text-gray-900 dark:hover:text-white">திட்டங்களைப் பார்க்கவும்</a>.
            </p>
        @endif
    </section>

    <section class="mt-10">
        <h2 class="text-lg font-semibold">நன்கொடை வரலாறு</h2>

        @forelse ($donations as $donation)
            <div class="mt-3 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <span>{{ number_format($donation->amount / 100, 2) }} {{ strtoupper($donation->currency) }}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->status->getLabel() }} &middot; {{ $donation->created_at->translatedFormat('d M Y') }}</span>
            </div>
        @empty
            <p class="mt-3 text-gray-600 dark:text-gray-400">இதுவரை நன்கொடைகள் இல்லை.</p>
        @endforelse
    </section>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button type="submit" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium dark:border-gray-700">
            வெளியேறு
        </button>
    </form>
</x-frontend.layout>
