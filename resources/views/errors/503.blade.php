@extends('errors.layout')

@section('title') Server error! @stop

@section('content')
    <img src="{{ url_static('error', 'images', 'error-img.png') }}" alt="error" />
    <p><span><label>O</label>hh.....</span>Looks like something went wrong!.</p>
    <p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing.</p>
@stop
