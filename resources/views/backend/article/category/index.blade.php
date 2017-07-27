@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.article.category.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <div class="row">
                                @if (count($arrLanguage) > 1)
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                        <label class="mr05">Ngôn ngữ</label>
                                        <select class="form-control r04" name="language_id">
                                            @foreach ($arrLanguage as $lang => $data)
                                                <option value="{{ $lang }}"{!! $lang == $language_id ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label class="mr05">{{ trans('common.status.title') }}</label>
                                    <select class="form-control r04" name="status">
                                        <option value="">{{ trans('common.status.all') }}</option>
                                        @foreach (config('cms.backend.status') as $name => $value)
                                            <option value="{{ $value }}"{!! $value == $status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                        @endforeach
                                    </select>
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
        @if (check_permission('category', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.article.category.create', [$language_id]) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListCategory, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        @if (count($arrListCategory) > 0)
            @if (check_permission('category', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.article.category.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
                <button type="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.article.category.sort', [$language_id]) !!}">{{ trans('common.action.sort') }}</button>
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
                    <th>Tên chuyên mục</th>
                    <th>Code</th>
                    <th class="w80px text-right">Bài viết</th>
                    <th class="w150px text-center">Ngày cập nhật</th>
                    @if (count($arrLanguage) > 1)
                        <th class="w{!! count($arrLanguage) * 40 !!}px text-center">{!! language_switcher() !!}</th>
                    @endif
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if (count($arrListCategory) > 0)
                @foreach ($arrListCategory as $category)
                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $category->category_id }}" />
                        </td>
                        <td>
                            @if (check_permission('category', 'update'))
                                <a href="{!! route('backend.article.category.edit', [$category->category_id]) !!}">
                                	{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title }}
                            	</a>
                            @else
                                {{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title }}
                            @endif
                        </td>
                        <td>{{ $category->category_code }}</td>
                        <td class="text-right">
                            <a href="{{ route('backend.article.index', ['category_id' => $category->category_id]) }}">{{ $category->articles->count() }}</a>
                        </td>
                        <td class="text-center">{{ format_date($category->updated_at) }}</td>
                        @if (count($arrLanguage) > 1)
                            <td class="text-center">
                                <?php $arrTranslations = translation_item($category->category_id, 'category'); ?>
                                @if (!empty($arrTranslations))
                                    @foreach ($arrTranslations as $language => $translation)
                                        @if (empty($translation) && $language != $category->language_id)
                                            @if (check_permission('category', 'insert'))
                                                @if ($category->language_id != config('app.locale'))
                                                    <a href="{!! route('backend.article.category.create', [$language, 'item_id' => $category->category_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @else
                                                    <a href="{!! route('backend.article.category.create', [$language, 'source_item_id' => $category->category_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                @endif
                                            @endif
                                        @else
                                            @if (check_permission('category', 'update'))
                                                <a href="{!! route('backend.article.category.edit', [$translation->category_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endif
                        <td class="text-center">
                            <select class="form-control r04 wp100" data-forstatus="{{ $category->category_id }}" data-status="true" data-link="{{ route('backend.article.category.changestatus', [0]) }}" data-old="{{ $category->status }}"{!! check_permission('category', 'update') ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                	<option value="{{ $value }}"{!! $value == $category->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            @if (check_permission('category', 'update'))
                                <a href="{!! route('backend.article.category.edit', [$category->category_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('category', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.category.delete') }}" href="{!! route('backend.article.category.destroy', [$category->category_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'category', 'model_id' => $category->category_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
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
        @include('backend.partials.pagination', ['arrData' => $arrListCategory, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-ui.min.js') }}"></script>
@stop