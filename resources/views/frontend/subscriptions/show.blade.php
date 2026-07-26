<x-frontend.layout :title="$plan->name_en">
    <div class="mx-auto max-w-lg">
        <h1 class="font-headline text-2xl font-bold text-ink">{{ $plan->name_en }}</h1>
        <p class="mt-1 text-slate">{{ $plan->name_ta }}</p>
        <p class="mt-4 font-headline text-2xl font-bold text-ink">
            {{ number_format($plan->amount / 100, 2) }} {{ strtoupper($plan->currency) }}
            <span class="font-meta text-sm font-normal text-slate">/ {{ $plan->interval === 'year' ? 'ஆண்டு' : 'மாதம்' }}</span>
        </p>
        <p class="mt-1 font-meta text-xs tracking-wider text-slate uppercase">{{ $plan->gateway->getLabel() }} மூலம் பணம் செலுத்தப்படும்</p>

        @guest
            <div class="mt-8 border border-gold/40 bg-gold/5 p-6">
                <p class="text-ink">சந்தா செய்ய முதலில் உள்நுழையவும்.</p>
                <div class="mt-4 flex gap-3">
                    <a href="{{ route('login') }}" class="bg-ambedkar px-4 py-2 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">உள்நுழையவும்</a>
                    <a href="{{ route('register') }}" class="border border-hairline px-4 py-2 font-meta text-xs tracking-wider text-ink uppercase hover:border-ambedkar">கணக்கு தொடங்குக</a>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('subscriptions.checkout', $plan) }}" class="mt-8 space-y-4">
                @csrf

                @if ($plan->tier->value === 'print_digital')
                    <div class="border border-hairline p-5">
                        <p class="font-meta text-xs tracking-wider text-ink uppercase">அச்சு இதழ் அனுப்பும் முகவரி</p>
                        <div class="mt-3 space-y-3">
                            <input type="text" name="shipping_name" placeholder="பெயர்" required value="{{ old('shipping_name') }}"
                                class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                            @error('shipping_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                            <input type="text" name="shipping_line1" placeholder="முகவரி வரி 1" required value="{{ old('shipping_line1') }}"
                                class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                            @error('shipping_line1') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                            <input type="text" name="shipping_line2" placeholder="முகவரி வரி 2 (விருப்பம்)" value="{{ old('shipping_line2') }}"
                                class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">

                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="shipping_city" placeholder="நகரம்" required value="{{ old('shipping_city') }}"
                                    class="border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                                <input type="text" name="shipping_state" placeholder="மாநிலம்" required value="{{ old('shipping_state') }}"
                                    class="border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                                <input type="text" name="shipping_postal_code" placeholder="அஞ்சல் குறியீடு" required value="{{ old('shipping_postal_code') }}"
                                    class="border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                                <input type="text" name="shipping_country" placeholder="நாடு" required value="{{ old('shipping_country') }}"
                                    class="border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                            </div>

                            <input type="text" name="shipping_phone" placeholder="தொலைபேசி எண்" required value="{{ old('shipping_phone') }}"
                                class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none">
                        </div>
                    </div>
                @endif

                <button type="submit" class="w-full bg-gold px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:brightness-90">
                    தொடர்ந்து பணம் செலுத்தவும்
                </button>
            </form>
        @endguest
    </div>
</x-frontend.layout>
