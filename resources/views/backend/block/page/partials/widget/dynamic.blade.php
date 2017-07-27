<?php
$arrWidgetConfig = json_decode($widget->widget_config);
?>
<li class="widget-item {{ $widget->status == 1 ? 'active' : 'inactive' }}" data-id="{{ $widget->widget_id }}" data-status="{{ $widget->status }}">
    <div class="template-body">
        <div class="template-header">
            <div class="pull-left">
                <h4>{{ $widget->func->function_name . (isset($arrWidgetConfig->view->title) ? ' - ' . $arrWidgetConfig->view->title : '') }}</h4>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-primary btn-status" data-link="{{ route('backend.block.page.layout.widget.changestatus', [$widget->widget_id]) }}" data-message="{{ trans('common.messages.block.widget.status') }}"><i class="fa {{ $widget->status == 1 ? 'fa-unlock' : 'fa-lock' }}"></i></button>
                <button type="button" class="btn btn-xs btn-primary btn-edit" data-link="{{ route('backend.block.page.layout.widget.edit', [$widget->widget_id]) }}"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-xs btn-primary btn-delete" data-link="{{ route('backend.block.page.layout.widget.destroy', [$widget->widget_id]) }}" data-message="{{ trans('common.messages.block.widget.delete') }}"><i class="fa fa-trash"></i></button>
            </div>
            <div class="clearfix"></div>
        </div>
        <form class="template-form" action="{{ route('backend.block.page.layout.widget.update', [$widget->widget_id]) }}" method="put">
            <div class="template-content">
                <img src="{{ image_url($widget->template->template_thumbnail, 'medium') }}" class="img-responsive" />
            </div>
            <div class="function-content hidden"></div>
            <div class="template-footer hidden">
                <button type="submit" class="btn btn-sm btn-primary btn-save">Save</button>
                <button type="button" class="btn btn-sm btn-info btn-cancel">Cancel</button>
            </div>
        </form>
    </div>
</li>