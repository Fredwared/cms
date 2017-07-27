@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
	<!-- form start -->
	<form id="frmGroup" name="frmGroup" role="form" action="{{ route('backend.group.store') }}" method="post">
		<div class="box-body">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                	<label for="role_name" class="required">Tên nhóm</label>
                    <input type="text" class="form-control" id="group_name" name="group_name" value="{{ old('group_name') }}" placeholder="Tên nhóm">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                    <label for="role_code">Mô tả nhóm</label>
                    <input type="text" class="form-control" id="group_description" name="group_description" value="{{ old('group_description') }}" placeholder="Mô tả nhóm">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                    <label for="status" class="required">{{ trans('common.status.title') }}</label>
                    <select class="form-control" id="status" name="status">
                        @foreach (config('cms.backend.status') as $name => $value)
                        	<option value="{{ $value }}"{!! $value == old('status', config('cms.backend.status.active')) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
		</div>
		<!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.group.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
	</form>
</div>
@stop

@section('javascript')
<!-- js link here -->
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
	});
</script>
@stop