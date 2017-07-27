@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->-->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
    <!-- form start -->
    <form id="frmRole" name="frmRole" role="form" action="{{ route('backend.role.update', [$roleInfo->role_code]) }}" method="post">
        <div class="box-body">
            <label for="role_name" class="required">Tên quyền</label>
			<div class="row">
                @if (count(config('cms.backend.languages')) == 1)
                    <?php $key = config('cms.backend.default_locale'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                        <input type="text" class="form-control" id="role_name_{!! $key !!}" name="role_name[{!! $key !!}]" value="{{ old('role_name.' . $key, trans_by_locale($roleInfo->role_name, $key)) }}" placeholder="Tên quyền">
                    </div>
                @else
                    @foreach (config('cms.backend.languages') as $key => $lang)
                        <div class="col-lg-{{ 12 / count(config('cms.backend.languages')) }} col-md-{{ 12 / count(config('cms.backend.languages')) }} col-sm-12 col-xs-12 form-group has-feedback">
                            <input type="text" class="form-control" id="role_name_{!! $key !!}" name="role_name[{!! $key !!}]" value="{{ old('role_name.' . $key, trans_by_locale($roleInfo->role_name, $key)) }}" placeholder="{{ $lang['native'] }}">
                            <span class="flag-icon flag-icon-background flag-icon-{!! $lang['flag'] !!} form-control-feedback flag-icon-embed"></span>
                        </div>
                    @endforeach
                @endif
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label for="role_code" class="required">Mã quyền</label>
                    <input type="text" class="form-control" id="role_code" name="role_code" value="{{ old('role_code', $roleInfo->role_code) }}" placeholder="Mã quyền" readonly="readonly">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                    <label for="role_priority">Độ ưu tiên</label>
                    <input type="text" class="form-control" id="role_priority" name="role_priority" value="{{ old('role_priority', $roleInfo->role_priority) }}" placeholder="Độ ưu tiên">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                    <label for="action_applied">Action</label>
                    <select class="form-control" id="action_applied" name="action_applied[]" data-multiselect="true" data-tags="true" data-width="100%" data-placeholder="Chọn action"  multiple="multiple">
                        @foreach ($actions as $action)
                            <option value="{{ $action }}"{!! in_array($action, old('action_applied', array_filter(explode(',', $roleInfo->action_applied)))) ? ' selected="selected"' : '' !!}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group clearfix">
                <div class="pull-left">
                    <a role="button" class="btn btn-primary" href="{{ route('backend.role.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="put">
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Role.validate({
            role_name: {
                required: "{{ trans('validation.role.role_name.required') }}",
            	maxlength: "{{ trans('validation.role.role_name.maxlength') }}"
            },
            role_code: {
                required: "{{ trans('validation.role.role_code.required') }}",
                maxlength: "{{ trans('validation.role.role_code.maxlength') }}"
            },
            role_priority: {
                number: "{{ trans('validation.role.role_priority.number') }}"
            },
            status: {
                required: "{{ trans('validation.status.required') }}"
            }
        });

        Backend.multiSelect();
    });
</script>
@stop