@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.configwebsite.slide.index') }}">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <label class="mr05">Loại slide</label>
                                    <select class="form-control r04" name="type">
                                        @foreach (config('cms.backend.slide.type') as $value)
                                            <option value="{{ $value }}"{!! $value == $type ? ' selected="selected"' : '' !!}>{{ trans('common.slide.type.' . $value) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (count($arrLanguage) > 1)
                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                        <label class="mr05">Ngôn ngữ</label>
                                        <select class="form-control r04" name="language_id">
                                            @foreach ($arrLanguage as $lang => $data)
                                                <option value="{{ $lang }}"{!! $lang == $language_id ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <label class="mr05">{{ trans('common.status.title') }}</label>
                                    <select class="form-control r04" name="status">
                                        <option value="">{{ trans('common.status.all') }}</option>
                                        @foreach (config('cms.backend.status') as $name => $value)
                                            <option value="{{ $value }}"{!! $value == $status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (check_permission('slide', 'insert'))
            <div class="text-right mb10">
                <a role="button" class="btn btn-sm btn-primary" href="{!! route('backend.configwebsite.slide.create', [$language_id]) !!}"><i class="fa fa-plus"></i> {{ trans('common.action.add') }}</a>
            </div>
        @endif
        @include('backend.partials.pagination', ['arrData' => $arrListSlide, 'pagination' => $pagination, 'item' => $item, 'position' => 'top'])
    </div>
    <div class="box-body table-responsive">
        @if ($arrListSlide->count() > 0)
            @if (check_permission('slide', 'update'))
                @foreach (config('cms.backend.status') as $name => $value)
                    <button type="button" class="btn btn-sm btn-primary" data-status="true" data-link="{{ route('backend.configwebsite.slide.changestatus', [$value]) }}">{{ trans('common.status.' . $name) }}</button>
                @endforeach
                <button role="button" class="btn btn-sm btn-primary btn-show-sidebar" data-link="{!! route('backend.configwebsite.slide.sort', [$language_id, $type]) !!}">{{ trans('common.action.sort') }}</button>
            @endif
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="w10px">
                        <input type="checkbox" class="checkbox" id="chkAll" />
                    </th>
                    <th class="w10px">STT</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th class="w150px text-center">{{ trans('common.status.title') }}</th>
                    <th class="w100px text-center">{{ trans('common.action.title') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($arrListSlide->count() > 0)
                @foreach ($arrListSlide as $slide)
                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $slide->slide_id }}" />
                        </td>
                        <td class="text-center">
                            {{ $slide->slide_order }}
                        </td>
                        <td>
                            <img src="{{ image_url($slide->slide_image, 'small') }}" rel="{{ $slide->slide_title }}">
                        </td>
                        <td>
                            {{ $slide->slide_title }}
                        </td>
                        <td class="text-center">
                            <select class="form-control r04 wp100" data-forstatus="{{ $slide->slide_id }}" data-status="true" data-link="{{ route('backend.configwebsite.slide.changestatus', [0]) }}" data-old="{{ $slide->status }}"{!! check_permission('slide', 'update') ? '' : ' disabled="disabled"' !!}>
                                @foreach (config('cms.backend.status') as $name => $value)
                                    <option value="{{ $value }}"{!! $value == $slide->status ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            @if (check_permission('slide', 'update'))
                                <a href="{!! route('backend.configwebsite.slide.edit', [$slide->slide_id]) !!}" title="{{ trans('common.action.edit') }}"><i class="glyphicon glyphicon-edit"></i></a>
                            @endif
                            @if (check_permission('slide', 'delete'))
                                <a data-delete="true" data-message="{{ trans('common.messages.slide.delete') }}" href="{!! route('backend.configwebsite.slide.destroy', [$slide->slide_id]) !!}" title="{{ trans('common.action.delete') }}"><i class="glyphicon glyphicon-trash"></i></a>
                            @endif
                            <a href="{!! route('backend.log.index', ['model_name' => 'slide', 'model_id' => $slide->slide_id]) !!}" title="{{ trans('common.action.log') }}" target="_blank"><i class="fa fa-history"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">{{ trans('common.messages.nodata') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListSlide, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom'])
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop