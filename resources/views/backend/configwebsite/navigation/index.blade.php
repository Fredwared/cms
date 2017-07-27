@extends('backend.layouts.dashboard')

@section('css')
<!-- css link here -->
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        @if (count($arrLanguage) > 1)
            <form method="get" action="{{ route('backend.configwebsite.navigation.index') }}">
                <div class="panel panel-info">
                    <div class="panel-body bg-info">
                        <div class="form-group w150px">
                            <label class="mr05">Ngôn ngữ</label>
                            <select class="form-control r04" name="language_id">
                                @foreach ($arrLanguage as $lang => $data)
                                    <option value="{{ $lang }}"{!! $lang == $language_id ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-5 col-xs-5">
                <div id="menu_settings_column" class="panel-group" role="tablist">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a href="#custom" role="button" data-toggle="collapse" data-parent="#menu_settings_column">Link</a>
                            </h3>
                        </div>
                        <div id="custom" class="panel-collapse collapse in" role="tabpanel">
                            <form action="{{ route('backend.configwebsite.navigation.store', [$language_id, 'custom']) }}" method="post">
                                <div class="panel-body form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">Tên menu</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" name="navigation_title" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">Url</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" name="navigation_url" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">Menu cha</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <select class="form-control" name="parent_id">
                                                <option value="0">--</option>
                                                @foreach ($arrListParent as $parent)
                                                    <option value="{{ $parent->navigation_id }}">{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->navigation_level - 1) . $parent->navigation_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb0">
                                        <div class="col-sm-12 col-xs-12 text-right">
                                            <button type="submit" class="btn btn-primary btn-sm btn-add">Thêm vào menu</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (count($arrListPage) > 0)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a href="#page" role="button" data-toggle="collapse" data-parent="#menu_settings_column">Page</a>
                                </h3>
                            </div>
                            <div id="page" class="panel-collapse collapse out" role="tabpanel">
                                <form action="{{ route('backend.configwebsite.navigation.store', [$language_id, 'page']) }}" method="post">
                                    <div class="panel-body">
                                        @foreach ($arrListPage as $page)
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="navigation_type_id[{{ $page->page_id }}]" value="{{ $page->page_title }}" />{{ $page->page_title }}</label>
                                            </div>
                                        @endforeach
                                        <div class="form-group">
                                            <label>Menu cha</label>
                                            <select class="form-control" name="parent_id">
                                                <option value="0">--</option>
                                                @foreach ($arrListParent as $parent)
                                                    <option value="{{ $parent->navigation_id }}">{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->navigation_level - 1) . $parent->navigation_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-left mt05">
                                            <a href="#" role="button" class="btn-select">Chọn hết</a>
                                            <span>|</span>
                                            <a href="#" role="button" class="btn-unselect">Bỏ chọn hết</a>
                                        </div>
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary btn-sm btn-add">Thêm vào menu</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if (count($arrListCategoryArticle) > 0)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a href="#category_article" role="button" data-toggle="collapse" data-parent="#menu_settings_column">Danh mục bài viết</a>
                                </h3>
                            </div>
                            <div id="category_article" class="panel-collapse collapse out" role="tabpanel">
                                <form action="{{ route('backend.configwebsite.navigation.store', [$language_id, 'category']) }}" method="post">
                                    <div class="panel-body">
                                        @foreach ($arrListCategoryArticle as $category)
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="navigation_type_id[{{ $category->category_id }}]" value="{{ $category->category_title }}" />{{ $category->category_title }}</label>
                                            </div>
                                        @endforeach
                                        <div class="form-group">
                                            <label>Menu cha</label>
                                            <select class="form-control" name="parent_id">
                                                <option value="0">--</option>
                                                @foreach ($arrListParent as $parent)
                                                    <option value="{{ $parent->navigation_id }}">{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->navigation_level - 1) . $parent->navigation_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-left mt05">
                                            <a href="#" role="button" class="btn-select">Chọn hết</a>
                                            <span>|</span>
                                            <a href="#" role="button" class="btn-unselect">Bỏ chọn hết</a>
                                        </div>
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary btn-sm btn-add">Thêm vào menu</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if (count($arrListCategoryProduct) > 0)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a href="#category_product" role="button" data-toggle="collapse" data-parent="#menu_settings_column">Danh mục sản phẩm</a>
                                </h3>
                            </div>
                            <div id="category_product" class="panel-collapse collapse out" role="tabpanel">
                                <form action="{{ route('backend.configwebsite.navigation.store', [$language_id, 'category']) }}" method="post">
                                    <div class="panel-body">
                                        @foreach ($arrListCategoryProduct as $category)
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="navigation_type_id[{{ $category->category_id }}]" value="{{ $category->category_title }}" />{{ $category->category_title }}</label>
                                            </div>
                                        @endforeach
                                        <div class="form-group">
                                            <label>Menu cha</label>
                                            <select class="form-control" name="parent_id">
                                                <option value="0">--</option>
                                                @foreach ($arrListParent as $parent)
                                                    <option value="{{ $parent->navigation_id }}">{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->navigation_level - 1) . $parent->navigation_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-left mt05">
                                            <a href="#" role="button" class="btn-select">Chọn hết</a>
                                            <span>|</span>
                                            <a href="#" role="button" class="btn-unselect">Bỏ chọn hết</a>
                                        </div>
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary btn-sm btn-add">Thêm vào menu</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-7">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title clearfix">
                            <label class="mt10">Cấu trúc menu</label>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-savesort">Lưu sắp xếp</button>
                            </div>
                        </h3>
                    </div>
                </div>
                <div id="navigation_panel" class="panel-group navigation-panel" role="tablist" data-link="{{ route('backend.configwebsite.navigation.index', ['language_id' => $language_id]) }}" data-link_sort="{{ route('backend.configwebsite.navigation.sort', [$language_id]) }}">
                    @include('backend.configwebsite.navigation.list')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function() {
        Navigation.init();
    });
</script>
@stop