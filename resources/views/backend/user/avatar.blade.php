<div class="sidebar-header">Avatar</div>
<div class="sidebar-content sidebar-sm">
    <div id="frmUserAvatar" data-link="{{ route('backend.user.avatar') }}" data-method="put">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="hidden" id="avatar" name="avatar" value="{{ old('avatar', $userInfo->avatar) }}">
                    <div id="fileUploader">Upload</div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" id="uploadInfo">
                    @if (old('avatar', $userInfo->avatar))
                        <img src="{{ image_url(old('avatar', $userInfo->avatar), 'square') }}" alt="{{ old('avatar', $userInfo->avatar) }}" class="img-circle img-thumbnail w90px" />
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="put">
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
<script type="text/javascript">
    User.uploadAvatar({
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
        mediaUrl: "{!! image_url('', 'square') !!}"
    });
</script>