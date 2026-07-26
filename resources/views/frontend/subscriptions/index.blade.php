<x-frontend.layout title="சந்தா" description="அதிர்வெண் சந்தா திட்டங்கள்">
    <h1 class="font-headline text-3xl font-bold text-ink">சந்தா திட்டங்கள்</h1>
    <p class="mt-2 text-slate">
        சந்தாதாரர்களுக்கான கட்டுரைகளை முழுமையாகப் படிக்கவும், அதிர்வெணை ஆதரிக்கவும்.
    </p>

    @if (session('error'))
        <div class="mt-6 border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-10 space-y-10">
        @foreach ($plans as $tierPlans)
            <section>
                <h2 class="font-headline text-xl font-bold text-ink">{{ $tierPlans->first()->tier->getLabel() }}</h2>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($tierPlans as $plan)
                        <div class="border border-hairline p-6">
                            <p class="font-meta text-xs tracking-wider text-slate uppercase">{{ $plan->gateway->getLabel() }}</p>
                            <p class="mt-2 font-headline text-2xl font-bold text-ink">
                                {{ number_format($plan->amount / 100, 2) }} {{ strtoupper($plan->currency) }}
                                <span class="font-meta text-sm font-normal text-slate">/ {{ $plan->interval === 'year' ? 'ஆண்டு' : 'மாதம்' }}</span>
                            </p>
                            <a href="{{ route('subscriptions.show', $plan) }}"
                               class="mt-4 inline-block bg-gold px-4 py-2 font-meta text-xs tracking-wider text-white uppercase hover:brightness-90">
                                சந்தா செய்யுங்கள்
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <p class="mt-10 text-sm text-slate">
        ஒரு முறை நன்கொடை அளிக்க விரும்புகிறீர்களா? <a href="{{ route('donations.index') }}" class="text-ambedkar hover:text-ambedkar-ink">நன்கொடை பக்கத்திற்குச் செல்லவும்</a>.
    </p>
</x-frontend.layout>
