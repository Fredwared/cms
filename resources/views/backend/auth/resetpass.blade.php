@extends('backend.layouts.login')

@section('css')
<!-- css link here -->
@stop

@section('content')
<form action="{{ route('backend.auth.resetpass', [$key]) }}" method="post" id="frmResetPass" name="frmResetPass">
	<div class="form-group has-feedback">
		<input type="password" class="form-control" placeholder="{{ trans('common.auth.password') }}" name="password" id="password" value="{{ old('password') }}" />
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	<div class="form-group has-feedback">
		<input type="password" class="form-control" placeholder="{{ trans('common.auth.re_password') }}" name="re_password" id="re_password" value="{{ old('re_password') }}" />
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('common.layout.changepass') }}</button>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="_method" value="put">
	</div>
</form>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
	$(document).ready(function() {
    	Auth.resetPass({
    		password: {
                required: "{{ trans('validation.user.password.required') }}",
                rangelength: "{{ trans('validation.user.password.rangelength') }}"
            },
            re_password: {
                required: "{{ trans('validation.user.re_password.required') }}",
                equalTo: "{{ trans('validation.user.re_password.equalTo') }}"
            }
    	});
	});
</script>
@stop