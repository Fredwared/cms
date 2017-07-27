@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmCategory" name="frmCategory" role="form" action="{{ route('backend.article.category.update', [$categoryInfo->category_id]) }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_title" class="required">Tên chuyên mục</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="category_title" name="category_title" value="{{ old('category_title', $categoryInfo->category_title) }}" placeholder="Tên chuyên mục" data-code="true" data-for="#category_code" data-link="{{ route('backend.utils.createcode') }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" data-code="true">Lấy code</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_code" class="required">Code</label>
                            <input type="text" class="form-control" id="category_code" name="category_code" value="{{ old('category_code', $categoryInfo->category_code) }}" placeholder="Code">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="cateparent_id" class="required">Chuyên mục cha</label>
                            <select class="form-control" id="cateparent_id" name="cateparent_id" data-id="{{ old('cateparent_id', $categoryInfo->cateparent_id) }}"></select>
                        </div>
                        @if (count($arrLanguage) > 1)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="language_id" class="required">Ngôn ngữ</label>
                                <select class="form-control" id="language_id" name="language_id" data-linkcategory="{{ route('backend.utils.getcateparent', ['category_type' => $categoryInfo->category_type]) }}" data-linksource="{{ route('backend.utils.getsourcelangmap', ['category_type' => $categoryInfo->category_type]) }}"{!! $categoryInfo->articles->count() > 0 ? ' disabled="disabled"' : '' !!}>
                                    @foreach ($arrLanguage as $lang => $data)
                                        <option value="{{ $lang }}"{!! $lang == old('language_id', $categoryInfo->language_id) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="category_source">Chuyên mục gốc</label>
                                <select class="form-control" id="category_source" name="category_source" data-id="{{ old('category_source', $categorySource->source_item_id) }}"></select>
                            </div>
                        @else
                            <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', $categoryInfo->language_id) }}">
                        @endif
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="status" class="required">{{ trans('common.status.title') }}</label>
                            <select class="form-control" id="status" name="status">
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == old('status', $categoryInfo->status) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="category_showfe" class="required">Hiển thị trên menu</label>
                            <select class="form-control" id="category_showfe" name="category_showfe">
                                <option value="1"{!! old('category_showfe', $categoryInfo->category_showfe) == 1 ? ' selected="selected"' : '' !!}>Có</option>
                                <option value="0"{!! old('category_showfe', $categoryInfo->category_showfe) == 0 ? ' selected="selected"' : '' !!}>Không</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="category_icon">Icon</label>
                            <input type="text" class="form-control" id="category_icon" name="category_icon" value="{{ old('category_icon', $categoryInfo->category_icon) }}" placeholder="Icon">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="category_order">Thứ tự</label>
                            <input type="text" class="form-control" id="category_order" name="category_order" value="{{ old('category_order', $categoryInfo->category_order) }}" placeholder="Thứ tự">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_description">Giới thiệu</label>
                            <textarea class="form-control" rows="3" id="category_description" name="category_description" placeholder="Giới thiệu">{{ old('category_description', $categoryInfo->category_description) }}</textarea>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_seo_title">Tiêu đề trang web</label>
                            <input type="text" class="form-control" id="category_seo_title" name="category_seo_title" value="{{ old('category_seo_title', $categoryInfo->category_seo_title) }}" placeholder="Tiêu đề trang web">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_seo_keywords">Từ khóa</label>
                            <input type="text" class="form-control" id="category_seo_keywords" name="category_seo_keywords" value="{{ old('category_seo_keywords', $categoryInfo->category_seo_keywords) }}" placeholder="Từ khóa">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="category_seo_description">Mô tả</label>
                            <textarea class="form-control" rows="3" id="category_seo_description" name="category_seo_description" placeholder="Mô tả">{{ old('category_seo_description', $categoryInfo->category_seo_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.article.category.index', ['language_id' => $categoryInfo->language_id]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        Backend.createCode();
        Category.init({
            default_language: "{{ config('app.locale') }}",
            current_language: "{{ $categoryInfo->language_id }}",
            messages: {
                category_title: {
                    required: "{{ trans('validation.category.category_title.required') }}",
                    maxlength: "{{ trans('validation.category.category_title.maxlength') }}"
                },
                category_code: {
                    required: "{{ trans('validation.category.category_code.required') }}",
                    code: "{{ trans('validation.category.category_code.code') }}",
                    maxlength: "{{ trans('validation.category.category_code.maxlength') }}"
                },
                cateparent_id: {
                    required: "{{ trans('validation.category.cateparent_id.required') }}"
                },
                language_id: {
                    required: "{{ trans('validation.language.required') }}"
                },
                category_source: {
                    required: "{{ trans('validation.category.category_source.required') }}"
                },
                status: {
                    required: "{{ trans('validation.status.required') }}"
                },
                category_icon: {
                    maxlength: "{{ trans('validation.category.category_icon.maxlength') }}"
                },
                category_order: {
                    number: "{{ trans('validation.category.category_order.number') }}"
                }
            }
        });
    });
</script>
@stop