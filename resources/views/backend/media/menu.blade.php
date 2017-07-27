@if ($type == 'menu')
    @if (check_permission($typeName, 'update'))
        <li><a href="#" data-link="{{ route('backend.media.' . $typeName . '.edit', $mediaInfo->media_id) }}" data-action="edit"><i class="fa fa-edit"></i> {{ trans('common.folder.edit_file') }}</a></li>
    @endif
    @if (check_permission($typeName, 'delete'))
        <li><a href="#" data-message="{{ sprintf(trans('common.messages.media.' . $typeName . '_delete'), $mediaInfo->media_title) }}" data-link="{{ route('backend.media.destroy', $mediaInfo->media_id) }}" data-action="delete"><i class="fa fa-trash"></i> {{ trans('common.folder.delete_file') }}</a></li>
    @endif
    <li><a href="#" data-link="{{ $mediaInfo->media_type == 1 ? image_url($mediaInfo, 'download') : route('backend.media.file.download', $mediaInfo->media_id) }}" data-action="download"><i class="fa fa-download"></i> {{ trans('common.folder.download_file') }}</a></li>
@else
	@if (check_permission($typeName, 'update'))
        <a href="#" class="btn btn-sm btn-primary mr05 mt05 mb05" role="button" data-link="{{ route('backend.media.' . $typeName . '.edit', $mediaInfo->media_id) }}" data-action="edit"><i class="fa fa-edit"></i> {{ trans('common.folder.edit_file') }}</a>
    @endif
    @if (check_permission($typeName, 'delete'))
        <a href="#" class="btn btn-sm btn-primary mr05 mt05 mb05" role="button" data-message="{{ sprintf(trans('common.messages.media.' . $typeName . '_delete'), $mediaInfo->media_title) }}" data-link="{{ route('backend.media.destroy', $mediaInfo->media_id) }}" data-action="delete"><i class="fa fa-trash"></i> {{ trans('common.folder.delete_file') }}</a>
    @endif
    <a href="#" class="btn btn-sm btn-primary mr05 mt05 mb05" role="button" data-link="{{ $mediaInfo->media_type == 1 ? image_url($mediaInfo, 'download') : route('backend.media.file.download', $mediaInfo->media_id) }}" data-action="download"><i class="fa fa-download"></i> {{ trans('common.folder.download_file') }}</a>
@endif