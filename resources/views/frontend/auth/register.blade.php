<x-frontend.layout title="கணக்கு தொடங்குக">
    <div class="mx-auto max-w-sm">
        <h1 class="text-2xl font-semibold">கணக்கு தொடங்குக</h1>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium">பெயர்</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">மின்னஞ்சல்</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">கடவுச்சொல்</label>
                <input type="password" name="password" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">கடவுச்சொல் உறுதிப்படுத்தல்</label>
                <input type="password" name="password_confirmation" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
            </div>
            <button type="submit" class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900">
                கணக்கு தொடங்குக
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            ஏற்கனவே கணக்கு உள்ளதா? <a href="{{ route('login') }}" class="underline hover:text-gray-900 dark:hover:text-white">உள்நுழையவும்</a>
        </p>
    </div>
</x-frontend.layout>
