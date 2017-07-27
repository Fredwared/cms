@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.article.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                            <label>Tiêu đề</label>
                            <input type="text" class="form-control r04" name="title" value="{{ $title }}">
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 form-group">
                            <label>Tác giả</label>
                            <select class="form-control r04" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.user') }}" data-placeholder="Chọn tác giả" data-fields="id|fullname,email" name="user_id[]" multiple="multiple">
                                @foreach ($arrUser as $user)
                                	<option value="{{ $user->id }}" selected="selected">{{ $user->fullname . ' - ' . $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-group">
                            <label class="mr05">Ngày xuất bản</label>
                            <div class="clearfix">
                                <div class="input-group date pull-left wp48" id="date_from">
                                    <input type="text" class="form-control r04" name="date_from" value="{{ $date_from }}" placeholder="Từ ngày" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <div class="input-group date pull-right wp48" id="date_to">
                                    <input type="text" class="form-control r04" name="date_to" value="{{ $date_to }}" placeholder="Đến ngày" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
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
        @if (check_permission('article', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.article.create', [$language_id]) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListArticle, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th class="text-center">Độ ưu tiên</th>
                    <th class="w150px text-center">Ngày xuất bản</th>
                    <th class="text-center">Tác giả</th>
                    @if (count($arrLanguage) > 1)
                        <th class="w{!! count($arrLanguage) * 40 !!}px text-center">{!! language_switcher() !!}</th>
                    @endif
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($arrListArticle->count() > 0)
                @foreach ($arrListArticle as $article)
                    <tr>
                        <td>
                            @if (check_permission('article', 'update'))
                                <a href="{!! route('backend.article.edit', [$article->article_id]) !!}">
                                	{{ $article->article_title }}
                            	</a>
                            @else
                                {{ $article->article_title }}
                            @endif
                        </td>
                        <td>{{ $article->category->category_title }}</td>
                        <td class="text-center">{{ trans('common.article.priority.' . $article->article_priority) }}</td>
                        <td class="text-center">{{ format_date($article->published_at) }}</td>
                        <td class="text-center"><a href="{{ route('backend.user.edit', [$article->user_id]) }}">{{ $article->user->fullname }}</a></td>
                        @if (count($arrLanguage) > 1)
                            <td class="text-center">
                                <?php $arrTranslations = translation_item($article->article_id, 'article'); ?>
                                @if (!empty($arrTranslations))
                                    @foreach ($arrTranslations as $language => $translation)
                                        @if (empty($translation) && $language != $article->language_id)
                                            @if (check_permission('article', 'insert'))
                                                @if ($article->language_id != config('app.locale'))
                                                    <a href="{!! route('backend.article.create', [$language, 'item_id' => $article->article_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @else
                                                    <a href="{!! route('backend.article.create', [$language, 'source_item_id' => $article->article_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @endif
                                            @endif
                                        @else
                                            @if (check_permission('article', 'update'))
                                                <a href="{!! route('backend.article.edit', [$translation->article_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endif
                        <td class="text-center">
                            <select class="form-control r04 wp100" data-forstatus="{{ $article->article_id }}" data-status="true" data-link="{{ route('backend.article.changestatus', [0]) }}" data-old="{{ $article->status }}"{!! check_permission('article', 'update') ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == $article->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            @if (check_permission('article', 'update'))
                                <a href="{!! route('backend.article.edit', [$article->article_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('article', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.article.delete') }}" href="{!! route('backend.article.destroy', [$article->article_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'article', 'model_id' => $article->article_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    @if (count($arrLanguage) > 1)
                        <td colspan="8" class="text-center">{!! language_switcher() !!}</td>
                    @else
                        <td colspan="8" class="text-center">{{ trans('common.messages.nodata') }}</td>
                    @endif
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListArticle, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
        Backend.multiSelect();
	});
</script>
@stop