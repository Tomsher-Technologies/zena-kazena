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
                    {{ trans('messages.orders') }} <small>{{ trans('messages.order_breadcrumb') }}

                    </small></li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->



    <!-- start :: about us -->
    <!-- Main About Section -->
    @if (!empty($order))
        <section class="zk-contactus-section">
            <div class="container container--type-2">
                <div class="order-header">
                    <h1 class="order-title">{{ trans('messages.rent') . ' ' . trans('messages.details') }}</h1>
                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-label">{{ trans('messages.order_code') }}</div>
                            <div class="info-value">{{ $order->order_code }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ trans('messages.order') . ' ' . trans('messages.date') }}</div>
                            <div class="info-value">{{ $order->order_date->format('M d, Y h:i A') }}</div>

                        </div>
                        <div class="info-item ">
                            <div class="info-label">{{ trans('messages.status') }}</div>
                            <div class="order-status">{{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}</div>
                        </div>
                    </div>
                    <div class="zk-order-status">
                        @php
                            $pending = $dataByStatus['pending']['date'] ?? null;
                            $confirmed = $dataByStatus['confirmed']['date'] ?? null;
                            $picked_up = $dataByStatus['picked_up']['date'] ?? null;
                            $on_the_way = $dataByStatus['on_the_way']['date'] ?? null;
                            $delivered = $dataByStatus['delivered']['date'] ?? null;
                            $cancelled = $dataByStatus['cancelled']['date'] ?? null;

                        @endphp
                        <div class="row zk-order-status-timeline-area">
                            <div class="col-12 col-md-12 zk-order-status-timeline pt45 pb20">
                                <div class="row @if ($cancelled == null && $order->cancel_approval == 0) justify-content-between @endif">
                                    <div class="order-tracking @if ($pending != null) completed @endif">
                                        <span class="is-complete"></span>
                                        <p>{{ trans('messages.ordered') }}<br><span>{{ $pending }}</span></p>
                                    </div>

                                    @if ($cancelled || $order->cancel_approval == 1)
                                        <div class="order-tracking  completed ">
                                            <span class="is-cancelled"></span>
                                            <p>{{ trans('messages.cancelled') }}<br><span>{{ $cancelled }}</span></p>
                                        </div>
                                    @else
                                        <div class="order-tracking  @if ($confirmed != null) completed @endif">
                                            <span class="is-complete"></span>
                                            <p>{{ trans('messages.confirmed') }}<br><span>{{ $confirmed }}</span></p>
                                        </div>
                                        <div class="order-tracking  @if ($picked_up != null) completed @endif">
                                            <span class="is-complete"></span>
                                            <p>{{ trans('messages.picked_up') }}<br><span>{{ $picked_up }}</span></p>
                                        </div>
                                        <div class="order-tracking  @if ($on_the_way != null) completed @endif">
                                            <span class="is-complete"></span>
                                            <p>{{ trans('messages.on_the_way') }}<br><span>{{ $on_the_way }}</span></p>
                                        </div>
                                        <div class="order-tracking @if ($delivered != null) completed @endif">
                                            <span class="is-complete"></span>
                                            <p>{{ trans('messages.delivered') }}<br><span>{{ $delivered }}</span></p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-list">
                    @if ($order)
                        <div class="product-item">
                            <img src="{{ get_product_image($order->product->thumbnail_img, '300') }}" alt="Product"
                                class="product-image">
                            <div class="product-details">
                                <h3 class="product-name">{{ $order->product->getTranslation('name', $lang) }} <span
                                        class="text-muted">x {{ $order->quantity }}</span></h3>
                                @if ($order->productStock != null)
                                    @php
                                        $variations = json_decode($order->productStock);
                                    @endphp

                                    @if ($variations && $variations->variant)
                                        @foreach (json_decode($variations->variant, true) as $key => $value)
                                            <div class="product-meta">{{ ucfirst($key) }}: {{ $value }}</div>
                                        @endforeach
                                    @endif
                                @endif

                                <div class="product-meta">{{ trans('messages.sku') }}: {{ $order->productStock->sku }}
                                </div>
                            </div>
                            <div class="product-price">
                                {{-- @if ($order->productStock->og_price != $order->productStock->price)
                                    <del>{{ env('DEFAULT_CURRENCY') }}
                                        {{ single_price($order->productStock->og_price) }}</del> <br>
                                @endif --}}
                                {{ env('DEFAULT_CURRENCY') }}
                                {{ single_price($order->productStock->price ) }}
                            </div>
                        </div>
                    @endif

                </div>

                <div class="order-summary">
                    <div class="summary-row">
                        <span>{{ trans('messages.no_of_days') }}</span>
                        <span> {{ ($order->no_of_days) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ trans('messages.subtotal') }}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->sub_total) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ trans('messages.deposit') }}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->deposit) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ trans('messages.shipping_charge') }}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->shipping_cost) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ trans('messages.tax') }}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->tax) }}</span>
                    </div>
                    {{-- <div class="summary-row">
                        <span>{{trans('messages.coupon').' '.trans('messages.discount')}}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->coupon_discount) }}</span>
                    </div> --}}
                    {{-- <div class="summary-row">
                        <span>{{trans('messages.discount')}}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->offer_discount) }}</span>
                    </div> --}}
                    <div class="summary-total">
                        <span>{{ trans('messages.total') }}</span>
                        <span>{{ env('DEFAULT_CURRENCY') }} {{ single_price($order->grand_total) }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="shipping-info col-sm-6">
                        <h2 class="shipping-title">{{ trans('messages.shipping') . ' ' . trans('messages.information') }}</h2>
                        <div class="address-details">
                            <p>{{ json_decode($order->shipping_address)->name }}</p>
                            <p>{{ json_decode($order->shipping_address)->address }}</p>
                            <p>{{ json_decode($order->shipping_address)->zipcode }}</p>
                            <p>{{ json_decode($order->shipping_address)->phone }}</p>
                            <p>{{ json_decode($order->shipping_address)->email }}</p>
                        </div>
                    </div>

                    <div class="shipping-info col-sm-6">
                        <h2 class="shipping-title">{{ trans('messages.billing') . ' ' . trans('messages.information') }}</h2>
                        <div class="address-details">
                            <p>{{ json_decode($order->billing_address)->name }}</p>
                            <p>{{ json_decode($order->billing_address)->address }}</p>
                            <p>{{ json_decode($order->billing_address)->zipcode }}</p>
                            <p>{{ json_decode($order->billing_address)->phone }}</p>
                            <p>{{ json_decode($order->billing_address)->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="action-buttons">
                    @if ($order->delivery_status == 'pending' || $order->delivery_status == 'confirmed')
                        @if ($order->cancel_request == 0)
                            <button class="btn btn-cancel cancel-order-btn" data-order-id="{{$order->id}}">{{ trans('messages.cancel').' '.trans('messages.order') }}</button>
                        @else
                            <span style="color: red;">{{ trans('messages.cancel_request_send') }}</span>
                        @endif
                        
                    @endif
                    
                    @if ($order->delivery_status == 'delivered')
                        @if ($order->return_request == 0)
                            <button class="btn btn-return return-order-btn" data-order-id="{{$order->id}}">{{trans('messages.return')}}</button>
                        @else
                            <span style="color: red;">{{ trans('messages.return_request_send') }}</span>
                        @endif

                    @endif
                </div> --}}
            </div>
            {{-- <div id="cancelModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close-modal close-modal-cancel">&times;</span>
                    <h3>Cancel Order</h3>
                    <textarea id="cancelReason" placeholder="Type your reason here..."></textarea>
                    <button id="submitCancelReason">Submit</button>
                </div>
            </div>

            <div id="returnModal" class="modal" style="display:none;">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <span class="close-modal close-modal-return">&times;</span>
                    <h3>Return Order</h3>
                    <textarea id="returnReason" placeholder="Type your reason for returning the order here..."></textarea>
                    <button id="submitReturnReason" class="submit-btn">Submit</button>
                </div>
            </div> --}}
        </section>


    @endif

    <!-- end :: about us -->
@endsection

@section('header')
    <style>
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            position: relative;
        }

        .close-modal-cancel {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Open the modal
            $(".cancel-order-btn").click(function() {
                const orderId = $(this).data("order-id"); // Get the order ID from the button
                $("#cancelModal").data("order-id", orderId).fadeIn(); // Pass order ID to modal
            });

            // Close the modal
            $(".close-modal-cancel").click(function() {
                $("#cancelModal").fadeOut();
            });

            // Submit the cancellation reason
            $("#submitCancelReason").click(function() {
                const orderId = $("#cancelModal").data("order-id"); // Get the order ID
                const reason = $("#cancelReason").val().trim(); // Get the reason

                if (!reason) {
                    toastr.error("{{ trans('messages.reason_for_cancel') }}", 'Error');
                    return;
                }

                // Send the AJAX request
                $.ajax({
                    url: "/cancel-order", // Backend route
                    type: "POST",
                    data: {
                        order_id: orderId,
                        reason: reason,
                        _token: $('meta[name="csrf-token"]').attr("content") // CSRF token
                    },
                    success: function(response) {
                        if (response.status == true) {
                            toastr.success(response.message,
                            "{{ trans('messages.success') }}");
                            $("#cancelModal").fadeOut();
                            window.location.reload();
                        } else {
                            toastr.error(response.message, "{{ trans('messages.error') }}");
                        }

                    },
                    error: function(xhr) {
                        toastr.error("{{ trans('messages.something_went_wrong') }}",
                            "{{ trans('messages.error') }}");
                    }
                });
            });

            $(".return-order-btn").click(function() {
                const orderId = $(this).data("order-id");
                $("#returnModal").data("order-id", orderId).fadeIn(); // Attach order ID and show modal
            });

            $(".close-modal-return, .modal-overlay").click(function() {
                $("#returnModal").fadeOut();
            });

            $("#submitReturnReason").click(function() {
                const orderId = $("#returnModal").data("order-id");
                const reason = $("#returnReason").val().trim();

                if (!reason) {
                    toastr.error("{{ trans('messages.reason_for_return') }}", 'Error');
                    return;
                }

                // AJAX request
                $.ajax({
                    url: "/return-order",
                    type: "POST",
                    data: {
                        order_id: orderId,
                        reason: reason,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        if (response.status == true) {
                            toastr.success(response.message,
                            "{{ trans('messages.success') }}");
                            $("#returnModal").fadeOut();
                            window.location.reload();
                        } else {
                            toastr.error(response.message, "{{ trans('messages.error') }}");
                        }

                    },
                    error: function(xhr) {
                        toastr.error("{{ trans('messages.something_went_wrong') }}",
                            "{{ trans('messages.error') }}");
                    }
                });
            });
        });
    </script>
@endsection
