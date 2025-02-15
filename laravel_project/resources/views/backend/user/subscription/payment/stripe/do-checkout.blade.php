<html>
<body>
<span>{{ __('stripe.pay-redirect-message') }}</span>

<script src="https://js.stripe.com/v3/"></script>
<script>

    var stripe = Stripe("{{ $stripe_published_key }}");

    stripe.redirectToCheckout({
        // Make the id field from the Checkout Session creation API response
        // available to this file, so you can provide it as argument here
        // instead of the CHECKOUT_SESSION_ID placeholder.
        sessionId: "{{ $stripe_session_id }}"
    }).then(function (result) {
        // If `redirectToCheckout` fails due to a browser or network
        // error, display the localized error message to your customer
        // using `result.error.message`.

        // alert error message
        window.alert(result.error.message);

        // redirect to the cancel checkout route to cancel the invoice
        window.location.href = "{{ route('user.stripe.checkout.cancel') }}";
    });
</script>
</body>
</html>
