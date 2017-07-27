@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <form method="get" action="{{ route('backend.article.buildtop.index') }}" id="frmSearch" name="frmSearch">
            <div class="panel panel-info">
                <div class="panel-body bg-info">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group">
                            <label class="mr05">Vị trí</label>
                            <select class="form-control r04" name="category_id">
                                <option value="0">Trang chủ</option>
                                @foreach ($arrListCategory as $category)
                                    <option value="{{ $category->category_id }}"{!! $category->category_id == $category_id ? ' selected="selected"' : '' !!}>{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (count($arrLanguage) > 1)
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group">
                                <label for="language_id" class="required">Ngôn ngữ</label>
                                <select class="form-control" name="language_id">
                                    @foreach ($arrLanguage as $lang => $data)
                                        <option value="{{ $lang }}"{!! $lang == $language_id ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="language_id" value="{{ $language_id }}">
                        @endif
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label>Tiêu đề</label>
                            <input type="text" class="form-control r04" name="title">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 form-group">
                            <label class="mr05">Ngày xuất bản</label>
                            <div class="clearfix">
                                <div class="input-group date pull-left wp48" id="date_from">
                                    <input type="text" class="form-control r04" name="date_from" placeholder="Từ ngày" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <div class="input-group date pull-right wp48" id="date_to">
                                    <input type="text" class="form-control r04" name="date_to" placeholder="Đến ngày" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
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
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                @if (check_permission('buildtop', 'update'))
                    <button type="button" class="btn btn-sm btn-primary" id="btnSave" data-link="{{ route('backend.product.buildtop.save') }}"><i class="fa fa-save"></i> Lưu</button>
                @endif
                @if (check_permission('buildtop', 'delete'))
                    <button type="button" class="btn btn-sm btn-primary" id="btnDelete" data-link="{{ route('backend.product.buildtop.destroy') }}"><i class="fa fa-trash"></i> Xóa</button>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover" id="tblListBuildtop" data-link="{{ route('backend.product.buildtop.index') }}">
                        <thead>
                            <tr>
                                <th class="w10px">
                                    <input type="checkbox" class="checkbox" id="chkAll" />
                                </th>
                                <th class="w10px">STT</th>
                                <th>Tiêu đề</th>
                                <th class="text-center">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('backend.product.buildtop.listbuildtop')
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                @if (check_permission('buildtop', 'insert'))
                    <button type="button" class="btn btn-sm btn-primary" id="btnAdd"><i class="fa fa-plus"></i> Thêm</button>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover" id="tblListSearch" data-link="{{ route('backend.product.buildtop.listproduct') }}">
                        <thead>
                            <tr>
                                <th class="w10px">
                                    <input type="checkbox" class="checkbox" id="chkAll" />
                                </th>
                                <th>Tiêu đề</th>
                                <th>Chuyên mục</th>
                                <th class="text-center">Độ ưu tiên</th>
                                <th class="text-center">Ngày xuất bản</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
        Buildtop.init();
    });
</script>
@stop