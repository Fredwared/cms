<div class="sidebar-header">Chủ đề</div>
<div id="panelArticleTopic" class="sidebar-content sidebar-sm">
    <form method="get" action="{{ route('backend.utils.articletopic', ['language_id' => $language_id]) }}" id="frmArticleTopic" name="frmArticleTopic">
        <div class="panel panel-info">
            <div class="panel-body bg-info">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
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
                    <th class="text-center">Ngày cập nhật</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrListTopic->count() > 0)
                    @foreach ($arrListTopic as $topic)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox" data-for="chkAll" value="{{ $topic->topic_id }}" />
                            </td>
                            <td>{{ $topic->topic_title }}</td>
                            <td>{{ $topic->category->category_title }}</td>
                            <td class="text-center">{{ format_date($topic->updated_at) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">{{ trans('common.messages.nodata') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <!-- /.table-responsive -->
    </div>
    <div class="box-footer clearfix">
        @include('backend.partials.pagination', ['arrData' => $arrListTopic, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
    </div>
</div>
<script type="text/javascript">
    Article.initTopic();
</script>