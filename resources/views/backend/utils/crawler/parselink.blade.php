<div class="sidebar-content sidebar-sm">
    <div class="sidebar-header">{{ trans('common.utils.crawler.parselink.title') }}</div>
    <form method="post" action="{{ route('backend.utils.crawler.parselink') }}" id="frmParseLink" name="frmParseLink">
        <div class="form-group">
            <label for="link" class="required">Link</label>
            <p class="help-block">{{ trans('common.utils.crawler.parselink.note') }}</p>
            <input type="text" class="form-control" id="link" name="link">
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">{{ trans('common.utils.crawler.parselink.button') }}</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    Crawler.parseLink({
        link: {
            required: "{{ trans('validation.crawler.link.required') }}"
        }
    }, function(data) {
        $('#article_title').val(data.title);
        $('#article_description').val(data.description);
        CKEDITOR.instances['article_content'].setData(data.content);
    });
</script>