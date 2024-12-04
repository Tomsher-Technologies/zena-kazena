@extends('frontend.layouts.app')
@section('content')
    <div class="minimal-about">
        <div class="">
            <div class="">
                <!-- Container -->
                <div class="minimal-about__container">
                    <!-- Login -->
                    <div class="login">
                        <!-- Container -->
                        <div class="row text-center">

                            <div class="col-sm-4">
                            </div>
                            <!-- Login box -->
                            <div class="col-sm-4 login__box active js-new-register">
                                <!-- Login heading -->
                                <h4 class="login__h4">{{ trans('messages.reset_password') }}</h4>
                                <!-- End login heading -->
                                <!-- Login description -->
                                <p class="login__description">

                                </p>
                                <!-- End login description -->
                                <!-- Form -->
                                <form action="{{ route('password.reset') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <!-- Form group -->
                                    <div class="form-group required">
                                        <input type="email" id="email" placeholder="{{ trans('messages.email') }}"
                                            class="form-group__input" name="email" readonly value="{{ old('email', $email) }}" required>
                                        @error('email')
                                            <span class="red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End form group -->

                                    <!-- Form group -->
                                    <div class="form-group required">
                                        <input type="password" id="password" name="password" class="form-group__input"
                                            placeholder="{{ trans('messages.new_password') }}" required>
                                        @error('password')
                                            <span class="red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End form group -->
                                    <div class="form-group required">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="{{ trans('messages.confirm_password') }}" class="form-group__input"
                                            required>
                                    </div>
                                    <!-- Action -->
                                    <div class="login__action"><input class="second-button" type="submit"
                                            value="{{ trans('messages.submit') }}"></div>
                                    <!-- End action -->
                                </form>
                               
                                <!-- End form -->
                            </div>

                            <div class="col-sm-4">
                            </div>
                            <!-- End container -->
                        </div>
                        <!-- End container -->
                    </div>
                    <!-- End login -->
                </div>
                <!-- End container -->
            </div>
        </div>
    </div>
@endsection
