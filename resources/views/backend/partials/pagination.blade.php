@if ($arrData->total() > 0)
    @if ($position == 'bottom' && $arrData->total() > $item)
        <div class="text-center pb05">{{ $pagination }}</div>
    @endif
    <div class="clearfix">
        <div class="pull-left pt05">Hiển thị từ {{ $arrData->firstItem() }} đến {{ $arrData->lastItem() }} trong tổng số {{ $arrData->total() }} dòng.</div>
        @if (!isset($showpaging) || $showpaging)
            <div class="pull-right">
                <select class="form-control r04" data-pagination="true">
                    @foreach (config('cms.backend.pagination.list') as $value)
                        <option value="{{ $value }}"{!! $value == $item ? ' selected="selected"' : '' !!}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    @if ($position == 'top' && $arrData->total() > $item)
        <div class="text-center pt05">{{ $pagination }}</div>
    @endif
@endif