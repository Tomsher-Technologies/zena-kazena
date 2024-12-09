@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ trans('messages.home') }} </a></li>
                <li class="breadcrumb__item active" aria-current="page">
                    {{trans('messages.address')}}</li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->



    <!-- Shopping cart -->
    <div class="shopping-cart pt-2">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Second container -->
            <div class="">
                <!-- Title -->

                <a class="sixth-button mt-3" href="#"  data-toggle="modal" data-target="#addressModal"><i class="lnil lnil-circle-plus"></i> {{ trans('messages.add_new') }} {{ trans('messages.address') }}  </a>

                <div class="address">
                    <!-- Customer information -->
                    <div class="order-complete__customer-information ">
                        <div class="row">

                            @if (!empty($addresses[0]))
                                @foreach ($addresses as $key => $address)
                                    <div class="col-12 col-md-6" id="address-{{ $address->id }}">
                                        <div class="customer-information__section @if($address->set_default == 1) default-address @endif">
                                            <h4 class="order-information__section-heading">{{ trans('messages.address') }} {{$key+1}}
                                            </h4>
                                            <div class="order-information__section-content">
                                                {{ $address->name }}<br>
                                                {!! nl2br($address->address) !!}<br>
                                                {{ $address->city }}<br>
                                                {{trans('messages.zipcode')}} : {{ $address->postal_code }}<br>
                                                {{trans('messages.phone')}} : {{ $address->phone }}<br>
                                            </div>
                                            <a class="sixth-button mt-3 edit-address-btn" 
                                                data-address-id="{{ $address->id }}" data-name="{{ $address->name }}" 
                                                data-address="{{ $address->address }}" data-city="{{ $address->city }}" data-zipcode="{{ $address->postal_code }}" data-phone="{{ $address->phone }}"  href="#"><i class="lnil lnil-pencil"></i> {{ trans('messages.edit') }}</a>
                                            <a class="sixth-button mt-3 delete-address-btn" data-address-id="{{ $address->id }}" href="#"><i class="lnil lnil-trash-can-alt"></i>
                                                {{ trans('messages.delete') }}</a>
                                            @if($address->set_default == 0)
                                                <a class="sixth-button mt-3 set-default-btn" data-address-id="{{ $address->id }}"  href="#"><i class="lnil lnil-save"></i> {{ trans('messages.set_as') }} {{ trans('messages.default') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- End row -->
                    </div>
                    <!-- End customer information -->
                </div>


                <div id="addressModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addressModalLabel">{{ trans('messages.address') }} {{ trans('messages.information') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addressForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group  m-2">
                                        <label for="name">{{ trans('messages.name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="description">{{ trans('messages.address') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="city">{{ trans('messages.town_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="zipcode">{{ trans('messages.zip') }}</label>
                                        <input type="text" class="form-control" id="zipcode" name="zipcode" >
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="phone">{{ trans('messages.phone') }}<span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
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

                <div id="addressEditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addressEditModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addressEditModalLabel">{{ trans('messages.address') }} {{ trans('messages.information') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addressEditForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group  m-2">
                                        <label for="edit_name">{{ trans('messages.name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                                        <input type="hidden" id="address_id" name="address_id">
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="edit_description">{{ trans('messages.address') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="edit_address" name="edit_address" rows="3"></textarea>
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="edit_city">{{ trans('messages.town_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_city" name="edit_city" required>
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="edit_zipcode">{{ trans('messages.zip') }}</label>
                                        <input type="text" class="form-control" id="edit_zipcode" name="edit_zipcode" >
                                    </div>
                                    <div class="form-group m-2">
                                        <label for="edit_phone">{{ trans('messages.phone') }}<span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="edit_phone" name="edit_phone" required>
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
            </div>
            <!-- End second container -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shopping cart -->
@endsection
@section('script')
    
    <script type="text/javascript">
        $(document).ready(function () {
            $("#addressForm").on("submit", function (e) {
                e.preventDefault(); // Prevent the page from reloading
            });

            $("#addressForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    address: {
                        required: true,
                        minlength: 10
                    },
                    city: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.name_min_msg')}}"
                    },
                    phone: {
                        required: "{{ trans('messages.required_field') }}",
                        digits: "{{trans('messages.digits_allowed')}}",
                        minlength: "{{trans('messages.phn_min_msg')}}",
                        maxlength: "{{trans('messages.phn_max_msg')}}"
                    },
                    address: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.description_msg')}}"
                    },
                    city: {
                        required: "{{ trans('messages.required_field') }}"
                    }
                },
                submitHandler: function (form) {
                    event.preventDefault();
                    // Perform an AJAX request or submit the form
                    $.ajax({
                        url: "{{ route('save-address') }}", // Replace with your route
                        type: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $('#addressForm')[0].reset(); // Reset the form
                                $('#addressModal').modal('hide'); // Hide the modal
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
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
            $('#addressModal').on('hidden.bs.modal', function () {
                $("#addressForm")[0].reset(); // Clear the form
                $("#addressForm").validate().resetForm(); // Reset validation
            });

            $(document).on('click', '.set-default-btn', function() {
                let addressId = $(this).data('address-id');

                $.ajax({
                    url: '/address/default',
                    type: 'POST',
                    data: {
                        address_id: addressId,
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if (response.status == true) {
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);

                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                        
                    },
                    error: function(xhr) {
                        toastr.error("{{trans('messages.something_went_wrong')}}", "{{trans('messages.error')}}");
                    }
                });
            }); 

            $(document).on('click', '.delete-address-btn', function() {
                let addressId = $(this).data('address-id');
                let confirmation = confirm("{{ trans('messages.delete_address_confirm') }}");

                if (confirmation) {
                    $.ajax({
                        url: '/address/delete',
                        type: 'DELETE',
                        data: {
                            address_id: addressId,
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $(`#address-${addressId}`).remove();

                            } else {
                                toastr.error(response.message, "{{trans('messages.error')}}");
                            }
                        },
                        error: function(xhr) {
                            alert('Failed to delete address: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });

            // Show modal with current address data
            $(document).on('click', '.edit-address-btn', function () {
                let addressId = $(this).data('address-id');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let phone = $(this).data('phone');
                let city = $(this).data('city');
                let zipcode = $(this).data('zipcode');

                $('#address_id').val(addressId);
                $('#edit_address').val(address);
                $('#edit_name').val(name);
                $('#edit_phone').val(phone);
                $('#edit_city').val(city);
                $('#edit_zipcode').val(zipcode);
                $('#addressEditModal').modal('show');
            });


            $("#addressEditForm").validate({
                rules: {
                    edit_name: {
                        required: true,
                        minlength: 3
                    },
                    edit_phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    edit_address: {
                        required: true,
                        minlength: 10
                    },
                    edit_city: {
                        required: true,
                    }
                },
                messages: {
                    edit_name: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.name_min_msg')}}"
                    },
                    edit_phone: {
                        required: "{{ trans('messages.required_field') }}",
                        digits: "{{trans('messages.digits_allowed')}}",
                        minlength: "{{trans('messages.phn_min_msg')}}",
                        maxlength: "{{trans('messages.phn_max_msg')}}"
                    },
                    edit_address: {
                        required: "{{ trans('messages.required_field') }}",
                        minlength: "{{trans('messages.description_msg')}}"
                    },
                    edit_city: {
                        required: "{{ trans('messages.required_field') }}"
                    }
                },
                submitHandler: function (form) {
                    event.preventDefault();
                    // Perform an AJAX request or submit the form
                    $.ajax({
                        url: "{{ route('address.update') }}", // Replace with your route
                        type: "POST",
                        data: {
                            address_id: $('#address_id').val(),
                            name : $('#edit_name').val(),
                            address: $('#edit_address').val(),
                            city: $('#edit_city').val(),
                            phone: $('#edit_phone').val(),
                            zip_code: $('#edit_zipcode').val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $('#addressEditForm')[0].reset(); // Reset the form
                                $('#addressEditModal').modal('hide'); // Hide the modal
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
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

        });


    </script>
@endsection
