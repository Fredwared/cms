@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmSlide" name="frmSlide" role="form" action="{{ route('backend.configwebsite.slide.update', [$slideInfo->id]) }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="mb15">
                        <button type="button" class="btn btn-md btn-primary btn-show-filemanager" data-link="{{ route('backend.media.image.index', ['modal' => 1, 'multi' => 1, 'source' => 'slide']) }}">
                            <i class="fa fa-picture-o"></i> Chọn hình ảnh
                        </button>
                    </div>
                    <p><strong>Hoặc</strong></p>
                    <div class="mt15">
                        <div id="fileUploader" data-config="{{ json_encode($upload_config) }}">Upload</div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="slide_panel" class="row"></div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.configwebsite.slide.index', ['language_id' => $slideInfo->language_id]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
            </div>
        </div>
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Slide.init({
            language_id: {
                required: "{{ trans('validation.language.required') }}"
            },
            status: {
                required: "{{ trans('validation.status.required') }}"
            },
            slide_type: {
                required: "{{ trans('validation.slide.slide_type.required') }}"
            },
            slide_title: {
                required: "{{ trans('validation.slide.slide_title.required') }}",
                maxlength: "{{ trans('validation.slide.slide_title.maxlength') }}"
            },
            slide_description: {
                maxlength: "{{ trans('validation.slide.slide_description.maxlength') }}"
            },
            slide_link: {
                maxlength: "{{ trans('validation.slide.slide_link.maxlength') }}"
            }
        });
    });
</script>
@stop