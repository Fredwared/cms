<div class="sidebar-content sidebar-sm">
    <div class="sidebar-header">{{ trans('common.action.sort') }}</div>
    <form id="frmSort" name="frmSort" role="form" action="{{ route('backend.configwebsite.slide.sort', [$language, $type]) }}" method="put">
        <div class="form-group sidebar-scroll sort-website">
            <ul>

            </ul>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('common.button.update') }}</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="put">
        </div>
    </form>
</div>
<script type="text/javascript">
    Backend.initSort();
</script>