@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmSlide" name="frmSlide" role="form" action="{{ route('backend.configwebsite.slide.store') }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        @if (count($arrLanguage) > 1)
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
                                <label for="language_id">Ngôn ngữ</label>
                                <select class="form-control" id="language_id" name="language_id">
                                    @foreach ($arrLanguage as $lang => $data)
                                        <option value="{{ $lang }}"{!! $lang == old('language_id', $language) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', $language) }}">
                        @endif
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
                            <label for="slide_type">Loại slide</label>
                            <select class="form-control" id="slide_type" name="slide_type">
                                @foreach (config('cms.backend.slide.type') as $value)
                                    <option value="{{ $value }}"{!! $value == old('slide_type', $type) ? ' selected="selected"' : '' !!}>{{ trans('common.slide.type.' . $value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
                            <label for="status">{{ trans('common.status.title') }}</label>
                            <select class="form-control" id="status" name="status">
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == old('status', config('cms.backend.status.active')) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Hình ảnh</h3>
                        </div>
                        <div class="box-body bg-info">
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
                                    <div id="slide_panel" class="row">
                                        @if (old('slide_image'))
                                            @foreach (old('slide_image') as $id => $image)
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="position: relative;">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <img src="{{ old('slide_image.' . $id) }}" class="img-responsive">
                                                            <input type="hidden" class="form-control" id="slide_image_{{ $id }}" name="slide_image[{{ $id }}]" value="{{ old('slide_image.' . $id) }}">
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="slide_title_{{ $id }}" name="slide_title[{{ $id }}]" value="{{ old('slide_title.' . $id) }}" placeholder="Title">
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea class="form-control" rows="3" id="slide_description_{{ $id }}" name="slide_description[{{ $id }}]" placeholder="Description">{{ old('slide_description.' . $id) }}</textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 form-group">
                                                                    <input type="text" class="form-control mb05" id="slide_link_{{ $id }}" name="slide_link[{{ $id }}]" value="{{ old('slide_link.' . $id) }}" placeholder="Link">
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
                                                                    <select class="form-control" id="slide_target_{{ $id }}" name="slide_target[{{ $id }}]">
                                                                        <option value="_blank"{!! old('slide_target.' . $id) == '_blank' ? ' selected="selected"' : '' !!}>Blank</option>
                                                                        <option value="_parent"{!! old('slide_target.' . $id) == '_parent' ? ' selected="selected"' : '' !!}>Parent</option>
                                                                        <option value="_self"{!! old('slide_target.' . $id) == '_self' ? ' selected="selected"' : '' !!}>Self</option>
                                                                        <option value="_top"{!! old('slide_target.' . $id) == '_top' ? ' selected="selected"' : '' !!}>Top</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-primary r0" style="position: absolute; left: 15px; top: 0;"><i class="fa fa-close"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.configwebsite.slide.index', ['language_id' => $language]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
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