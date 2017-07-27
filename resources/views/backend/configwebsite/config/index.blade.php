@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.configwebsite.config.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 form-group">
                            <label class="mr05">Tên cấu hình</label>
                            <input type="text" class="form-control r04" name="field_name" value="{{ $field_name }}" />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 form-group">
                            <label class="mr05">Giá trị</label>
                            <input type="text" class="form-control r04" name="field_value" value="{{ $field_value }}" />
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4 form-group text-right mt25">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('config', 'insert'))
        	<div class="clearfix">
            	<div class="pull-left mb10">
                    <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.utils.import', ['config']) !!}"><i class="fa fa-upload"></i> {{ trans('common.action.import') }}</button>
                </div>
                <div class="pull-right mb10">
                    <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.configwebsite.config.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</button>
                </div>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListConfig, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tên cấu hình</th>
                    <th>Giá trị</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($arrListConfig->total() > 0)
                @foreach ($arrListConfig as $config)
                    <tr>
                        <td>
                            @if (check_permission('config', 'update'))
                                <a class="btn-show-sidebar" href="{!! route('backend.configwebsite.config.edit', [$config->config_id]) !!}">{{ $config->field_name }}</a>
                            @else
                                {{ $config->field_name }}
                            @endif
                        </td>
                        <td>{{ $config->field_value }}</td>
                        <td class="text-center">{{ format_date($config->updated_at) }}</td>
                        <td class="text-center">
                            @if (check_permission('config', 'update'))
                                <a class="btn-show-sidebar" href="{!! route('backend.configwebsite.config.edit', [$config->config_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'config', 'model_id' => $config->config_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">{{ trans('common.messages.nodata') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListConfig, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
@stop