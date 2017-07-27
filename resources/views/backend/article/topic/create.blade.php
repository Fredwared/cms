@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmTopic" name="frmTopic" role="form" action="{{ route('backend.article.topic.store') }}" method="post">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="topic_title" class="required">Tên chủ đề</label>
                            <input type="text" class="form-control" id="topic_title" name="topic_title" value="{{ old('topic_title') }}" placeholder="Tên chủ đề">
                            <input type="hidden" name="item_id" value="{{ $item_id }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="category_id" class="required">Chuyên mục</label>
                            <select class="form-control" id="category_id" name="category_id" data-id="{{ old('category_id', 0) }}"></select>
                        </div>
                        @if (count($arrLanguage) > 1)
                            <div class="col-lg-3 col-md-6 col-sm-4 col-xs-12 form-group">
                                <label for="language_id" class="required">Ngôn ngữ</label>
                                <select class="form-control" id="language_id" name="language_id" data-linkcategory="{{ route('backend.utils.getcategory') }}" data-linksource="{{ route('backend.utils.getsourcelangmap') }}">
                                    @foreach ($arrLanguage as $lang => $data)
                                        <option value="{{ $lang }}"{!! $lang == old('language_id', $language) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="topic_source">Chủ đề gốc</label>
                                <select class="form-control" id="topic_source" name="topic_source" data-id="{{ old('topic_source', $topic_source) }}"></select>
                            </div>
                        @else
                            <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', config('app.locale')) }}">
                        @endif
                        <div class="col-lg-3 col-md-6 col-sm-4 col-xs-6 form-group">
                            <label for="status" class="required">{{ trans('common.status.title') }}</label>
                            <select class="form-control" id="status" name="status">
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == old('status', config('cms.backend.status.active')) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-4 col-xs-6 form-group">
                            <label for="max_article">Số bài viết</label>
                            <select class="form-control" id="max_article" name="max_article">
                                @foreach ([5, 10, 15, 20] as $num)
                                    <option value="{{ $num }}"{!! $num == old('max_article', 5) ? ' selected="selected"' : '' !!}>{{ $num }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="clearfix mb10">
                            <div class="pull-left">
                                <label>Danh sách bài viết</label>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-sm btn-show-sidebar" data-link="{!! route('backend.utils.getarticle', ['language_id' => $language, 'callback' => 'Topic.initArticle();']) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</button>
                            </div>
                        </div>
                        <input type="hidden" id="article_topic" name="article_topic" value="{!! old('article_topic') !!}" />
                        <div class="table-responsive">
                            <table class="table table-hover" id="tblArticleTopic">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Tiêu đề</th>
                                        <th>Chuyên mục</th>
                                        <th class="text-center">Độ ưu tiên</th>
                                        <th class="text-center">Ngày xuất bản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.article.topic.index', ['language_id' => $language]) }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        Topic.init({
            default_language: "{{ config('app.locale') }}",
            current_language: "{{ $language }}",
            messages: {
                topic_title: {
                    required: "{{ trans('validation.topic.topic_title.required') }}",
                    maxlength: "{{ trans('validation.topic.topic_title.maxlength') }}"
                },
                category_id: {
                    required: "{{ trans('validation.topic.category_id.required') }}"
                },
                language_id: {
                    required: "{{ trans('validation.language.required') }}"
                },
                topic_source: {
                    required: "{{ trans('validation.topic.topic_source.required') }}"
                },
                status: {
                    required: "{{ trans('validation.status.required') }}"
                }
            }
        });
    });
</script>
@stop