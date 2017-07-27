@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
	<!-- form start -->
	<form id="frmBlockIp" name="frmBlockIp" role="form" action="{{ route('backend.blockip.update', [$blockipInfo->id]) }}" method="post">
		<div class="box-body">
			<div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                    <label for="ip_address" class="required">Địa chỉ ip</label>
                    <input type="text" class="form-control" id="ip_address" name="ip_address" value="{{ old('ip_address', $blockipInfo->ip_address) }}" placeholder="Địa chỉ ip">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                    <label for="status" class="required">{{ trans('common.status.title') }}</label>
                    <select class="form-control" id="status" name="status">
                    	@foreach (config('cms.backend.status') as $name => $value)
                        	<option value="{{ $value }}"{!! $value == old('status', $blockipInfo->status) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        	<label for="ip_description">Mô tả</label>
            <div class="row">
                @if (count(config('cms.backend.languages')) == 1)
                    <?php $key = config('cms.backend.default_locale'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                        <input type="text" class="form-control" id="ip_description_{!! $key !!}" name="ip_description[{!! $key !!}]" value="{{ old('ip_description.' . $key, trans_by_locale($blockipInfo->ip_description, $key)) }}" placeholder="Mô tả">
                    </div>
                @else
                    @foreach (config('cms.backend.languages') as $key => $lang)
                        <div class="col-lg-{{ 12 / count(config('cms.backend.languages')) }} col-md-{{ 12 / count(config('cms.backend.languages')) }} col-sm-12 col-xs-12 form-group has-feedback">
                            <input type="text" class="form-control" id="ip_description_{!! $key !!}" name="ip_description[{!! $key !!}]" value="{{ old('ip_description.' . $key, trans_by_locale($blockipInfo->ip_description, $key)) }}" placeholder="{{ $lang['native'] }}">
                            <span class="flag-icon flag-icon-background flag-icon-{!! $lang['flag'] !!} form-control-feedback flag-icon-embed"></span>
                        </div>
                    @endforeach
                @endif
            </div>
		</div>
		<!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="pull-left">
                <a role="button" class="btn btn-primary" href="{{ route('backend.blockip.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript">
	$(document).ready(function() {
		BlockIp.validate({
            ip_address: {
            	required: "{{ trans('validation.blockip.ip_address.required') }}",
            	maxlength: "{{ trans('validation.blockip.ip_address.maxlength') }}"
        	},
            ip_description: {
                maxlength: "{{ trans('validation.blockip.ip_description.maxlength') }}"
			},
        	status: {
				required: "{{ trans('validation.status.required') }}"
			}
    	});
	});
</script>
@stop