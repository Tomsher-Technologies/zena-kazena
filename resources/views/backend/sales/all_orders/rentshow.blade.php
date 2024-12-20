@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-info"
                href="{{ Session::has('last_url') ? Session::get('last_url') : route('rent.all_orders.index') }}">Go Back</a>
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
                                <td class="text-right text-info text-bold"> {{ $order->order_code }}</td>
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
                                <td class="text-right">
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y h:i A') }}</td>
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
                                <th class="min-col text-center text-uppercase">No Of Days
                                </th>
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($order)
                                <tr>
                                    <td>1</td>
                                    <td>
                                        @if ($order->product != null)
                                            {{-- <img height="50"
                                                src="{{ get_product_image($order->product->thumbnail_img, '300') }}"> --}}
                                            @php
                                                $imagePath = get_product_image($order->product->thumbnail_img, '300');
                                                $imageExists = \Storage::disk('public')->exists(
                                                    $order->product->thumbnail_img,
                                                );
                                            @endphp

                                            @if ($imageExists && $imagePath)
                                                <img height="50" src="{{ $imagePath }}" alt="Image">
                                            @else
                                            <img src="{{ asset(optional($order->product)->thumbnail_img) }}"
                                                        class="img-fluid img-fit "
                                                        style="max-width: 50px; height: auto;">
                                            @endif
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->product != null)
                                            <strong class="text-muted fs-13">{{ $order->product->name }}</strong>
                                            {{-- <small> --}}
                                            @if (optional($order->productStock)->variant != null)
                                                @php
                                                    $variations = json_decode($order->productStock->variant);

                                                @endphp
                                                <ul>
                                                    @foreach ($variations as $var)
                                                        @if ($var != null)
                                                            <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $order->no_of_days }}</td>
                                    <td class="text-center">{{ $order->quantity }}</td>
                                    <td class="text-center">
                                        @if (optional($order->productStock)->price != optional($order->productStock)->offer_price)
                                            <del>{{ single_price($order->productStock->price) }}</del> <br>
                                        @endif
                                        {{ single_price(optional($order->productStock)->offer_price) }}
                                    </td>
                                </tr>
                            @endif
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
                                {{ single_price($order->sub_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Deposit :</strong>
                            </td>
                            <td>
                                {{ single_price($order->deposit) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                {{ single_price($order->tax) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                {{ single_price($order->shipping_cost) }}
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
                    <a href="{{ route('rent.invoice.download', $order->id) }}" type="button"
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
                            @if ($order->product->vendor_id != null && $order->payment_status === 'paid')
                                @php
                                    
                                    $commission = getCommissionDetails($order->id, $order->product_id,$order->product->vendor_id);

                                    $profit_share = ($order->product->vendor) ? $order->product->vendor->profit_share : 0;

                                    $admin_amount = calculateAdminCommission($profit_share, $order->sub_total);

                                    $vendor_amount = ($commission) ? $commission->vendor_amount : ($order->sub_total - $admin_amount);

                                    $admin_amount = ($commission) ? $commission->admin_amount : $admin_amount;
                                @endphp
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        @if ($order->product != null)
                                            <img height="50" src="{{ get_product_image($order->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif

                                        @if ($order->product != null)
                                            <strong class="text-muted fs-13">{{ $order->product->name }}</strong>
                                            {{-- <small> --}}
                                                @if ($order->variation != null)
                                                    @php
                                                        $variations = json_decode($order->variation);
                                                    
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
                                        {{ $order->product->vendor->name }}
                                    </td>
                                
                                    <td class="text-center">{{ $profit_share }}%</td>
                                    <td class="text-center">
                                        {{ $order->sub_total }}
                                    </td>
                                    <td class="text-center"> {{ $vendor_amount }} </td>
                                    <td class="text-center"> {{ $admin_amount }} </td>
                                    <td class="text-center">
                                        @if ($commission)
                                            Paid
                                        @else
                                            <button class="btn btn-success" onclick="confirmPayment({{ $order->id }}, {{ $order->product_id }}, {{ $order->product->vendor_id }}, {{ $order->sub_total }}, {{ $admin_amount }}, {{ $vendor_amount }}, {{ $profit_share }},1)">
                                            Mark as Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
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
            $.post('{{ route('rent.orders.update_delivery_status') }}', {
                    _token: '{{ @csrf_token() }}',
                    order_id: order_id,
                    status: status
                }).done(function(data) {
                    AIZ.plugins.notify('success', 'Delivery status has been updated');

                    // Update the status badge dynamically
                    var badgeClass = (status === 'delivered') ? 'badge-success' : 'badge-info';
                    var statusText = status.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());

                    $('td.text-right span').removeClass('badge-success badge-info').addClass(badgeClass).text(
                        statusText);
                })
                .fail(function(error) {
                    console.error(error);
                    AIZ.plugins.notify('danger', 'Failed to update delivery status');
                });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('rent.orders.update_payment_status') }}', {
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
            $.post('{{ route('rent.orders.update_tracking_code') }}', {
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
                    order_type:'rent'
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
