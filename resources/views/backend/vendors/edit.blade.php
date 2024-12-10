@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Edit Vendor</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    <form class="form-horizontal" action="{{ route('vendors.update', $vendor) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mx-auto">
                                <!-- Name -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Name" id="name" name="name"
                                            class="form-control" value="{{ $vendor->name }}" required>
                                    </div>
                                </div>

                                <!-- Email (Disabled) -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" placeholder="Email" class="form-control"
                                            value="{{ $vendor->email }}"name="email" readonly>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Phone Number</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Phone number" class="form-control"
                                            value="{{ $vendor->phone }}" name="phone" required>
                                    </div>
                                </div>

                                <hr>
                                <h6>Reset Password</h6>
                                <span>Fill only if you want to reset password</span>

                                <!-- Password -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Password</label>
                                    <div class="col-md-9">
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control password" name="password"
                                                placeholder="Password">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary passwordToggle" type="button">
                                                    <i class="las la-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Confirm Password</label>
                                    <div class="col-md-9">
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control password"
                                                name="password_confirmation" placeholder="Confirm Password">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary passwordToggle" type="button">
                                                    <i class="las la-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mx-auto">
                                <!-- Logo -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Logo</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="logo">
                                    </div>
                                </div>

                                <!-- Business Name -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Business Name</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Business Name" id="business_name"
                                            name="business_name" class="form-control" value="{{ $vendor->business_name }}"
                                            required>
                                    </div>
                                </div>

                                <!-- Business Type -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Business Type</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Business Type" class="form-control"
                                            name="business_type" value="{{ $vendor->business_type }}" required>
                                    </div>
                                </div>

                                <!-- Trade License -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Trade License</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="trade_license">
                                    </div>
                                </div>
                                <!-- Profit Share -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Profit Share (%)</label>
                                    <div class="col-md-9">
                                        <input type="number" placeholder="Profit share for admin in %" value="{{$vendor->profit_share}}"class="form-control" name="profit_share" min="0" max="100" step="0.01"
                                            required>
                                    </div>
                                </div>
                                <!-- Address -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Address</label>
                                    <div class="col-md-9">
                                        <textarea placeholder="Address" class="form-control" name="address" required>{{ $vendor->address }}</textarea>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="form-group mb-0 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
