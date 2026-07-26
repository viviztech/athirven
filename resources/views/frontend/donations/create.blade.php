<x-frontend.layout title="நன்கொடை" description="அதிர்வெணை ஆதரிக்கவும்">
    <div class="mx-auto max-w-lg">
        <h1 class="font-headline text-2xl font-bold text-ink">நன்கொடை அளிக்கவும்</h1>
        <p class="mt-2 text-slate">
            உங்கள் ஆதரவு அதிர்வெண் தொடர்ந்து சுதந்திரமாக இயங்க உதவுகிறது.
        </p>

        @if (session('error'))
            <div class="mt-6 border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('donations.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">தொகை</label>
                <input type="number" name="amount" min="1" step="0.01" required value="{{ old('amount') }}"
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
                @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">நாணயம் / பணம் செலுத்தும் முறை</label>
                <div class="mt-2 grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 border border-hairline px-3 py-2 has-checked:border-ambedkar">
                        <input type="radio" name="gateway_currency" value="stripe:usd" data-gateway="stripe" data-currency="usd" checked
                            onchange="document.getElementById('gateway').value='stripe'; document.getElementById('currency').value='usd'; document.getElementById('recurring-wrap').classList.remove('hidden');">
                        Stripe (USD)
                    </label>
                    <label class="flex items-center gap-2 border border-hairline px-3 py-2 has-checked:border-ambedkar">
                        <input type="radio" name="gateway_currency" value="razorpay:inr" data-gateway="razorpay" data-currency="inr"
                            onchange="document.getElementById('gateway').value='razorpay'; document.getElementById('currency').value='inr'; document.getElementById('recurring-wrap').classList.add('hidden'); document.getElementById('recurring').checked=false;">
                        Razorpay (INR)
                    </label>
                </div>
                <input type="hidden" id="gateway" name="gateway" value="stripe">
                <input type="hidden" id="currency" name="currency" value="usd">
            </div>

            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">பெயர் (விருப்பம்)</label>
                <input type="text" name="donor_name" value="{{ old('donor_name') }}"
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
            </div>

            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">மின்னஞ்சல் (விருப்பம்)</label>
                <input type="email" name="donor_email" value="{{ old('donor_email') }}"
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
            </div>

            <label class="flex items-center gap-2 text-sm text-ink">
                <input type="checkbox" name="is_anonymous" value="1">
                பொதுவில் என் பெயரைக் காட்ட வேண்டாம்
            </label>

            <label id="recurring-wrap" class="flex items-center gap-2 text-sm text-ink">
                <input type="checkbox" id="recurring" name="is_recurring" value="1">
                மாதாந்திர நன்கொடையாக மாற்று (Stripe மட்டும்)
            </label>
            @error('is_recurring') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <button type="submit" class="w-full bg-gold px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:brightness-90">
                நன்கொடை அளிக்கவும்
            </button>
        </form>
    </div>
</x-frontend.layout>
