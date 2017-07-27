<div class="sidebar-header">Bài viết liên quan</div>
<div id="panelArticleReference" class="sidebar-content sidebar-md">
    <form method="get" action="{{ route('backend.utils.articlereference', ['language_id' => $language_id]) }}" id="frmArticleReference" name="frmArticleReference">
        <div class="panel panel-info">
            <div class="panel-body bg-info">
                <div class="row">
                	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 form-group">
                        <label class="mr05">Chuyên mục</label>
                        <select class="form-control r04" name="category_id">
                        	<option value="">Tất cả</option>
                        	@foreach ($arrListCategory as $category)
                        		<option value="{{ $category->category_id }}"{!! $category->category_id == $category_id ? ' selected="selected"' : '' !!}>{{ str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title }}</option>
                        	@endforeach
                        </select>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8 form-group">
                        <label>Tiêu đề</label>
                        <input type="text" class="form-control r04" name="title" value="{{ $title }}">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th class="text-center">Độ ưu tiên</th>
                    <th class="text-center">Ngày xuất bản</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrListArticle->count() > 0)
                    @foreach ($arrListArticle as $article)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $article->article_id }}" />
                            </td>
                            <td>{{ $article->article_title }}</td>
                            <td>{{ $article->category->category_title }}</td>
                            <td class="text-center">{{ trans('common.article.priority.' . $article->article_priority) }}</td>
                            <td class="text-center">{{ format_date($article->published_at) }}</td>
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
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListArticle, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
    </div>
</div>
<script type="text/javascript">
    Article.initReference();
</script>