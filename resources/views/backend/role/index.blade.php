@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.role.index') }}">
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
        @if (check_permission('role', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.role.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
    </div>
	<div class="box-body table-responsive">
        @if (count($arrListRole) > 0)
            @if (check_permission('role', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.role.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
            @endif
        @endif
		<table class="table table-hover">
			<thead>
				<tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
                    <th>Mã quyền</th>
					<th>Tên quyền</th>
                    <th>Action</th>
                    <th class="w150px text-center">Ngày cập nhật</th>
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
				</tr>
			</thead>
			<tbody>
				@if (count($arrListRole) > 0)
					@foreach ($arrListRole as $role)
					<tr>
                        <td>
                            <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $role->role_code }}"{!! $role->profiles->count() > 0 ? ' disabled="disabled"' : '' !!} />
                        </td>
                        <td>
                        	@if (check_permission('role', 'update'))
                        		<a href="{!! route('backend.role.edit', [$role->role_code]) !!}">{{ $role->role_code }}</a>
                            @else
                        		{{ $role->role_code }}
                    		@endif
                    	</td>
						<td>{{ trans_by_locale($role->role_name, session('backend-locale')) }}</td>
                        <td>{{ $role->action_applied }}</td>
						<td class="text-center">{{ format_date($role->updated_at) }}</td>
						<td class="text-center">
							<select class="form-control r04 wp100" data-forstatus="{{ $role->role_code }}" data-status="true" data-link="{{ route('backend.role.changestatus', [0]) }}" data-old="{{ $role->status }}"{!! check_permission('role', 'update') && $role->profiles->count() <= 0 ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                	<option value="{{ $value }}"{!! $value == $role->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
						</td>
						<td class="text-center">
                            @if (check_permission('role', 'update'))
							    <a href="{!! route('backend.role.edit', [$role->role_code]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if ($role->profiles->count() <= 0 && check_permission('role', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.role.delete') }}" href="{!! route('backend.role.destroy', [$role->role_code]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'role', 'model_id' => $role->role_code]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
						</td>
					</tr>
					@endforeach
				@else
					<tr>
						<td colspan="6" class="text-center">{{ trans('common.messages.nodata') }}</td>
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
@stop