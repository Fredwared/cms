@if ($arrListArticle->count() > 0)
    @foreach ($arrListArticle as $article)
        <tr>
            <td class="text-center">
                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $article->article_id }}" data-title="{{ $article->article_title }}" />
            </td>
            <td>
                @if (check_permission('article', 'update'))
                    <a href="{!! route('backend.article.edit', [$article->article_id]) !!}" target="_blank">
                        {{ $article->article_title }}
                    </a>
                @else
                    {{ $article->article_title }}
                @endif
            </td>
            <td>{{ $article->category->category_title }}</td>
            <td class="text-center">{{ trans('common.article.priority.' . $article->article_priority) }}</td>
            <td class="text-center">{{ format_date($article->published_at) }}</td>
        </tr>
    @endforeach
    <tr class="pagination-ajax">
        <td colspan="6">
            @include('backend.partials.pagination', ['arrData' => $arrListArticle, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
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