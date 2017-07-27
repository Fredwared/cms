@foreach ($arrListWard as $ward)
	<div class="form-group row" data-id="{{ $ward->ward_id }}">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<label>{{ $ward->ward_name }}</label>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
			<span class="label label-{!! $ward->status == 1 ? 'success' : 'danger' !!}">{!! trans('common.status.' . $ward->status) !!}</span>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if (check_permission('location', 'update'))
				<a class="btn-show-sidebar" href="{!! route('backend.location.ward.edit', [$ward->ward_id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
			@endif
			@if (check_permission('location', 'delete'))
				<a data-delete="true" data-message="{{ trans('common.messages.location.ward.delete') }}" data-parent="row" href="{!! route('backend.location.ward.destroy', [$ward->ward_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
			@endif
		</div>
	</div>
@endforeach
<script type="text/javascript">
	var link = $('#ward_panel').parent().find('.box-header button').data('link');
	link = link.replace(0, '{!! $districtInfo->district_id !!}');
	$('#ward_panel').parent().find('.box-header button').attr('data-link', link).data('link', link);
</script>