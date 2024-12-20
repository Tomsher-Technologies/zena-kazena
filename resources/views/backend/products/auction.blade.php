@extends('backend.layouts.app')
@section('content')
    <style>
        .bread .breadcrumb {
            all: unset;
        }

        .bread .breadcrumb li {
            display: inline-block;
        }

        .bread nav {
            display: inline-block;
            max-width: 250px;
        }

        .bread .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
        }

        .breadcrumb-item+.breadcrumb-item {
            padding-left: 0;
        }

        .bread a {
            pointer-events: none;
            cursor: sw-resize;
        }
    </style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">All Auction Completed Products</h1>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">Auction Completed Products</h5>
                </div>


               
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type & Enter">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-info " type="submit">Filter</button>
                    <a href="{{ route('auctions.all') }}" class="btn btn-warning">Reset</a>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('messages.name') }}</th>
                            <th>{{ trans('messages.vendor') }}</th>
                            <th>{{ trans('messages.info') }}</th>
                            <th class="text-center">Admin Profit Percentage</th>
                            <th class="text-center">High Bid Price</th>
                            <th class="text-center">Vendor Amount</th>
                            <th class="text-center">Admin Amount</th>
                            <th class="text-center">{{ trans('messages.options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            @php
                           
                                $high_bid_amount = $product->stocks[0]->high_bid_amount;
                                $commission = getCommissionDetails(NULL, $product->id, $product->vendor_id);

                                $profit_share = ($product->vendor) ? $product->vendor->profit_share : 0;

                                $admin_amount = calculateAdminCommission($profit_share, $high_bid_amount);

                                $vendor_amount = ($commission) ? $commission->vendor_amount : ($high_bid_amount - $admin_amount);

                                $admin_amount = ($commission) ? $commission->admin_amount : $admin_amount;
                            @endphp

                            <tr>
                                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td>
                                    <div class="row gutters-5  w-md-250px mw-100">

                                        @if ($product->thumbnail_img)
                                            <div class="col-auto">
                                                @php
                                                    $imagePath = get_product_image($product->thumbnail_img, '300');
                                                    $imageExists = \Storage::disk('public')->exists(
                                                        $product->thumbnail_img,
                                                    );
                                                @endphp

                                                @if ($imageExists && $imagePath)
                                                    <img src="{{ $imagePath }}" alt="Image"
                                                        class="size-50px img-fit">
                                                @else
                                                    <img src="{{ asset($product->thumbnail_img) }}"
                                                        class="img-fluid img-fit avatar"
                                                        style="max-width: 50px; height: auto;">
                                                @endif


                                            </div>
                                        @endif
                                        <div class="col">
                                            <span class="text-muted text-truncate-2">{{ $product->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="bread">
                                    @if ($product->vendor_id != null)
                                        {{ $product->vendor?->name }}
                                    @else
                                        Admin
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($product->type == 'auction')
                                        @php
                                            $product->auction_status == 1 ? $auction_status = '<span class="badge badge-inline badge-success text-capitalize">Completed</span>' : $auction_status = '<span class="badge badge-inline badge-warning text-capitalize">Ongoing</span>';
                                        @endphp
                                        <strong>{{ trans('messages.sku') }}:</strong> {{ $product->sku }} <br>
                                        <strong>{{ trans('messages.auction') }} {{ trans('messages.status') }}: </strong>{!! $auction_status !!}  <br>
                                        @if ($product->auction_status == 1)
                                            <strong>{{ trans('messages.winner') }}:</strong> {{ $product->winner_auction?->name }} <br>
                                        @endif
                                    @endif
                                    
                                </td>

                                @if ($product->vendor_id != null)
                                    <td class="text-center">{{ $profit_share }}%</td>
                                @else
                                    <td class="text-center">100%</td>
                                @endif

                                
                                <td class="text-center">
                                    {{ $high_bid_amount }}
                                </td>

                                @if ($product->vendor_id != null)
                                    <td class="text-center"> {{ $vendor_amount }} </td>
                                    <td class="text-center"> {{ $admin_amount }} 
                                        @if ($commission)
                                            <span class="badge badge-inline  badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-inline  badge-danger">Not Paid</span>
                                        @endif
                                    </td>
                                @else
                                    <td class="text-center"> 0.00 </td>
                                    <td class="text-center"> {{ $high_bid_amount }} 
                                        
                                    </td>
                                @endif
                               

                                <td class="text-center">
                                    @if ($product->type == 'auction')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            href="{{ route('product.bid-history', $product->id) }}" title="View Bid History">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endif

                                    @if (!$commission && $product->vendor_id != null)
                                        
                                        <button class="btn btn-success" onclick="confirmPayment(null, {{ $product->id }}, {{ $product->vendor_id }}, {{ $high_bid_amount }}, {{ $admin_amount }}, {{ $vendor_amount }}, {{ $profit_share }},1,event)">
                                        Mark as Paid
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </form>
    </div>
    <div id="confirmation-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.8); padding: 20px; border-radius: 8px; color: #fff; z-index: 1000;">
        <p>Are you sure you want to change payment status?</p>
        
        <button onclick="submitPayment()" style="padding: 10px 20px; margin: 10px; background-color: green; color: white; border: none; cursor: pointer;">Confirm</button>
        <button onclick="closePopup()" style="padding: 10px 20px; margin: 10px; background-color: red; color: white; border: none; cursor: pointer;">Cancel</button>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
      
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let orderData = {};

        function confirmPayment(order_id, product_id, vendor_id, total_amount, admin_amount, vendor_amount, share_percentage, status,event) {
            event.preventDefault();
            // Store the order data in a global variable
            orderData = {
                order_id: order_id,
                product_id: product_id,
                vendor_id: vendor_id,
                total_amount: total_amount,
                admin_amount: admin_amount,
                vendor_amount: vendor_amount,
                share_percentage: share_percentage,
                paid_status: status
            };
            
            document.getElementById('confirmation-popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('confirmation-popup').style.display = 'none';
        }

        function submitPayment() {
            // Hide the popup after confirmation
            document.getElementById('confirmation-popup').style.display = 'none';

            // Send the data to the Laravel controller via AJAX
            $.ajax({
                url: '{{ route('saveCommission') }}', // Laravel route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderData.order_id,
                    product_id: orderData.product_id,
                    vendor_id: orderData.vendor_id,
                    total_amount: orderData.total_amount,
                    admin_amount: orderData.admin_amount,
                    vendor_amount: orderData.vendor_amount,
                    share_percentage: orderData.share_percentage,
                    paid_status: orderData.paid_status,
                    order_type:'auction'
                },
                success: function(response) {
                   
                    AIZ.plugins.notify('success', response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);

                },
                error: function(error) {
                    AIZ.plugins.notify('success', 'There was an error processing your request.');
                }
            });
        }
    </script>
@endsection
