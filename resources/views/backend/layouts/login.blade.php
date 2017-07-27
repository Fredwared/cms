<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@section('title') {{ 'Administration CMS Page' }} @show</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'bootstrap-datepicker.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'font-awesome.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'ionicons.min.css') }}">
        <!-- Flag Icon -->
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'flag-icon.min.css') }}">
        <!-- Admin LTE -->
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'adminlte.css') }}">
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'skin-blue.min.css') }}">
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'backend.css') }}">
        @yield('css')
        <!-- jQuery -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-2.2.2.min.js') }}"></script>
    </head>
    <body class="hold-transition login-page">
    	<div class="login-box">
    		<div class="login-logo">
    			<a href="{{ route('backend.index') }}"><b>Administration</b> CMS</a>
    		</div>
    		<!-- /.login-logo -->
    		<div class="login-box-body">
				@include('backend.partials.message', ['type' => 'fixed'])
    			@yield('content')
    		</div>
    		<!-- /.login-box-body -->
    	</div>
    	<!-- /.login-box -->
        <!-- Bootstrap -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.inputmask.bundle.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'common.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('be', 'js', 'backend.js') }}"></script>
        <script type="text/javascript">
            Common.arrLanguage = {!! json_encode(config('cms.backend.languages')) !!}

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        	$('document').ready(function() {
				@if (Session::has('flash_notification.message'))
                    Backend.showMessage("{{ Session::get('flash_notification.message') }}", {
                		className: "alert-{{ Session::get('flash_notification.level') }}",
						timeout: 5000
                	});
        		@endif
        	});
        </script>
        @yield('javascript')
  	</body>
</html>
