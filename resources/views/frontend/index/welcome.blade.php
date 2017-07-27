<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Welcome you!</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url_static('fe', 'css', 'welcome.css') }}">
		{!! get_config('ga') !!}
    </head>
    <body>
    	<div class="content">
    		<div class="wrap">
    			<div class="content-grid">
    				<p>
    					<img src="{{ url_static('fe', 'images', 'top.png') }}" alt="top">
    				</p>
    			</div>
    			<div class="grid">
    				<p>
    					<img src="{{ url_static('fe', 'images', 'coming.png') }}" alt="coming">
    				</p>
    				<h3>We are Still Working on it.</h3>
    			</div>
    			<div class="clear"></div>
    			<div class="footer">
    				<p class="a">
    					<a href="http://facebook.com/doquyettien" target="_blank"><img src="{{ url_static('fe', 'images', 'facebook.png') }}" alt="facebook"></a>
    					<a href="http://twitter.com/tiendq_it" target="_blank"><img src="{{ url_static('fe', 'images', 'twitter.png') }}" alt="twitter"></a>
    				</p>
    				<p>Copyright 2016 - <a href="mailto:doquyettien@gmail.com">doquyettien@gmail.com</a></p>
    			</div>
    			<div class="clear"></div>
    		</div>
    	</div>
    	<div class="clear"></div>
    	<!-- jQuery -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-2.2.2.min.js') }}"></script>
        <!-- Bootstrap -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap.min.js') }}"></script>
    </body>
</html>