<x-app-layout>
    <h1>Paiement</h1>
    <h2> Tu veux louer ? paie un 5e en vif stp</h2>
    <form id='payment-form'>
        @csrf
        <div id="card-element"></div>
        <button type="submit">Payer</button>
    </form>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('stripe.publishable_key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const result = await stripe.confirmCardPayment('{{ $secret }}', {
                payment_method: {
                    card: cardElement,
                },
            });
            if (result.error) {
                console.error(result.error.message);
            } else if (result.paymentIntent.status === 'succeeded') {
                // Notifie le serveur pour créer la location
                await fetch('{{ route('locations.paiement.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ payment_intent_id: result.paymentIntent.id })
                });
                window.location.href = '{{ route('locations.index') }}';
            }
        });
    </script>
</x-app-layout>
