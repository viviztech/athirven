<x-frontend.layout title="சந்தா ரத்து">
    <div class="mx-auto max-w-lg text-center">
        <h1 class="text-2xl font-semibold">பணம் செலுத்துதல் ரத்து செய்யப்பட்டது</h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400">
            எந்த பணமும் கழிக்கப்படவில்லை. நீங்கள் மீண்டும் முயற்சிக்கலாம்.
        </p>
        <a href="{{ route('subscriptions.index') }}" class="mt-6 inline-block rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900">
            சந்தா திட்டங்களுக்குத் திரும்பு
        </a>
    </div>
</x-frontend.layout>
