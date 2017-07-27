@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
	<!-- form start -->
	<form id="frmMenu" name="frmMenu" role="form" action="{{ route('backend.menu.update', [$menuInfo->menu_id]) }}" method="post">
		<div class="box-body">
			<div class="row">
    			<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <label for="menu_name" class="required">Tên menu</label>
                    <div class="row">
						@if (count(config('cms.backend.languages')) == 1)
							<?php $key = config('cms.backend.default_locale'); ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
								<input type="text" class="form-control" id="menu_name_{!! $key !!}" name="menu_name[{!! $key !!}]" value="{{ old('menu_name.' . $key, trans_by_locale($menuInfo->menu_name, $key)) }}" placeholder="Tên menu">
							</div>
						@else
							@foreach (config('cms.backend.languages') as $key => $lang)
								<div class="col-lg-{{ 12 / count(config('cms.backend.languages')) }} col-md-{{ 12 / count(config('cms.backend.languages')) }} col-sm-12 col-xs-12 form-group has-feedback">
									<input type="text" class="form-control" id="menu_name_{!! $key !!}" name="menu_name[{!! $key !!}]" value="{{ old('menu_name.' . $key, trans_by_locale($menuInfo->menu_name, $key)) }}" placeholder="{{ $lang['native'] }}">
									<span class="flag-icon flag-icon-background flag-icon-{!! $lang['flag'] !!} form-control-feedback flag-icon-embed"></span>
								</div>
							@endforeach
						@endif
                    </div>
                    <div class="row">
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="menu_code">Mã menu</label>
            				<input type="text" class="form-control" id="menu_code" name="menu_code" value="{{ old('menu_code', $menuInfo->menu_code) }}" placeholder="Mã menu">
            			</div>
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="menu_url">Tên route</label>
            				<input type="text" class="form-control" id="route_name" name="route_name" value="{{ old('route_name', $menuInfo->route_name) }}" placeholder="Tên route">
            			</div>
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="menu_icon">Icon menu</label>
            				<input type="text" class="form-control" id="menu_icon" name="menu_icon" value="{{ old('menu_icon', $menuInfo->menu_icon) }}" placeholder="Icon của menu">
            			</div>
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="parent_id">Menu cha</label>
            				<select class="form-control" id="parent_id" name="parent_id">
            					<option value="0">{{ trans('common.layout.home_title') }}</option>
            					@foreach ($arrListMenu as $menu)
            						<option value="{{ $menu->menu_id }}"{!! $menu->menu_id == old('parent_id', $menuInfo->parent_id) ? ' selected="selected"' : '' !!}>
                                        {{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $menu->menu_level - 1) . trans_by_locale($menu->menu_name, session('backend-locale')) }}
                                    </option>
            					@endforeach
            				</select>
            			</div>
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="status" class="required">{{ trans('common.status.title') }}</label>
            				<select class="form-control" id="status" name="status">
            					@foreach (config('cms.backend.status') as $name => $value)
                                	<option value="{{ $value }}"{!! $value == old('status', $menuInfo->status) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
            				</select>
            			</div>
            			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            				<label for="display_order">Thứ tự hiển thị</label>
            				<input type="text" class="form-control" id="display_order" name="display_order" value="{{ old('display_order', $menuInfo->display_order) }}" placeholder="Thứ tự hiển thị">
            			</div>
        			</div>
					<div class="box-footer clearfix">
                        <div class="pull-left">
                            <a role="button" class="btn btn-primary" href="{{ route('backend.menu.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
                        </div>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    		<input type="hidden" name="_method" value="put">
                        </div>
                    </div>
    			</div>
    			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    @include('backend.menu.partials.menu_tree')
                </div>
            </div>
		</div>
		<!-- /.box-body -->
	</form>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
	$(document).ready(function() {
		Menu.validate({
        	menu_name: {
            	required: "{{ trans('validation.menu.menu_name.required') }}",
            	maxlength: "{{ trans('validation.menu.menu_name.maxlength') }}"
        	},
        	menu_code: {
            	maxlength: "{{ trans('validation.menu.menu_code.maxlength') }}"
			},
			route_name: {
            	maxlength: "{{ trans('validation.menu.route_name.maxlength') }}"
			},
			menu_icon: {
            	maxlength: "{{ trans('validation.menu.menu_icon.maxlength') }}"
        	},
			parent_id: {
            	required: "{{ trans('validation.menu.parent_id.required') }}"
        	},
        	status: {
				required: "{{ trans('validation.status.required') }}"
			},
			display_order: {
				required: "{{ trans('validation.display_order.required') }}",
				number: "{{ trans('validation.display_order.number') }}"
			}
    	});
	});
</script>
@stop