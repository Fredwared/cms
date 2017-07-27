@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmUserProfile" name="frmUserProfile" role="form" action="{{ route('backend.user.profile') }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <label for="fullname" class="required">Họ và tên</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $userInfo->fullname) }}" placeholder="Họ và tên">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <label for="email" class="required">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $userInfo->email) }}" placeholder="Email">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $userInfo->address) }}" placeholder="Địa chỉ">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label for="phone">Điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $userInfo->phone) }}" maxlength="20" placeholder="Điện thoại">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label for="email">Giới tính</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="1"{!! old('gender', $userInfo->gender) == 1 ? ' selected="selected"' : '' !!}>Nam</option>
                        <option value="2"{!! old('gender', $userInfo->gender) == 2 ? ' selected="selected"' : '' !!}>Nữ</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label for="default_language">Ngôn ngữ</label>
                    <select class="form-control" id="default_language" name="default_language">
                        @foreach (config('cms.backend.languages') as $key => $lang)
                            <option value="{{ $key }}"{!! old('default_language', $userInfo->default_language) == $key ? ' selected="selected"' : '' !!}>{{ $lang['native'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label for="stay_after_save">Sau khi lưu</label>
                    <select class="form-control" id="action_after_save" name="action_after_save">
                        <option value="list"{!! old('action_after_save', $userInfo->action_after_save) == 'list' ? ' selected="selected"' : '' !!}>Về trang danh sách</option>
                        <option value="detail"{!! old('action_after_save', $userInfo->action_after_save) == 'detail' ? ' selected="selected"' : '' !!}>Về trang chi tiết</option>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <label for="password">{{ trans('common.auth.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ trans('common.auth.password') }}">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <label for="re_password">{{ trans('common.auth.re_password') }}</label>
                    <input type="password" class="form-control" id="re_password" name="re_password" placeholder="{{ trans('common.auth.re_password') }}">
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="text-center">
                <button type="submit" class="btn btn-primary w200px"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
            </div>
        </div>
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        User.updateProfile({
            fullname: {
                required: "{{ trans('validation.user.fullname.required') }}",
                maxlength: "{{ trans('validation.user.fullname.maxlength') }}"
            },
            email: {
                required: "{{ trans('validation.user.email.required') }}",
                email: "{{ trans('validation.user.email.email') }}",
                maxlength: "{{ trans('validation.user.email.maxlength') }}"
            },
            address: {
                maxlength: "{{ trans('validation.user.address.maxlength') }}"
            },
            phone: {
                number: "{{ trans('validation.user.phone.number') }}",
                maxlength: "{{ trans('validation.user.phone.maxlength') }}"
            },
            password: {
                required: "{{ trans('validation.user.password.required') }}",
                rangelength: "{{ trans('validation.user.password.rangelength') }}"
            },
            re_password: {
                required: "{{ trans('validation.user.re_password.required') }}",
                equalTo: "{{ trans('validation.user.re_password.equalTo') }}"
            }
        });

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
    });
</script>
@stop