<x-frontend.layout title="கணக்கு தொடங்குக">
    <div class="mx-auto max-w-sm">
        <h1 class="font-headline text-2xl font-bold text-ink">கணக்கு தொடங்குக</h1>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">பெயர்</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">மின்னஞ்சல்</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">கடவுச்சொல்</label>
                <input type="password" name="password" required
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="font-meta text-xs tracking-wider text-slate uppercase">கடவுச்சொல் உறுதிப்படுத்தல்</label>
                <input type="password" name="password_confirmation" required
                    class="mt-1 w-full border border-hairline bg-paper-raised px-3 py-2 text-ink focus:border-ambedkar focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-ambedkar px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
                கணக்கு தொடங்குக
            </button>
        </form>

        <p class="mt-6 text-sm text-slate">
            ஏற்கனவே கணக்கு உள்ளதா? <a href="{{ route('login') }}" class="text-ambedkar hover:text-ambedkar-ink">உள்நுழையவும்</a>
        </p>
    </div>
</x-frontend.layout>
