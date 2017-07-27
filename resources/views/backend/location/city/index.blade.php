@foreach ($arrListCity as $city)
	<div class="form-group row" data-id="{{ $city->city_id }}">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<label>{{ $city->city_name }}</label>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
			<span class="label label-{!! $city->status == 1 ? 'success' : 'danger' !!}">{!! trans('common.status.' . $city->status) !!}</span>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
			@if (check_permission('location', 'update'))
				<a class="btn-show-sidebar" href="{!! route('backend.location.city.edit', [$city->city_id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
			@endif
			@if ($city->districts->count() == 0 && check_permission('location', 'delete'))
				<a data-delete="true" data-message="{{ trans('common.messages.location.city.delete') }}" data-parent="row" href="{!! route('backend.location.city.destroy', [$city->city_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
			@endif
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
			@if ($city->districts->count() > 0)
				<a href="{!! route('backend.location.district.index', [$city->city_id]) !!}" data-panel="#district_panel"><i class="glyphicon glyphicon-arrow-right"></i></a>
			@endif
		</div>
	</div>
@endforeach
<script type="text/javascript">
	$('#city_panel a[data-panel]:first').trigger('click');

    var link = $('#city_panel').parent().find('.box-header button').data('link');
    link = link.replace(0, '{!! $countryInfo->country_id !!}');
    $('#city_panel').parent().find('.box-header button').attr('data-link', link).data('link', link);
</script>