@if (count($arrListMenu) > 0)
    <div class="panel panel-success">
        <div class="panel-body">
            <ul class="nav">
                <li class="mb10">{{ trans('common.layout.home_title') }}</li>
                @foreach ($arrTreeMenu as $menu)
                    <li class="mb10">
                    	{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $menu->menu_level - 1) }}
                    	<i class="fa {{ $menu->menu_icon }}"></i>
                        {{ trans_by_locale($menu->menu_name, session('backend-locale')) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif