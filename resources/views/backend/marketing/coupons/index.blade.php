@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{trans('messages.all').' '.trans('messages.coupons')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('coupon.create') }}" class="btn btn-circle btn-info">
				<span>{{trans('messages.add_new').' '.trans('messages.coupon')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 mr-4">{{trans('messages.coupons')}}</h5>
        <form class="" id="sort_categories" action="" method="GET" style="width: 100%">

            <div class="row gutters-5">
                <div class="col-md-3">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" 
                        name="coupon_type" id="" data-selected={{ $coupon_type }}>
                        <option value="">{{trans('messages.coupon').' '.trans('messages.type').' '.trans('messages.filter')}}</option>
                        <option value="cart_base">{{ trans('messages.cart_base') }}</option>
                        <option value="product_base">{{ trans('messages.product_base') }}</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ trans('messages.type_name_enter') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" 
                        name="use_type" id="" data-selected={{ $use_type }}>
                        <option value="">{{ trans('messages.one_time_use').' '. trans('messages.filter')}}</option>
                        <option value="0">{{ trans('messages.no') }}</option>
                        <option value="1">{{ trans('messages.yes') }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info" type="submit">{{ trans('messages.filter') }}</button>
                    <a href="{{ route('coupon.index') }}" class="btn btn-warning">{{ trans('messages.reset') }}</a>
                </div>
            </div>

        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table p-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th class="text-center">{{trans('messages.code')}}</th>
                    <th class="text-center">{{trans('messages.type')}}</th>
                    <th class="text-center">{{trans('messages.discount')}}</th>
                    <th class="text-center">{{trans('messages.one_time_use')}}</th>
                    <th class="text-center">{{trans('messages.start_date')}}</th>
                    <th class="text-center">{{trans('messages.end_date')}}</th>
                    <th width="10%">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $key => $coupon)
                    <tr>
                        <td>{{ $key + 1 + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
                        <td class="text-center">{{$coupon->code}}</td>
                        <td class="text-center">@if ($coupon->type == 'cart_base')
                                {{ trans('messages.cart_base') }}
                            @elseif ($coupon->type == 'product_base')
                                {{ trans('messages.product_base') }}
                        @endif</td>
                        
                        <td class="text-center">
                            {{ $coupon->discount }} ({{ ($coupon->discount_type == "percent") ? '%':'AED'}})
                        </td>
                        <td class="text-center">{{ ($coupon->one_time_use == 1) ? trans('messages.yes') : trans('messages.no') }}</td>
                        <td class="text-center">{{ date('d-m-Y', $coupon->start_date) }}</td>
                        <td class="text-center">{{ date('d-m-Y', $coupon->end_date) }}</td>
						<td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('coupon.edit', encrypt($coupon->id) )}}" title="{{ trans('messages.edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('coupon.destroy', $coupon->id)}}" title="{{ trans('messages.delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $coupons->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
