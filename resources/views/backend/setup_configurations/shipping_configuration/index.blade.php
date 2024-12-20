@extends('backend.layouts.app')

@section('content')
    
  
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Free Shipping Settings</h5>
                </div>
                <form action="{{ route('shipping_configuration.free_shipping') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="type" value="free_shipping">

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Default shipping amount
                            </label>
                            <div class="col-md-8">
                                <input step="0.01" class="form-control" type="number"
                                    name="default_shipping_amount"
                                    value="{{ get_setting('default_shipping_amount') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Free shipping status
                            </label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input name="free_shipping_status"
                                        {{ get_setting('free_shipping_status') == '1' ? 'checked' : '' }} type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Free shipping min amount
                            </label>
                            <div class="col-md-8">
                                <input step="0.01" class="form-control" type="number"
                                    name="free_shipping_min_amount"
                                    value="{{ get_setting('free_shipping_min_amount') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Order Return Time Limit</h5>
                </div>
                <form action="{{ route('configuration.return_settings') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="type" value="return_product_limit">

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Return Time Limit (Days)
                            </label>
                            <div class="col-md-8">
                                <input step="1" class="form-control" type="number"
                                    name="default_return_time"
                                    value="{{ get_setting('default_return_time') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
