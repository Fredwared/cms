@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            @if (check_permission('template', 'insert'))
                <div class="text-left">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ trans('common.action.add') }}
                            <span class="fa fa-caret-down"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach (config('cms.backend.block.type') as $type)
                                <li><a href="{!! route('backend.block.template.create', [$type]) !!}" class="btn-show-sidebar">{{ ucfirst($type) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="box-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w100px text-center">Hình đại diện</th>
                        <th>Tên template</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Area</th>
                        <th>Functions</th>
                        <th class="text-center">Ngày cập nhật</th>
                        <th class="text-center">{{ trans('common.action.title') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($arrListTemplate->count() > 0)
                        @foreach ($arrListTemplate as $template)
                            <tr>
                                <td class="text-center"><img src="{{ image_url($template->template_thumbnail) }}" class="img-responsive" /></td>
                                <td>
                                    @if (check_permission('template', 'update'))
                                        <a class="btn-show-sidebar" href="{!! route('backend.block.template.edit', [$template->template_id]) !!}">{{ $template->template_name }}</a>
                                    @else
                                        {{ $template->template_name }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $template->template_type }}</td>
                                <td class="text-center">{{ $template->template_area }}</td>
                                <td>
                                    @if ($template->functions->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach ($template->functions as $function)
                                                <li>
                                                    @if (check_permission('function', 'update'))
                                                        <a class="btn-show-sidebar" href="{!! route('backend.block.function.edit', [$function->function_id]) !!}">{{ $function->function_name }}</a>
                                                    @else
                                                        {{ $function->function_name }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="text-center">{{ format_date($template->updated_at) }}</td>
                                <td class="text-center">
                                    @if (check_permission('template', 'update'))
                                        <a class="btn-show-sidebar" href="{!! route('backend.block.template.edit', [$template->template_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                    @endif
                                    @if (check_permission('template', 'delete'))
                                        <a data-delete="true" data-message="{{ trans('common.messages.block.template.delete') }}"  href="{!! route('backend.block.template.destroy', [$template->template_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">{{ trans('common.messages.nodata') }}</td>
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
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
@stop