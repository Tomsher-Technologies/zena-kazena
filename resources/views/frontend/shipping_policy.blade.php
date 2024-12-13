@extends('frontend.layouts.app')
@section('content')
    <!-- About page -->
    <div class="about-page">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Title -->
            <h1 class="about-page__title">{{ $page->getTranslation('title', $lang) }}</h1>
            {!! $page->getTranslation('content', $lang) !!}
            <!-- End title -->
        </div>
        <!-- End container -->

    </div>
    <!-- End about company -->
@endsection
