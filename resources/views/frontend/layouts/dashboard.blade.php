<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="index,follow" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'bootstrap.min.css') }}">
        @yield('css')
        {!! get_config('ga') !!}
    </head>
    <body>
        <div class="wrapper">
            @yield('content')
        </div><!-- ./wrapper -->
        <!-- jQuery -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-2.2.2.min.js') }}"></script>
        <!-- Bootstrap -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'common.js') }}"></script>
        <script type="text/javascript">
            Common.url = {
                root: "{{ config('app.url') }}",
                static: {
                    source: {
                        css: "{{ url_static('3rd', 'css') }}",
                        images: "{{ url_static('3rd', 'images') }}",
                        js: "{{ url_static('3rd', 'js') }}"
                    },
                    frontend: {
                        css: "{{ url_static('fe', 'css') }}",
                        images: "{{ url_static('fe', 'images') }}",
                        js: "{{ url_static('fe', 'js') }}"
                    }
                }
            };
            Common.device_env = {!! $device_env !!};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @yield('javascript')
    </body>
</html>