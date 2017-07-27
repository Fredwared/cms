<?php
$arrInfo = json_decode($fileInfo->media_info, true);
$arrClass = ['pdf' => 'fa-file-pdf-o', 'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'doc' => 'fa-file-word-o', 'docx' => 'fa-file-word-o'];
 ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    	<div class="thumbnail mb10">
        	<i class="fa {{ $arrClass[$arrInfo['ext']] }} fa-5x"></i>
        </div>
    	<div class="form-group text-left">
            {{ $fileInfo->media_title }}
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
        <div class="form-group form-inline">
            <label>Bài viết: </label>
            {{ $fileInfo->articles->count() }}
        </div>
        <div class="form-group form-inline">
            <label>Sản phẩm: </label>
            {{ $fileInfo->products->count() }}
        </div>
        <div class="form-group form-inline">
            <label>Dung lượng: </label>
            {{ covert_size($arrInfo['size']) }}
        </div>
        <div class="form-group form-inline">
            <label>Ngày tạo: </label>
            {{ format_date($fileInfo->created_at)  }}
        </div>
        <div class="form-group form-inline">
            <label>Ngày cập nhật: </label>
            {{ format_date($fileInfo->updated_at)  }}
        </div>
        <div class="form-group clearfix">
        	 @if ($fileInfo->articles->count() <= 0 && check_permission('file', 'delete'))
                <a data-id="{{ $fileInfo->media_id }}" data-action="delete" data-message="{{ sprintf(trans('common.messages.media.file_delete'), $fileInfo->media_title) }}" href="{{ route('backend.media.destroy', [$fileInfo->media_id]) }}" class="btn btn-primary btn-sm pull-left" title="{{ trans('common.action.delete') }}">
                	<i class="fa fa-trash"></i> {{ trans('common.action.delete') }}
            	</a>
            @endif
            <a href="{{ route('backend.media.file.download', $fileInfo->media_id) }}" class="btn btn-primary btn-sm pull-right" title="Download">
            	<i class="fa fa-download"></i> Download
        	</a>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group text-left">
            <label>Nhãn: </label>
            <select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $fileInfo->media_type]) }}" data-placeholder="Chọn nhãn" data-tags="true" data-fields="label_name|label_name" id="media_label" name="media_label[]" multiple="multiple">
                @foreach (array_filter(explode(',', $fileInfo->media_label)) as $media_label)
                    <option value="{{ $media_label }}" selected="selected">{{ $media_label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-primary btn-updatelabel" data-link="{{ route('backend.media.updatelabel', [$fileInfo->media_id]) }}"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
        </div>
	</div>
</div>