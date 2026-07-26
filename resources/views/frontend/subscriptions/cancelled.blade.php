<x-frontend.layout title="சந்தா ரத்து">
    <div class="mx-auto max-w-lg text-center">
        <h1 class="font-headline text-2xl font-bold text-ink">பணம் செலுத்துதல் ரத்து செய்யப்பட்டது</h1>
        <p class="mt-3 text-slate">
            எந்த பணமும் கழிக்கப்படவில்லை. நீங்கள் மீண்டும் முயற்சிக்கலாம்.
        </p>
        <a href="{{ route('subscriptions.index') }}" class="mt-6 inline-block bg-ambedkar px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
            சந்தா திட்டங்களுக்குத் திரும்பு
        </a>
    </div>
</x-frontend.layout>
