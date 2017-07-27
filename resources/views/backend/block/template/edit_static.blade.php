<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
<div class="sidebar-content sidebar-sm sidebar-scroll">
    <form id="frmBlockTemplate" name="frmBlockTemplate" role="form" action="{{ route('backend.block.template.update', [$templateInfo->template_id]) }}" method="put">
        <div class="form-group">
            <label for="template_name" class="required">Tên template</label>
            <input type="text" class="form-control" id="template_name" name="template_name" value="{{ old('template_name', $templateInfo->template_name) }}" placeholder="Tên template">
        </div>
        <div class="form-group">
            <label for="template_area" class="required">Vị trí</label>
            <select class="form-control" id="template_area" name="template_area">
                <option value="">Chọn vị trí</option>
                @foreach (config('cms.backend.block.area') as $area)
                    <option value="{{ $area }}"{!! $area == old('template_area', $templateInfo->template_area) ? ' selected="selected"' : '' !!}>{{ ucfirst($area) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="template_content" class="required">Nội dung</label>
            <textarea class="form-control" id="template_content" name="template_content" data-editor="{{ json_encode(['width' => '99%', 'height' => '250px', 'toolbarName' => 'Content', 'language' => auth('backend')->user()->default_language]) }}">{{ old('template_content', $templateInfo->template_content) }}</textarea>
        </div>
        <div class="form-group">
            <label for="template_thumbnail" class="required">Hình đại diện</label>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="hidden" id="template_thumbnail" name="template_thumbnail" value="{{ old('template_thumbnail', $templateInfo->template_thumbnail) }}">
                    <div id="fileUploader">Upload</div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" id="uploadInfo">
                    <img src="{{ image_url($templateInfo->template_thumbnail) }}" class="img-responsive" />
                </div>
            </div>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>
<script type="text/javascript">
    Block.initTemplate({
        template_name: {
            required: "{{ trans('validation.block.template.template_name.required') }}",
            maxlength: "{{ trans('validation.block.template.template_name.maxlength') }}"
        },
        template_area: {
            required: "{{ trans('validation.block.template.template_area.required') }}"
        },
        template_view: {
            required: "{{ trans('validation.block.template.template_view.required') }}",
            maxlength: "{{ trans('validation.block.template.template_view.maxlength') }}"
        },
        template_content: {
            required: "{{ trans('validation.block.template.template_content.required') }}"
        },
        template_thumbnail: {
            required: "{{ trans('validation.block.template.template_thumbnail.required') }}"
        }
    });

    Block.uploadImage({
        url: "{{ route('backend.utils.upload') }}",
        uploadPanel: '#uploadInfo',
        maxFileAllowed: 1,
        allowedTypes: "{!! config('cms.backend.media.ext.image') !!}", //seperate with ','
        maxFileSize: "{!! config('cms.backend.media.size.image') !!}", //in byte
        maxFileAllowedErrorStr: "{!! trans('validation.upload.maxfile_error') !!}",
        dragDropStr: "{!! trans('common.messages.media.dragdrop') !!}",
        dragDropErrorStr: "{!! trans('validation.upload.dragdrop_error') !!}",
        uploadErrorStr: "{!! trans('validation.upload.upload_error') !!}",
        extErrorStr: "{!! trans('validation.upload.ext_error') !!}",
        sizeErrorStr: "{!! trans('validation.upload.size_error') !!}",
        mediaUrl: "{!! image_url('', 'medium') !!}"
    });
</script>