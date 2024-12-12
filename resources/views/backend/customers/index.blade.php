@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">All Customers</h1>
        </div>
    </div>


    <div class="card">
        <form class="" id="sort_customers" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">Customers</h5>
                </div>

               

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type email or name & Enter">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                           
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Emirates ID</th>
                            <th class="text-center">Phone Verified Status</th>
                            <th class="text-center">Emirates ID Verification</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            @if ($user != null)
                                <tr>
                                    <td class="text-center">{{ $key + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    
                                    <td>
                                        @if ($user->banned == 1)
                                            <i class="las la-ban text-danger" aria-hidden="true"></i>
                                        @endif
                                        {{ $user->name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->eid_no }}</td>
                                    <td class="text-center">
                                        @if ($user->is_phone_verified)
                                            <span class="badge badge-inline badge-success">Verified</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">Unverified</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->eid_approval_status == 0)
                                            <span class="badge badge-inline badge-warning">Pending</span>
                                        @elseif ($user->eid_approval_status == 1) 
                                            <span class="badge badge-inline badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">Denied</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->eid_no != NULL || $user->eid_image_front != NULL || $user->eid_image_back != NULL)
                                            <a href="#" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="Emirates ID details" onclick="eidDetails('{{ encrypt($user->id) }}','{{$user->eid_no}}','{{ $user->eid_image_front }}','{{ $user->eid_image_back }}','{{$user->eid_approval_status}}')">
                                                <i class="las la-address-card"></i>
                                            </a>
                                        @endif
                                        @if ($user->banned != 1)
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                onclick="confirm_ban('{{ route('customers.ban', encrypt($user->id)) }}');"
                                                title="Ban this Customer">
                                                <i class="las la-user-slash"></i>
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                onclick="confirm_unban('{{ route('customers.ban', encrypt($user->id)) }}');"
                                                title="Unban this Customer">
                                                <i class="las la-user-check"></i>
                                            </a>
                                        @endif
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('customers.destroy', $user->id) }}"
                                            title="Delete">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $users->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>


    <div class="modal fade" id="confirm-ban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to ban this Customer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <a type="button" id="confirmation" class="btn btn-primary">Proceed!</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-unban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to unban this Customer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <a type="button" id="confirmationunban" class="btn btn-primary">Proceed!</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="eid_details">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Emirates ID Details</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="idimageback">Emirates ID Number :</label>  <span id='eid_no'></span>
                            </div>
                        </div>

                        <div class="col-sm-12" id="eid_front_div">
                            <div class="form-group">
                                <label for="idimageback1">Emirates ID Front Image :</label>
                                <div class="file-preview box sm">
                                    <a href="#" id="eid_front_a" target="_blank">
                                        <img src="#" class="img-fit"  id="eid_front_img">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="eid_back_div">
                            <div class="form-group">
                                <label for="idimageback12">Emirates ID Back Image :</label>
                                <div class="file-preview box sm">
                                    <a href="#" id="eid_back_a" target="_blank">
                                        <img src="#" class="img-fit"  id="eid_back_img">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <a type="button" data-status="1" data-user="" id="approveButton" class="btn btn-success changeEidStatus">Approve!</a>
                    <a type="button" data-status="2" data-user="" id="denyButton" class="btn btn-danger changeEidStatus">Deny!</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function eidDetails(uid,eid_no,eid_front,eid_back,status) {
            $('#eid_front_div').css('display','none');
            $('#eid_back_div').css('display','none');
            $('#eid_details').modal('show', {
                // backdrop: 'static'
            });
            $('#eid_no').text(eid_no);
            $('.changeEidStatus').attr('data-user',uid);
            if(eid_front != ''){
                eid_front = eid_front.replace(/^\/+/, '');
                $('#eid_front_div').css('display','block');
                document.getElementById('eid_front_a').setAttribute('href', "{{ asset('') }}" + eid_front);
                document.getElementById('eid_front_img').setAttribute('src', "{{ asset('') }}" + eid_front);
            }

            if(eid_back != ''){
                eid_back = eid_back.replace(/^\/+/, '');
                $('#eid_back_div').css('display','block');
                document.getElementById('eid_back_a').setAttribute('href', "{{ asset('') }}" + eid_back);
                document.getElementById('eid_back_img').setAttribute('src', "{{ asset('') }}" + eid_back);
            }
            if(status == 1){
                $('#approveButton').css('display','none');
            }else if(status == 2){
                $('#denyButton').css('display','none');
            }else{
                $('#approveButton').css('display','block');
                $('#denyButton').css('display','block');
            }
            
        }

        function sort_customers(el) {
            $('#sort_customers').submit();
        }

        function confirm_ban(url) {
            $('#confirm-ban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmation').setAttribute('href', url);
        }

        function confirm_unban(url) {
            $('#confirm-unban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmationunban').setAttribute('href', url);
        }

        
        $(document).on('click', '.changeEidStatus', function () {
            let id = $(this).data('user');
            let status = $(this).data('status');
            $.ajax({
                url: '{{ route("change-eid-status") }}',  // Endpoint to check login status
                type: 'POST',
                data: {
                    id: id,
                    status:status,
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function (response) {
                    if (response.status == true) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);

                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong')}}");
                }
            });
        });

        // function bulk_delete() {
        //     var data = new FormData($('#sort_customers')[0]);
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ route('bulk-customer-delete') }}",
        //         type: 'POST',
        //         data: data,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             if (response == 1) {
        //                 location.reload();
        //             }
        //         }
        //     });
        // }
    </script>
@endsection
