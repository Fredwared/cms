<div class="sidebar-content sidebar-sm">
	<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
	<form id="frmCountry" name="frmCountry" role="form" action="{{ route('backend.location.country.update', [$countryInfo->country_id]) }}" method="put">
		<div class="form-group">
			<label for="country_name" class="required">Tên quốc gia</label>
			<input type="text" class="form-control" id="country_name" name="country_name" value="{{ old('country_name', $countryInfo->country_name) }}" placeholder="Tên quốc gia">
		</div>
		<div class="form-group">
			<label for="country_code" class="required">Mã quốc gia</label>
			<input type="text" class="form-control" id="country_code" name="country_code" value="{{ old('country_code', $countryInfo->country_code) }}" placeholder="Mã quốc gia">
		</div>
		<div class="form-group text-right">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="put">
		</div>
	</form>
</div>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
<script type="text/javascript">
	Location.validateCountry({
		country_name: {
			required: "{{ trans('validation.location.country.country_name.required') }}",
			maxlength: "{{ trans('validation.location.country.country_name.maxlength') }}"
		},
		country_code: {
			required: "{{ trans('validation.location.country.country_code.required') }}",
			maxlength: "{{ trans('validation.location.country.country_code.maxlength') }}"
		}
	});
</script>