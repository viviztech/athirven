<x-frontend.layout title="சந்தா" description="அதிர்வெண் சந்தா திட்டங்கள்">
    <h1 class="text-3xl font-semibold">சந்தா திட்டங்கள்</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">
        சந்தாதாரர்களுக்கான கட்டுரைகளை முழுமையாகப் படிக்கவும், அதிர்வெணை ஆதரிக்கவும்.
    </p>

    @if (session('error'))
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-10 space-y-10">
        @foreach ($plans as $tierPlans)
            <section>
                <h2 class="text-xl font-semibold">{{ $tierPlans->first()->tier->getLabel() }}</h2>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($tierPlans as $plan)
                        <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-800">
                            <p class="text-sm uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $plan->gateway->getLabel() }}</p>
                            <p class="mt-1 text-2xl font-semibold">
                                {{ number_format($plan->amount / 100, 2) }} {{ strtoupper($plan->currency) }}
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">/ {{ $plan->interval === 'year' ? 'ஆண்டு' : 'மாதம்' }}</span>
                            </p>
                            <a href="{{ route('subscriptions.show', $plan) }}"
                               class="mt-4 inline-block rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
                                சந்தா செய்யுங்கள்
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <p class="mt-10 text-sm text-gray-500 dark:text-gray-400">
        ஒரு முறை நன்கொடை அளிக்க விரும்புகிறீர்களா? <a href="{{ route('donations.index') }}" class="underline hover:text-gray-900 dark:hover:text-white">நன்கொடை பக்கத்திற்குச் செல்லவும்</a>.
    </p>
</x-frontend.layout>
