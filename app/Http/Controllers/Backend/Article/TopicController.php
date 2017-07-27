<?php
namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Topic;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class TopicController extends BackendController
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $language_id = check_language($request->language_id);
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessTopic = Globals::getBusiness('Topic');

        // get list topic;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => $status,
            'title' => $title,
            'item' => $item,
            'page' => $page
        ];
        $arrListTopic = $businessTopic->getListTopic($params);

        if ($arrListTopic->total() > 0) {
            $maxPage = ceil($arrListTopic->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.article.topic.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListTopic->appends($params)->links();

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' =>  config('cms.backend.category.type.article')
        ]);

        $arrLanguage = config('laravellocalization.supportedLocales');

        return view('backend.article.topic.index', compact('arrListTopic', 'arrListCategory', 'arrLanguage', 'category_id', 'language_id', 'status', 'title', 'pagination', 'item'));
    }

    public function create(Request $request, $language = null)
    {
        $language = check_language($language);
        $topic_source = $request->source_item_id ?? 0;
        $item_id = $request->item_id ?? null;

        //get languages
        $arrLanguage = config('laravellocalization.supportedLocales');

        //check language is valid or not
        if (!in_array($language, array_keys($arrLanguage))) {
            return redirect(route('backend.article.topic.create', [config('app.locale')]));
        }

        return view('backend.article.topic.create', compact('arrLanguage', 'language', 'topic_source', 'item_id'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'topic_title' => 'required|max:200|unique:topic,topic_title,null,topic_id,deleted,0',
            'category_id' => 'required|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'topic_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:topic,topic_id,status,' . config('cms.backend.status.active'),
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status'))),
            'max_article' => 'in:5,10,15,20'
        ], [
            'topic_title.required' => trans('validation.topic.topic_title.required'),
            'topic_title.max' => trans('validation.topic.topic_title.maxlength'),
            'topic_title.unique' => trans('validation.topic.topic_title.unique'),
            'category_id.required' => trans('validation.topic.category_id.required'),
            'category_id.exists' => trans('validation.topic.category_id.not_exist'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'topic_source.required_unless' => trans('validation.topic.topic_source.required'),
            'topic_source.exists' => trans('validation.topic.topic_source.not_exist'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid'),
            'max_article.in' => trans('validation.topic.max_article.invalid')
        ]);

        //get model
        $businessTopic = Globals::getBusiness('Topic');
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleTopic = Globals::getBusiness('Article_Topic');

        $params = [
            'topic_title' => clean($request->topic_title, 'notags'),
            'category_id' => $request->category_id,
            'language_id' => $request->language_id,
            'status' => $request->status,
            'max_article' => $request->max_article,
        ];
        $topicInfo = $businessTopic->create($params);

        //insert into table langmap
        $topic_source = $request->topic_source ?? null;
        $params = [
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id,
            'source_item_id' => $topicInfo->topic_id,
            'source_language_id' => null,
        ];
        if (!empty($topic_source)) {
            $topicSourceInfo = $businessTopic->find($topic_source);

            $params['source_item_id'] = $topicSourceInfo->topic_id;
            $params['source_language_id'] = $topicSourceInfo->language_id;
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id
        ], $params);

        $item_id = $request->item_id ?? null;
        if (!empty($item_id)) {
            $item_info = $businessTopic->find($item_id);

            if ($item_info) {
                $businessLangMap->updateOrCreate([
                    'item_module' => 'topic',
                    'item_id' => $item_info->topic_id,
                    'language_id' => $item_info->language_id
                ], [
                    'item_module' => 'topic',
                    'item_id' => $item_info->topic_id,
                    'language_id' => $item_info->language_id,
                    'source_item_id' => $topicInfo->topic_id,
                    'source_language_id' => $topicInfo->language_id,
                ]);
            }
        }

        //insert into table article_topic
        $businessArticleTopic->addArticle($topicInfo->topic_id, explode(',', $request->article_topic));

        flash()->success(trans('common.messages.topic.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.topic.edit', [$topicInfo->topic_id]));
        }

        return redirect(route('backend.article.topic.index', ['language_id' => $request->language_id]));
    }

    public function edit(Business_Topic $topicInfo)
    {
        //get model
        $businessLangMap = Globals::getBusiness('LangMap');

        $arrLanguage = config('laravellocalization.supportedLocales');

        $topic_source = $businessLangMap->findByAttributes([
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id
        ]);

        return view('backend.article.topic.edit', compact('arrLanguage', 'topicInfo', 'topic_source'));
    }

    public function update(Request $request, Business_Topic $topicInfo)
    {
        $this->validate($request, [
            'topic_title' => 'required|max:200|unique:topic,topic_title,' . $topicInfo->topic_id . ',topic_id,deleted,0',
            'category_id' => 'required|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'topic_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:topic,topic_id,status,' . config('cms.backend.status.active'),
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status'))),
            'max_article' => 'in:5,10,15,20'
        ], [
            'topic_title.required' => trans('validation.topic.topic_title.required'),
            'topic_title.max' => trans('validation.topic.topic_title.maxlength'),
            'topic_title.unique' => trans('validation.topic.topic_title.unique'),
            'category_id.required' => trans('validation.topic.category_id.required'),
            'category_id.exists' => trans('validation.topic.category_id.not_exist'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'topic_source.required_unless' => trans('validation.topic.topic_source.required'),
            'topic_source.exists' => trans('validation.topic.topic_source.not_exist'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid'),
            'max_article.in' => trans('validation.topic.max_article.invalid')
        ]);

        //get model
        $businessTopic = Globals::getBusiness('Topic');
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleTopic = Globals::getBusiness('Article_Topic');

        $params = [
            'topic_title' => clean($request->topic_title, 'notags'),
            'category_id' => $request->category_id,
            'language_id' => $request->language_id,
            'status' => $request->status,
            'max_article' => $request->max_article,
        ];
        $topicInfo->update($params);

        //insert into table langmap
        $topic_source = $request->topic_source ?? null;
        $params = [
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id,
            'source_item_id' => $topicInfo->topic_id,
            'source_language_id' => null,
        ];
        if (!empty($topic_source)) {
            $topicSourceInfo = $businessTopic->find($topic_source);

            $params['source_item_id'] = $topicSourceInfo->topic_id;
            $params['source_language_id'] = $topicSourceInfo->language_id;
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id
        ], $params);

        //insert into table article_topic
        $businessArticleTopic->addArticle($topicInfo->topic_id, explode(',', $request->article_topic));

        flash()->success(trans('common.messages.topic.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.topic.edit', [$topicInfo->topic_id]));
        }

        return redirect(route('backend.article.topic.index', ['language_id' => $request->language_id]));
    }

    public function destroy(Request $request, Business_Topic $topicInfo)
    {
        $topicInfo->delete();

        //get model
        $businessLangMap = Globals::getBusiness('LangMap');
        $businessArticleTopic = Globals::getBusiness('Article_Topic');

        //delete from table langmap
        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'topic',
            'item_id' => $topicInfo->topic_id,
            'language_id' => $topicInfo->language_id
        ]);

        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'topic',
            'source_item_id' => $topicInfo->topic_id
        ]);

        $businessArticleTopic->forceDeleteByAttributes([
            'topic_id' => $topicInfo->topic_id
        ]);

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.topic.deleted'));

            return redirect(route('backend.article.topic.index', ['language_id' => $topicInfo->language_id]));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.topic.deleted')]);
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
            $businessTopic = Globals::getBusiness('Topic');

            foreach ($arrId as $id) {
                $topicInfo = $businessTopic->find($id);
                $topicInfo->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
