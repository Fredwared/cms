<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
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
        <link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
        <!-- Admin LTE -->
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'adminlte.css') }}">
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'skin-blue.min.css') }}">
        <link rel="stylesheet" href="{{ url_static('be', 'css', 'backend.css') }}">
    </head>
    <body class="skin-blue">
        <div class="ckeditor-wrapper">
            <div class="panel panel-info mb0">
                <div class="panel-body bg-info">
                    <div id="media_message" class="alert alert-danger pl30 pr30" style="display: none;"></div>
                    <div class="row">
                        <div id="media_form" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <form method="get" action="{{ route('backend.media.' . $typeName . '.index') }}" id="frmSearch" name="frmSearch">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 form-group">
                                        <select class="form-control r04" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $type]) }}" data-placeholder="Chọn nhãn" data-fields="label_name|label_name" id="label" name="label[]" multiple="multiple">
                                            @foreach (array_filter($label) as $media_label)
                                                <option value="{{ $media_label }}" selected="selected">{{ $media_label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 form-group">
                                        <div class="input-group date" id="date_from">
                                            <input type="text" class="form-control r04" name="date_from" value="{{ $date_from }}" placeholder="Từ ngày" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 form-group">
                                        <div class="input-group date" id="date_to">
                                            <input type="text" class="form-control r04" name="date_to" value="{{ $date_to }}" placeholder="Đến ngày" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="media_data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <div class="panel panel-info mb0">
                                <div class="panel-body">
                                    <div id="media_content_header">
                                        @if (check_permission('image', 'insert'))
                                            <div class="mt15">
                                                <div id="fileUploader" data-config="{{ json_encode($upload_config) }}">Upload</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div id="media_content" class="mt15">
                                        @include('backend.media.' . $typeName . '.index')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div><!-- ./wrapper -->
        <!-- jQuery -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-2.2.2.min.js') }}"></script>
        @if ($device_env != 3)
        	<script type="text/javascript" src="{{ url_static('3rd', 'js', 'hammer.min.js') }}"></script>
        	<script type="text/javascript" src="{{ url_static('3rd', 'js', 'hammer-time.min.js') }}"></script>
        	<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.hammer.js') }}"></script>
        @endif
        <!-- Bootstrap -->
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootbox.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'cookie.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.scrolltotop.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
        <script type="text/javascript" src="{{ url_static('be', 'js', 'backend.js') }}"></script>
        <script type="text/javascript">
        	Backend.url = {
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
        	Backend.device_env = {!! $device_env !!};
        	Backend.arrLanguage = {!! json_encode(config('cms.backend.languages')) !!};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                Media.CKEditor = {};
                Media.CKEditor.funcNum = {{ $CKEditorFuncNum }};

                if (window.parent && window.parent.CKEDITOR)
                    Media.CKEditor.object = window.parent.CKEDITOR;
                else if (window.opener && window.opener.CKEDITOR) {
                    Media.CKEditor.object = window.opener.CKEDITOR;
                    Media.CKEditor.callBack = true;
                } else {
                    Media.CKEditor = null;
                }

                Media.init({
                    type: {{ $type }},
                    modal: 1,
                    multi: "{{ $multi }}",
                    source: "{{ $source }}",
                    link_menu: "{{ route('backend.media.menu', [0]) }}",
                    messages: {
                        delete: "{{ trans('common.messages.media.' . $typeName . '_delete') }}"
                    }
                });
            });
        </script>
    </body>
</html>