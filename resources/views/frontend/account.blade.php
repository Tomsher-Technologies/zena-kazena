@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ trans('messages.home') }} </a></li>

                <li class="breadcrumb__item active" aria-current="page">{{trans('messages.profile')}}</li>
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
            <!-- Line 1 px -->
            <!-- End line 1x px -->
            <!-- Row -->
            <div class="row">
                <!-- Title -->
                <div class="col-lg-4">
                    <h3 class="contact-section__title">{{ trans('messages.general_info') }}</h3>
                </div>
                <!-- End title -->
                <!-- Content -->
                <div class="col-lg-8">
                    <!-- Form -->
                    <form class="contact-page__form" action="{{ route('account.update') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">{{ trans('messages.full_name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-group__input" placeholder="{{ trans('messages.full_name') }}" value="{{old('name', $user['name'])}}">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-6">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">{{ trans('messages.email') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="email" disabled class="form-group__input" placeholder="{{ trans('messages.email') }}" value="{{old('email', $user['email'])}}">
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">{{ trans('messages.phone') }}</label>
                                    <input type="text" name="phone" class="form-group__input" placeholder="{{ trans('messages.phone') }}" value="{{old('phone', $user['phone'])}}">
                                    @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>

                            <div class="col-md-6">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="First name">{{ trans('messages.eid') }} {{ trans('messages.number') }}</label>
                                    <input type="text" name="eid_no" class="form-group__input" placeholder="{{ trans('messages.eid') }} {{ trans('messages.number') }}" value="{{old('eid_no', $user['eid_no'])}}">
                                    @error('eid_no')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="idimage">{{ trans('messages.eid') }} {{ trans('messages.front') }} {{ trans('messages.image') }}</label>
                                    <input type="file" class="form-control" id="idimage" name="idimage" accept="image/*">
                                    @error('idimage')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    
                                </div>
                                @if ($user['eid_image_front'] != null)
                                    <div class="file-preview box sm">
                                        <a href="{{ asset($user['eid_image_front']) }}" target="_blank">
                                            <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div class="align-items-center align-self-stretch d-flex thumb">
                                                    <img src="{{ asset($user['eid_image_front']) }}" class="img-fit">
                                                </div>
                                                
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="idimageback">{{ trans('messages.eid') }} {{ trans('messages.back') }} {{ trans('messages.image') }}</label>
                                    <input type="file" class="form-control" id="idimageback" name="idimageback" accept="image/*">
                                    @error('idimageback')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                   
                                </div>
                                @if ($user['eid_image_back'] != null)
                                    <div class="file-preview box sm">
                                        <a href="{{ asset($user['eid_image_back']) }}" target="_blank">
                                            <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div
                                                    class="align-items-center align-self-stretch d-flex thumb">
                                                    <img src="{{ asset($user['eid_image_back']) }}" class="img-fit">
                                                </div>
                                                
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>


                        
                        
                        <!-- End row -->
                        <!-- Action -->
                        <div class="form__action">
                            <button type="submit" class="second-button">{{ trans('messages.update_info') }}</button>
                        </div>
                        <!-- End action -->
                    </form>
                    <!-- End form -->
                </div>
                <!-- End content -->
            </div>
            <!-- End row -->
            <!-- Row -->
            <div class="row">
                <!-- Title -->
                <div class="col-lg-4">
                    <h3 class="contact-section__title">{{ trans('messages.change_password') }}</h3>
                </div>
                <!-- End title -->
                <!-- Content -->
                <div class="col-lg-8">
                    <!-- Form -->
                    <form class="contact-page__form" action="{{ route('account.changePassword') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="current_password">{{ trans('messages.current_password') }}</label>
                                    <input type="password" id="current_password" name="current_password" class="form-group__input"
                                        placeholder="**********">
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
                                    <input type="password" id="new_password" name="new_password" class="form-group__input"
                                        placeholder="**********">
                                    @error('new_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                            <div class="col-md-4">
                                <!-- Form group -->
                                <div class="form-group">
                                    <label for="new_password_confirmation">{{ trans('messages.confirm_new_password') }}</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-group__input" placeholder="**********">
                                    @error('new_password_confirmation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- End form group -->
                            </div>
                        </div>
                   
                        <!-- Action -->
                        <div class="form__action">
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
