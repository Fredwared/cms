@extends('errors.layout')

@section('title') Permission denied! @stop

@section('content')
<img src="{{ url_static('error', 'images', 'error-img.png') }}" alt="error" />
<p><span><label>O</label>hh.....</span>You have no permission on this page.</p>
@stop