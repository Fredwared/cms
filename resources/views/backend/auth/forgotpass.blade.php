@extends('backend.layouts.login')

@section('css')
<!-- css link here -->
@stop

@section('content')
<form action="{{ route('backend.auth.forgotpass') }}" method="post">
	<div class="form-group has-feedback">
		<input type="email" class="form-control" placeholder="{{ trans('common.auth.email') }}" id="email" name="email" value="{{ old('email') }}" />
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	</div>
	<div class="form-group form-inline">
		<input type="text" class="form-control" placeholder="Captcha" id="captcha" name="captcha" />
		<img src="{!! captcha_src('auth') !!}" id="captcha_image">
		<button type="button" class="btn btn-primary" id="refresh_captcha"><i class="fa fa-refresh"></i></button>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-unlock"></i> {{ trans('common.auth.resetpass') }}</button>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="put">
	</div>
</form>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
	$(document).ready(function() {
		Backend.loadCaptcha("{{ route('getcaptcha', ['auth']) }}");
	});
</script>
@stop