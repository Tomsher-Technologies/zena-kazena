<div class="card-header mb-2">
    <h3 class="h6">{{trans('messages.add').' '.trans('messages.your_product_base_coupon')}}</h3>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-from-label" for="coupon_code">{{trans('messages.coupon').' '.trans('messages.code')}}</label>
    <div class="col-lg-9">
        <input type="text" placeholder="{{trans('messages.coupon').' '.trans('messages.code')}}" id="coupon_code" name="coupon_code" class="form-control" required>
    </div>
</div>
<div class="product-choose-list">
    <div class="product-choose">
        <div class="form-group row">
            <label class="col-lg-3 col-from-label" for="name">{{trans('messages.product')}}</label>
            <div class="col-lg-9">
                <select name="product_ids[]" class="form-control product_id aiz-selectpicker" data-live-search="true" data-selected-text-format="count" required multiple>
                    @foreach($products as $product)
                        <option value="{{$product->id}}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<br>
<div class="form-group row">
    <label class="col-sm-3 control-label" for="start_date">{{trans('messages.date')}}</label>
    <div class="col-sm-9">
      <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{trans('messages.date')}}">
    </div>
</div>
<div class="form-group row">
   <label class="col-lg-3 col-from-label">{{trans('messages.discount')}}</label>
   <div class="col-lg-7">
      <input type="number" lang="en" min="0" step="0.01" placeholder="{{trans('messages.discount')}}" name="discount" class="form-control" required>
   </div>
   <div class="col-lg-2">
       <select class="form-control aiz-selectpicker" name="discount_type">
           <option value="amount">{{trans('messages.amount')}}</option>
           <option value="percent">{{trans('messages.percent')}}</option>
       </select>
   </div>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        $('.aiz-date-range').daterangepicker();
        AIZ.plugins.bootstrapSelect('refresh');
    });

</script>
