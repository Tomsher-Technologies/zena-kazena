@extends('backend.layouts.app', ['body_class' => '', 'title' => 'Partners'])
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' '.trans('messages.partners') }}</h5>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('partners.create') }}" class="btn btn-primary">
                    <span>{{ trans('messages.add_new').' '.trans('messages.partner') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($partners)
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Sl No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Image</th>
                                    <th scope="col" class="text-center">Sort Order</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($partners[0]))
                                    @foreach ($partners as $key=>$partner)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 + ($partners->currentPage() - 1) * $partners->perPage() }}</td>
                                            <td>{{ $partner->name }}</td>
                                            <td class="text-center">
                                                <img class="partner-image" src="{{ uploaded_asset($partner->image) }}" alt="">
                                            </td>
                                            <td class="text-center">{{ $partner->sort_order }}</td>
                                            <td class="text-center">
                                                <b>{!! $partner->status == 1 ? '<span class="text-success">Enabled</span>' : '<span class="text-danger">Disabled</span>' !!}</b>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('partners.edit', $partner) }}"
                                                    class="btn btn-secondary mb-1">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No data found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="aiz-pagination float-right">
                            {{ $partners->appends(request()->input())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('header')
@endpush
