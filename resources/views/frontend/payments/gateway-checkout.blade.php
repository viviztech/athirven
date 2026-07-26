<x-frontend.layout title="பணம் செலுத்துதல்">
    <div class="mx-auto max-w-lg text-center">
        <h1 class="font-headline text-2xl font-bold text-ink">Razorpay பணம் செலுத்துதல் திறக்கப்படுகிறது…</h1>
        <p class="mt-3 text-slate">
            இது தானாக தொடங்கவில்லை எனில், கீழே உள்ள பொத்தானை அழுத்தவும்.
        </p>
        <button id="rzp-open" class="mt-6 bg-gold px-4 py-2.5 font-meta text-xs tracking-wider text-white uppercase hover:brightness-90">
            பணம் செலுத்தவும்
        </button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const options = {
            ...@json($payload),
            handler: function () {
                window.location.href = @json($successUrl);
            },
            modal: {
                ondismiss: function () {
                    window.location.href = @json($cancelUrl);
                },
            },
        };

        const rzp = new Razorpay(options);
        document.getElementById('rzp-open').addEventListener('click', () => rzp.open());
        rzp.open();
    </script>
</x-frontend.layout>
