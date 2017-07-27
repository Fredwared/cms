@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.group.index') }}">
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
        @if (check_permission('group', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.group.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListGroup, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
	<div class="box-body table-responsive">
        @if ($arrListGroup->total() > 0)
            @if (check_permission('group', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.group.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
            @endif
        @endif
		<table class="table table-hover">
			<thead>
				<tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
					<th>Tên nhóm</th>
					<th>Mô tả</th>
					<th class="w100px text-right">Thành viên</th>
                    <th class="w150px text-center">Ngày cập nhật</th>
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
				</tr>
			</thead>
			<tbody>
                @if ($arrListGroup->total() > 0)
					@foreach ($arrListGroup as $group)
    					<tr>
                            <td>
                                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $group->group_id }}"{!! $group->users->count() > 0 ? ' disabled="disabled"' : '' !!} />
                            </td>
                            <td>
                            	@if (check_permission('group', 'update'))
                            		<a href="{!! route('backend.group.profile', [$group->group_id]) !!}">{{ $group->group_name }}</a>
                                @else
                            		{{ $group->group_name }}
                        		@endif
                        	</td>
    						<td>{{ $group->group_description }}</td>
    						<td class="text-right">{{ $group->users->count() }}</td>
    						<td class="text-center">{{ format_date($group->updated_at) }}</td>
    						<td class="text-center">
    							<select class="form-control r04 wp100" data-forstatus="{{ $group->group_id }}" data-status="true" data-link="{{ route('backend.group.changestatus', [0]) }}" data-old="{{ $group->status }}"{!! check_permission('group', 'update') && $group->users->count() <= 0 ? '' : ' disabled="disabled"' !!}>
                                    @foreach (config('cms.backend.status') as $name => $value)
                                    	<option value="{{ $value }}"{!! $value == $group->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                    @endforeach
                                </select>
    						</td>
    						<td class="text-center">
                                @if (check_permission('group', 'update'))
                                    <a href="{!! route('backend.group.edit', [$group->group_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                @endif
    							@if ($group->users->count() <= 0 && check_permission('group', 'delete'))
    								<a data-delete="true" data-message="{{ trans('common.messages.group.delete') }}" href="{!! route('backend.group.destroy', [$group->group_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
    							@endif
                                <a href="{!! route('backend.log.index', ['model_name' => 'group', 'model_id' => $group->group_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
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
        @include('backend.partials.pagination', ['arrData' => $arrListGroup, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop