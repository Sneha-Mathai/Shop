@extends('layouts.shop')

@section('content')
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 mb-6">Checkout</h1>

    <div class="card-lg panel-medium">
        <table class="w-full text-left text-sm mb-6">
            <tbody>
                @foreach ($cart as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $item['name'] }} &times; {{ $item['quantity'] }}</td>
                        <td class="py-2 text-right">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mb-6">
            <span class="text-lg font-semibold text-slate-900">Total</span>
            <span class="text-xl font-semibold text-slate-900">${{ number_format($total, 2) }}</span>
        </div>

        <button id="pay-button" class="btn-primary">Pay Now</button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            const options = {
                key: "{{ $razorpayKey }}",
                amount: {{ (int) round($total * 100) }},
                currency: "INR",
                name: "{{ config('app.name') }}",
                description: "Order payment",
                order_id: "{{ $razorpayOrderId }}",
                handler: function (response) {
                    // Razorpay's JS checkout hands back the payment id/order id/
                    // signature here — POST them to the server to verify and
                    // create the actual Order record.
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('checkout.confirm') }}";

                    const fields = {
                        _token: "{{ csrf_token() }}",
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature,
                    };

                    for (const key in fields) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = fields[key];
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                },
                prefill: {
                    name: "{{ auth()->user()->name }}",
                    email: "{{ auth()->user()->email }}",
                },
                theme: {
                    color: "#0d9488"
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        });
    </script>
@endsection
