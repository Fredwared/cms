@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-body">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Danh sách người dùng</h3>
            </div>
            <div class="panel-body">
                @foreach ($groupInfo->users as $user)
                    <span class="label label-info">{{ $user->fullname }} - {{ $user->email }}</span>
                @endforeach
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <th>Tên menu</th>
                    @foreach ($arrListRole as $role)
                        <th class="text-center wp10">
                            {{ trans_by_locale($role->role_name, session('backend-locale')) }}
                        </th>
                    @endforeach
                </thead>
                <tbody>
                    @foreach ($arrListMenu as $menu)
                        <tr>
                            <td{!! $menu->menu_level > 1 ? ' class="pl' . (15 * $menu->menu_level) . '"' : '' !!}>
                                {{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                            </td>
                            @foreach ($arrListRole as $role)
                                <td class="text-center">
                                    @if (!empty($menu->route_name))
                                        <input type="checkbox" disabled="disabled"{!! check_permission($menu->menu_id, $role->role_code, $groupInfo->group_id) ? ' checked="checked"' : '' !!} />
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        <div class="pull-left">
            <a role="button" class="btn btn-primary" href="{{ route('backend.group.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
        </div>
        @if (check_permission('group', 'update'))
            <div class="pull-right">
                <a role="button" class="btn btn-primary" href="{{ route('backend.group.edit', [$groupInfo->group_id]) }}"><i class="fa fa-edit"></i> {{ trans('common.action.edit') }}</a>
            </div>
        @endif
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop