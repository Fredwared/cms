<ul class="sidebar-menu">
	<li class="header">{{ trans('common.layout.sidebar_header') }}</li>
    <?php $index = 1; $parent_id = 0; $menu_code = $namespace . '_' . $controller . '_index'; ?>
    @foreach ($arrListMenu as $menu)
        @if (!empty($menu->route_name) && !in_array($menu->menu_id, $arrMenuByUser))
            @continue
        @endif
        @if ($menu->menu_level == 1)
            @if ($index > 1)
                @if ($parent_id != 0)
                    </ul>
                @endif
                </li>
            @endif
            @if (!empty(array_intersect($menu->childs->pluck('menu_id')->toArray(), $arrMenuByUser)))
                <li class="treeview{!! $menu->menu_code == $menu_code ? ' active' : '' !!}">
                    <a href="{!! !empty($menu->route_name) ? route($menu->route_name) : '#' !!}">
                        <i class="fa {{ $menu->menu_icon }}"></i>
                        <span>{{ trans_by_locale($menu->menu_name, session('backend-locale')) }}</span>
                        @if (empty($menu->route_name))
                            <i class="fa fa-angle-left pull-right"></i>
                        @endif
                    </a>
                    @if (empty($menu->route_name))
                        <ul class="treeview-menu">
                    @endif
            @endif
        @else
            <li{!! $menu->menu_code == $menu_code ? ' class="active"' : '' !!}>
                <a href="{!! !empty($menu->route_name) ? route($menu->route_name) : '#' !!}">
                    <i class="fa {{ $menu->menu_icon }}"></i>
                    <span>{{ trans_by_locale($menu->menu_name, session('backend-locale')) }}</span>
                </a>
            </li>
        @endif
        <?php $parent_id = $menu->parent_id; $index++; ?>
    @endforeach
</ul>