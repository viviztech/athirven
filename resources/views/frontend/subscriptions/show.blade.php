<x-frontend.layout :title="$plan->name_en">
    <div class="mx-auto max-w-lg">
        <h1 class="text-2xl font-semibold">{{ $plan->name_en }}</h1>
        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $plan->name_ta }}</p>
        <p class="mt-4 text-2xl font-semibold">
            {{ number_format($plan->amount / 100, 2) }} {{ strtoupper($plan->currency) }}
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">/ {{ $plan->interval === 'year' ? 'ஆண்டு' : 'மாதம்' }}</span>
        </p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $plan->gateway->getLabel() }} மூலம் பணம் செலுத்தப்படும்.</p>

        @guest
            <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-6 dark:border-amber-900 dark:bg-amber-950/40">
                <p class="text-amber-900 dark:text-amber-200">சந்தா செய்ய முதலில் உள்நுழையவும்.</p>
                <div class="mt-4 flex gap-3">
                    <a href="{{ route('login') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900">உள்நுழையவும்</a>
                    <a href="{{ route('register') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium dark:border-gray-700">கணக்கு தொடங்குக</a>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('subscriptions.checkout', $plan) }}" class="mt-8 space-y-4">
                @csrf

                @if ($plan->tier->value === 'print_digital')
                    <div class="rounded-lg border border-gray-200 p-5 dark:border-gray-800">
                        <p class="font-medium">அச்சு இதழ் அனுப்பும் முகவரி</p>
                        <div class="mt-3 space-y-3">
                            <input type="text" name="shipping_name" placeholder="பெயர்" required value="{{ old('shipping_name') }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                            @error('shipping_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                            <input type="text" name="shipping_line1" placeholder="முகவரி வரி 1" required value="{{ old('shipping_line1') }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                            @error('shipping_line1') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                            <input type="text" name="shipping_line2" placeholder="முகவரி வரி 2 (விருப்பம்)" value="{{ old('shipping_line2') }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">

                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="shipping_city" placeholder="நகரம்" required value="{{ old('shipping_city') }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                                <input type="text" name="shipping_state" placeholder="மாநிலம்" required value="{{ old('shipping_state') }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                                <input type="text" name="shipping_postal_code" placeholder="அஞ்சல் குறியீடு" required value="{{ old('shipping_postal_code') }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                                <input type="text" name="shipping_country" placeholder="நாடு" required value="{{ old('shipping_country') }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                            </div>

                            <input type="text" name="shipping_phone" placeholder="தொலைபேசி எண்" required value="{{ old('shipping_phone') }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                        </div>
                    </div>
                @endif

                <button type="submit" class="w-full rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
                    தொடர்ந்து பணம் செலுத்தவும்
                </button>
            </form>
        @endguest
    </div>
</x-frontend.layout>
