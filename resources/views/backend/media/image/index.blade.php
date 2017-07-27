<div class="media-content-header pagination-ajax">
    @include('backend.partials.pagination', ['arrData' => $arrListMedia, 'pagination' => $pagination, 'item' => $item, 'position' => 'top', 'showpaging' => false])
</div>
<div class="media-content-body clearfix">
    @if (!$arrListMedia->isEmpty())
        @foreach ($arrListMedia as $image)
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 pl05 pr05" title="{{ $image->media_title }}">
                <div class="thumbnail media-item" data-id="{{ $image->media_id }}" data-filename="{{ image_url($image) }}" data-title="{{ $image->media_title }}">
                    <img src="{{ image_url($image, 'medium') }}" class="img-responsive" alt="{{ $image->media_title }}">
                </div>
            </div>
        @endforeach
    @endif
</div>
<div class="media-content-footer pagination-ajax">
    @include('backend.partials.pagination', ['arrData' => $arrListMedia, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
</div>