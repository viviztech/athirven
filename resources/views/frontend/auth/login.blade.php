<x-frontend.layout title="உள்நுழையவும்">
    <div class="mx-auto max-w-sm">
        <h1 class="text-2xl font-semibold">உள்நுழையவும்</h1>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium">மின்னஞ்சல்</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">கடவுச்சொல்</label>
                <input type="password" name="password" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <input type="checkbox" name="remember">
                என்னை நினைவில் வைத்திரு
            </label>
            <button type="submit" class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900">
                உள்நுழையவும்
            </button>
        </form>

        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            கணக்கு இல்லையா? <a href="{{ route('register') }}" class="underline hover:text-gray-900 dark:hover:text-white">தொடங்குக</a>
        </p>
    </div>
</x-frontend.layout>
