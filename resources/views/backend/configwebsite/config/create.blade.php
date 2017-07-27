<div class="sidebar-header">{{ trans('common.layout.create') }}</div>
<div class="sidebar-content sidebar-sm">
    <form id="frmConfig" name="frmConfig" role="form" action="{{ route('backend.configwebsite.config.store') }}" method="post">
        <div class="form-group">
            <label for="field_name" class="required">Tên cấu hình</label>
            <input type="text" class="form-control" id="field_name" name="field_name" value="{{ old('field_name') }}" placeholder="Tên cấu hình">
        </div>
        <label for="field_value" class="required">Giá trị</label>
        @foreach (config('laravellocalization.supportedLocales') as $language => $data)
            <div class="form-group has-feedback">
                <textarea class="form-control" rows="5" id="field_value_{!! $language !!}" name="field_value[{!! $language !!}]" placeholder="{{ $data['native'] }}">{{ old('field_value.' . $language) }}</textarea>
                <span class="flag-icon flag-icon-background flag-icon-{!! $data['flag'] !!} form-control-feedback flag-icon-embed" style="right: 15px;"></span>
            </div>
        @endforeach
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>
<script type="text/javascript">
    Config.arrLanguage = {!! json_encode(config('laravellocalization.supportedLocales')) !!};
    Config.validate({
        field_name: {
            required: "{{ trans('validation.config.field_name.required') }}",
            maxlength: "{{ trans('validation.config.field_name.maxlength') }}"
        },
        field_value: {
            required: "{{ trans('validation.config.field_value.required') }}",
            maxlength: "{{ trans('validation.config.field_value.maxlength') }}"
        }
    });
</script>