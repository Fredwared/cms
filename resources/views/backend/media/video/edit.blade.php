@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
@stop

@section('content')
<div class="box box-primary">
	<!-- form start -->
	<form id="frmMediaVideo" name="frmMediaVideo" role="form" action="{{ route('backend.media.video.update', [$videoInfo->media_id]) }}" method="post">
		<div class="box-body">
			<div class="row">
    			<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        			<div class="form-group">
        				<label for="media_filename">Link video</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="media_filename" name="media_filename" value="{{ old('media_filename', $videoInfo->media_filename) }}" data-link="{{ route('backend.utils.videoinfo') }}" placeholder="Link video">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat btn-preview">Xem trước</button>
                            </span>
                        </div>
        			</div>
        			<div class="form-group">
        				<label for="media_source">Nguồn video</label>
        				<select class="form-control" id="media_source" name="media_source">
        					@foreach (config('cms.backend.media.source') as $name => $link)
                                <option value="{{ $name }}" data-link="{{ $link }}"{!! $name == old('media_source', $videoInfo->media_source) ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
        				</select>
        			</div>
        			<div class="form-group">
        				<label for="menu_classname">Nhãn video</label>
        				<select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $videoInfo->media_type]) }}" data-placeholder="Chọn nhãn" data-tags="true" data-fields="label_name|label_name" id="media_label" name="media_label[]" multiple="multiple">
        					@foreach (old('media_label', array_filter(explode(',', $videoInfo->media_label))) as $label)
        						<option value="{{ $label }}" selected="selected">{{ $label }}</option>
        					@endforeach
        				</select>
        			</div>
    			</div>
    			<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 form-group">
    				<label>Xem trước video</label>
					<div class="embed-responsive embed-responsive-16by9" id="previewVideo">
						<iframe class="embed-responsive-item" src="{{ old('media_filename', $videoInfo->media_filename) }}"></iframe>
					</div>
    			</div>
            </div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer clearfix">
			<div class="pull-left">
				<a role="button" class="btn btn-primary" href="{{ route('backend.media.video.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('common.button.back') }}</a>
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
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
        Media.validateVideo({
        	media_filename: {
            	required: "{{ trans('validation.media.video.media_filename.required') }}",
            	maxlength: "{{ trans('validation.media.video.media_filename.maxlength') }}"
        	},
        	media_source: {
        		required: "{{ trans('validation.media.video.media_source.required') }}"
			}
    	});
        Backend.multiSelect();
	});
</script>
@stop