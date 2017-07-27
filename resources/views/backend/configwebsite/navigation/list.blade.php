@if (count($arrListNavigation) > 0)
    @foreach ($arrListNavigation as $navigation)
        @include('backend.configwebsite.navigation.detail', ['navigation' => $navigation])
    @endforeach
@endif