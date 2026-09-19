@extends('layouts.shop')

@section('content')
    <div class="card-lg panel-narrow text-center">
        <h1 class="text-2xl font-semibold text-slate-900">Payment Successful</h1>
        <p class="mt-2 text-sm text-slate-500">
            Order #{{ $order->id }} — ${{ number_format($order->total_amount, 2) }}
        </p>

        <table class="w-full text-left text-sm mt-6">
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $item->product_name }} &times; {{ $item->quantity }}</td>
                        <td class="py-2 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('home') }}" class="btn-muted block-link mt-6">Continue shopping &rarr;</a>
    </div>
@endsection
