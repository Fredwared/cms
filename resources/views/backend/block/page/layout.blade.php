@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        @if (check_permission('page', 'update'))
            <ul id="widget_type" class="widget-type">
                @foreach (config('cms.backend.block.type') as $type)
                    <li data-type="{{ $type }}">Thêm widget {{ $type }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('backend.block.page.partials.layout.top', ['arrListWidget' => $arrListWidgetTop])
            </div>
            @if ($pageInfo->page_layout == 1)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @include('backend.block.page.partials.layout.center', ['arrListWidget' => $arrListWidgetCenter])
                </div>
            @elseif ($pageInfo->page_layout == 2)
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr0">
                    @include('backend.block.page.partials.layout.left', ['arrListWidget' => $arrListWidgetLeft])
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl0">
                    @include('backend.block.page.partials.layout.right', ['arrListWidget' => $arrListWidgetRight])
                </div>
            @else
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pr0">
                    @include('backend.block.page.partials.layout.left', ['arrListWidget' => $arrListWidgetLeft])
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pr0 pl0">
                    @include('backend.block.page.partials.layout.center', ['arrListWidget' => $arrListWidgetCenter])
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl0">
                    @include('backend.block.page.partials.layout.right', ['arrListWidget' => $arrListWidgetRight])
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('backend.block.page.partials.layout.bottom', ['arrListWidget' => $arrListWidgetBottom])
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery-ui.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Block.initLayout();
    });
</script>
@stop