@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.configwebsite.page.index') }}">
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
        @if (check_permission('page', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.configwebsite.page.create', [$language_id]) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListPage, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        @if (count($arrListPage) > 0)
            @if (check_permission('page', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.configwebsite.page.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
                    <th>Tên trang</th>
                    <th>Code</th>
                    <th class="w150px text-center">Ngày cập nhật</th>
                    @if (count($arrLanguage) > 1)
                        <th class="w{!! count($arrLanguage) * 40 !!}px text-center">{!! language_switcher() !!}</th>
                    @endif
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
                @if (count($arrListPage) > 0)
                    @foreach ($arrListPage as $page)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $page->page_id }}" />
                            </td>
                            <td>
                                @if (check_permission('page', 'update'))
                                    <a href="{!! route('backend.configwebsite.page.edit', [$page->page_id]) !!}">
                                        {{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $page->page_level - 1) . $page->page_title }}
                                    </a>
                                @else
                                    {{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $page->page_level - 1) . $page->page_title }}
                                @endif
                            </td>
                            <td>{{ $page->page_code }}</td>
                            <td class="text-center">{{ format_date($page->updated_at) }}</td>
                            @if (count($arrLanguage) > 1)
                                <td class="text-center">
                                    <?php $arrTranslations = translation_item($page->page_id, 'page'); ?>
                                    @if (!empty($arrTranslations))
                                        @foreach ($arrTranslations as $language => $translation)
                                            @if (empty($translation) && $language != $page->language_id)
                                                @if (check_permission('page', 'insert'))
                                                    @if ($page->language_id != config('app.locale'))
                                                        <a href="{!! route('backend.configwebsite.page.create', [$language, 'item_id' => $page->page_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                    @else
                                                        <a href="{!! route('backend.configwebsite.page.create', [$language, 'source_item_id' => $page->page_id]) !!}" title="{{ trans('common.action.add') }}"><i class="glyphicon glyphicon-plus"></i></a>
                                                    @endif
                                                @endif
                                            @else
                                                @if (check_permission('page', 'update'))
                                                    <a href="{!! route('backend.configwebsite.page.edit', [$translation->page_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                            <td class="text-center">
                                <select class="form-control r04 wp100" data-forstatus="{{ $page->page_id }}" data-status="true" data-link="{{ route('backend.configwebsite.page.changestatus', [0]) }}" data-old="{{ $page->status }}"{!! check_permission('page', 'update') ? '' : ' disabled="disabled"' !!}>
                                    @foreach (config('cms.backend.status') as $name => $value)
                                        <option value="{{ $value }}"{!! $value == $page->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                @if (check_permission('page', 'update'))
                                    <a href="{!! route('backend.configwebsite.page.edit', [$page->page_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                @endif
                                @if (check_permission('page', 'delete'))
                                    <a data-delete="true" data-message="{{ trans('common.messages.page.delete') }}" href="{!! route('backend.configwebsite.page.destroy', [$page->page_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                                @endif
                                <a href="{!! route('backend.log.index', ['model_name' => 'page', 'model_id' => $page->page_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @if (count($arrLanguage) > 1)
                            <td colspan="7" class="text-center">{!! language_switcher() !!}</td>
                        @else
                            <td colspan="6" class="text-center">{{ trans('common.messages.nodata') }}</td>
                        @endif
                    </tr>
                @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListPage, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop