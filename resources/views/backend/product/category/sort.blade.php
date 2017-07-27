<div class="sidebar-content sidebar-sm">
    <div class="sidebar-header">{{ trans('common.action.sort') }}</div>
    <form id="frmSort" name="frmSort" role="form" action="{{ route('backend.article.category.sort', [$language]) }}" method="put">
        <div class="form-group sidebar-scroll sort-website">
            <ul>
                @foreach ($arrListCategory as $parent)
                    <li{!! $parent->childs->count() > 0 ? ' class="has-child"' : '' !!}>
                        <input type="hidden" name="category[0][]" value="{{ $parent->category_id }}">
                        <a href="#">{{ $parent->category_title }}</a>
                        @if ($parent->childs->count() > 0)
                            <ul>
                                @foreach ($parent->childs as $child)
                                    <li{!! $child->childs->count() > 0 ? ' class="has-child"' : '' !!}>
                                        <input type="hidden" name="category[{{ $parent->category_id }}][]" value="{{ $child->category_id }}">
                                        <a href="#">{{ $child->category_title }}</a>
                                        @if ($child->childs->count() > 0)
                                            <ul>
                                                @foreach ($child->childs as $category)
                                                    <li>
                                                        <input type="hidden" name="category[{{ $child->category_id }}][]" value="{{ $category->category_id }}">
                                                        <a href="#">{{ $category->category_title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
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