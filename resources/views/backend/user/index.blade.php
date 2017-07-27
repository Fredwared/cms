@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.user.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 form-group">
                            <label class="mr05">{{ trans('common.status.title') }}</label>
                            <select class="form-control r04" name="status">
                                <option value="">{{ trans('common.status.all') }}</option>
                                @foreach (config('cms.backend.user.status') as $name => $value) {
                                	<option value="{{ $value }}"{!! $value == $status ? ' selected="selected"' : '' !!}>{{ trans('common.auth.status.' . $name) }}</option>
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
        @if (check_permission('user', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.user.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListUser, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        @if ($arrListUser->total() > 0)
            @if (check_permission('user', 'update'))
                <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.user.changestatus', [1]) }}">{{ trans('common.auth.status.1') }}</button>
                <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.user.changestatus', [4]) }}">{{ trans('common.auth.status.4') }}</button>
            @endif
        @endif
        <table class="table table-hover">
            <thead>
            <tr>
                <th class="w10px">
                    <input type="checkbox" class="checkbox" id="chkAll" />
                </th>
                <th>Tên người dùng</th>
                <th>Email</th>
                <th>Nhóm</th>
                <th class="w150px text-center">Ngày cập nhật</th>
                <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                <th class="w100px text-center">{{ trans('common.action.title') }}</th>
            </tr>
            </thead>
            <tbody>
                @if ($arrListUser->total() > 0)
                    @foreach ($arrListUser as $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $user->id }}" />
                            </td>
                            <td>
                            	@if (check_permission('user', 'update'))
                            		<a href="{!! route('backend.user.edit', [$user->id]) !!}">{{ $user->fullname }}</a>
                                @else
                            		{{ $user->fullname }}
                        		@endif
                        	</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->groups as $group)
                                    <a href="{{ route('backend.group.edit', [$group->group_id]) }}" class="label label-info">{{ $group->group_name }}</a>
                                @endforeach
                            </td>
                            <td class="text-center">{{ format_date($user->updated_at) }}</td>
                            <td class="text-center">
                                <span class="label label-{!! $user->status == 1 ? 'success' : 'danger' !!}" data-forstatus="{{ $user->id }}">{!! trans('common.auth.status.' . $user->status) !!}</span>
                            </td>
                            <td class="text-center">
                                @if (check_permission('user', 'update'))
                                    <a href="{!! route('backend.user.edit', [$user->id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                @endif
                                @if ($user->user_id != config('cms.backend.super_admin_id') && check_permission('user', 'update'))
                                    <a data-ajax="true" href="{!! route('backend.user.forgotpass', [$user->id]) !!}" title="{{ trans('common.action.resetpass') }}"><i class="glyphicon glyphicon-refresh"></i></a>
                                @endif
                                @if ($user->user_id != config('cms.backend.super_admin_id') && check_permission('user', 'delete'))
                                    <a data-delete="true" data-message="{{ trans('common.messages.user.delete') }}" href="{!! route('backend.user.destroy', [$user->id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                                @endif
                                <a href="{!! route('backend.log.index', ['model_name' => 'user', 'model_id' => $user->id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
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
        @include('backend.partials.pagination', ['arrData' => $arrListUser, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop