@foreach ($arrListDistrict as $district)
	<div class="form-group row" data-id="{{ $district->district_id }}">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<label>{{ $district->district_name }}</label>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
			<span class="label label-{!! $district->status == 1 ? 'success' : 'danger' !!}">{!! trans('common.status.' . $district->status) !!}</span>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
			@if (check_permission('location', 'update'))
				<a class="btn-show-sidebar" href="{!! route('backend.location.district.edit', [$district->district_id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
			@endif
			@if ($district->wards->count() == 0 && check_permission('location', 'delete'))
				<a data-delete="true" data-message="{{ trans('common.messages.location.district.delete') }}" data-parent="row" href="{!! route('backend.location.district.destroy', [$district->district_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
			@endif
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
			@if ($district->wards->count() > 0)
				<a href="{!! route('backend.location.ward.index', [$district->district_id]) !!}" data-panel="#ward_panel"><i class="glyphicon glyphicon-arrow-right"></i></a>
			@endif
		</div>
	</div>
@endforeach
<script type="text/javascript">
	$('#district_panel a[data-panel]:first').trigger('click');

	var link = $('#district_panel').parent().find('.box-header button').data('link');
	link = link.replace(0, '{!! $cityInfo->city_id !!}');
	$('#district_panel').parent().find('.box-header button').attr('data-link', link).data('link', link);
</script>