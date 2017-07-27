<link rel="stylesheet" href="{{ url_static('3rd', 'css', 'select2.min.css') }}">
<div class="panel panel-info mb0">
    <div class="panel-body bg-info">
        <div id="media_message" class="alert alert-danger pl30 pr30" style="display: none;"></div>
        <div class="row">
            <div id="media_form" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                <form method="get" action="{{ route('backend.media.' . $typeName . '.index') }}" id="frmSearch" name="frmSearch">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 form-group">
                            <select class="form-control r04" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.medialabel', ['t' => $type]) }}" data-placeholder="Chọn nhãn" data-fields="label_name|label_name" id="label" name="label[]" multiple="multiple">
                                @foreach (array_filter($label) as $media_label)
                                    <option value="{{ $media_label }}" selected="selected">{{ $media_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 form-group">
                            <div class="input-group date" id="date_from">
                                <input type="text" class="form-control r04" name="date_from" value="{{ $date_from }}" placeholder="Từ ngày" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 form-group">
                            <div class="input-group date" id="date_to">
                                <input type="text" class="form-control r04" name="date_to" value="{{ $date_to }}" placeholder="Đến ngày" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="media_data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                <div class="panel panel-info mb0">
                    <div class="panel-body">
                        <div id="media_content_header">
                            @if (check_permission('image', 'insert'))
                                <div class="mt15">
                                    <div id="fileUploader" data-config="{{ json_encode($upload_config) }}">Upload</div>
                                </div>
                            @endif
                        </div>
                        <div id="media_content" class="mt15">
                            @include('backend.media.' . $typeName . '.index')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ url_static('3rd', 'js', 'jquery.uploadfile.js') }}"></script>
<script type="text/javascript">
    Media.init({
        type: {{ $type }},
        modal: 1,
        multi: "{{ $multi }}",
        source: "{{ $source }}",
        editor_name: "{{ $editor_name }}",
        link_menu: "{{ route('backend.media.menu', [0]) }}",
        messages: {
            delete: "{{ trans('common.messages.media.' . $typeName . '_delete') }}"
        }
    });
</script>