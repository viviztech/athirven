<x-frontend.layout title="பணம் செலுத்துதல்">
    <div class="mx-auto max-w-lg text-center">
        <h1 class="text-2xl font-semibold">Razorpay பணம் செலுத்துதல் திறக்கப்படுகிறது…</h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400">
            இது தானாக தொடங்கவில்லை எனில், கீழே உள்ள பொத்தானை அழுத்தவும்.
        </p>
        <button id="rzp-open" class="mt-6 rounded-md bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
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
