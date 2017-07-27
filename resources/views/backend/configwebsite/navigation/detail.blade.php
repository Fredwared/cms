<div class="panel panel-default navigation-item navigation-level-{{ $navigation->navigation_level }}" data-id="{{ $navigation->navigation_id }}" data-parent_id="{{ $navigation->parent_id }}" data-child="{{ $navigation->childs->count() }}">
    <div class="panel-heading" role="tab">
        <h3 class="panel-title clearfix">
            <div class="pull-left">
                <a href="#navigation_{{ $navigation->navigation_id }}" role="button" data-toggle="collapse" data-parent="#navigation_panel">{{ $navigation->navigation_title }}</a>
            </div>
            <div class="pull-right">
                <label>{{ ucfirst($navigation->navigation_type) }}</label>
                @if ($navigation->navigation_type == 'page')
                    <span>: <a href="{{ route('backend.configwebsite.page.edit', [$navigation->page->page_id]) }}" target="_blank">{{ $navigation->page->page_title }}</a></span>
                @elseif ($navigation->navigation_type == 'category')
                    <span>: <a href="{{ route('backend.article.category.edit', [$navigation->category->category_id]) }}" target="_blank">{{ $navigation->category->category_title }}</a></span>
                @endif
                <span class="ml05">&nbsp;</span>
                <button type="button" class="btn btn-xs btn-default btn-up" title="Di chuyển lên" style="display: none;"><i class="fa fa-angle-up"></i></button>
                <button type="button" class="btn btn-xs btn-default btn-down" title="Di chuyển xuống" style="display: none;"><i class="fa fa-angle-down"></i></button>
            </div>
        </h3>
    </div>
    <div id="navigation_{{ $navigation->navigation_id }}" class="panel-collapse collapse out" role="tabpanel">
        <form action="{{ route('backend.configwebsite.navigation.update', [$navigation->navigation_id]) }}" method="put">
            <div class="panel-body form-horizontal">
                @if ($navigation->navigation_type == 'custom')
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-12 control-label">Tên menu</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="navigation_title" class="form-control" value="{{ $navigation->navigation_title }}" data-old="{{ $navigation->navigation_title }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-12 control-label">Url</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="navigation_url" class="form-control" value="{{ $navigation->navigation_url }}" data-old="{{ $navigation->navigation_url }}" />
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-12 control-label">Tên menu</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="navigation_title" class="form-control" value="{{ $navigation->navigation_title }}" data-old="{{ $navigation->navigation_title }}" />
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="col-sm-3 col-xs-12 control-label">Menu cha</label>
                    <div class="col-sm-9 col-xs-12">
                        <select class="form-control" name="parent_id" data-old="{{ $navigation->parent_id }}">
                            <option value="0"{!! $navigation->parent_id == 0 ? ' selected="selected"' : '' !!}>--</option>
                            @foreach ($arrListParent as $parent)
                                <option value="{{ $parent->navigation_id }}"{!! $navigation->parent_id == $parent->navigation_id ? ' selected="selected"' : '' !!}{!! ($navigation->navigation_id == $parent->navigation_id || $navigation->navigation_id == $parent->parent_id) ? ' disabled="disabled"' : '' !!}>{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->navigation_level - 1) . $parent->navigation_title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">
                    <button type="submit" class="btn btn-xs btn-primary btn-save"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-xs btn-danger btn-delete" data-link="{{ route('backend.configwebsite.navigation.destroy', [$navigation->navigation_id]) }}" data-message="{{ trans('common.messages.navigation.delete') }}" title="{{ trans('common.button.delete') }}"><i class="fa fa-trash"></i> {{ trans('common.button.delete') }}</button>
                    <a href="#navigation_{{ $navigation->navigation_id }}" role="button" class="btn btn-xs btn-info btn-cancel" data-toggle="collapse" title="{{ trans('common.button.cancel') }}"><i class="fa fa-close"></i> {{ trans('common.button.cancel') }}</a>
                    <a href="{!! route('backend.log.index', ['model_name' => 'navigation', 'model_id' => $navigation->navigation_id]) !!}" role="button" class="btn btn-xs btn-info btn-cancel" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i> {{ trans('common.action.log') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>