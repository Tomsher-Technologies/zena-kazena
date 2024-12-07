@extends('frontend.layouts.app')
@section('content')
    <!-- About page -->
    <div class="about-page">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Title -->
            <h1 class="about-page__title">{{ $page->getTranslation('title', $lang) }}</h1>
            <!-- End title -->
            <!-- Description -->
            <div class="about-page__description">
                {!! $page->getTranslation('content', $lang) !!}
            </div>
            <!-- End description -->
            <div class="mb-5 text-center">
                <a href="#" class="eighth-button" data-toggle="modal" data-target="#salesModal">{{trans('messages.start')}} {{trans('messages.sales')}} </a>
            </div>

        </div>
        <!-- End container -->
        <!-- About company -->
        <div class="about-company">
            <!-- Container -->
            <div class="container container--type-2">
                <!-- Row -->
                <div class="row">
                    <!-- Company -->
                    <div class="col-lg-6 about-company__item">
                        <!-- Image -->
                        <div class="about-company__image">
                            <img alt="Image" data-sizes="auto"
                                data-srcset="{{ asset($page->getTranslation('image1', $lang)) }}"
                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                class="lazyload" />
                        </div>
                        <!-- End image -->
                        <!-- Title -->
                        <h2 class="about-company__title"> {!! $page->getTranslation('heading1', $lang) !!}</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                        
                            {!! $page->getTranslation('content1', $lang) !!}
                        </div>
                        <!-- End description -->

                        {{-- <div class="mt-5 text-left">
                            <a href="#" class="eighth-button " data-toggle="modal" data-target="#salesModal">Start sales</a>
                        </div> --}}
                    </div>
                    <!-- End company -->
                    <!-- Leader -->
                    <div class="col-lg-6 about-company__item">
                        <!-- Image -->
                        <div class="about-company__image">
                            <img alt="Image" data-sizes="auto"
                                data-srcset="{{ asset($page->getTranslation('image2', $lang)) }}"
                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                class="lazyload" />
                        </div>
                        <!-- End image -->
                        <!-- Title -->
                        <h2 class="about-company__title"> {!! $page->getTranslation('heading2', $lang) !!}</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                            {!! $page->getTranslation('content2', $lang) !!}
                        </div>
                        <!-- End description -->


                        <div class="mt-5 text-left">
                            <a href="#" class="eighth-button " data-toggle="modal" data-target="#salesModal">{{trans('messages.start')}} {{trans('messages.sales')}}</a>
                        </div>
                    </div>
                    <!-- End leader -->
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
        </div>
        <!-- End about company -->
    </div>

    <div id="salesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="salesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesModalLabel">{{ trans('messages.sales') }} {{ trans('messages.information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="salesForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group  m-2">
                            <label for="name">{{ trans('messages.name') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="email">{{ trans('messages.email') }}<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="phone">{{ trans('messages.phone') }}<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="description">{{ trans('messages.description') }}<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-group m-2">
                            <label for="metal">{{ trans('messages.metal') }}<span class="text-danger">*</span></label>
                            <select class="form-control" id="metal" name="metal" required="">
                                <option value="gold">{{ trans('messages.gold') }}</option>
                                <option value="silver">{{ trans('messages.silver') }}</option>
                                <option value="rose_gold">{{ trans('messages.rose_gold') }}</option>
                                <option value="white_gold">{{ trans('messages.white_gold') }}</option>
                            </select>
                        </div>

                        <div class="form-group m-2">
                            <label for="metal_type">{{ trans('messages.metal_type') }}<span class="text-danger">*</span></label>
                            <select class="form-control" id="metal_type" name="metal_type" required="">
                                <option value="18k">18k</option>
                                <option value="22k">22k</option>
                                <option value="24k">24k</option>
                            </select>
                        </div>

                        <div class="form-group m-2">
                            <label for="weight">{{ trans('messages.metal') }} {{ trans('messages.weight') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="weight" name="weight" required>
                        </div>

                        <div class="form-group m-2">
                            <label for="eidno">{{ trans('messages.eid') }} {{ trans('messages.number') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eidno" name="eidno" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="image">{{ trans('messages.upload') }} {{ trans('messages.eid') }} {{ trans('messages.front') }} {{ trans('messages.image') }}<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="idimage" name="idimage" accept="image/*">
                        </div>
                        <div class="form-group m-2">
                            <label for="image">{{ trans('messages.upload') }} {{ trans('messages.eid') }} {{ trans('messages.back') }} {{ trans('messages.image') }}<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="idimageback" name="idimageback" accept="image/*">
                        </div>
                        <div class="form-group m-2">
                            <label for="image">{{ trans('messages.upload') }} {{ trans('messages.product') }} {{ trans('messages.image') }}<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ trans('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    
    <script type="text/javascript">
        $(document).ready(function () {
            $("#salesForm").on("submit", function (e) {
                e.preventDefault(); // Prevent the page from reloading
            });

            $("#salesForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    idimage: {
                        required: true,
                    },
                    image: {
                        required: true,
                    },
                    idimageback: {
                        required: true,
                    },
                    duration: {
                        required: true,
                    },
                    metal: {
                        required: true,
                    },
                    metal_type: {
                        required: true,
                    },
                    weight: {
                        required: true,
                    },
                    eidno: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.name_min_msg')}}"
                    },
                    email: {
                        required: "{{ trans('messages.required_field') }}",
                        email: "Please enter a valid email address"
                    },
                    phone: {
                        required: "{{ trans('messages.required_field') }}",
                        digits: "{{trans('messages.digits_allowed')}}",
                        minlength: "{{trans('messages.phn_min_msg')}}",
                        maxlength: "{{trans('messages.phn_max_msg')}}"
                    },
                    description: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.description_msg')}}"
                    },
                    image: {
                        required: "{{ trans('messages.required_field') }}",
                        accept: "{{ trans('messages.image_files_allowed') }}",
                    },
                    idimage: {
                        required: "{{ trans('messages.required_field') }}",
                        accept: "{{ trans('messages.image_files_allowed') }}",
                    },
                    idimageback: {
                        required: "{{ trans('messages.required_field') }}",
                        accept: "{{ trans('messages.image_files_allowed') }}",
                    },
                    image: {
                        required: "{{ trans('messages.required_field') }}",
                        accept: "{{ trans('messages.image_files_allowed') }}",
                    },
                    duration: {
                        required: "{{ trans('messages.required_field') }}",
                    },
                    metal: {
                        required: "{{ trans('messages.required_field') }}",
                    },
                    metal_type: {
                        required: "{{ trans('messages.required_field') }}",
                    },
                    weight: {
                        required: "{{ trans('messages.required_field') }}",
                    },
                    eidno: {
                        required: "{{ trans('messages.required_field') }}",
                    }
                },
                submitHandler: function (form) {
                    event.preventDefault();
                    // Perform an AJAX request or submit the form
                    $.ajax({
                        url: "{{ route('submit-sales') }}", // Replace with your route
                        type: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $('#salesForm')[0].reset(); // Reset the form
                                $('#salesModal').modal('hide'); // Hide the modal
                            } else {
                                toastr.error(response.message, "{{trans('messages.error')}}");
                            }
                        },
                        error: function (xhr) {
                            toastr.error("{{trans('messages.something_went_wrong')}}", "{{trans('messages.error')}}");
                        }
                    });
                }
            });

            // Reset validation on modal close
            $('#salesModal').on('hidden.bs.modal', function () {
                $("#salesForm")[0].reset(); // Clear the form
                $("#salesForm").validate().resetForm(); // Reset validation
            });
        });


    </script>
@endsection
