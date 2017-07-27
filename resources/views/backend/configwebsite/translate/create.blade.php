<div class="sidebar-header">{{ trans('common.layout.create') }}</div>
<div class="sidebar-content sidebar-md">
    <form id="frmTranslate" name="frmTranslate" role="form" action="{{ route('backend.configwebsite.translate.store') }}" method="post">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                <label for="translate_code" class="required">Code</label>
                <input type="text" class="form-control" id="translate_code" name="translate_code" value="{{ old('translate_code') }}" placeholder="Code">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                <label for="translate_code" class="required">Chế độ</label>
                <select class="form-control" id="translate_mode" name="translate_mode">
                    <option value="text">Text</option>
                    <option value="editor">Editor</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                <label for="status" class="required">{{ trans('common.status.title') }}</label>
                <select class="form-control" id="status" name="status">
                    @foreach (config('cms.backend.status') as $name => $value)
                        <option value="{{ $value }}"{!! $value == old('status', config('cms.backend.status.active')) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <label for="translate_content" class="required">Nội dung</label>
        @foreach (config('laravellocalization.supportedLocales') as $language => $data)
            <div class="form-group has-feedback">
                <textarea class="form-control" rows="5" id="translate_content_{!! $language !!}" name="translate_content[{!! $language !!}]" placeholder="{{ $data['native'] }}" data-editor="{{ json_encode(['width' => '99%', 'height' => '150px', 'toolbarName' => 'Description', 'language' => session('backend-locale')]) }}">{{ old('translate_content.' . $language) }}</textarea>
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
    Translate.arrLanguage = {!! json_encode(config('laravellocalization.supportedLocales')) !!};
    Translate.validate({
        translate_code: {
            required: "{{ trans('validation.translate.translate_code.required') }}",
            maxlength: "{{ trans('validation.translate.translate_code.maxlength') }}"
        },
        translate_mode: {
            required: "{{ trans('validation.translate.translate_mode.required') }}"
        },
        translate_content: {
            required: "{{ trans('validation.translate.translate_content.required') }}",
            maxlength: "{{ trans('validation.translate.translate_content.maxlength') }}"
        },
        status: {
            required: "{{ trans('validation.status.required') }}"
        }
    });
</script>