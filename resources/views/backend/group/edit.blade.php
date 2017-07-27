@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
	<!-- form start -->
	<form id="frmGroup" name="frmGroup" role="form" action="{{ route('backend.group.update', [$groupInfo->group_id]) }}" method="post">
		<div class="box-body">
			<div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                	<label for="role_name" class="required">Tên nhóm</label>
                    <input type="text" class="form-control" id="group_name" name="group_name" value="{{ old('group_name', $groupInfo->group_name) }}" placeholder="Tên nhóm">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                    <label for="role_code">Mô tả nhóm</label>
                    <input type="text" class="form-control" id="group_description" name="group_description" value="{{ old('group_description', $groupInfo->group_description) }}" placeholder="Mô tả nhóm">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                    <label for="status" class="required">{{ trans('common.status.title') }}</label>
                    <select class="form-control" id="status" name="status">
                    	@foreach (config('cms.backend.status') as $name => $value)
                        	<option value="{{ $value }}"{!! $value == old('status', $groupInfo->status) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                    <label for="role_code">Danh sách người dùng</label>
                    <select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.user') }}" data-placeholder="Chọn người dùng" data-fields="id|fullname,email" id="users" name="users[]" multiple="multiple">
                        @foreach ($groupInfo->users as $user)
                            <option value="{{ $user->id }}" selected="selected">{{ $user->fullname . ' - ' . $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                	<div class="table-responsive">
                		<table class="table table-hover table-bordered" id="tblProfile">
                			<thead>
                				<th>Tên menu</th>
                				@foreach ($arrListRole as $role)
                					<th class="text-center wp10 form-inline">
                						<label>
                							<input type="checkbox" class="checkbox" data-role="{{ $role->role_code }}" />
                							{{ trans_by_locale($role->role_name, session('backend-locale')) }}
            							</label>
            						</th>
                				@endforeach
                			</thead>
                			<tbody>
                				@foreach ($arrListMenu as $menu)
                					<tr>
                						<td class="form-inline{!! $menu->menu_level > 1 ? ' pl' . (15 * $menu->menu_level) : '' !!}">
                                            <label>
                                                @if (!empty($menu->route_name))
                                                    <input type="checkbox" class="checkbox" data-menu="{{ $menu->menu_id }}" />
                                                @endif
                                                {{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                                            </label>
                                        </td>
                						@foreach ($arrListRole as $role)
                        					<td class="text-center">
                        						@if (!empty($menu->route_name))
                    								<input type="checkbox" data-formenu="{{ $menu->menu_id }}" data-forrole="{{ $role->role_code }}" name="profile[{{ $menu->menu_id }}][{{ $role->role_code }}]"{!! check_permission($menu->menu_id, $role->role_code, $groupInfo->group_id) ? ' checked="checked"' : '' !!} />
                                				@endif
                    						</td>
                        				@endforeach
                					</tr>
                				@endforeach
                			</tbody>
                		</table>
                	</div>
                </div>
            </div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer clearfix">
			<div class="pull-left">
				<a role="button" class="btn btn-primary" href="{{ route('backend.group.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="_method" value="put">
			</div>
		</div>
	</form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		Group.validate({
        	group_name: {
            	required: "{{ trans('validation.group.group_name.required') }}",
            	maxlength: "{{ trans('validation.group.group_name.maxlength') }}"
        	},
        	group_description: {
        		maxlength: "{{ trans('validation.group.group_description.maxlength') }}"
			},
        	status: {
				required: "{{ trans('validation.status.required') }}"
			}
    	});

        Group.initProfile();
        Backend.multiSelect();
	});
</script>
@stop