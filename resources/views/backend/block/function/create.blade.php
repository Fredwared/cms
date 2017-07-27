<div class="sidebar-header">{{ trans('common.layout.create') }}</div>
<div class="sidebar-content sidebar-sm">
    <form id="frmBlockFunction" name="frmBlockFunction" role="form" action="{{ route('backend.block.function.store') }}" method="post">
        <div class="form-group">
            <label for="function_name" class="required">Tên function</label>
            <input type="text" class="form-control" id="function_name" name="function_name" value="{{ old('function_name') }}" placeholder="Tên function">
        </div>
        <div class="form-group">
            <label for="function_params" class="required">Parameters</label>
            <textarea class="form-control" id="function_params" name="function_params" rows="3">{{ old('function_params') }}</textarea>
            <p class="help-block">Chú ý: Parameters phải có dạng json như sau.</p>
            <p class="help-block">{"class":"[CLASS_NAME]","function":"[FUNCTION_NAME]","params":{"param_1":param_1_value,"param_2":param_2_value}}</p>
        </div>
        <div class="form-group">
            <label for="function_type">Áp dụng cho</label>
            <select class="form-control" id="function_type" name="function_type">
                @foreach (config('cms.backend.block.function.type') as $type)
                    <option value="{{ $type }}"{!! $type == old('function_type') ? ' selected="selected"' : '' !!}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>
<script type="text/javascript">
    Block.initFunction({
        function_name: {
            required: "{{ trans('validation.block.function.function_name.required') }}",
            maxlength: "{{ trans('validation.block.function.function_name.maxlength') }}"
        },
        function_params: {
            required: "{{ trans('validation.block.function.function_params.required') }}"
        }
    });
</script>