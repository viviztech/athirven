<x-frontend.layout title="உள்நுழையவும்">
    <div class="mx-auto max-w-sm">
        <h1 class="font-headline text-2xl font-bold text-ink">உள்நுழையவும்</h1>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">மின்னஞ்சல்</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">கடவுச்சொல்</label>
                <input type="password" name="password" required
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate">
                <input type="checkbox" name="remember">
                என்னை நினைவில் வைத்திரு
            </label>
            <button type="submit" class="w-full bg-ambedkar px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
                உள்நுழையவும்
            </button>
        </form>

        <p class="mt-6 text-sm text-slate">
            கணக்கு இல்லையா? <a href="{{ route('register') }}" class="text-ambedkar hover:text-ambedkar-ink">தொடங்குக</a>
        </p>
    </div>
</x-frontend.layout>
