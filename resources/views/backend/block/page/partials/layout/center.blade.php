<div class="widget-box">
    <ul class="widget-list" data-area="center" data-link_template="{{ route('backend.block.page.layout.template', [$pageInfo->page_code]) }}" data-link_sort="{{ route('backend.block.page.layout.widget.sort') }}">
        @if (!$arrListWidget->isEmpty())
            @foreach ($arrListWidget as $widget)
                @include('backend.block.page.partials.widget.' . $widget->widget_type, ['widget' => $widget])
            @endforeach
        @else
            <li class="widget-item no-sortable"></li>
        @endif
    </ul>
</div>