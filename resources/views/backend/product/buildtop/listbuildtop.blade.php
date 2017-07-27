@if ($arrListBuildtop->count() > 0)
    @foreach ($arrListBuildtop as $index => $data)
        <tr data-id="{{ $data->id }}">
            <td class="text-center">
                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $data->id }}" data-type_id="{{ $data->type_id }}" />
            </td>
            <td class="text-center order">{{ $index + 1 }}</td>
            <td>{{ $data->product->product_title }}</td>
            <td class="text-center">{{ format_date($data->updated_at) }}</td>
        </tr>
    @endforeach
@else
    <tr class="nodata">
        <td colspan="4" class="text-center">{{ trans('common.messages.nodata') }}</td>
    </tr>
@endif