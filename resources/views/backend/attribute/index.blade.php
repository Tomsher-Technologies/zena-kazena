@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{trans('messages.all') .' '.trans('messages.attributes')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">{{ trans('messages.attributes')}}</h5>
			</div>
			<div class="card-body">
				<table class="table aiz-table mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>{{ trans('messages.name')}}</th>
							<th>{{ trans('messages.values')}}</th>
							<th>{{ trans('messages.status')}}</th>
							<th class="text-right">{{ trans('messages.options')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($attributes as $key => $attribute)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$attribute->name}}</td>
								<td>
									@foreach($attribute->attribute_values as $key => $value)
									<span class="badge badge-inline badge-md bg-soft-dark">{{ $value->getTranslatedName(env('DEFAULT_LANGUAGE')) }}</span>
									@endforeach
									
								</td>
								<td>
									<label class="aiz-switch aiz-switch-success mb-0">
										<input type="checkbox" onchange="update_status(this)" value="{{ $attribute->id }}"
											<?php if ($attribute->is_active == 1) {
												echo 'checked';
											} ?>>
										<span></span>
									</label>
								</td>
								<td class="text-right">
									<a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('attributes.show', $attribute->id)}}" title="Attribute values">
										<i class="las la-cog"></i>
									</a>
									<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('attributes.edit', ['id'=>$attribute->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="Edit">
										<i class="las la-edit"></i>
									</a>
									{{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('attributes.destroy', $attribute->id)}}" title="Delete">
										<i class="las la-trash"></i>
									</a> --}}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card">
			<div class="card-header">
					<h5 class="mb-0 h6">{{ trans('messages.add').' '.trans('messages.new').' '.trans('messages.attribute') }}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('attributes.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="name">{{trans('messages.name')}}</label>
						<input type="text" placeholder="{{ trans('messages.name')}}" id="name" name="name" class="form-control" required>
					</div>
					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-info">{{trans('messages.Save')}}</button>
					</div>
				</form>
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
      
        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('attributes.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', "{{ trans('messages.attribute').trans('messages.updated_msg') }}");
                } else {
                    AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong')}}");
                }
            });
        }
    </script>
@endsection

