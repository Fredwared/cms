@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmPage" name="frmPage" role="form" action="{{ route('backend.configwebsite.page.update', [$pageInfo->page_id]) }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_title" class="required">Tên trang</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="page_title" name="page_title" value="{{ old('page_title', $pageInfo->page_title) }}" placeholder="Tên trang" data-code="true" data-for="#page_code" data-link="{{ route('backend.utils.createcode') }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" data-code="true">Lấy code</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_code" class="required">Code</label>
                            <input type="text" class="form-control" id="page_code" name="page_code" value="{{ old('page_code', $pageInfo->page_code) }}" placeholder="Code">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="parent_id" class="required">Trang cha</label>
                            <select class="form-control" id="parent_id" name="parent_id" data-id="{{ old('parent_id', 0) }}"></select>
                        </div>
                        @if (count($arrLanguage) > 1)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="language_id" class="required">Ngôn ngữ</label>
                                <select class="form-control" id="language_id" name="language_id" data-linkpage="{{ route('backend.utils.getpageparent') }}" data-linksource="{{ route('backend.utils.getsourcelangmap') }}">
                                    @foreach ($arrLanguage as $lang => $data)
                                        <option value="{{ $lang }}"{!! $lang == old('language_id', $pageInfo->language_id) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="page_source">Trang gốc</label>
                                <select class="form-control" id="page_source" name="page_source" data-id="{{ old('page_source', $pageSource->source_item_id) }}"></select>
                            </div>
                        @else
                            <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', $pageInfo->language_id) }}">
                        @endif
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="status" class="required">{{ trans('common.status.title') }}</label>
                            <select class="form-control" id="status" name="status">
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == old('status', $pageInfo->status) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_seo_title">Tiêu đề trang web</label>
                            <input type="text" class="form-control" id="page_seo_title" name="page_seo_title" value="{{ old('page_seo_title', $pageInfo->page_seo_title) }}" placeholder="Tiêu đề trang web">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_seo_keywords">Từ khóa</label>
                            <input type="text" class="form-control" id="page_seo_keywords" name="page_seo_keywords" value="{{ old('page_seo_keywords', $pageInfo->page_seo_keywords) }}" placeholder="Từ khóa">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_seo_description">Mô tả</label>
                            <textarea class="form-control" rows="3" id="page_seo_description" name="page_seo_description" placeholder="Mô tả">{{ old('page_seo_description', $pageInfo->page_seo_description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="page_content" class="required">Nội dung</label>
                            <textarea class="form-control" rows="3" id="page_content" name="page_content" placeholder="Nội dung" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px', 'toolbarName' => 'Content', 'language' => auth('backend')->user()->default_language]) }}">{{ old('page_content', $pageInfo->page_content) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.configwebsite.page.index', ['language_id' => $pageInfo->language_id]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
            </div>
        </div>
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Backend.createCode();
        Page.init({
            default_language: "{{ config('app.locale') }}",
            current_language: "{{ $pageInfo->language_id }}",
            messages: {
                page_title: {
                    required: "{{ trans('validation.page.page_title.required') }}",
                    maxlength: "{{ trans('validation.page.page_title.maxlength') }}"
                },
                page_code: {
                    required: "{{ trans('validation.page.page_code.required') }}",
                    code: "{{ trans('validation.page.page_code.code') }}",
                    maxlength: "{{ trans('validation.page.page_code.maxlength') }}"
                },
                parent_id: {
                    required: "{{ trans('validation.page.parent_id.required') }}"
                },
                language_id: {
                    required: "{{ trans('validation.language.required') }}"
                },
                page_source: {
                    required: "{{ trans('validation.page.page_source.required') }}"
                },
                status: {
                    required: "{{ trans('validation.status.required') }}"
                },
                page_content: {
                    required: "{{ trans('validation.page.page_content.required') }}"
                }
            }
        });
    });
</script>
@stop