<div class="sidebar-header">Nhập dữ liệu cho bảng {{ $table }}</div>
<div class="sidebar-content sidebar-sm">
    <div class="form-group">
        <div id="fileUploader" data-config="{{ json_encode($upload_config) }}">Upload</div>
    </div>
</div>
<script type="text/javascript">
    Backend.import();
</script>