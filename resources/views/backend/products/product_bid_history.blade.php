@extends('backend.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">Bid History</h1>
        </div>
        <div class="col text-right">
            <a  href="{{ Session::has('product_list_last_url') ? Session::get('product_list_last_url') : route('products.all') }}" class="btn btn-circle btn-info">
                <span>Go Back</span>
            </a>
        </div>
       
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Bids</h5>
            </div>

        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{trans('messages.sl_no')}}</th>
                        <th>{{trans('messages.user')}} {{trans('messages.name')}}</th>
                        <th class="text-center">{{trans('messages.bid')}} {{trans('messages.amount')}}</th>
                        <th class="text-center">{{trans('messages.time')}} {{trans('messages.placed')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($bids[0]))
                        @foreach($bids as $key => $bid)
                            <tr>
                                <td class="text-center">{{ $key + 1 + ($bids->currentPage() - 1) * $bids->perPage() }}</td>
                                <td> 
                                    <b>{{trans('messages.name')}} </b>: {{ $bid->user->name }}  
                                    @if ($bid->winner == 1)
                                        <span class="badge badge-inline badge-success text-capitalize"> Winner </span>
                                    @endif<br>
                                    <b>{{trans('messages.email')}} </b>: {{ $bid->user->email }}
                                </td>
                                <td class="text-center">{{ env('DEFAULT_CURRENCY') }} {{ $bid->amount }}</td>
                                <td class="text-center">{{ $bid->created_at->format('d M, Y H:i a') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $bids->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </form>
</div>



@endsection