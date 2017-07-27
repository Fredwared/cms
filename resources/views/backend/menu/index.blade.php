@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.menu.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 form-group">
                            <label class="mr05">{{ trans('common.status.title') }}</label>
                            <select class="form-control r04" name="status">
                                <option value="">{{ trans('common.status.all') }}</option>
                                @foreach (config('cms.backend.status') as $name => $value)
                                	<option value="{{ $value }}"{!! $value == $status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-6 form-group text-right mt25">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('menu', 'insert'))
            <div class="text-right">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.menu.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
    </div>
	<div class="box-body table-responsive">
        @if (count($arrListMenu) > 0)
            @if (check_permission('menu', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.menu.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
                <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.menu.sort') !!}">{{ trans('common.action.sort') }}</button>
            @endif
        @endif
		<table class="table table-hover">
			<thead>
				<tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
					<th>Tên menu</th>
					<th>Mã menu</th>
					<th>Tên route</th>
					<th>Icon menu</th>
					<th class="w150px text-center">Ngày cập nhật</th>
					<th class="w150px text-center">{{ trans('common.status.title') }}</th>
					<th class="w100px text-center">{{ trans('common.action.title') }}</th>
				</tr>
			</thead>
			<tbody>
				@if (count($arrListMenu) > 0)
					@foreach ($arrListMenu as $menu)
					<tr>
                        <td>
                            <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $menu->menu_id }}" />
                        </td>
                        <td>
                        	@if (check_permission('menu', 'update'))
                        		<a href="{!! route('backend.menu.edit', [$menu->menu_id]) !!}">
                        			{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $menu->menu_level - 1) }}
                                    <i class="fa {{ $menu->menu_icon }}"></i>
                                    {{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                        		</a>
                            @else
                            	{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $menu->menu_level - 1) }}
                                <i class="fa {{ $menu->menu_icon }}"></i>
                                {{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                    		@endif
                    	</td>
						<td>{{ $menu->menu_code }}</td>
						<td>{{ $menu->route_name }}</td>
						<td>{{ $menu->menu_icon }}</td>
						<td class="text-center">{{ format_date($menu->updated_at) }}</td>
						<td class="text-center">
							<select class="form-control r04 wp100" data-forstatus="{{ $menu->menu_id }}" data-status="true" data-link="{{ route('backend.menu.changestatus', [0]) }}" data-old="{{ $menu->status }}"{!! check_permission('menu', 'update') ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                	<option value="{{ $value }}"{!! $value == $menu->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
						</td>
						<td class="text-center">
                            @if (check_permission('menu', 'update'))
							    <a href="{!! route('backend.menu.edit', [$menu->menu_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
							<a href="{!! route('backend.log.index', ['model_name' => 'menu', 'model_id' => $menu->menu_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
						</td>
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
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-ui.min.js') }}"></script>
@stop