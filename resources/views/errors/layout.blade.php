<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
		<title>@yield('title')</title>
		<link rel="stylesheet" href="{{ url_static('error', 'css', 'style.css') }}">
	</head>
	<body>
		<!--start-wrap--->
		<div class="wrap">
			<!---start-header---->
				<div class="header">
					<div class="logo">
						<h1><a href="{{ route('homepage') }}">Ohh</a></h1>
					</div>
				</div>
			<!---End-header---->
			<!--start-content------>
			<div class="content">
				@yield('content')
				<a href="{{ route('homepage') }}">Back To Home</a>
				<div class="copy-right">
					<p>&#169 All rights Reserved | Designed by <a href="http://w3layouts.com/" target="_blank">W3Layouts</a></p>
				</div>
   			</div>
			<!--End-Cotent------>
		</div>
		<!--End-wrap--->
	</body>
</html>