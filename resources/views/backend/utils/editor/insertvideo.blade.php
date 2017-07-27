<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
<form method="get" action="{{ route('backend.utils.editor.insertvideo') }}" id="frmSearch" name="frmSearch">
    <div class="panel panel-info">
        <div class="panel-body bg-info">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <input type="hidden" name="item" value="{{ $item }}" />
                    <input type="hidden" name="editor_name" value="{{ $editor_name }}" />
                    <label class="mr05">Nguồn</label>
                    <select class="form-control r04" name="status">
                        <option value="">Tất cả</option>
                        @foreach (config('cms.backend.media.source') as $source => $link)
                            <option value="{{ $source }}"{!! $source == $media_source ? ' selected="selected"' : '' !!}>{{ ucfirst($source) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                    <label class="mr05">Nhãn video</label>
                    <select class="form-control r04" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $type]) }}" data-placeholder="Chọn nhãn" data-fields="label_name|label_name" id="label" name="label[]" multiple="multiple">
                        @foreach (array_filter($label) as $media_label)
                            <option value="{{ $media_label }}" selected="selected">{{ $media_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                    <label class="mr05">Ngày tạo</label>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group date" id="date_from">
                                <input type="text" class="form-control r04" name="date_from" value="{{ $date_from }}" placeholder="Từ ngày" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group date" id="date_to">
                                <input type="text" class="form-control r04" name="date_to" value="{{ $date_to }}" placeholder="Đến ngày" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
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
<div class="text-right">
    <button type="button" class="btn btn-info btn-select"><i class="fa fa-check"></i> Chọn</button>
</div>
@include('backend.partials.pagination', ['arrData' => $arrListVideo, 'pagination' => $pagination, 'item' => $item, 'position' => 'top', 'showpaging' => false])
<div class="box-body table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="w10px">&nbsp;</th>
                <th>Link video</th>
                <th>Nguồn video</th>
                <th>Thông tin video</th>
                <th class="text-center">Ngày cập nhật</th>
            </tr>
        </thead>
        <tbody>
            @if (count($arrListVideo) > 0)
                @foreach ($arrListVideo as $video)
                    <?php
                    $arrInfo = json_decode($video->media_info, true);
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="media_id" value="{{ $video->media_id }}" data-info="{{ $video->media_info }}" data-filename="{{ $video->media_filename }}" />
                        </td>
                        <td>{{ $video->media_filename }}</td>
                        <td>{{ ucfirst($video->media_source) }}</td>
                        <td>
                            <div><label>Title:</label> {{ $arrInfo['title'] }}</div>
                            <div><label>Duration:</label> {{ $arrInfo['duration'] }}</div>
                            <div><label>Quality:</label> {{ $arrInfo['definition'] }}</div>
                        </td>
                        <td class="text-center">{{ format_date($video->updated_at) }}</td>
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
<div class="box-footer clearfix">
    @include('backend.partials.pagination', ['arrData' => $arrListVideo, 'pagination' => $pagination, 'item' => $item, 'position' => 'bottom', 'showpaging' => false])
</div>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript">
    Backend.multiSelect();
    Backend.initDate('#date_from', '#date_to');
    Utils.insertVideo('{{ $editor_name }}');
</script>