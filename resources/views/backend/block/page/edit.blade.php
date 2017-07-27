<div class="sidebar-header">{{ trans('common.layout.edit') }}</div>
<div class="sidebar-content sidebar-sm">
    <form id="frmBlockPage" name="frmBlockPage" role="form" action="{{ route('backend.block.page.update', [$pageInfo->page_code]) }}" method="put">
        <div class="form-group">
            <label for="page_name" class="required">Tên trang</label>
            <input type="text" class="form-control" id="page_name" name="page_name" value="{{ old('page_name', $pageInfo->page_name) }}" placeholder="Tên trang">
        </div>
        <div class="form-group">
            <label for="page_code" class="required">Code</label>
            <input type="text" class="form-control" disabled="disabled" value="{{ old('page_code', $pageInfo->page_code) }}" placeholder="Code">
        </div>
        <div class="form-group">
            <label for="page_layout" class="required">Layout</label>
            <select class="form-control" id="page_layout" name="page_layout">
                <option value="">Chọn layout</option>
                @foreach (config('cms.backend.block.page.layout') as $layout)
                    <option value="{{ $layout }}"{!! $layout == old('page_layout', $pageInfo->page_layout) ? ' selected="selected"' : '' !!}>{{ trans('common.block.page.layout.' . $layout) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="page_url">Url trang</label>
            <input type="text" class="form-control" id="page_url" name="page_url" value="{{ old('page_url', $pageInfo->page_url) }}" placeholder="Url trang">
        </div>
        @if (count($arrLanguage) > 1)
            <div class="form-group">
                <label for="language_id" class="required">Ngôn ngữ</label>
                <select class="form-control" id="language_id" name="language_id">
                    @foreach ($arrLanguage as $lang => $data)
                        <option value="{{ $lang }}"{!! $lang == old('language_id', $pageInfo->language_id) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', $pageInfo->language_id) }}">
        @endif
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.save') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>
<script type="text/javascript">
    Block.initPage({
        page_name: {
            required: "{{ trans('validation.block.page.page_name.required') }}",
            maxlength: "{{ trans('validation.block.page.page_name.maxlength') }}"
        },
        page_code: {
            required: "{{ trans('validation.block.page.page_code.required') }}",
            maxlength: "{{ trans('validation.block.page.page_code.maxlength') }}"
        },
        page_layout: {
            required: "{{ trans('validation.block.page.page_layout.required') }}"
        },
        page_url: {
            maxlength: "{{ trans('validation.block.page.page_url.maxlength') }}",
            invalid: "{{ trans('validation.block.page.page_url.invalid') }}"
        },
        language_id: {
            required: "{{ trans('validation.language.required') }}"
        }
    });
</script>