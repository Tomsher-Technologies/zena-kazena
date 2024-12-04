@extends('frontend.layouts.app')
@section('content')
<div class="container container--type-2  p-5">
    <img alt="Image"  style="width:10%;" src="{{asset('assets/images/success.png')}}"  />
    <h1>{{trans('messages.order_successful') }}</h1>
    <p class="order-summary__title"> {{trans('messages.order_placed_success') }}</p>
    
    {{-- <h1>{{ trans('messages.order').' '.trans('messages.details') }}:</h1> --}}
    <ul>
        <li>{{ trans('messages.order_code') }}: {{ $order->code }}</li>
        <li>{{ trans('messages.status') }}: {{ $order->delivery_status }}</li>
        <li>{{ trans('messages.total') }}: {{ env('DEFAULT_CURRENCY') }} {{ $order->grand_total }}</li>
        <li>{{ trans('messages.date') }}: {{ $order->created_at->format('d M Y, H:i') }}</li>
    </ul>
    
    <p>{{ trans('messages.thankyou_purchase') }}</p>
</div>

@endsection