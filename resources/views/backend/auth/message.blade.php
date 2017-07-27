<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ 'Administration CMS Page' }} @show</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'backend.css') }}">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box" style="max-width: 480px; width: auto;">
            <div class="login-logo">
                <a href="{{ route('backend.index') }}"><b>Administration</b> CMS</a>
            </div>
            <!-- /.login-logo -->
            <div class="box box-{{ Session::get('flash_notification.level') }}">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('common.layout.message') }}</h3>
                </div>
                <div class="box-body">{{ Session::get('flash_notification.message') }}</div>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-2.2.2.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setInterval(function() {
                	window.location.href = "{{ route('backend.auth.login') }}";
                }, 5000);
            });
        </script>
    </body>
</html>
