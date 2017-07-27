@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            @if (check_permission('function', 'insert'))
                <div class="text-left">
                    <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.block.function.create') !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</button>
                </div>
            @endif
        </div>
        <div class="box-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tên function</th>
                        <th>Params</th>
                        <th>Type</th>
                        <th>Templates</th>
                        <th class="text-center">Ngày cập nhật</th>
                        <th class="text-center">{{ trans('common.action.title') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($arrListFunction->count() > 0)
                        @foreach ($arrListFunction as $function)
                            <tr>
                                <td>
                                    @if (check_permission('function', 'update'))
                                        <a class="btn-show-sidebar" href="{!! route('backend.block.function.edit', [$function->function_id]) !!}">{{ $function->function_name }}</a>
                                    @else
                                        {{ $function->function_name }}
                                    @endif
                                </td>
                                <td>{{ $function->function_params }}</td>
                                <td>{{ ucfirst($function->function_type) }}</td>
                                <td>
                                    @if ($function->templates->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach ($function->templates as $template)
                                                <li>
                                                    @if (check_permission('template', 'update'))
                                                        <a class="btn-show-sidebar" href="{!! route('backend.block.template.edit', [$template->template_id]) !!}">{{ $template->template_name }}</a>
                                                    @else
                                                        {{ $template->template_name }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="text-center">{{ format_date($function->updated_at) }}</td>
                                <td class="text-center">
                                    @if (check_permission('function', 'update'))
                                        <a class="btn-show-sidebar" href="{!! route('backend.block.function.edit', [$function->function_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                                    @endif
                                    @if (check_permission('function', 'delete'))
                                        <a data-delete="true" data-message="{{ trans('common.messages.block.function.delete') }}"  href="{!! route('backend.block.function.destroy', [$function->function_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">{{ trans('common.messages.nodata') }}</td>
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