@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.log.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label>Loại log</label>
                            <input type="hidden" name="item" value="{{ $item }}" />
                            <select class="form-control r04" name="log_type">
                                <option value="">Tất cả</option>
                                @foreach (config('cms.backend.log.type') as $type)
                                    @if ($type == 'query')
                                        @continue
                                    @endif
                                    <option value="{{ $type }}"{!! $log_type == $type ? ' selected="selected"' : '' !!}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label>Tên model</label>
                            <input type="text" class="form-control r04" name="model_name" value="{{ $model_name }}" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label>Model ID</label>
                            <input type="text" class="form-control r04" name="model_id" value="{{ $model_id }}" />
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label>Địa chỉ IP</label>
                            <input type="text" class="form-control r04" name="log_ip" value="{{ $log_ip }}" />
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 form-group">
                            <label>Người dùng</label>
                            <select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.user') }}" data-placeholder="Chọn người dùng" data-fields="id|fullname,email" name="user_id[]" multiple="multiple">
                                @foreach ($arrUser as $user)
                                	<option value="{{ $user->id }}" selected="selected">{{ $user->fullname . ' - ' . $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-group">
                            <label class="mr05">Ngày tạo</label>
                            <div class="row">
                            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="input-group date" id="date_from">
                                        <input type="text" class="form-control r04" name="date_from" value="{{ $date_from }}" placeholder="Từ ngày" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="input-group date" id="date_to">
                                        <input type="text" class="form-control r04" name="date_to" value="{{ $date_to }}" placeholder="Đến ngày" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="pull-left">
                                <a href="{{ route('backend.log.query') }}" role="button" class="btn btn-primary" target="_blank">Query log</a>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('backend.partials.pagination', ['arrData' => $arrListLog, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        @if ($arrListLog->count() > 0)
            @if (check_permission('log', 'delete'))
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Xóa log <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [0, $log_type]) }}">Xóa hết</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [1, $log_type]) }}">Cách 1 ngày</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [7, $log_type]) }}">Cách 1 tuần</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [30, $log_type]) }}">Cách 1 tháng</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [90, $log_type]) }}">Cách 3 tháng</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [365, $log_type]) }}">Cách 1 năm</a></li>
                    </ul>
                </div>
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">Loại log</th>
                    <th class="text-center">Tên model</th>
                    <th class="text-center">Model ID</th>
                    <th class="text-center">Địa chỉ IP</th>
                    <th>Nội dung</th>
                    <th class="text-center">Người dùng</th>
                    <th>User agent</th>
                    <th class="text-center">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrListLog->count() > 0)
                    @foreach ($arrListLog as $log)
                        <tr>
                            <td class="text-center">{{ ucfirst($log->log_type) }}</td>
                            <td class="text-center">{{ $log->model_name }}</td>
                            <td class="text-center">{{ $log->model_id }}</td>
                            <td class="text-center">{{ $log->log_ip }}</td>
                            <td>
                                @if (!empty($log->log_content) && $log->log_content != 'null')
                                    <?php $arrContent = json_decode($log->log_content, true);  ?>
                                    @if (isset($arrContent['new']) || isset($arrContent['old']))
                                    	<div class="row">
                                            @if (isset($arrContent['old']))
                                            	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="panel panel-info">
                                                        <div class="panel-body bg-info">
                                                            @foreach ($arrContent['old'] as $key => $content)
                                                                @if (in_array($key, ['password', 'remember_token']))
                                                                    @continue
                                                                @endif
                                                                <div><label>{{ ucfirst($key) }}:</label> {{ $content }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-lg-{!! isset($arrContent['old']) ? 6 : 12 !!} col-md-{!! isset($arrContent['old']) ? 6 : 12 !!} col-sm-12 col-xs-12">
                                                <div class="panel panel-danger">
                                                    <div class="panel-body bg-danger">
                                                        @foreach ($arrContent['new'] as $key => $content)
                                                            @if (in_array($key, ['password', 'remember_token']))
                                                                @continue
                                                            @endif
                                                            <div><label>{{ ucfirst($key) }}:</label> {{ $content }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="panel panel-{!! $log->log_type == 'error' ? 'danger' : 'primary' !!}">
                                            <div class="panel-body bg-{!! $log->log_type == 'error' ? 'danger' : 'primary' !!}">
                                                @foreach ($arrContent as $key => $content)
                                                    @if (in_array($key, ['password', 'remember_token']))
                                                        @continue
                                                    @endif
                                                    @if ($log->log_type == 'error' && $key == 'trace')
                                                    	<?php $arrTrace = array_filter(explode('#', $content));  ?>
                                                    	<div>
                                                    		<label>{{ ucfirst($key) }}:</label>
                                                    		@foreach ($arrTrace as $trace)
                                                    			<div>#{{ $trace }}</div>
                                                    		@endforeach
                                                		</div>
                                                	@else
                                                		<div><label>{{ ucfirst($key) }}:</label> {{ $content }}</div>
                                                	@endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if (!empty($log->user_id))
                                    <a href="{{ route('backend.user.edit', [$log->user_id]) }}">{{ $log->user->fullname }}</a>
                                @endif
                            </td>
                            <td>{{ $log->user_agent }}</td>
                            <td class="text-center">{{ format_date($log->updated_at) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">{{ trans('common.messages.nodata') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListLog, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
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