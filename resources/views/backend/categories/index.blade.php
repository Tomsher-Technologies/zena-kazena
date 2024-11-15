@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{trans('messages.all') . ' '.trans('messages.categories')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <span>{{trans('messages.add_new') . ' '.trans('messages.category')}}</span>
            </a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6 mr-4">{{ trans('messages.categories') }}</h5>
        
        <form class="" id="sort_categories" action="" method="GET" style="width: 100%">

            <div class="row gutters-5">
                <div class="col-md-4">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true"
                        name="catgeory" id="" data-selected={{ $catgeory }}>
                        <option value="0">All</option>
                        @foreach (getAllCategories()->where('parent_id', 0) as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @if ($item->child)
                                @foreach ($item->child as $cat)
                                    @include('backend.categories.menu_child_category', [
                                        'category' => $cat,
                                        'selected_id' => 0,
                                    ])
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ trans('messages.type_name_enter') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-info" type="submit">Filter</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-warning">Reset</a>
                </div>
            </div>

        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th >#</th>
                    <th>{{trans('messages.name')}}</th>
                    <th >{{ trans('messages.parent').' '.trans('messages.category') }}</th>
                    <th >{{trans('messages.icon')}}</th>
                    <th >{{trans('messages.status')}}</th>
                    <th width="10%" class="text-right">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ ($key+1) + ($categories->currentPage() - 1)*$categories->perPage() }}</td>
                        <td>{{ $category->getTranslation('name') }}</td>
                        <td>
                            @php
                                $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                            @endphp
                            @if ($parent != null)
                                {{ $parent->getTranslation('name') }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if ($category->getTranslation('icon') != null)
                                <span class="avatar avatar-square avatar-xs">
                                    <img src="{{ uploaded_asset($category->getTranslation('icon')) }}" alt="{{trans('messages.icon')}}">
                                </span>
                            @else
                                —
                            @endif
                        </td>
                       
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="update_status(this)" value="{{ $category->id }}" <?php if($category->is_active == 1) echo "checked";?>>
                                <span></span>
                            </label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('categories.edit', ['id'=>$category->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ trans('messages.edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $categories->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection


@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('categories.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ trans('messages.category').' '. trans('messages.status').' '. trans('messages.updated_msg') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ trans('messages.something_went_wrong') }}');
                }
            });
        }
    </script>
@endsection
