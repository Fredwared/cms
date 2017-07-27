<div class="row">
    @if (count($arrLanguage) > 1)
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            <label for="language_id" class="required">Ngôn ngữ</label>
            <select class="form-control" id="language_id" name="language_id" data-linkcategory="{{ route('backend.utils.getcategory') }}" data-linksource="{{ route('backend.utils.getsourcelangmap') }}">
                @foreach ($arrLanguage as $lang => $data)
                    <option value="{{ $lang }}"{!! $lang == old('language_id', $language) ? ' selected="selected"' : '' !!}>{{ $data['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
            <label for="article_source">Bài viết gốc</label>
            <select class="form-control" id="article_source" name="article_source" data-id="{{ old('article_source', $article_source) }}" data-type="{{ $type }}"></select>
        </div>
    @else
        <input type="hidden" id="language_id" name="language_id" value="{{ old('language_id', config('app.locale')) }}">
    @endif
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
    	<label for="category_id" class="required">Chuyên mục</label>
    	<input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}">
    	<div data-for="category_id" class="list-folder bdr-c3 p05" data-id="{{ old('category_id', 0) }}" data-liston="{{ implode(',', old('list_category_id', [])) }}"></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    	<div class="form-group">
            <label for="tags">Tags</label>
            <select class="form-control" data-width="100%" data-multiselect="true" data-ajax="1" data-url="{{ route('backend.utils.search.tag', ['l' => $language]) }}" data-placeholder="Chọn từ khóa" data-tags="true" data-fields="tag_name|tag_name" id="article_tags" name="article_tags[]" multiple="multiple">
                @foreach (old('article_tags', explode(',', $articleSourceInfo ? $articleSourceInfo->article_tags : '')) as $tag)
                    <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
            	<input type="hidden" id="article_reference" name="article_reference" value="{{ old('article_reference') }}">
                <button type="button" class="form-control btn btn-primary btn-show-sidebar" data-link="{!! route('backend.utils.articlereference', ['language_id' => $language]) !!}">Tin liên quan</button>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
            	<input type="hidden" id="article_topic" name="article_topic" value="{{ old('article_topic') }}">
                <button type="button" class="form-control btn btn-primary btn-show-sidebar" data-link="{!! route('backend.utils.articletopic', ['language_id' => $language]) !!}">Chủ đề</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    	<div class="row">
            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-5">
                <input type="hidden" id="thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url', $articleSourceInfo ? $articleSourceInfo->thumbnail_url : '') }}">
                <input type="hidden" id="thumbnail_url2" name="thumbnail_url2" value="{{ old('thumbnail_url2', $articleSourceInfo ? $articleSourceInfo->thumbnail_url2 : '') }}">
                <div class="row">
                	<?php
                	$landscape = '<button class="btn btn-xs btn-primary btn-show-filemanager" data-link="' . route('backend.media.image.index', ['modal' => 1, 'multi' => 0, 'source' => 'thumbnail_url']) . '" title="Chọn hình"><i class="fa fa-hdd-o"></i></button><button class="btn btn-xs btn-danger ml10" title="Xóa" onclick="javascript:Article.delThumb(\'thumbnail_url\');"><i class="fa fa-trash"></i></button>';
                	$portrait = '<button class="btn btn-xs btn-primary btn-show-filemanager" data-link="' . route('backend.media.image.index', ['modal' => 1, 'multi' => 0, 'source' => 'thumbnail_url2']) . '" title="Chọn hình"><i class="fa fa-hdd-o"></i></button><button class="btn btn-xs btn-danger ml10" title="Xóa" onclick="javascript:Article.delThumb(\'thumbnail_url2\');"><i class="fa fa-trash"></i></button>';
                	?>
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                		<label class="center-block">Hình ngang</label>
                		<button type="button" class="btn btn-primary pl05 pr05 dropable" data-toggle="popover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" data-content="{{ $landscape }}">
							@if (empty(old('thumbnail_url', $articleSourceInfo ? $articleSourceInfo->thumbnail_url : '')))
								<img src="{{ url_static('be', 'images', 'noimage_thumbnail_url.jpg') }}" data-for="thumbnail_url" class="img-responsive">
							@else
								<img src="{{ old('thumbnail_url', $articleSourceInfo ? $articleSourceInfo->thumbnail_url : '') }}" data-for="thumbnail_url" class="img-responsive">
							@endif
						</button>
                	</div>
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-right">
                		<label class="center-block">Hình dọc</label>
                		<button type="button" class="btn btn-primary pl05 pr05 dropable" data-toggle="popover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" data-content="{{ $portrait }}">
							@if (empty(old('thumbnail_url2', $articleSourceInfo ? $articleSourceInfo->thumbnail_url2 : '')))
								<img src="{{ url_static('be', 'images', 'noimage_thumbnail_url2.jpg') }}" data-for="thumbnail_url2" class="img-responsive">
							@else
								<img src="{{ old('thumbnail_url2', $articleSourceInfo ? $articleSourceInfo->thumbnail_url2 : '') }}" data-for="thumbnail_url2" class="img-responsive">
							@endif
						</button>
                	</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
            	<div class="form-group">
                    <label for="status" class="required">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                    	@foreach (config('cms.backend.status') as $name => $value)
                        	<option value="{{ $value }}"{!! $value == old('status', config('cms.backend.article.status.pending')) ? ' selected="selected"' : '' !!}>{{ trans('common.status.' . $name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="article_priority">Độ ưu tiên</label>
                    <select class="form-control" id="article_priority" name="article_priority">
                    	@foreach (config('cms.backend.article.priority') as $name => $value)
                        	<option value="{{ $value }}"{!! $value == old('article_priority', config('cms.backend.article.priority.normal')) ? ' selected="selected"' : '' !!}>{{ trans('common.article.priority.' . $name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="form-group">
            		<label class="mr05 ml05"><input type="checkbox" id="article_comment" name="article_comment" value="1"{!! old('article_comment', 1) == 1 ? ' checked="checked"' : '' !!}> Bình luận</label>
                    <label class="mr05 ml05"><input type="checkbox" id="article_hasimage" name="article_hasimage" value="{{ old('article_hasimage', 2) }}"{!! old('article_hasimage') ? ' checked="checked"' : '' !!}> Có hình ảnh</label>
                    <label class="mr05 ml05"><input type="checkbox" id="article_hasvideo" name="article_hasvideo" value="{{ old('article_hasvideo', 4) }}"{!! old('article_hasvideo') ? ' checked="checked"' : '' !!}> Có video</label>
        		</div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-info">
            <div class="box-header bg-info">
                <h3 class="box-title">SEO</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="article_seo_title">Tiêu đề trang web</label>
                    <input type="text" class="form-control" id="article_seo_title" name="article_seo_title" value="{{ old('article_seo_title', $articleSourceInfo ? $articleSourceInfo->article_seo_title : '') }}" placeholder="Tiêu đề trang web">
                </div>
                <div class="form-group">
                    <label for="article_seo_keywords">Từ khóa</label>
                    <input type="text" class="form-control" id="article_seo_keywords" name="article_seo_keywords" value="{{ old('article_seo_keywords', $articleSourceInfo ? $articleSourceInfo->article_seo_keywords : '') }}" placeholder="Từ khóa">
                </div>
                <div class="form-group">
                    <label for="article_seo_description">Mô tả</label>
                    <textarea class="form-control" rows="3" id="article_seo_description" name="article_seo_description" placeholder="Mô tả">{{ old('article_seo_description', $articleSourceInfo ? $articleSourceInfo->article_seo_description : '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>