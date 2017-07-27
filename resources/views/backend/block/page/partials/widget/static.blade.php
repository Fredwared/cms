<li class="widget-item {{ $widget->status == 1 ? 'active' : 'inactive' }}" data-id="{{ $widget->widget_id }}" data-status="{{ $widget->status }}">
    <div class="template-body">
        <div class="template-header">
            <div class="pull-left">
                <h4>{{ $widget->template->template_name }}</h4>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-primary btn-status" data-link="{{ route('backend.block.page.layout.widget.changestatus', [$widget->widget_id]) }}" data-message="{{ trans('common.messages.block.widget.status') }}"><i class="fa {{ $widget->status == 1 ? 'fa-unlock' : 'fa-lock' }}"></i></button>
                <button type="button" class="btn btn-xs btn-primary btn-delete" data-link="{{ route('backend.block.page.layout.widget.destroy', [$widget->widget_id]) }}" data-message="{{ trans('common.messages.block.widget.delete') }}"><i class="fa fa-trash"></i></button>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="template-content">{!! $widget->template->template_content !!}</div>
    </div>
</li>