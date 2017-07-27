<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <body class="hold-transition skin-blue sidebar-mini fixed">
        <?php
        $avatar = !empty(auth('backend')->user()->avatar) ? image_url(auth('backend')->user()->avatar, 'square') : (auth('backend')->user()->gender == 1 ? url_static('be', 'images', 'avatar_male.png') : url_static('be', 'images', 'avatar_female.png'));
        ?>
        <div class="wrapper">
          	<header class="main-header">
                <!-- Logo -->
            	<a href="{{ route('backend.index') }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
              		<span class="logo-mini">CMS</span>
                    <!-- logo for regular state and mobile devices -->
              		<span class="logo-lg"><b>Admin</b> CMS</span>
        		</a>
                <!-- Header Navbar: style can be found in header.less -->
            	<nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
              		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                		<span class="sr-only">Toggle navigation</span>
              		</a>
              		<div class="navbar-custom-menu">
                    	<ul class="nav navbar-nav">
                            <!-- User Account: style can be found in dropdown.less -->
                      		<li class="dropdown user user-menu">
                        		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              		<img src="{!! $avatar !!}" class="user-image" alt="User Image">
                              		<span class="hidden-xs">{{ auth('backend')->user()->fullname }}</span>
                            	</a>
                            	<ul class="dropdown-menu">
                                    <li class="user-header">
                                        <img src="{!! $avatar !!}" class="img-circle btn-show-sidebar" data-link="{{ route('backend.user.avatar') }}" alt="User Image">
                                        <p>{{ auth('backend')->user()->fullname }}</p>
                                    </li>
                              		<li class="user-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
                                                <a href="{{ route('backend.user.profile') }}">{{ trans('common.layout.userinfo') }}</a>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                                <a href="{{ route('backend.auth.logout') }}"><i class="glyphicon glyphicon-log-out"></i> {{ trans('common.layout.logout') }}</a>
                                            </div>
                                        </div>
                              		</li>
                            	</ul>
                      		</li>
        				</ul>
          			</div>
    			</nav>
			</header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
            	<section class="sidebar">
                    <!-- Sidebar user panel -->
              		<div class="user-panel">
                		<div class="pull-left image btn-show-sidebar" data-link="{{ route('backend.user.avatar') }}">
                            <img src="{!! $avatar !!}" class="img-circle" alt="User Image">
                		</div>
                		<div class="pull-left info">
                  			<p>{{ auth('backend')->user()->fullname }}</p>
                		</div>
              		</div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
              		@include('backend.partials.sidebar')
            	</section>
                <!-- /.sidebar -->
          	</aside>
          	<!-- Content Wrapper. Contains page content -->
          	<div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                	@include('backend.partials.content_header')
                </section>
                <!-- Main content -->
                <section class="content">
                	@include('backend.partials.message')
                    @yield('content')
                </section><!-- /.content -->
          	</div><!-- /.content-wrapper -->
          	@include('backend.partials.footer')
            <!-- Control sidebar -->
            <aside class="sidebar-option bdr-bc2 bdr-lc2">
                <span class="icon-loading"><i class="fa fa-spinner fa-spin fa-2x"></i></span>
                <a href="#" class="icon-close"><i class="fa fa-close fa-2x"></i></a>
                <div class="sidebar-body"></div>
            </aside><!-- /.Control sidebar -->
    	</div><!-- ./wrapper -->
        <div id="scrolltop">
            <a href="#">
                <img src="{{ url_static('be', 'images', 'icon_top.png') }}" />
            </a>
        </div>
        <!-- Bootstrap -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootbox.min.js') }}"></script>
     	<script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.serialize-hash.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'cookie.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'common.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('be', 'js', 'backend.js') }}"></script>
        <script type="text/javascript">
            Common.url = {
        	    root: "{{ config('app.url') }}",
                static: {
                    source: {
                        css: "{{ url_static('3rd', 'css') }}",
                        images: "{{ url_static('3rd', 'images') }}",
                        js: "{{ url_static('3rd', 'js') }}"
                    },
                    backend: {
                        css: "{{ url_static('be', 'css') }}",
                        images: "{{ url_static('be', 'images') }}",
                        js: "{{ url_static('be', 'js') }}"
                    },
                    frontend: {
                        css: "{{ url_static('fe', 'css') }}",
                        images: "{{ url_static('fe', 'images') }}",
                        js: "{{ url_static('fe', 'js') }}"
                    }
                }
        	};
            Common.device_env = {!! $device_env !!};
            Common.arrLanguage = {!! json_encode(config('cms.backend.languages')) !!};
        	
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
        <!-- Backend -->
        @yield('javascript')
    </body>
</html>