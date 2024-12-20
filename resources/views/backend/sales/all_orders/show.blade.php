@extends('backend.layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-info" href="{{ Session::has('last_url') ? Session::get('last_url') : route('all_orders.index') }}" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp

               

                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">Payment Status</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid
                        </option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid
                        </option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">Delivery Status</label>
                    @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                Pending</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                Confirmed</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                Picked Up</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                On The Way</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                Delivered</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                Cancel</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                    @endif
                </div>
                <div class="col-md-3 ml-auto d-none">
                    <label for="update_tracking_code">Tracking Code (optional)</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="{{ $order->tracking_code }}">
                </div>
            </div>
            <div class="mb-3">
                
            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">
                    <address>
                        <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                        {{ json_decode($order->shipping_address)->email }}<br>
                        {{ json_decode($order->shipping_address)->phone }}<br>
                        {{ json_decode($order->shipping_address)->address }},
                        {{ json_decode($order->shipping_address)->city }},
                        <br>
                        {{ json_decode($order->shipping_address)->zipcode }}
                    </address>
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">Payment Information</strong><br>
                        Name: {{ json_decode($order->manual_payment_data)->name }},
                        Amount: {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        TRX ID: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Status</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Date </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong class="text-muted fs-13">{{ $orderDetail->product->name }}</strong>
                                            {{-- <small> --}}
                                                @if ($orderDetail->variation != null)
                                                    @php
                                                        $variations = json_decode($orderDetail->variation);
                                                    
                                                    @endphp
                                                    <ul>
                                                        @foreach($variations as $var)
                                                        <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                    </td>
                                   
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if ($orderDetail->og_price)
                                            <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                        @endif
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">Sub Total :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Vendor Commissions</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col-sm-12">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Product</th>
                                <th>Vendor</th>
                                <th class="text-center">Admin Profit Percentage</th>
                                <th class="text-center">Product Total Price</th>
                                <th class="text-center">Vendor Amount</th>
                                <th class="text-center">Admin Amount</th>
                                <th class="text-center">Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @if ($orderDetail->product->vendor_id != null && checkDateExpired($orderDetail->return_expiry_date) == 1)
                                    @php
                                        $i++;
                                        $commission = getCommissionDetails($order->id, $orderDetail->product_id,$orderDetail->product->vendor_id);

                                        $profit_share = ($orderDetail->product->vendor) ? $orderDetail->product->vendor->profit_share : 0;

                                        $admin_amount = calculateAdminCommission($profit_share, $orderDetail->price);

                                        $vendor_amount = ($commission) ? $commission->vendor_amount : ($orderDetail->price - $admin_amount);

                                        $admin_amount = ($commission) ? $commission->admin_amount : $admin_amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td>
                                            @if ($orderDetail->product != null)
                                                <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                            @else
                                                <strong>N/A</strong>
                                            @endif

                                            @if ($orderDetail->product != null)
                                                <strong class="text-muted fs-13">{{ $orderDetail->product->name }}</strong>
                                                {{-- <small> --}}
                                                    @if ($orderDetail->variation != null)
                                                        @php
                                                            $variations = json_decode($orderDetail->variation);
                                                        
                                                        @endphp
                                                        <ul>
                                                            @foreach($variations as $var)
                                                            <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                {{-- </small> --}}
                                            @else
                                                <strong>Product Unavailable</strong>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $orderDetail->product->vendor->name }}
                                        </td>
                                    
                                        <td class="text-center">{{ $profit_share }}%</td>
                                        <td class="text-center">
                                            {{ $orderDetail->price }}
                                        </td>
                                        <td class="text-center"> {{ $vendor_amount }} </td>
                                        <td class="text-center"> {{ $admin_amount }} </td>
                                        <td class="text-center">
                                            @if ($commission)
                                                Paid
                                            @else
                                                <button class="btn btn-success" onclick="confirmPayment({{ $order->id }}, {{ $orderDetail->product_id }}, {{ $orderDetail->product->vendor_id }}, {{ $orderDetail->price }}, {{ $admin_amount }}, {{ $vendor_amount }}, {{ $profit_share }},1)">
                                                Mark as Paid
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmation-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.8); padding: 20px; border-radius: 8px; color: #fff; z-index: 1000;">
        <p>Are you sure you want to change payment status?</p>
        
        <button onclick="submitPayment()" style="padding: 10px 20px; margin: 10px; background-color: green; color: white; border: none; cursor: pointer;">Confirm</button>
        <button onclick="closePopup()" style="padding: 10px 20px; margin: 10px; background-color: red; color: white; border: none; cursor: pointer;">Cancel</button>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        

        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Delivery status has been updated');
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Payment status has been updated');
            });
        });

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', 'Order tracking code has been updated');
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let orderData = {};

        function confirmPayment(order_id, product_id, vendor_id, total_amount, admin_amount, vendor_amount, share_percentage, status) {
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
                    order_type:'sales'
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
