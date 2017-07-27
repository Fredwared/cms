@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.article.topic.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <div class="row">
                                @if (count($arrLanguage) > 1)
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">
                                        <label class="mr05">Ngôn ngữ</label>
                                        <select class="form-control r04" name="language_id">
                                            @foreach ($arrLanguage as $lang => $data)
                                                <option value="{{ $lang }}"{!! $lang == $language_id ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                                    <label class="mr05">Chuyên mục</label>
                                    <select class="form-control r04" name="category_id">
                                        <option value="">Tất cả</option>
                                        @foreach ($arrListCategory as $category)
                                            <option value="{{ $category->category_id }}"{!! $category->category_id == $category_id ? ' selected="selected"' : '' !!}>{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                                    <label class="mr05">{{ trans('common.status.title') }}</label>
                                    <select class="form-control r04" name="status">
                                        <option value="">{{ trans('common.status.all') }}</option>
                                        @foreach (config('cms.backend.status') as $name => $value) {
                                            <option value="{{ $value }}"{!! $value == $status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-6 col-xs-6 form-group">
                                    <label>Tiêu đề</label>
                                    <input type="text" class="form-control r04" name="title" value="{{ $title }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('topic', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.article.topic.create', [$language_id]) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListTopic, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th class="text-right">Số lượng bài viết</th>
                    <th class="w150px text-center">Ngày cập nhật</th>
                    @if (count($arrLanguage) > 1)
                        <th class="w{!! count($arrLanguage) * 40 !!}px text-center">{!! language_switcher() !!}</th>
                    @endif
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($arrListTopic->count() > 0)
                @foreach ($arrListTopic as $topic)
                    <tr>
                        <td>
                            @if (check_permission('topic', 'update'))
                                <a href="{!! route('backend.article.topic.edit', [$topic->topic_id]) !!}">
                                    {{ $topic->topic_title }}
                                </a>
                            @else
                                {{ $topic->topic_title }}
                            @endif
                        </td>
                        <td>{{ $topic->category->category_title }}</td>
                        <td class="text-right">{{ $topic->articles->count() }}</td>
                        <td class="text-center">{{ format_date($topic->updated_at) }}</td>
                        @if (count($arrLanguage) > 1)
                            <td class="text-center">
                                <?php $arrTranslations = translation_item($topic->topic_id, 'topic'); ?>
                                @if (!empty($arrTranslations))
                                    @foreach ($arrTranslations as $language => $translation)
                                        @if (empty($translation) && $language != $topic->language_id)
                                            @if (check_permission('topic', 'insert'))
                                                @if ($topic->language_id != config('app.locale'))
                                                    <a href="{!! route('backend.article.topic.create', [$language, 'item_id' => $topic->topic_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @else
                                                    <a href="{!! route('backend.article.topic.create', [$language, 'source_item_id' => $topic->topic_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @endif
                                            @endif
                                        @else
                                            @if (check_permission('topic', 'update'))
                                                <a href="{!! route('backend.article.topic.edit', [$translation->topic_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endif
                        <td class="text-center">
                            <span class="label label-{!! $topic->status == 1 ? 'success' : 'danger' !!}" data-forstatus="{{ $topic->topic_id }}">{!! trans('common.status.' . $topic->status) !!}</span>
                        </td>
                        <td class="text-center">
                            @if (check_permission('topic', 'update'))
                                <a href="{!! route('backend.article.topic.edit', [$topic->topic_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('topic', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.article.delete') }}" href="{!! route('backend.article.topic.destroy', [$topic->topic_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'topic', 'model_id' => $topic->topic_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    @if (count($arrLanguage) > 1)
                        <td colspan="8" class="text-center">{!! language_switcher() !!}</td>
                    @else
                        <td colspan="7" class="text-center">{{ trans('common.messages.nodata') }}</td>
                    @endif
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListTopic, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop