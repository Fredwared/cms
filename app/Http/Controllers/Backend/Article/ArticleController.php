<?php
namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Article;
use Carbon\Carbon;

class ArticleController extends BackendController
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $language_id = check_language($request->language_id);
        $user_id = $request->user_id ?? [];
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //get model
        $businessArticle = Globals::getBusiness('Article');
        $businessCategory = Globals::getBusiness('Category');
        $businessUser = Globals::getBusiness('User');

        // get list post;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => $status,
            'title' => $title,
            'user_id' => $user_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $arrListArticle = $businessArticle->getListArticle($params);

        if ($arrListArticle->total() > 0) {
            $maxPage = ceil($arrListArticle->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.article.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListArticle->appends($params)->links();

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' => config('cms.backend.category.type.article')
        ]);

        $arrLanguage = config('laravellocalization.supportedLocales');

        //get user info
        $arrUser = $businessUser->findByMany($user_id);

        return view('backend.article.index', compact('arrListArticle', 'arrListCategory', 'arrLanguage', 'category_id', 'language_id', 'status', 'title', 'pagination', 'item', 'user_id', 'arrUser', 'date_from', 'date_to'));
    }

    public function create(Request $request, $language = null)
    {
        $language = check_language($language);
        $article_source = $request->source_item_id ?? 0;
        $item_id = $request->item_id ?? null;

        //get model
        $businessArticle = Globals::getBusiness('Article');

        //get languages
        $arrLanguage = config('laravellocalization.supportedLocales');

        //check language is valid or not
        if (!in_array($language, array_keys($arrLanguage))) {
            return redirect(route('backend.article.create', [config('app.locale')]));
        }

        // get detail article source
        $articleSourceInfo = null;
        if ($article_source && $language != config('app.locale')) {
            $articleSourceInfo = $businessArticle->find($article_source);
        }

        return view('backend.article.create', compact('arrLanguage', 'language', 'article_source', 'item_id', 'articleSourceInfo'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'article_title' => 'required|max:200',
            'article_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:article,article_code,null,null,deleted,0,language_id,' . $request->language_id,
            'article_description' => 'required|max:1000',
            'article_content' => 'required',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'category_id' => 'required|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'article_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:article,article_id,status,' . config('cms.backend.status.active'),
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'article_title.required' => trans('validation.article.article_title.required'),
            'article_title.max' => trans('validation.article.article_title.maxlength'),
            'article_code.required' => trans('validation.article.article_code.required'),
            'article_code.regex' => trans('validation.article.article_code.code'),
            'article_code.max' => trans('validation.article.article_code.maxlength'),
            'article_code.unique' => trans('validation.article.article_code.unique'),
            'article_description.required' => trans('validation.article.article_description.required'),
            'article_description.max' => trans('validation.article.article_description.maxlength'),
            'article_content.required' => trans('validation.article.article_content.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'category_id.required' => trans('validation.article.category_id.required'),
            'category_id.exists' => trans('validation.article.category_id.not_exist'),
            'article_source.required' => trans('validation.article.article_source.required'),
            'article_source.exists' => trans('validation.article.article_source.not_exist'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessArticle = Globals::getBusiness('Article');
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleCategory = Globals::getBusiness('Article_Category');
        $businessArticleReference = Globals::getBusiness('Article_Reference');
        $businessArticleTopic = Globals::getBusiness('Article_Topic');
        $businessArticleMedia = Globals::getBusiness('Article_Media');
        $businessTag = Globals::getBusiness('Tag');

        //process save image from content in other website
        $arrImgContent = $request->image_content ?? [];
        $arrResult = $businessArticle->processImage($arrImgContent, $request->article_content, $request->thumbnail_url, $request->thumbnail_url2);

        $arrCategoryListon = $request->list_category_id;

        $params = [
            'article_title' => clean($request->article_title, 'notags'),
            'article_code' => $request->article_code,
            'article_description' => clean($request->article_description, 'notags'),
            'article_content' => $arrResult['content'],
            'article_priority' => $request->article_priority,
            'article_privacy' => ($request->article_hasimage ?? 0) + ($request->article_hasvideo ?? 0),
            'thumbnail_url' => $arrResult['thumbnail_url'],
            'thumbnail_url2' => $arrResult['thumbnail_url2'],
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
            'category_liston' => implode(',', $arrCategoryListon),
            'article_tags' => implode(',', $request->article_tags ?? []),
            'article_comment' => $request->has('article_comment') ? 1 : 0,
            'article_seo_title' => clean($request->article_seo_title, 'notags'),
            'article_seo_keywords' => clean($request->article_seo_keywords, 'notags'),
            'article_seo_description' => clean($request->article_seo_description, 'notags'),
            'user_id' => auth('backend')->user()->getAccountId(),
            'status' => $request->status,
        ];

        //make score if status is published
        if ($params['status'] == config('cms.backend.status.active')) {
            $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $params['article_score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['article_priority'] . format_date($params['published_at'], 'His');
        }

        $articleInfo = $businessArticle->create($params);

        //create share_url
        $articleInfo->update([
            'share_url' => config('app.url') . '/' . $articleInfo->article_code . '-' . $articleInfo->article_id . '.html'
        ]);

        //insert into table article_media
        $businessArticleMedia->addByArticle($articleInfo->article_id, $arrResult['media']);

        //insert into table article_category
        $businessArticleCategory->addByArticle($articleInfo->article_id, $arrCategoryListon);

        //insert into table tag
        if (!empty($request->article_tags)) {
            foreach ($request->article_tags as $tag) {
                if (empty($tag)) {
                    continue;
                }

                $businessTag->updateOrCreate([
                    'tag_name' => $tag,
                    'language_id' => $articleInfo->language_id
                ], [
                    'tag_name' => $tag,
                    'language_id' => $articleInfo->language_id
                ]);
            }
        }

        //insert into table article_reference
        $businessArticleReference->addReference($articleInfo->article_id, explode(',', $request->article_reference));

        //insert into table article_topic
        $businessArticleTopic->addTopic($articleInfo->article_id, explode(',', $request->article_topic));

        //insert into table langmap
        $article_source = $request->article_source ?? null;
        $params = [
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $articleInfo->language_id,
            'source_item_id' => $articleInfo->article_id,
            'source_language_id' => null,
        ];
        if (!empty($article_source)) {
            $articleSourceInfo = $businessArticle->find($article_source);

            $params['source_item_id'] = $articleSourceInfo->article_id;
            $params['source_language_id'] = $articleSourceInfo->language_id;

            $articleInfo->update([
                'article_code' => $articleSourceInfo->article_code
            ]);
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $articleInfo->language_id
        ], $params);

        $item_id = $request->item_id ?? null;
        if (!empty($item_id)) {
            $itemInfo = $businessArticle->find($item_id);

            if ($itemInfo) {
                $businessLangMap->updateOrCreate([
                    'item_module' => 'article',
                    'item_id' => $itemInfo->article_id,
                    'language_id' => $itemInfo->language_id
                ], [
                    'item_module' => 'article',
                    'item_id' => $itemInfo->article_id,
                    'language_id' => $itemInfo->language_id,
                    'source_item_id' => $articleInfo->article_id,
                    'source_language_id' => $articleInfo->language_id,
                ]);
            }
        }

        flash()->success(trans('common.messages.article.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.edit', [$articleInfo->article_id]));
        }

        return redirect(route('backend.article.index', ['language_id' => $request->language_id]));
    }

    public function edit(Business_Article $articleInfo)
    {
        //get model
        $businessLangMap = Globals::getBusiness('LangMap');

        $arrLanguage = config('laravellocalization.supportedLocales');

        $articleSource = $businessLangMap->findByAttributes([
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $articleInfo->language_id
        ]);

        return view('backend.article.edit', compact('arrLanguage', 'articleInfo', 'articleSource'));
    }

    public function update(Request $request, Business_Article $articleInfo)
    {
        $this->validate($request, [
            'article_title' => 'required|max:200',
            'article_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:article,article_code,' . $articleInfo->article_id . ',article_id,deleted,0,language_id,' . $request->language_id,
            'article_description' => 'required|max:1000',
            'article_content' => 'required',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'category_id' => 'required|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'article_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:article,article_id,status,' . config('cms.backend.status.active'),
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'article_title.required' => trans('validation.article.article_title.required'),
            'article_title.max' => trans('validation.article.article_title.maxlength'),
            'article_code.required' => trans('validation.article.article_code.required'),
            'article_code.regex' => trans('validation.article.article_code.code'),
            'article_code.max' => trans('validation.article.article_code.maxlength'),
            'article_code.unique' => trans('validation.article.article_code.unique'),
            'article_description.required' => trans('validation.article.article_description.required'),
            'article_description.max' => trans('validation.article.article_description.maxlength'),
            'article_content.required' => trans('validation.article.article_content.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'category_id.required' => trans('validation.article.category_id.required'),
            'category_id.exists' => trans('validation.article.category_id.not_exist'),
            'article_source.required' => trans('validation.article.article_source.required'),
            'article_source.exists' => trans('validation.article.article_source.not_exist'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessArticle = Globals::getBusiness('Article');
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleCategory = Globals::getBusiness('Article_Category');
        $businessArticleReference = Globals::getBusiness('Article_Reference');
        $businessArticleTopic = Globals::getBusiness('Article_Topic');
        $businessArticleMedia = Globals::getBusiness('Article_Media');
        $businessTag = Globals::getBusiness('Tag');

        //process save image from content in other website
        $arrImgContent = $request->image_content ?? [];
        $arrResult = $businessArticle->processImage($arrImgContent, $request->article_content, $request->thumbnail_url, $request->thumbnail_url2);

        $arrCategoryListon = $request->list_category_id;

        //save old language
        $old_language_id = $articleInfo->language_id;

        $params = [
            'article_title' => clean($request->article_title, 'notags'),
            'article_code' => $request->article_code,
            'article_description' => clean($request->article_description, 'notags'),
            'article_content' => $arrResult['content'],
            'article_priority' => $request->article_priority,
            'article_privacy' => ($request->article_hasimage ?? 0) + ($request->article_hasvideo ?? 0),
            'thumbnail_url' => $arrResult['thumbnail_url'],
            'thumbnail_url2' => $arrResult['thumbnail_url2'],
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
            'category_liston' => implode(',', $arrCategoryListon),
            'article_tags' => implode(',', $request->article_tags ?? []),
            'share_url' => config('app.url') . '/' . $request->article_code . '-' . $articleInfo->article_id . '.html',
            'article_comment' => $request->has('article_comment') ? 1 : 0,
            'article_seo_title' => clean($request->article_seo_title, 'notags'),
            'article_seo_keywords' => clean($request->article_seo_keywords, 'notags'),
            'article_seo_description' => clean($request->article_seo_description, 'notags'),
            'status' => $request->status,
        ];

        //make score if status is published
        if ($articleInfo->status != config('cms.backend.status.active')) {
            if ($params['status'] == config('cms.backend.status.active')) {
                $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $params['article_score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['article_priority'] . format_date($params['published_at'], 'His');
            }
        } else {
            $params['article_score'] = format_date($articleInfo->published_at, 'Ymd') . '0' . $params['article_priority'] . format_date($articleInfo->published_at, 'His');
        }

        $articleInfo->update($params);

        //insert into table article_media
        $businessArticleMedia->addByArticle($articleInfo->article_id, $arrResult['media']);

        //insert into table article_category
        $businessArticleCategory->addByArticle($articleInfo->article_id, $arrCategoryListon);

        //insert into table tag
        if (!empty($request->article_tags)) {
            foreach ($request->article_tags as $tag) {
                if (empty($tag)) {
                    continue;
                }

                $businessTag->updateOrCreate([
                    'tag_name' => $tag,
                    'language_id' => $articleInfo->language_id
                ], [
                    'tag_name' => $tag,
                    'language_id' => $articleInfo->language_id
                ]);
            }
        }

        //insert into table article_reference
        $businessArticleReference->addReference($articleInfo->article_id, explode(',', $request->article_reference));

        //insert into table article_topic
        $businessArticleTopic->addTopic($articleInfo->article_id, explode(',', $request->article_topic));

        //insert into table langmap
        $article_source = $request->article_source ?? null;
        $params = [
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $articleInfo->language_id,
            'source_item_id' => $articleInfo->article_id,
            'source_language_id' => null,
        ];
        if (!empty($article_source)) {
            $articleSourceInfo = $businessArticle->find($article_source);

            $params['source_item_id'] = $articleSourceInfo->article_id;
            $params['source_language_id'] = $articleSourceInfo->language_id;

            $articleInfo->update([
                'article_code' => $articleSourceInfo->article_code
            ]);
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $old_language_id
        ], $params);

        flash()->success(trans('common.messages.article.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.edit', [$articleInfo->article_id]));
        }

        return redirect(route('backend.article.index', ['language_id' => $request->language_id]));
    }

    public function destroy(Request $request, Business_Article $articleInfo)
    {
        $articleInfo->delete();

        //get model
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleCategory = Globals::getBusiness('Article_Category');
        $businessArticleReference = Globals::getBusiness('Article_Reference');
        $businessArticleMedia = Globals::getBusiness('Article_Media');

        //delete from table langmap
        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'article',
            'item_id' => $articleInfo->article_id,
            'language_id' => $articleInfo->language_id
        ]);

        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'article',
            'source_item_id' => $articleInfo->article_id
        ]);

        $businessArticleCategory->forceDeleteByAttributes([
            'article_id' => $articleInfo->article_id
        ]);

        $businessArticleMedia->forceDeleteByAttributes([
            'article_id' => $articleInfo->article_id
        ]);

        $businessArticleReference->forceDeleteByAttributes([
            'article_id' => $articleInfo->article_id
        ]);

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.article.deleted'));

            return redirect(route('backend.article.index', ['language_id' => $articleInfo->language_id]));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.article.deleted')]);
        }
    }

    public function changeStatus(Request $request, $status)
    {
        if (!$request->ajax()) {
            return redirect('404');
        }

        if (!in_array($status, array_values(config('cms.backend.status')))) {
            return response()->json(['error' => 1, 'message' => trans('validation.status.invalid')]);
        }

        $arrId = $request->has('id') ? $request->id : [];

        if (!empty($arrId)) {
            $arrId = (array)$arrId;

            //get model
            $businessArticle = Globals::getBusiness('Article');

            foreach ($arrId as $id) {
                $businessArticle->find($id)->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
