<x-frontend.layout title="என் கணக்கு">
    <h1 class="font-headline text-2xl font-bold text-ink">என் கணக்கு</h1>

    @if (session('status'))
        <div class="mt-4 border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif

    <section class="mt-8">
        <h2 class="font-headline text-lg font-bold text-ink">சந்தா</h2>

        @if ($subscription)
            <div class="mt-3 border border-hairline p-5">
                <p class="font-meta text-xs tracking-wider text-ink uppercase">{{ $subscription->plan->name_en }}</p>
                <p class="mt-2 text-sm text-slate">
                    நிலை: {{ $subscription->status->getLabel() }}
                    @if ($subscription->current_period_ends_at)
                        &middot; {{ $subscription->current_period_ends_at->translatedFormat('d M Y') }} வரை
                    @endif
                </p>

                @if (in_array($subscription->status->value, ['active', 'past_due']))
                    <form method="POST" action="{{ route('account.subscriptions.cancel', $subscription) }}" class="mt-4"
                          onsubmit="return confirm('சந்தாவை ரத்து செய்ய விரும்புகிறீர்களா?');">
                        @csrf
                        <button type="submit" class="border border-red-300 px-4 py-2 font-meta text-xs tracking-wider text-red-700 uppercase hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950/40">
                            சந்தாவை ரத்து செய்யவும்
                        </button>
                    </form>
                @endif
            </div>
        @else
            <p class="mt-3 text-slate">
                உங்களிடம் சந்தா இல்லை. <a href="{{ route('subscriptions.index') }}" class="text-ambedkar hover:text-ambedkar-ink">திட்டங்களைப் பார்க்கவும்</a>.
            </p>
        @endif
    </section>

    <section class="mt-10">
        <h2 class="font-headline text-lg font-bold text-ink">நன்கொடை வரலாறு</h2>

        @forelse ($donations as $donation)
            <div class="mt-3 flex items-center justify-between border-b border-hairline pb-3">
                <span class="text-ink">{{ number_format($donation->amount / 100, 2) }} {{ strtoupper($donation->currency) }}</span>
                <span class="font-meta text-xs tracking-wider text-slate uppercase">{{ $donation->status->getLabel() }} &middot; {{ $donation->created_at->translatedFormat('d M Y') }}</span>
            </div>
        @empty
            <p class="mt-3 text-slate">இதுவரை நன்கொடைகள் இல்லை.</p>
        @endforelse
    </section>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button type="submit" class="border border-hairline px-4 py-2 font-meta text-xs tracking-wider text-ink uppercase hover:border-ambedkar">
            வெளியேறு
        </button>
    </form>
</x-frontend.layout>
