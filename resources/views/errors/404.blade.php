@extends('errors.layout')

@section('title') Page not found! @stop

@section('content')
<img src="{{ url_static('error', 'images', 'error-img.png') }}" alt="error" />
<p><span><label>O</label>hh.....</span>You Requested the page that is no longer There.</p>
@stop