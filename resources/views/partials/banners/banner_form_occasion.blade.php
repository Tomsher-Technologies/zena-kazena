<div class="form-group row">
    <label class="col-md-3 col-form-label">Link</label>
    <div class="col-md-9">
        <select class="form-control aiz-selectpicker" title="Select an option" name="link_ref_id" id="link_ref_id" data-live-search="true" required>
            @foreach ($occasions as $occasion)
                <option {{ $old_data == $occasion->id ? 'selected' : '' }} value="{{ $occasion->id }}">
                    {{ $occasion->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<script>
    $('#link_ref_id').selectpicker({
        size: 5,
        virtualScroll: false
    });
</script>