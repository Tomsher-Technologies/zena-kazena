@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">Home </a></li>

                <li class="breadcrumb__item active" aria-current="page">
                    Profile</li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->
    <!-- Section -->
    <div class="contact-page__section">
        <!-- Container -->
        <div class="container">
            <form class="contact-page__form" method="post" action="{{ route('vendor.update.info') }}",
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Title -->
                    <div class="col-lg-3">
                        <h3 class="contact-section__title">General Info</h3>
                    </div>
                    <!-- End title -->
                    <!-- Content -->
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Business Name</label>
                                    <input type="text" name="business_name" class="form-group__input"
                                        placeholder="Muhsil" value="{{ $vendor->business_name }}">
                                </div>
                                <!-- End form group -->
                            </div>

                            <div class="col-md-4">

                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Business Type</label>
                                    <input type="text" name="business_type" class="form-group__input"
                                        value="{{ $vendor->business_type }}">
                                </div>
                                <!-- End form group -->

                            </div>
                            <div class="col-md-4">

                                <!-- Form group -->
                                <div class="form-group">
                                    <div class="row align-items-center">
                                        <!-- Column for the Logo -->
                                        <div class="col-2 text-center">
                                            <img src="{{ asset('storage/' . $vendor->logo) }}"
                                                class="img-fluid img-fit avatar" style="max-width: 50px; height: auto;"
                                                alt="Business Logo">
                                        </div>
                                        <!-- Column for the Label and Input -->
                                        <div class="col-10">
                                            <label for="business_type" class="form-label">Business Logo</label>
                                            <input type="file"name="logo" id="logo" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End form -->
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Email</label>
                                    <input type="text" readonly name="email" class="form-group__input"
                                        value="{{ $vendor->email }}">
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Name</label>
                                    <input type="text" name="name" class="form-group__input"
                                        value="{{ $vendor->name }}">
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Phone number</label>
                                    <input type="text" name="phone" class="form-group__input"
                                        value={{ $vendor->phone }}>
                                </div>
                                <!-- End form group -->
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Name</label>
                                    <input type="text" name="name" class="form-group__input"
                                        value="{{ $vendor->name }}">
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">Address</label>
                                    <input type="address" name="address" class="form-group__input"
                                        value="{{ $vendor->address }}">
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="profit_share">Profit Share (%)</label>
                                    <input type="number" id="profit_share"name="profit_share" class="form-group__input"
                                        value="{{ $vendor->profit_share }}" placeholder="{{__('messages.profit_share_with_admin')}}" readonly>
                                </div>
                                <!-- End form group -->
                            </div>
                        </div>
                        <!-- End row -->
                        </div>
                        <!-- End content -->
                    </div>
                    <!-- End row -->
                    <!-- Row -->
                    <div class="row">
                        <!-- Title -->
                        <div class="col-lg-3">
                            {{-- <h3 class="contact-section__title">Security</h3> --}}
                        </div>
                        <div class="col-lg-9">
                            <div class="form__action align-item-center">
                                <button type="submit" class="second-button mb-2">Update Info</button>
                            </div>
                        </div>
                    </div>
            </form>
            <div class="row">
                <!-- Title -->
                <div class="col-lg-3">
                    <h3 class="contact-section__title">{{ trans('messages.change_password') }}</h3>
                </div>
                <!-- End title -->
                <!-- Content -->
                <div class="col-lg-9">
                    <!-- Form -->
                    <form class="contact-page__form" action="{{ route('vendor.account.changePassword') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="current_password">{{ trans('messages.current_password') }}</label>
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-group__input" placeholder="**********">
                                    @error('current_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="new_password">{{ trans('messages.new_password') }}</label>
                                    <input type="password" id="new_password" name="new_password"
                                        class="form-group__input" placeholder="**********">
                                    @error('new_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label
                                        for="new_password_confirmation">{{ trans('messages.confirm_new_password') }}</label>
                                    <input type="password" id="new_password_confirmation"
                                        name="new_password_confirmation" class="form-group__input"
                                        placeholder="**********">
                                    @error('new_password_confirmation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="form__action mb-3">
                            <button type="submit" class="second-button">{{ trans('messages.change_password') }}</button>
                        </div>
                        <!-- End action -->
                    </form>
                    <!-- End form -->
                </div>
                <!-- End content -->
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </div>
    <!-- End section -->
@endsection
