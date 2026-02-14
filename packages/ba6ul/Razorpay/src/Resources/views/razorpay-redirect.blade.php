<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
</head>
<body>
    <form action="{{ route('razorpay.callback') }}" method="POST" id="razorpay-form">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        
        <button id="rzp-button1" style="display:none;">Pay</button>
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        // recover the data passed from the Controller
        var options = @json($data);

        // Add the "handler" - what happens after payment succeeds
        options.handler = function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            
            // Submit the form back to your "verify" route
            document.getElementById('razorpay-form').submit();
        };

        var rzp1 = new Razorpay(options);
        
        // If payment fails
        rzp1.on('payment.failed', function (response){
            alert("Payment Failed: " + response.error.description);
            window.location.href = "{{ route('shop.checkout.cart.index') }}";
        });

        // Open the popup automatically
        window.onload = function(){
            rzp1.open();
        };
        
        document.getElementById('rzp-button1').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>