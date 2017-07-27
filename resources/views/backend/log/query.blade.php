@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.log.query') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4 form-group">
                            <label>Url</label>
                            <input type="text" class="form-control r04" name="log_url" value="{{ $log_url }}" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4 form-group">
                            <label>Địa chỉ IP</label>
                            <input type="text" class="form-control r04" name="log_ip" value="{{ $log_ip }}" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4 form-group">
                            <label>Method</label>
                            <select class="form-control r04" name="log_method">
                                <option value="">Tất cả</option>
                                <option value="get"{!! $log_method == 'get' ? ' selected="selected"' : '' !!}>Get</option>
                                <option value="post"{!! $log_method == 'post' ? ' selected="selected"' : '' !!}>Post</option>
                                <option value="put"{!! $log_method == 'put' ? ' selected="selected"' : '' !!}>Put</option>
                                <option value="delete"{!! $log_method == 'delete' ? ' selected="selected"' : '' !!}>Delete</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="hidden" name="item" value="{{ $item }}" />
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
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
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [0, 'query']) }}">Xóa hết</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [1, 'query']) }}">Cách 1 ngày</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [7, 'query']) }}">Cách 1 tuần</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [30, 'query']) }}">Cách 1 tháng</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [90, 'query']) }}">Cách 3 tháng</a></li>
                        <li><a data-delete="true" data-reload="true" data-message="{{ trans('common.messages.log.delete') }}" href="{{ route('backend.log.destroy', [365, 'query']) }}">Cách 1 năm</a></li>
                    </ul>
                </div>
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Url</th>
                    <th>Method</th>
                    <th class="text-center">Địa chỉ IP</th>
                    <th class="text-center">Thời gian xử lý</th>
                    <th>Nội dung</th>
                    <th>User agent</th>
                    <th class="text-center">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrListLog->count() > 0)
                    @foreach ($arrListLog as $log)
                        <tr>
                            <td>{{ $log->log_url }}</td>
                            <td>{{ ucfirst($log->log_method) }}</td>
                            <td class="text-center">{{ $log->log_ip }}</td>
                            <td class="text-center">{{ $log->query_time }} ms</td>
                            <td>{{ $log->log_content }}</td>
                            <td>{{ $log->user_agent }}</td>
                            <td class="text-center">{{ format_date($log->updated_at) }}</td>
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
        @include('backend.partials.pagination', ['arrData' => $arrListLog, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop