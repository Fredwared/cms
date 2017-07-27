<div class="sidebar-content sidebar-sm">
	<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
	<form id="frmWard" name="frmWard" role="form" action="{{ route('backend.location.ward.update', [$wardInfo->ward_id]) }}" method="put">
		<div class="form-group">
			<label for="ward_name" class="required">Tên phường/xã</label>
			<input type="text" class="form-control" id="ward_name" name="ward_name" value="{{ old('ward_name', $wardInfo->ward_name) }}" placeholder="Tên phường/xã">
		</div>
		<div class="form-group">
			<label for="district_id" class="required">Quận/huyện/thị xã</label>
			<select class="form-control" id="district_id" name="district_id">
				<option value="">Chọn quận/huyện/thị xã</option>
				@foreach ($arrListDistrict as $district)
					<option value="{{ $district->district_id }}"{!! old('district_id', $wardInfo->district_id) == $district->district_id ? ' selected="selected"' : '' !!}>{{ $district->district_name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="ward_location">Vị trí</label>
			<input type="text" class="form-control" id="ward_location" name="ward_location" value="{{ old('ward_location', $wardInfo->ward_location) }}" placeholder="Vị trí">
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
	Location.validateWard({
		ward_name: {
			required: "{{ trans('validation.location.ward.ward_name.required') }}",
			maxlength: "{{ trans('validation.location.ward.ward_name.maxlength') }}"
		},
		district_id: {
			required: "{{ trans('validation.location.ward.district_id.required') }}"
		},
		ward_location: {
			maxlength: "{{ trans('validation.location.ward.ward_location.maxlength') }}"
		}
	});
</script>