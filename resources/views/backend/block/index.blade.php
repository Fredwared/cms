@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
        Block.init();
	});
</script>
@stop