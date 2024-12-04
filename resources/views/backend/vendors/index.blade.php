@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">All Vendors</h1>
        </div>
    </div>


    <div class="card">
        <form class="" id="sort_vendors" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">Vendors</h5>
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
                            <th data-breakpoints="lg">#</th>
                            <th>Logo</th>
                            <th>Contact</th>
                            <th data-breakpoints="lg">Business</th>
                            <th data-breakpoints="lg">Trade License</th>
                            <th data-breakpoints="lg">Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $key => $vendor)
                            @if ($vendor != null)
                                <tr>
                                    <td>{{ $key + 1 + ($vendors->currentPage() - 1) * $vendors->perPage() }}</td>
                                    <td><img src="{{ asset('storage/' . $vendor->logo) }}" class="avatar avatar-md"
                                            alt=""></td>
                                    <td>
                                        @if ($vendor->is_active == 0)
                                            <i class="las la-ban text-danger" aria-hidden="true"></i>
                                        @endif
                                        Name: <b>{{ $vendor->name }}</b><br>
                                        Email: {{ $vendor->email }}<br>
                                        Phone: {{ $vendor->phone }}
                                    </td>
                                    <td>
                                        <b>{{ $vendor->business_name }}</b><br>
                                        Type: {{ $vendor->business_type }}<br>
                                    </td>
                                    <td>
                                        @if ($vendor->trade_license)
                                            <a href="{{ route('vendor.download', $vendor->id) }}"
                                                class="font-size-16 download-link">Download
                                            </a>
                                        @else
                                            No Trade License
                                        @endif
                                    </td>
                                    <td>
                                        {{ $vendor->address }}
                                    </td>
                                    {{-- <td class="text-right"> --}}
                                    <td>
                                        @if ($vendor->is_active != 1)
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                onclick="confirm_ban('{{ route('vendors.ban', encrypt($vendor->id)) }}');"
                                                title="Ban this Vendors">
                                                <i class="las la-user-slash"></i>
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                onclick="confirm_unban('{{ route('vendors.ban', encrypt($vendor->id)) }}');"
                                                title="Unban this Vendors">
                                                <i class="las la-user-check"></i>
                                            </a>
                                        @endif
                                        <a href="#"
                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                        data-href="{{ route('vendors.destroy', $vendor->id) }}" title="Delete">
                                        <i class="las la-trash"></i>
                                    </a>
                                        <a class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                            href="{{ route('vendors.edit', $vendor->id) }}" title="Delete">
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $vendors->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>


    <div class="modal fade" id="confirm-unban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to reject this Vendor?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <a type="button" id="confirmationunban" class="btn btn-primary">Proceed!</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-ban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to approve this Vendor?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <a type="button" id="confirmation" class="btn btn-primary">Proceed!</a>
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

        function sort_vendors(el) {
            $('#sort_vendors').submit();
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

        function bulk_delete() {
            var data = new FormData($('#sort_vendors')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-vendor-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
