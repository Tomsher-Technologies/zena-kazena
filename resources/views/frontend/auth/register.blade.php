@extends('frontend.layouts.app')
@section('content')
    <!-- Minimal about -->
    <div class="minimal-about">
        <div class="minimal-about__d-flex d-flex">
            <!-- Image -->
            <div class="minimal-about__image">
                <img alt="Image" data-sizes="auto"
                    data-srcset="assets/images/login.webp 400w, assets/images/login.webp 800w"
                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="lazyload" />
            </div>
            <!-- End image -->
            <!-- Content -->
            <div class="minimal-about__content">
                <!-- Container -->
                <div class="minimal-about__container">
                    <!-- Login -->
                    <div class="login">
                        <!-- Container -->
                        <div class="">
                            <!-- Container -->
                            <div class="">
                                <!-- Login container -->
                                <div class="login__container">
                                    <!-- Login d-flex -->
                                    <div class="login__d-flex">
                                        <!-- Login left -->
                                        <div class="login__left">
                                            <!-- Login box -->
                                            <!-- Login box -->
                                            <div class="login__box active js-new-register">
                                                <!-- Login heading -->
                                                <h4 class="login__h4">{{trans('messages.register')}}</h4>
                                                <!-- End login heading -->
                                                <!-- Login description -->
                                                <p class="login__description">

                                                </p>
                                                <!-- End login description -->
                                                <!-- Form -->
                                                <form action="{{ url('register') }}" method="POST">
                                                    @csrf
                                                    <div class="login__forgot-password">
                                                        <a href="{{ route('login') }}" class="">{{trans('messages.existing_customer_login')}}
                                                        </a>
                                                    </div>
                                                    <!-- Form groups -->
                                                    <div class="form-group">
                                                        <input type="text" id="name"  class="form-group__input" name="name" value="{{ old('name') }}" required  placeholder="{{trans('messages.full_name')}}">
                                                        @error('name') <span class="red">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!-- End form groups -->
                                                    <!-- Form group -->
                                                    <div class="form-group required">
                                                        <input type="email" id="email"   placeholder="{{trans('messages.email')}}" class="form-group__input" name="email" value="{{ old('email') }}" required>
                                                        @error('email') <span class="red">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!-- End form group -->
                                                    <!-- Form group -->
                                                    <div class="form-group required">
                                                        <input type="password" id="password" name="password"  class="form-group__input" placeholder="{{trans('messages.password')}}" required>
                                                        @error('password') <span class="red">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!-- End form group -->
                                                    <div class="form-group required">
                                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{trans('messages.confirm_password')}}" class="form-group__input" required>
                                                    </div>
                                                    <!-- Action -->
                                                    <div class="login__action"><input class="second-button" type="submit"
                                                            value="{{trans('messages.create_account')}}"></div>
                                                    <!-- End action -->
                                                </form>
                                                <!-- End form -->
                                            </div>
                                            <!-- End login box -->
                                        </div>
                                        <!-- End login left -->
                                    </div>
                                    <!-- End login d-flex -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Feature -->
                                            <div class="home-about-us__feature d-flex">
                                                <!-- Icon -->
                                                <div class="feature__icon">
                                                    <i class="lni lni-crown"></i>
                                                </div>
                                                <!-- End icon -->
                                                <!-- Content -->
                                                <div class="feature__content">
                                                    <!-- Title -->
                                                    <h6 class="feature__h6">1 YEAR ZENA & KAZENA BRAND WARRANTY</h6>
                                                    <!-- End title -->
                                                    <!-- Description -->
                                                    <div class="feature__description">Zena & Kazena Promise for
                                                        Exchange and Upgrades.</div>
                                                    <!-- End Description -->
                                                </div>
                                                <!-- End content -->
                                            </div>
                                            <!-- End feature -->
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Feature -->
                                            <div class="home-about-us__feature d-flex">
                                                <!-- Icon -->
                                                <div class="feature__icon">
                                                    <i class="lni lni-spinner-solid"></i>
                                                </div>
                                                <!-- End icon -->
                                                <!-- Content -->
                                                <div class="feature__content">
                                                    <!-- Title -->
                                                    <h6 class="feature__h6">30 DAY RETURN POLICY</h6>
                                                    <!-- End title -->
                                                    <!-- Description -->
                                                    <div class="feature__description">Zena & Kazena Promise for
                                                        Exchange and Upgrades.</div>
                                                    <!-- End Description -->
                                                </div>
                                                <!-- End content -->
                                            </div>
                                            <!-- End feature -->
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Feature -->
                                            <div class="home-about-us__feature d-flex">
                                                <!-- Icon -->
                                                <div class="feature__icon">
                                                    <i class="lnil lnil-ship"></i>
                                                </div>
                                                <!-- End icon -->
                                                <!-- Content -->
                                                <div class="feature__content">
                                                    <!-- Title -->
                                                    <h6 class="feature__h6">FREE SHIPPING</h6>
                                                    <!-- End title -->
                                                    <!-- Description -->
                                                    <div class="feature__description">Durotan free shipping for all
                                                        orders over AED 199
                                                    </div>
                                                    <!-- End Description -->
                                                </div>
                                                <!-- End content -->
                                            </div>
                                            <!-- End feature -->
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Feature -->
                                            <div class="home-about-us__feature d-flex">
                                                <!-- Icon -->
                                                <div class="feature__icon">
                                                    <i class="lnil lnil-money-protection"></i>
                                                </div>
                                                <!-- End icon -->
                                                <!-- Content -->
                                                <div class="feature__content">
                                                    <!-- Title -->
                                                    <h6 class="feature__h6">SECURE PAYMENT</h6>
                                                    <!-- End title -->
                                                    <!-- Description -->
                                                    <div class="feature__description">We guarantee 100% secure with
                                                        online payment
                                                        on our
                                                        site.</div>
                                                    <!-- End Description -->
                                                </div>
                                                <!-- End content -->
                                            </div>
                                            <!-- End feature -->
                                        </div>
                                    </div>
                                </div>
                                <!-- End login container -->
                            </div>
                            <!-- End container -->
                        </div>
                        <!-- End container -->
                    </div>
                    <!-- End login -->
                </div>
                <!-- End container -->
            </div>
            <!-- End content -->
        </div>
    </div>
    <!-- End minimal about -->
@endsection
