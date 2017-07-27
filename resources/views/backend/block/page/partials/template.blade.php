<div class="template-body">
    <form class="template-form" action="{{ route('backend.block.page.layout.widget.store.' . $type, [$pageInfo->page_code, $area]) }}" method="post">
        <input type="hidden" name="template_id" />
        <div class="template-dropdown">
            @foreach ($arrListTemplate as $template)
                <a href="#" class="template-dropdown-item" data-id="{{ $template->template_id }}" data-type="{{ $template->template_type }}" data-link="{{ route('backend.block.page.layout.template.detail', [$pageInfo->page_code, $template->template_id]) }}">
                    <img src="{{ image_url($template->template_thumbnail, 'medium') }}" class="img-responsive" />
                </a>
            @endforeach
        </div>
        <div class="template-content"></div>
        <div class="function-content"></div>
        <div class="template-footer">
            <button type="submit" class="btn btn-sm btn-primary btn-save" disabled="disabled">Save</button>
            <button type="button" class="btn btn-sm btn-info btn-cancel">Cancel</button>
        </div>
    </form>
</div>