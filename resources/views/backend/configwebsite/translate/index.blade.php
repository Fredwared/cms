@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.configwebsite.translate.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label class="mr05">Code</label>
                            <input type="text" class="form-control r04" name="translate_code" value="{{ $translate_code }}" />
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label class="mr05">Chế độ</label>
                            <select class="form-control r04" name="translate_mode">
                                <option value="">Tất cả</option>
                                <option value="text"{!! $translate_mode == 'text' ? ' selected="selected"' : '' !!}>Text</option>
                                <option value="editor"{!! $translate_mode == 'editor' ? ' selected="selected"' : '' !!}>Editor</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-9 col-xs-8 form-group">
                            <label class="mr05">Nội dung</label>
                            <input type="text" class="form-control r04" name="translate_content" value="{{ $translate_content }}" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 form-group text-right mt25">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('translate', 'insert'))
        	<div class="clearfix">
            	<div class="pull-left mb10">
                    <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.utils.import', ['translate']) !!}"><i class="fa fa-upload"></i> {{ trans('common.action.import') }}</button>
                </div>
                <div class="pull-right mb10">
                    <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.configwebsite.translate.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</button>
                </div>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListTranslate, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
    	@if ($arrListTranslate->total() > 0)
            @if (check_permission('translate', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.configwebsite.translate.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
                    <th>Code</th>
                    <th>Chế độ</th>
                    <th>Nội dung</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($arrListTranslate->total() > 0)
                @foreach ($arrListTranslate as $translate)
                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $translate->translate_id }}" />
                        </td>
                        <td>
                            @if (check_permission('translate', 'update'))
                                <a class="btn-show-sidebar" href="{!! route('backend.configwebsite.translate.edit', [$translate->translate_id]) !!}">{{ $translate->translate_code }}</a>
                            @else
                                {{ $translate->translate_code }}
                            @endif
                        </td>
                        <td>{{ $translate->translate_mode }}</td>
                        <td>
                            <?php
                            $translate_content = json_decode($translate->translate_content, true);
                            ?>
                            @foreach ($translate_content as $content)
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        @if ($translate->translate_mode == 'editor')
                                            {!! $content !!}
                                        @else
                                            {{ $content }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td class="text-center">{{ format_date($translate->updated_at) }}</td>
                        <td class="text-center">
                            <select class="form-control r04 wp100" data-forstatus="{{ $translate->translate_id }}" data-status="true" data-link="{{ route('backend.configwebsite.translate.changestatus', [0]) }}" data-old="{{ $translate->status }}"{!! check_permission('translate', 'update') ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == $translate->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            @if (check_permission('translate', 'update'))
                                <a class="btn-show-sidebar" href="{!! route('backend.configwebsite.translate.edit', [$translate->translate_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('translate', 'delete'))
                            	<a data-delete="true" data-message="{{ trans('common.messages.translate.delete') }}" href="{!! route('backend.configwebsite.translate.destroy', [$translate->translate_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'translate', 'model_id' => $translate->translate_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">{{ trans('common.messages.nodata') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListTranslate, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
@stop