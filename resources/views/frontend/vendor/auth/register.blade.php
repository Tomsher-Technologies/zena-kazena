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
            <div class="minimal-about__content">
                <div class="minimal-about__container">
                    <div class="login">
                        <div class="">
                            <div class="">
                                <div class="login__container">
                                    <div class="login__d-flex">
                                        <div class="login__left">
                                            <div class="login__box active js-new-register">
                                                <h4 class="login__h4">Register</h4>
                                                <p class="login__description">Enter the e-mail address associated
                                                    with your account.
                                                    Click submit to have your password e-mailed to you.
                                                </p>
                                                <form action="{{ url('save-vendor') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    
                                                    <div class="login__forgot-password">
                                                        <a href="{{ route('vendor.login') }}" class="">EXISTING VENDOR?? LOGIN NOW!
                                                        </a>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="name" class="form-group__input" name="name" value="{{ old('name') }}" required placeholder="Full Name">
                                                        @error('name') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group required">
                                                        <input type="email" id="email" placeholder="Email" class="form-group__input" name="email" value="{{ old('email') }}" required>
                                                        @error('email') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="phone" class="form-group__input" name="phone" value="{{ old('phone') }}" required placeholder="Phone Number">
                                                        @error('phone') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="business_name" class="form-group__input" name="business_name" value="{{ old('business_name') }}" required placeholder="Business Name">
                                                        @error('business_name') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="business_type" class="form-group__input" name="business_type" value="{{ old('business_type') }}" required placeholder="Business Type">
                                                        @error('business_type') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group d-flex align-items-center">
                                                        <label for="trade_license" class="form-group__label" style="margin-right: 10px;">Trade License</label>
                                                        <input type="file" id="trade_license" class="form-group__input" name="trade_license" required>
                                                        @error('trade_license') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea id="address" class="form-group__input" name="address" required placeholder="Address">{{ old('address') }}</textarea>
                                                        @error('address') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group d-flex align-items-center">
                                                        <label for="business_logo" class="form-group__label" style="margin-right: 10px;">Logo</label>
                                                        <input type="file" id="business_logo" class="form-group__input" name="business_logo" required>
                                                        @error('business_logo') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group required">
                                                        <input type="password" id="password" name="password" class="form-group__input" placeholder="Password" required>
                                                        @error('password') <span>{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group required">
                                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="form-group__input" required>
                                                    </div>
                                                    <div class="login__action">
                                                        <input class="second-button" type="submit" value="Create an account">
                                                    </div>
                                                </form>
                                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="home-about-us__feature d-flex">
                                                <div class="feature__icon">
                                                    <i class="lni lni-crown"></i>
                                                </div>
                                                <div class="feature__content">
                                                    <h6 class="feature__h6">1 YEAR ZENA & KAZENA BRAND WARRANTY</h6>
                                                    <div class="feature__description">Zena & Kazena Promise for Exchange and Upgrades.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="home-about-us__feature d-flex">
                                                <div class="feature__icon">
                                                    <i class="lni lni-spinner-solid"></i>
                                                </div>
                                                <div class="feature__content">
                                                    <h6 class="feature__h6">30 DAY RETURN POLICY</h6>
                                                    <div class="feature__description">Zena & Kazena Promise for Exchange and Upgrades.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="home-about-us__feature d-flex">
                                                <div class="feature__icon">
                                                    <i class="lnil lnil-ship"></i>
                                                </div>
                                                <div class="feature__content">
                                                    <h6 class="feature__h6">FREE SHIPPING</h6>
                                                    <div class="feature__description">Durotan free shipping for all orders over AED 199
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="home-about-us__feature d-flex">
                                                <div class="feature__icon">
                                                    <i class="lnil lnil-money-protection"></i>
                                                </div>
                                                <div class="feature__content">
                                                    <h6 class="feature__h6">SECURE PAYMENT</h6>
                                                    <div class="feature__description">We guarantee 100% secure with online payment on our site.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
