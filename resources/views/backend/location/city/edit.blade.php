<div class="sidebar-content sidebar-sm">
	<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
	<form id="frmCity" name="frmCity" role="form" action="{{ route('backend.location.city.update', [$cityInfo->city_id]) }}" method="put">
		<div class="form-group">
			<label for="city_name" class="required">Tên thành phố/tỉnh</label>
			<input type="text" class="form-control" id="city_name" name="city_name" value="{{ old('city_name', $cityInfo->city_name) }}" placeholder="Tên thành phố/tỉnh">
		</div>
		<div class="form-group">
			<label for="country_id" class="required">Quốc gia</label>
			<select class="form-control" id="country_id" name="country_id">
				<option value="">Chọn quốc gia</option>
				@foreach ($arrListCountry as $country)
					<option value="{{ $country->country_id }}"{!! old('country_id', $cityInfo->country_id) == $country->country_id ? ' selected="selected"' : '' !!}>{{ $country->country_name }}</option>
				@endforeach
			</select>
		</div>
        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="city_zipcode">Zip code</label>
                <input type="text" class="form-control" id="city_zipcode" name="city_zipcode" value="{{ old('city_zipcode', $cityInfo->city_zipcode) }}" placeholder="Zip code">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="city_location">Vị trí</label>
                <input type="text" class="form-control" id="city_location" name="city_location" value="{{ old('city_location', $cityInfo->city_location) }}" placeholder="Vị trí">
                <p class="help-block">Kinh độ và vĩ độ, cách nhau bằng dấu phẩy ",".</p>
            </div>
        </div>
		<div class="form-group text-right">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="put">
		</div>
	</form>
</div>
<script type="text/javascript">
	Location.validateCity({
        city_name: {
            required: "{{ trans('validation.location.city.city_name.required') }}",
            maxlength: "{{ trans('validation.location.city.city_name.maxlength') }}"
        },
        country_id: {
            required: "{{ trans('validation.location.city.country_id.required') }}"
        },
        city_zipcode: {
            number: "{{ trans('validation.location.city.city_zipcode.number') }}"
        },
        city_location: {
            maxlength: "{{ trans('validation.location.city.city_location.maxlength') }}"
        }
    });
</script>