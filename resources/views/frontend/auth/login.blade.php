@extends('frontend.layouts.app')
@section('content')
    <!-- Minimal about -->
    <div class="minimal-about">
        <div class="minimal-about__d-flex d-flex">
            <!-- Image -->
            <div class="minimal-about__image">
                <img alt="Image" data-sizes="auto"
                    data-srcset="{{ asset('assets/images/login.webp')}}"
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
                                            <div class="login__box active js-login-in">
                                                <!-- Login heading -->
                                                <h4 class="login__h4">{{trans('messages.login')}}</h4>
                                                <!-- End login heading -->
                                                <!-- Login description -->
                                                <p class="login__description">{{trans('messages.sign_in_to_your_account_below')}}:</p>
                                                <!-- End login description -->
                                                <!-- Form -->
                                                <form action="{{ url('login') }}" method="POST">
                                                    @csrf
                                                    <!-- Form group -->
                                                    <div class="form-group required">
                                                        <input type="email" id="email" class="form-group__input" placeholder="{{trans('messages.email')}}" name="email" value="{{ old('email') }}" required>
                                                            @error('email') <span class="red">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!-- End form group -->
                                                    <!-- Form group -->
                                                    <div class="form-group required">
                                                        <input type="password" placeholder="{{trans('messages.password')}}" class="form-group__input" id="password" name="password" required>
                                                        @error('password') <span class="red">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!-- End form group -->
                                                    <!-- Forgot password -->
                                                    <div class="login__forgot-password">
                                                        <a href="{{route('register')}}" class="">{{trans('messages.new_customer_register_now')}}
                                                        </a>
                                                        <a href="#" class="js-login-forgot-password">{{trans('messages.forgot_password')}}?</a>
                                                    </div>
                                                    <!-- End forgot password -->
                                                    <!-- Action -->
                                                    <div class="login__action"><input class="second-button" type="submit"
                                                            value="{{trans('messages.login')}}"></div>
                                                    <!-- End action -->
                                                </form>
                                                <!-- End form -->
                                            </div>
                                            <!-- End login box -->
                                            <!-- Login box -->
                                            <div class="login__box js-forgot-password">
                                                <!-- Login heading -->
                                                <h4 class="login__h4">{{trans('messages.reset_your_password')}}</h4>
                                                <!-- End login heading -->
                                                <!-- Login description -->
                                                <p class="login__description">{{trans('messages.reset_password_msg')}}</p>
                                                <!-- End login description -->
                                                <!-- Form -->
                                                <form id="forgotPasswordForm">
                                                    <!-- Form group -->
                                                    <div class="form-group required">
                                                        <input type="email" required="required" name="email"
                                                            placeholder="{{trans('messages.email')}}" class="form-group__input">
                                                    </div>
                                                    <!-- End form group -->
                                                    <!-- Action -->
                                                    <div class="login__action"><input class="second-button" type="submit"
                                                            value="{{trans('messages.submit')}}"></div>
                                                    <!-- End action -->
                                                    <!-- Forgot password -->
                                                    <div class="login__back"><a href="#"
                                                            class="js-login-back">{{trans('messages.back')}}</a></div>
                                                    <!-- End forgot password -->
                                                </form>
                                                <div id="forgotPasswordMessage"></div>
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

@section('script')
<script>
    $('#forgotPasswordForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('password.sendResetLink') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#forgotPasswordMessage').html('<p style="color: green;">' + response.success + '</p>');
                $('#forgotPasswordForm')[0].reset();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors && errors.email) {
                    $('#forgotPasswordMessage').html('<p style="color: red;">' + errors.email[0] + '</p>');
                } else if (xhr.responseJSON.error) {
                    $('#forgotPasswordMessage').html('<p style="color: red;">' + xhr.responseJSON.error + '</p>');
                }
            }
        });
    });
</script>
@endsection
