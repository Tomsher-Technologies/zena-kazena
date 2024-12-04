@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{trans('messages.attribute_value').' '.trans('messages.information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
                @foreach (\App\Models\Language::all() as $key => $language)
                  <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('edit-attribute-value', ['id'=>$attribute_value->id, 'lang'=> $language->code] ) }}">
                      <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                      <span>{{ $language->name }}</span>
                    </a>
                  </li>
                 @endforeach
              </ul>
          <form class="p-4" action="{{ route('update-attribute-value', $attribute_value->id) }}" method="POST">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="lang" value="{{ $lang }}">
              <input type="hidden" name="attribute_id" value="{{ $attribute_value->attribute_id }}">
              @csrf
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="Attribute Value">
                      {{ trans('messages.attribute_value')}} 
                  </label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{ trans('messages.attribute_value')}}" id="value" name="value" class="form-control" required value="{{ $attribute_value->getTranslatedName($lang) }}">
                  </div>
              </div>
            
              <div class="form-group mb-0 text-right">
                  <button type="submit" class="btn btn-sm btn-info">{{trans('messages.Save')}}</button>
                  <a href="{{route('attributes.show', $attribute_value->attribute_id)}}" class="btn btn-sm btn-cancel">{{trans('messages.cancel')}}</a>
              </div>
            </form>
        </div>
    </div>
</div>

@endsection
