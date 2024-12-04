@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{trans('messages.attribute').' '.trans('messages.details')}}</h1>
	</div>
</div>

<div class="row">
    <!-- Small table -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">
                    {{ $attribute->name }}
                </strong>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{trans('messages.value')}}</th>
                            <th>{{ trans('messages.status')}}</th>
                            <th class="text-right">{{trans('messages.options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_attribute_values as $key => $attribute_value)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{ $attribute_value->getTranslatedName(env('DEFAULT_LANGUAGE')) }}
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_status(this)" value="{{ $attribute_value->id }}"
                                        <?php if ($attribute_value->is_active == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('edit-attribute-value', ['id'=>$attribute_value->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{trans('messages.edit')}}">
									<i class="las la-edit"></i>
								</a>
								{{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('destroy-attribute-value', $attribute_value->id)}}" title="Delete">
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
					<h5 class="mb-0 h6">{{ trans('messages.add').' '.trans('messages.new').' '.trans('messages.attribute_value')}}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('store-attribute-value') }}" method="POST">
				 	@csrf
				 	<div class="form-group mb-3">
				 		<label for="name">{{trans('messages.attribute').' '.trans('messages.name')}}</label>
				 		<input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
				 		<input type="text" placeholder="{{ trans('messages.name')}}" name="name" value="{{ $attribute->name }}"class="form-control" readonly>
				 	</div>
				 	<div class="form-group mb-3">
				 		<label for="name">{{trans('messages.attribute_value')}}</label>
				 		<input type="text" placeholder="{{ trans('messages.name')}}" id="value" name="value" class="form-control" required>
				 	</div>
				 	<div class="form-group mb-3 text-right">
				 		<button type="submit" class="btn btn-sm btn-info">{{trans('messages.Save')}}</button>
                         <a href="{{ route('attributes.index') }}" class="btn btn-sm btn-cancel">{{trans('messages.cancel')}}</a>
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
            $.post('{{ route('attribute_value.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', "{{ trans('messages.attribute_value').trans('messages.updated_msg') }}");
                } else {
                    AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong')}}");
                }
            });
        }
    </script>
@endsection


