@extends('backend.layouts.login')

@section('css')
<!-- css link here -->
@stop

@section('content')
<form action="{{ route('backend.auth.login.post') }}" method="post" id="frmLogin" name="frmLogin">
	<div class="form-group has-feedback">
		<input type="email" class="form-control" placeholder="{{ trans('common.auth.email') }}" name="email" id="email" value="{{ old('email') }}" />
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	</div>
	<div class="form-group has-feedback">
		<input type="password" class="form-control" placeholder="{{ trans('common.auth.password') }}" name="password" id="password" value="{{ old('password') }}" />
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> {{ trans('common.auth.signin') }}</button>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</div>
	<div class="form-group text-center">
		<a href="{{ route('backend.auth.forgotpass') }}">{{ trans('common.auth.forgotpass') }}</a>
	</div>
</form>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
	$(document).ready(function() {
		Auth.checkLogin({
        	email: {
            	required: "{{ trans('validation.auth.email.required') }}",
            	email: "{{ trans('validation.auth.email.email') }}"
        	},
        	password: {
				required: "{{ trans('validation.auth.password.required') }}"
			}
    	});
	});
</script>
@stop