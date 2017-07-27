@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="error-page">
	<h2 class="headline text-red mt0">403</h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> Oops! You have no permission on this page.</h3>
		<p>Please ask your web's administrator if you have permission or for more information.</p>
	</div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop