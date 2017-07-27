@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmArticle" name="frmArticle" role="form" action="{{ route('backend.article.store') }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                	<div class="row">
                		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="article_title" class="required">Tiêu đề</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="article_title" name="article_title" value="{{ old('article_title') }}" placeholder="Tiêu đề" data-code="true" data-for="#article_code" data-link="{{ route('backend.utils.createcode') }}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat" data-code="true">Lấy code</button>
                                        <button type="button" class="btn btn-info btn-flat btn-show-sidebar" data-link="{!! route('backend.utils.crawler.parselink') !!}" title="Lấy bài viết từ nguồn bên ngoài"><i class="fa fa-clipboard"></i></button>
                                    </span>
                                </div>
                                <input type="hidden" name="item_id" value="{{ $item_id }}">
                            </div>
                            <div class="form-group">
                                <label for="article_code" class="required">Code</label>
                                <input type="text" class="form-control" id="article_code" name="article_code" value="{{ old('article_code', ($articleSourceInfo ? $articleSourceInfo->article_code : '')) }}" placeholder="Code">
                            </div>
                            <div class="form-group">
                                <label for="article_description" class="required">Tóm tắt</label>
                                <textarea class="form-control" id="article_description" name="article_description" rows="4" placeholder="Tóm tắt">{{ old('article_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="article_content" class="required">Nội dung</label>
                                <textarea class="form-control" id="article_content" name="article_content" placeholder="Nội dung" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px', 'toolbarName' => 'Content', 'language' => auth('backend')->user()->default_language]) }}">{{ old('article_content', ($articleSourceInfo ? $articleSourceInfo->article_content : '')) }}</textarea>
                            </div>
                		</div>
                	</div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                	@include('backend.article.partials.create_right', ['type' => 'post', 'arrLanguage' => $arrLanguage, 'language' => $language, 'article_source' => $article_source])
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.article.index', ['language_id' => $language]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
    	Article.init({
        	default_language: "{{ config('app.locale') }}",
        	current_language: "{{ $language }}",
        	messages: {
        		article_title: {
                    required: "{{ trans('validation.article.article_title.required') }}",
                    maxlength: "{{ trans('validation.article.article_title.maxlength') }}"
                },
                article_code: {
                    required: "{{ trans('validation.article.article_code.required') }}",
                    code: "{{ trans('validation.article.article_code.code') }}",
                    maxlength: "{{ trans('validation.article.article_code.maxlength') }}"
                },
                article_description: {
                    required: "{{ trans('validation.article.article_description.required') }}",
                    maxlength: "{{ trans('validation.article.article_description.maxlength') }}"
                },
                article_content: {
                    required: "{{ trans('validation.article.article_content.required') }}"
                },
                category_id: {
                	required: "{{ trans('validation.article.category_id.required') }}"
                },
                language_id: {
                	required: "{{ trans('validation.language.required') }}"
                },
                article_source: {
                    required: "{{ trans('validation.article.article_source.required') }}"
                },
                status: {
                    required: "{{ trans('validation.status.required') }}"
                }
        	}
    	});
    });
</script>
@stop