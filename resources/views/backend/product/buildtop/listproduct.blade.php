@if ($arrListProduct->count() > 0)
    @foreach ($arrListProduct as $product)
        <tr>
            <td class="text-center">
                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $product->product_id }}" data-title="{{ $product->product_title }}" />
            </td>
            <td>
                @if (check_permission(config('cms.backend.product.name.' . $product->product_type), 'update'))
                    <a href="{!! route('backend.product.' . config('cms.backend.product.name.' . $product->product_type) . '.edit', [$product->product_id]) !!}" target="_blank">
                        {{ $product->product_title }}
                    </a>
                @else
                    {{ $product->product_title }}
                @endif
            </td>
            <td>{{ $product->category->category_title }}</td>
            <td class="text-center">{{ trans('common.product.priority.' . $product->product_priority) }}</td>
            <td class="text-center">{{ format_date($product->published_at) }}</td>
        </tr>
    @endforeach
    <tr class="pagination-ajax">
        <td colspan="6">
            @include('backend.partials.pagination', ['arrData' => $arrListProduct, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
        </td>
    </tr>
    <script type="text/javascript">
        //click paging
        Backend.initPagingAjax(function(obj, link) {
            $.ajax({
                url: link,
                method: 'get',
                success: function(data) {
                    $('#tblListSearch').find('tbody').html(data);
                }
            });
        });
    </script>
@else
    <tr class="nodata">
        <td colspan="6" class="text-center">{{ trans('common.messages.nodata') }}</td>
    </tr>
@endif