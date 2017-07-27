@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
	<div class="box-body">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="box box-primary box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Quốc gia</h3>
						@if (check_permission('location', 'insert'))
							<button role="button" class="btn btn-xs btn-primary btn-show-sidebar pull-right" data-link="{!! route('backend.location.country.create') !!}"><i class="glyphicon glyphicon-plus"></i></button>
						@endif
					</div>
					<div class="box-body" id="country_panel" data-link_sort="{{ route('backend.location.country.sort') }}">
						@foreach ($arrListCountry as $country)
							<div class="form-group row" data-id="{{ $country->country_id }}">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									@if (!empty($country->country_flag))
										<img src="{{ $country->country_flag }}" rel="{{ $country->country_name }}" class="img-circle w40px">
									@endif
									<label>{{ $country->country_name }}</label>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
									<span class="label label-{!! $country->status == 1 ? 'success' : 'danger' !!}">{!! trans('common.status.' . $country->status) !!}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
									@if (check_permission('location', 'update'))
										<a class="btn-show-sidebar" href="{!! route('backend.location.country.edit', [$country->country_id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
									@endif
									@if ($country->cities->count() == 0 && check_permission('location', 'delete'))
										<a data-delete="true" data-message="{{ trans('common.messages.location.country.delete') }}" data-parent="row" href="{!! route('backend.location.country.destroy', [$country->country_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
									@endif
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
									@if ($country->cities->count() > 0)
										<a href="{!! route('backend.location.city.index', [$country->country_id]) !!}" data-panel="#city_panel"><i class="glyphicon glyphicon-arrow-right"></i></a>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="box box-danger box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Thành phố/Tỉnh</h3>
						@if (check_permission('location', 'insert'))
							<button role="button" class="btn btn-xs btn-danger btn-show-sidebar pull-right" data-link="{!! route('backend.location.city.create', [0]) !!}"><i class="glyphicon glyphicon-plus"></i></button>
						@endif
					</div>
					<div class="box-body" id="city_panel" data-link_sort="{{ route('backend.location.city.sort') }}"></div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="box box-warning box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Quận/Huyện/Thị xã</h3>
						@if (check_permission('location', 'insert'))
							<button role="button" class="btn btn-xs btn-warning btn-show-sidebar pull-right" data-link="{!! route('backend.location.district.create', [0]) !!}"><i class="glyphicon glyphicon-plus"></i></button>
						@endif
					</div>
					<div class="box-body" id="district_panel" data-link_sort="{{ route('backend.location.district.sort') }}"></div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="box box-info box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Phường/Xã</h3>
						@if (check_permission('location', 'insert'))
							<button role="button" class="btn btn-xs btn-info btn-show-sidebar pull-right" data-link="{!! route('backend.location.ward.create', [0]) !!}"><i class="glyphicon glyphicon-plus"></i></button>
						@endif
					</div>
					<div class="box-body" id="ward_panel" data-link_sort="{{ route('backend.location.ward.sort') }}"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-ui.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		Location.init();
	});
</script>
@stop