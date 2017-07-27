@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmUser" name="frmUser" role="form" action="{{ route('backend.user.store') }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label for="fullname" class="required">Họ và tên</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname') }}" placeholder="Họ và tên">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-12 form-group">
                    <label for="email" class="required">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6 form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Địa chỉ">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                    <label for="phone">Điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" maxlength="20" placeholder="Điện thoại">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                    <label for="email">Giới tính</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="1"{!! old('gender', 1) == 1 ? ' selected="selected"' : '' !!}>Nam</option>
                        <option value="2"{!! old('gender', 1) == 2 ? ' selected="selected"' : '' !!}>Nữ</option>
                    </select>
                </div>
                <?php $cols = count(config('cms.backend.languages')) == 1 ? 4 : 3; ?>
                <div class="col-lg-{!! $cols !!} col-md-{!! $cols !!} col-sm-{!! $cols !!} col-xs-6 form-group">
                    <label for="landing_page">Landing page</label>
                    <select class="form-control" id="landing_page" name="landing_page">
    					<option value="backend.index">{{ trans('common.layout.home_title') }}</option>
    					@foreach ($arrListMenu as $menu)
    						@if ($menu->childs->count() > 0)
    							<optgroup label="{{ trans_by_locale($menu->menu_name, session('backend-locale')) }}">
    								@foreach ($menu->childs as $child)
    									<option value="{{ $child->route_name }}"{!! $child->route_name == old('landing_page') ? ' selected="selected"' : '' !!}>
                                        	{{ trans_by_locale($child->menu_name, session('backend-locale')) }}
                                    	</option>
    								@endforeach
    							</optgroup>
    						@else
    							<option value="{{ $menu->route_name }}"{!! $menu->route_name == old('landing_page') ? ' selected="selected"' : '' !!}>
                                	{{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                            	</option>
                        	@endif
    					@endforeach
    				</select>
                </div>
                @if (count(config('cms.backend.languages')) == 1)
                    <input type="hidden" id="default_language" name="default_language" value="{{ old('default_language', config('cms.backend.default_locale')) }}">
                @else
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                        <label for="default_language">Ngôn ngữ mặc định</label>
                        <select class="form-control" id="default_language" name="default_language">
                            @foreach (config('cms.backend.languages') as $key => $lang)
                                <option value="{{ $key }}"{!! old('default_language', config('cms.backend.default_locale')) == $key ? ' selected="selected"' : '' !!}>{{ $lang['native'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-lg-{!! $cols !!} col-md-{!! $cols !!} col-sm-{!! $cols !!} col-xs-6 form-group">
                    <label for="days_password_expired">Ngày hết hạn mật khẩu</label>
                    <input type="text" class="form-control" id="days_password_expired" name="days_password_expired" value="{{ old('days_password_expired', 365) }}" maxlength="3" placeholder="Ngày hết hạn mật khẩu">
                </div>
                <div class="col-lg-{!! $cols !!} col-md-{!! $cols !!} col-sm-{!! $cols !!} col-xs-6 form-group">
                    <label for="status" class="required">{{ trans('common.status.title') }}</label>
                    <select class="form-control" id="status" name="status">
                    	<option value="{{ config('cms.backend.user.status.active') }}"{!! old('status', config('cms.backend.user.status.active')) == config('cms.backend.user.status.active') ? ' selected="selected"' : '' !!}>{{ trans('common.auth.status.active') }}</option>
                        <option value="{{ config('cms.backend.user.status.inactive') }}"{!! old('status', config('cms.backend.user.status.active')) == config('cms.backend.user.status.inactive') ? ' selected="selected"' : '' !!}>{{ trans('common.auth.status.inactive') }}</option>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                    <label for="groups" class="required">Nhóm</label>
                    <select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.group') }}" data-placeholder="Chọn nhóm" data-fields="group_id|group_name" id="groups" name="groups[]" multiple="multiple"></select>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.user.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        User.validate({
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
            group: {
                required: "{{ trans('validation.user.group.required') }}"
            },
            status: {
                required: "{{ trans('validation.status.required') }}"
            }
        });

        Backend.multiSelect();
    });
</script>
@stop