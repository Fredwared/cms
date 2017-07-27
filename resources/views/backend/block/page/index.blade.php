@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        @if (check_permission('page', 'insert'))
            <div class="text-left">
                <button type="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.block.page.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</button>
            </div>
        @endif
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tên trang</th>
                    <th>Code</th>
                    <th class="text-center">Loại layout</th>
                    <th>Url</th>
                    <th class="text-center">Ngày cập nhật</th>
                    <th class="text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrListPage->count() > 0)
                    @foreach ($arrListPage as $page)
                        <tr>
                            <td>
                                @if (check_permission('page', 'update'))
                                    <a class="btn-show-sidebar" href="{!! route('backend.block.page.edit', [$page->page_code]) !!}">{{ $page->page_name }}</a>
                                @else
                                    {{ $page->page_name }}
                                @endif
                            </td>
                            <td>{{ $page->page_code }}</td>
                            <td class="text-center">{{ trans('common.block.page.layout.' . $page->page_layout) }}</td>
                            <td>{{ $page->page_url }}</td>
                            <td class="text-center">{{ format_date($page->updated_at) }}</td>
                            <td class="text-center">
                                @if (check_permission('page', 'update'))
                                    <a class="btn-show-sidebar" href="{!! route('backend.block.page.edit', [$page->page_code]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                @endif
                                @if (check_permission('page', 'delete'))
                                    <a data-delete="true" data-message="{{ trans('common.messages.block.page.delete') }}"  href="{!! route('backend.block.page.destroy', [$page->page_code]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                                @endif
                                @if (check_permission('page', 'view'))
                                    <a href="{!! route('backend.block.page.layout.index', [$page->page_code]) !!}" title="Xem layout"><i class="glyphicon glyphicon-equalizer"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">{{ trans('common.messages.nodata') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop