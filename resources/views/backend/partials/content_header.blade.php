<h1>{{ trans('common.layout.' . strtolower($action)) }}</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('backend.index') }}"><i class="fa fa-dashboard"></i> {{ trans('common.layout.home_title') }}</a></li>
    @if ($menuInfo)
        @if ($menuInfo->parent_id != 0)
        	<li>{{ trans_by_locale($menuInfo->parent->menu_name, session('backend-locale')) }}</li>
        @endif
        <li class="active">
            @if ($action == 'index')
            	{{ trans_by_locale($menuInfo->menu_name, session('backend-locale')) }}
        	@else
        		<a href="{{ route($menuInfo->route_name) }}">{{ trans_by_locale($menuInfo->menu_name, session('backend-locale')) }}</a>
        	@endif
    	</li>
	@endif
</ol>