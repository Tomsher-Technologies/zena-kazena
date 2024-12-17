@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">Mortgage Enquiries</h1>
		</div>
		<div class="col-md-6 text-md-right">
			
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">Mortgage Enquiries</h5>
        </div>

    </div>
    <div class="card-body">
       
        <table class="table aiz-table mb-0 ">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Sl No:</th>
                    <th scope="col">Customer Details</th>
                    <th scope="col">Product Details</th>
                    <th scope="col" >Product Image</th>
                    <th scope="col" style="width:25%;">Description</th>
                    <th scope="col">Emirates ID Number</th>
                    <th scope="col" >Emirates ID Images</th>
                    <th scope="col" class="text-center">Date</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($enquiries[0]))
                    @foreach ($enquiries as $key=>$con)
                        <tr>
                            <td scope="row" class="text-center">{{ $key + 1 + ($enquiries->currentPage() - 1) * $enquiries->perPage() }}</td>
                            <td>
                                <b>Name :</b> {{ $con->name }} <br>
                                <b>Email :</b> {{ $con->email }} <br>
                                <b> Phone :</b> {{ $con->phone }} 
                            </td>
                            <td>
                                <b>Duration :</b> {{ $con->duration }} Month<br>
                                <b>Metal :</b> {{  ucwords(str_replace('_', ' ', $con->metal)) }} <br>
                                <b>Metal Type :</b> {{ $con->metal_type }} <br>
                                <b>Weight :</b> {{ $con->weight }} 
                            </td>
                            <td>
                                <a href="{{ asset($con->product_image) }}" target="_blank"><img src="{{ asset($con->product_image) }}" style="width:100px; height:100px"/></a>
                            </td>
                            <td>{{ $con->description }}</td>
                            <td>{{ $con->eid_no }}</td>
                            <td>
                                <a href="{{ asset($con->id_image) }}" target="_blank"><img src="{{ asset($con->id_image) }}" style="width:100px; height:100px"/></a>
                                <a href="{{ asset($con->id_image_back) }}" target="_blank"><img src="{{ asset($con->id_image_back) }}" style="width:100px; height:100px"/></a>
                            </td>
                            <td class="text-center">{{ date('d M,Y', strtotime($con->created_at)) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">No data found </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $enquiries->appends(request()->input())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
