<div class="sidebar-content sidebar-sm">
	<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
	<form id="frmDistrict" name="frmDistrict" role="form" action="{{ route('backend.location.district.update', [$districtInfo->district_id]) }}" method="put">
		<div class="form-group">
			<label for="district_name" class="required">Tên quận/huyện/thị xã</label>
			<input type="text" class="form-control" id="district_name" name="district_name" value="{{ old('district_name', $districtInfo->district_name) }}" placeholder="Tên quận/huyện/thị xã">
		</div>
		<div class="form-group">
			<label for="city_id" class="required">Thành phố/tỉnh</label>
			<select class="form-control" id="city_id" name="city_id">
				<option value="">Chọn thành phố/tỉnh</option>
				@foreach ($arrListCity as $city)
					<option value="{{ $city->city_id }}"{!! old('city_id', $districtInfo->city_id) == $city->city_id ? ' selected="selected"' : '' !!}>{{ $city->city_name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="district_location">Vị trí</label>
			<input type="text" class="form-control" id="district_location" name="district_location" value="{{ old('district_location', $districtInfo->district_location) }}" placeholder="Vị trí">
			<p class="help-block">Kinh độ và vĩ độ, cách nhau bằng dấu phẩy ",".</p>
		</div>
		<div class="form-group text-right">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="put">
		</div>
	</form>
</div>
<script type="text/javascript">
	Location.validateDistrict({
		district_name: {
			required: "{{ trans('validation.location.district.district_name.required') }}",
			maxlength: "{{ trans('validation.location.district.district_name.maxlength') }}"
		},
		city_id: {
			required: "{{ trans('validation.location.district.city_id.required') }}"
		},
		district_location: {
			maxlength: "{{ trans('validation.location.district.district_location.maxlength') }}"
		}
	});
</script>