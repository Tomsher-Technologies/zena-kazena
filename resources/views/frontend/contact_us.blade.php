@extends('frontend.layouts.app')

@section('content')
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ trans('messages.home') }} </a></li>

                <li class="breadcrumb__item active" aria-current="page">
                    {{ trans('messages.contact') }}</li>
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
    <section class="zk-contactus-section">
        <div class="container container--type-2">
            <div class="zk-header">
                <h1 class="zk-header__title">{{ $page->getTranslation('title', $lang) }}</h1>
            </div>

            <div class="zk-store-location">
                <div class="zk-location-wrapper">
                    <div class="zk-map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13249.679258660537!2d55.2721875!3d25.2769875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f4348dddbdb6f%3A0x4745b5b1df3e5c13!2sDubai%20Mall!5e0!3m2!1sen!2sae!4v1631792012925!5m2!1sen!2sae"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <div class="zk-contact-info">
                        <h3 class="zk-contact-heading">{{ $page->getTranslation('heading2', $lang) }}</h3>
                        <!-- <p class="zk-contact-city">Dubai</p> -->
                        <div class="zk-contact-locations">
                            {!! $page->getTranslation('content', $lang) !!}
                            <br>{{ $page->getTranslation('heading3', $lang) }}
                            <br>{{ $page->getTranslation('heading4', $lang) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="zk-contact-form">
                <div>
                    <img src="{{ asset($page->image) }}" class="img-fluid h-100 cover" alt="">
                </div>

                <div class="zk-contact-form-area">
                    <h2 class="zk-contact-form__title">{{ $page->getTranslation('heading1', $lang) }}</h2>

                    <form action="{{ route('contact.submit') }}" method="post" class="zk-contact-form__form">
                        @csrf
                        <div class="zk-form-group">
                            <label for="firstName" class="zk-form-group__label">{{ trans('messages.first_name') }}</label>
                            <input type="text" id="firstName" name="firstName"  class="zk-form-group__input">
                            @error('firstName')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-form-group">
                            <label for="lastName" class="zk-form-group__label">{{ trans('messages.last_name') }}</label>
                            <input type="text" id="lastName" name="lastName"  class="zk-form-group__input">
                            @error('lastName')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-form-group">
                            <label for="email" class="zk-form-group__label">{{ trans('messages.email') }}</label>
                            <input type="email" id="email" name="email"  class="zk-form-group__input">
                            @error('email')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-form-group">
                            <label for="phone" class="zk-form-group__label">{{ trans('messages.phone') }}</label>
                            <input type="tel" id="phone" name="phone" class="zk-form-group__input">
                            @error('phone')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-form-group zk-form-group--full-width">
                            <label for="subject" class="zk-form-group__label">{{ trans('messages.subject') }}</label>
                            <select id="subject" name="subject"  class="zk-form-group__select">
                                <option value="">{{ trans('messages.select_subject') }}</option>
                                <option value="general_enquiry">{{ trans('messages.general_enquiry') }}</option>
                                <option value="support">{{ trans('messages.support') }}</option>
                                <option value="feedback">{{ trans('messages.feedback') }}</option>
                                <option value="custom_request">{{ trans('messages.custom_request') }}</option>
                            </select>
                            @error('subject')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-form-group zk-form-group--full-width">
                            <label for="message" class="zk-form-group__label">{{ trans('messages.message') }}</label>
                            <textarea id="message" name="message"  class="zk-form-group__textarea"></textarea>
                            @error('message')
                                <span class="red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="zk-submit-btn">
                            <!-- <button class="btn btn-return">Return Items</button> -->
                            <button type="submit" class="zk-submit-btn__button zk-btn">{{ trans('messages.submit') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection
