@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmArticle" name="frmArticle" role="form" action="{{ route('backend.article.update', [$articleInfo->article_id]) }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                	<div class="row">
                		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="article_title" class="required">Tiêu đề</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="article_title" name="article_title" value="{{ old('article_title', $articleInfo->article_title) }}" placeholder="Tiêu đề" data-code="true" data-for="#article_code" data-link="{{ route('backend.utils.createcode') }}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat" data-code="true">Lấy code</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="article_code" class="required">Code</label>
                                <input type="text" class="form-control" id="article_code" name="article_code" value="{{ old('article_code', $articleInfo->article_code) }}" placeholder="Code">
                            </div>
                            <div class="form-group">
                                <label for="article_description" class="required">Tóm tắt</label>
                                <textarea class="form-control" id="article_description" name="article_description" rows="4" placeholder="Tóm tắt">{{ old('article_description', $articleInfo->article_description) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="article_content" class="required">Nội dung</label>
                                <textarea class="form-control" id="article_content" name="article_content" placeholder="Nội dung" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px', 'toolbarName' => 'Content', 'language' => auth('backend')->user()->default_language]) }}">{{ old('article_content', $articleInfo->article_content) }}</textarea>
                            </div>
                		</div>
                	</div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                	@include('backend.article.partials.edit_right', ['type' => 'post', 'arrLanguage' => $arrLanguage, 'language' => $articleInfo->language_id, 'articleSource' => $articleSource])
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.article.index', ['language_id' => $articleInfo->language_id]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Article.init({
        	default_language: "{{ config('app.locale') }}",
        	current_language: "{{ $articleInfo->language_id }}",
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
                article_author: {
                    required: "{{ trans('validation.article.article_author.required') }}",
                    maxlength: "{{ trans('validation.article.article_author.maxlength') }}"
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