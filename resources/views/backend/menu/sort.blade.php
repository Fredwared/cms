<div class="sidebar-content sidebar-sm">
    <div class="sidebar-header">{{ trans('common.action.sort') }}</div>
    <form id="frmSort" name="frmSort" role="form" action="{{ route('backend.menu.sort') }}" method="put">
        <div class="form-group sidebar-scroll sort-website">
            <ul>
                @foreach ($arrListMenu as $parent)
                    <li{!! $parent->childs->count() > 0 ? ' class="has-child"' : '' !!}>
                        <input type="hidden" name="menu[0][]" value="{{ $parent->menu_id }}">
                        <a href="#">{{ trans_by_locale($parent->menu_name, session('backend-locale')) }}</a>
                        @if ($parent->childs->count() > 0)
                            <ul>
                                @foreach ($parent->childs as $child)
                                    <li{!! $child->childs->count() > 0 ? ' class="has-child"' : '' !!}>
                                        <input type="hidden" name="menu[{{ $parent->menu_id }}][]" value="{{ $child->menu_id }}">
                                        <a href="#">{{ trans_by_locale($child->menu_name, session('backend-locale')) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="put">
        </div>
    </form>
</div>
<script type="text/javascript">
    Backend.initSort();
</script>