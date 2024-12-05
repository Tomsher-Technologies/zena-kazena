@extends('frontend.layouts.app')
@section('content')
<div class="container container--type-1 text-center p-5">
    <img alt="Image" src="{{asset('assets/images/failed.png')}}" style="width:10%;" />
    <h1>{{trans('messages.order_failed') }}</h1>
    <p>{{trans('messages.order_failed_msg')}}</p>
    
    <a href="{{ route('home') }}" class="second-button">{{trans('messages.return_to_home')}}</a>
</div>

@endsection