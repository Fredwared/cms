<div class="media-content-header pagination-ajax">
    @include('backend.partials.pagination', ['arrData' => $arrListMedia, 'pagination' => $pagination, 'item' => $item, 'position' => 'top', 'showpaging' => false])
</div>
<div class="media-content-body mb20 clearfix">
    @if (!$arrListMedia->isEmpty())
        @foreach ($arrListMedia as $file)
            <?php
            $arrInfo = json_decode($file->media_info, true);
            $arrClass = ['pdf' => 'fa-file-pdf-o', 'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'doc' => 'fa-file-word-o', 'docx' => 'fa-file-word-o'];
            ?>
            <div>
                <div class="media-item clearfix pb05 pt05" data-id="{{ $file->media_id }}" data-filename="{{ $file->media_filename }}" data-title="{{ $file->media_title }}">
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 filename" title="{{ $file->media_title }}">
                        <i class="fa {{ $arrClass[$arrInfo['ext']] }}"></i>
                        <span class="ml05">{{ $file->media_title }}</span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{ format_date($file->created_at) }}
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{ covert_size($arrInfo['size']) }}
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<div class="media-content-footer pagination-ajax">
    @include('backend.partials.pagination', ['arrData' => $arrListMedia, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
</div>