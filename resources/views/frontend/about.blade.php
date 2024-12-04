@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ trans('messages.home') }} </a></li>

                <li class="breadcrumb__item active" aria-current="page">{{trans('messages.about_us')}}</li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->



    <!-- start :: about us -->
    <!-- Main About Section -->
    <section class="zk-about-section">
        <div class="container container--type-2">
            <div class="zk-content-container">
                <h4>{{ $page->getTranslation('title', $lang) }}</h4>
                {!! $page->getTranslation('content', $lang) !!}
            </div>

            <div class="zk-content-container">
                <h4>{{ $page->getTranslation('heading1', $lang) }}</h4>
                {!! $page->getTranslation('content1', $lang) !!}
            </div>

            <div class="zk-content-container">
                <h4>{{ $page->getTranslation('heading2', $lang) }}</h4>
                {!! $page->getTranslation('content2', $lang) !!}
            </div>

            <div class="zk-content-container">
                <h4>{{ $page->getTranslation('heading3', $lang) }}</h4>
                {!! $page->getTranslation('content3', $lang) !!}
            </div>

            <div class="zk-content-container">
                <h4>{{ $page->getTranslation('heading4', $lang) }}</h4>
                {!! $page->getTranslation('content4', $lang) !!}
            </div>
        </div>
    </section>
    <!-- end :: about us -->
@endsection
