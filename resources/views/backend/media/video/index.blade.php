@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content-header')
    <h1>Danh sách video</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('backend.index') }}"><i class="fa fa-dashboard"></i> {{ trans('common.layout.home_title') }}</a></li>
        <li class="active">Quản lý video</li>
    </ol>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.media.video.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 form-group">
                            <input type="hidden" name="item" value="{{ $item }}" />
                            <label class="mr05">Nguồn</label>
                            <select class="form-control r04" name="status">
                            	<option value="">Tất cả</option>
                                @foreach (config('cms.backend.media.source') as $source => $link)
                                    <option value="{{ $source }}"{!! $source == $media_source ? ' selected="selected"' : '' !!}>{{ ucfirst($source) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 form-group">
                            <label class="mr05">Nhãn video</label>
                            <select class="form-control r04" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $type]) }}" data-placeholder="Chọn nhãn" data-fields="label_name|label_name" id="label" name="label[]" multiple="multiple">
                                @foreach (array_filter($label) as $media_label)
                                    <option value="{{ $media_label }}" selected="selected">{{ $media_label }}</option>
                                @endforeach
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
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('video', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.media.video.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListVideo, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Link video</th>
                <th>Nguồn video</th>
                <th>Thông tin video</th>
                <th>Nhãn video</th>
                <th>Bài viết</th>
                <th class="text-center">Ngày cập nhật</th>
                <th class="text-center">{{ trans('common.action.title') }}</th>
            </tr>
            </thead>
            <tbody>
            @if (count($arrListVideo) > 0)
                @foreach ($arrListVideo as $video)
                	<?php
                    $arrInfo = json_decode($video->media_info, true);
                    ?>
                    <tr>
                        <td>
                        	@if (check_permission('video', 'update'))
                                <a href="{!! route('backend.media.video.edit', [$video->media_id]) !!}">{{ $video->media_filename }}</a>
                            @else
                        		{{ $video->media_filename }}
                    		@endif
                    	</td>
                        <td>{{ ucfirst($video->media_source) }}</td>
                        <td>
                        	<div><label>Title:</label> {{ $arrInfo['title'] }}</div>
                        	<div><label>Duration:</label> {{ $arrInfo['duration'] }}</div>
                        	<div><label>Quality:</label> {{ $arrInfo['definition'] }}</div>
                    	</td>
                        <td>{{ $video->media_label }}</td>
                        <td>{{ $video->articles->count() }}</td>
                        <td class="text-center">{{ format_date($video->updated_at) }}</td>
                        <td class="text-center">
                            @if (check_permission('video', 'update'))
                                <a href="{!! route('backend.media.video.edit', [$video->media_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('video', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.media.video_delete') }}" href="{!! route('backend.media.video.destroy', [$video->media_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
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
        @include('backend.partials.pagination', ['arrData' => $arrListVideo, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
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