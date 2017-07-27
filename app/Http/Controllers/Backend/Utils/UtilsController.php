<?php
namespace App\Http\Controllers\Backend\Utils;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\TranslateClient;

class UtilsController extends BackendController
{
    protected $arrTable = [
        'config' => [
            'path_name' => 'App\Models\Entity\Business\Business_Config',
            'unique_key' => ['field_name']
        ],
        'translate' => [
            'path_name' => 'App\Models\Entity\Business\Business_Translate',
            'unique_key' => ['translate_code', 'translate_mode']
        ]
    ];

    private function getModelPath($table)
    {
        return $this->arrTable[$table]['path_name'];
    }
    
    public function createCode(Request $request)
    {
        $title = $request->title ?? null;
        $language = $request->language ?? config('app.locale');

        if (!empty($title)) {
            if (in_array($language, config('cms.backend.google_translate'))) {
                $googleTranslate = new TranslateClient($language, 'en');
                $title = $googleTranslate->translate($title);
            }

            return str_slug($title);
        }

        return '';
    }
    
    public function getCategory(Request $request)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');

        $type = $request->type ?? 'tree';
        $selected = $request->selected ?? '';
        $liston = array_filter(explode(',', $request->liston ?? ''));
        $liston = array_unique(array_prepend($liston, $selected));

        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $request->language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' => $request->category_type ?? config('cms.backend.category.type.article')
        ]);
        
        $sHTML = '';
        foreach ($arrListCategory as $category) {
            if ($type == 'tree') {
                $sHTML .= '<div>'
                    . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1)
                    . '<input type="checkbox" name="list_category_id[]" value="' . $category->category_id . '"' . (in_array($category->category_id, $liston) ? ' checked="checked"' : '') . '>'
                    . '<span' . ($category->category_id == $selected ? ' class="seton"' : '') . '>' . $category->category_title . '</span>'
                . '</div>';
            } else {
                $sHTML .= '<option value="' . $category->category_id . '"' . ($category->category_id == $selected ? ' selected="selected"' : '') . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title . '</option>';
            }
        }

        if ($type == 'list') {
            $sHTML = '<option value="">Chọn chuyên mục</option>' . $sHTML;
        }
        
        return $sHTML;
    }
    
    public function getCateParent(Request $request)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');
        
        $arrListCategory = $businessCategory->getListCategory([
            'catparent_id' => 0,
            'language_id' => $request->language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' => $request->category_type ?? config('cms.backend.category.type.article')
        ]);
        
        $sHTML = '<option value="0">' . trans('common.layout.home_title') . '</option>';
        foreach ($arrListCategory as $category) {
            $sHTML .= '<option value="' . $category->category_id . '"' . ($category->category_id == $request->selected ? ' selected="selected"' : '') . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title . '</option>';
        }
        
        return $sHTML;
    }

    public function getPageParent(Request $request)
    {
        //get model
        $businessPage = Globals::getBusiness('Page');

        $arrListPage = $businessPage->getListPage([
            'parent_id' => 0,
            'language_id' => $request->language_id,
            'status' => config('cms.backend.status.active')
        ]);

        $sHTML = '<option value="0">' . trans('common.layout.home_title') . '</option>';
        foreach ($arrListPage as $page) {
            $sHTML .= '<option value="' . $page->page_id . '"' . ($page->page_id == $request->selected ? ' selected="selected"' : '') . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->page_level - 1) . $page->page_title . '</option>';
        }

        return $sHTML;
    }
    
    public function getSourceLangMap(Request $request)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessArticle = Globals::getBusiness('Article');
        $businessPage = Globals::getBusiness('Page');
        $businessProduct = Globals::getBusiness('Product');
        $businessLangMap = Globals::getBusiness('LangMap');

        $sHTML = '<option value="">- - -</option>';

        switch ($request->source) {
            case 'article':
                // get list article source;
                $arrListArticleSource = $businessArticle->getListArticle([
                    'language_id' => config('app.locale'),
                    'article_type' => $request->type
                ]);

                foreach ($arrListArticleSource as $article) {
                    $item_id = $businessLangMap->getItemBySource([
                        'item_module' => 'article',
                        'language_id' => $request->language_id,
                        'source_item_id' => $article->article_id,
                        'source_language_id' => $article->language_id
                    ]);

                    $disabled = $selected = '';
                    if ($request->selected != $article->article_id) {
                        $disabled = $item_id != 0 ? ' disabled="disabled"' : '';
                    } else {
                        $selected = ' selected="selected"';
                    }

                    $sHTML .= '<option value="' . $article->article_id . '"' . $disabled . $selected . '>' . $article->article_title . '</option>';
                }
                break;
            case 'category':
                // get list category source;
                $arrListCategorySource = $businessCategory->getListCategory([
                    'language_id' => config('app.locale'),
                    'category_type' => $request->category_type ?? config('cms.backend.category.type.article')
                ]);

                foreach ($arrListCategorySource as $category) {
                    $item_id = $businessLangMap->getItemBySource([
                        'item_module' => 'category',
                        'language_id' => $request->language_id,
                        'source_item_id' => $category->category_id,
                        'source_language_id' => $category->language_id
                    ]);

                    $disabled = $selected = '';
                    if ($request->selected != $category->category_id) {
                        $disabled = $item_id != 0 ? ' disabled="disabled"' : '';
                    } else {
                        $selected = ' selected="selected"';
                    }

                    $sHTML .= '<option value="' . $category->category_id . '"' . $disabled . $selected . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title . '</option>';
                }
                break;
            case 'product':
                // get list product source;
                $arrListProductSource = $businessProduct->getListProduct([
                    'language_id' => config('app.locale')
                ]);

                foreach ($arrListProductSource as $product) {
                    $item_id = $businessLangMap->getItemBySource([
                        'item_module' => 'product',
                        'language_id' => $request->language_id,
                        'source_item_id' => $product->product_id,
                        'source_language_id' => $product->language_id
                    ]);

                    $disabled = $selected = '';
                    if ($request->selected != $product->product_id) {
                        $disabled = $item_id != 0 ? ' disabled="disabled"' : '';
                    } else {
                        $selected = ' selected="selected"';
                    }

                    $sHTML .= '<option value="' . $product->product_id . '"' . $disabled . $selected . '>' . $product->product_title . '</option>';
                }
                break;
            case 'page':
                // get list page source;
                $arrListPageSource = $businessPage->getListPage([
                    'language_id' => config('app.locale')
                ]);

                foreach ($arrListPageSource as $page) {
                    $item_id = $businessLangMap->getItemBySource([
                        'item_module' => 'page',
                        'language_id' => $request->language_id,
                        'source_item_id' => $page->page_id,
                        'source_language_id' => $page->language_id
                    ]);

                    $disabled = $selected = '';
                    if ($request->selected != $page->page_id) {
                        $disabled = $item_id != 0 ? ' disabled="disabled"' : '';
                    } else {
                        $selected = ' selected="selected"';
                    }

                    $sHTML .= '<option value="' . $page->page_id . '"' . $disabled . $selected . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $page->page_level - 1) . $page->page_title . '</option>';
                }
                break;
            default:
                break;
        }

        return $sHTML;
    }

    public function getArticle(Request $request)
    {
        $language_id = check_language($request->language_id);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessArticle = Globals::getBusiness('Article');

        $category_id = $request->category_id ?? null;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);
        $callback = $request->callback ?? null;

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active')
        ]);

        // get list post;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'exclude' => empty($article_id) ? [] : [$article_id],
            'item' => $item,
            'page' => $page,
            'callback' => $callback
        ];
        $arrListArticle = $businessArticle->getListArticle($params);
        $pagination = $arrListArticle->appends($params)->links();

        return view('backend.utils.listarticle', compact('arrListCategory', 'arrListArticle', 'category_id', 'language_id', 'title', 'date_from', 'date_to', 'item', 'pagination', 'callback'));
    }

    public function import(Request $request, $table)
    {
        if ($request->isMethod('get')) {
            $upload_config = [
                'url' => route('backend.utils.import', [$table]),
                'maxFileAllowed' => 1,
                'allowedTypes' => config('cms.backend.import.type'), //seperate with ','
                'maxFileSize' => config('cms.backend.import.size'), //in byte
                'maxFileAllowedErrorStr' => trans('validation.media.maxfile_error'),
                'dragDropStr' => trans('common.messages.media.dragdrop'),
                'dragDropErrorStr' => trans('validation.media.dragdrop_error'),
                'uploadErrorStr' => trans('validation.media.upload_error'),
                'extErrorStr' => trans('validation.media.ext_error'),
                'sizeErrorStr' => trans('validation.media.size_error')
            ];
            
            return view('backend.utils.import', compact('table', 'upload_config'));
        } else {
            if (!isset($this->arrTable[$table])) {
                return response()->json(['error' => 1, 'message' => $table . ' is not exist!']);
            }
            
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return response()->json(['error' => 1, 'message' => $file->getErrorMessage()]);
            }
            
            $arrExt = explode(',', config('cms.backend.import.type'));
            $limitSize = config('cms.backend.import.size');
            
            // Get file info
            $fileName = $file->getClientOriginalName();
            $fileExt = $file->getClientOriginalExtension();
            $fileSize = $file->getClientSize();
            $maxFileSize = $file->getMaxFilesize();
            
            // Check extension valid or not
            if (!in_array($fileExt, $arrExt)) {
                return response()->json(['error' => 1, 'message' => trans_by_params('backend/validation.upload.ext_error', array($fileName, config('cms.backend.import.type')))]);
            }
            
            // Check size
            if ($fileSize > $limitSize) {
                return response()->json(['error' => 1, 'message' => trans_by_params('backend/validation.upload.size_error', array($fileName, $limitSize))]);
            }
            if ($fileSize > $maxFileSize) {
                return response()->json(['error' => 1, 'message' => trans_by_params('backend/validation.upload.size_error', array($fileName, $maxFileSize))]);
            }

            // Get file name exclude extension
            $fileName = str_replace($fileExt, '', $fileName);
            $fileName = str_slug($fileName) . '_' . time() . '.' . $fileExt;
            
            try {
                // Get disk storage
                $disk = Storage::disk(config('cms.backend.media.storage'));

                // Copy file
                $disk->put(config('cms.backend.media.path') . '/tmp/' . $fileName, file_get_contents($file->getRealPath()));

                $businessPath = $this->getModelPath($table);
                $strContent = $disk->get(config('cms.backend.media.path') . '/tmp/' . $fileName);
                $arrContent = str_getcsv($strContent, PHP_EOL);
                $arrContent = array_unique($arrContent);

                if (!empty($arrContent)) {
                    $arrHeader = str_getcsv(array_shift($arrContent));

                    foreach ($arrContent as $j => $content) {
                        $arrData = str_getcsv($content);
                        $arrMatchData = [];

                        foreach ($arrData as $index => $data) {
                            $arrMatchData[$arrHeader[$index]] = $data;
                        }

                        $arrTempUnique = [];
                        foreach ($this->arrTable[$table]['unique_key'] as $unique_key) {
                            $arrTempUnique[trim($unique_key)] = $arrMatchData[trim($unique_key)];
                        }

                        $businessPath::updateOrCreate($arrTempUnique, $arrMatchData);
                    }

                    $disk->delete(config('cms.backend.media.path') . '/tmp/' . $fileName);

                    return response()->json(['error' => 0, 'message' => 'Import data to table "' . $table . '" successed!']);
                }
                
                return response()->json(['error' => 1, 'message' => 'File is empty']);
            } catch (FileException $ex) {
                return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
            }
        }

        return redirect(route('backend.index'));
    }

    public function export(Request $request, $table)
    {
        
    }

    public function articleReference(Request $request)
    {
        $language_id = check_language($request->language_id);
        
        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessArticle = Globals::getBusiness('Article');
        
        $article_id = $request->article_id ?? null;
        if (!empty($article_id)) {
            $articleInfo = $businessArticle->find($article_id);
            
            if (!$articleInfo) {
                return redirect(404);
            }
            
            $language_id = $articleInfo->language_id;
        }
        
        $category_id = $request->category_id ?? null;
        $title = $request->title ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);
        
        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active')
        ]);
        
        // get list post;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'title' => $title,
            'exclude' => empty($article_id) ? [] : [$article_id],
            'item' => $item,
            'page' => $page
        ];
        $arrListArticle = $businessArticle->getListArticle($params);
        $pagination = $arrListArticle->appends($params)->links();
        
        return view('backend.utils.articlereference', compact('arrListCategory', 'arrListArticle', 'category_id', 'language_id', 'title', 'item', 'pagination'));
    }

    public function articleTopic(Request $request)
    {
        $language_id = check_language($request->language_id);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessTopic = Globals::getBusiness('Topic');

        $category_id = $request->category_id ?? null;
        $title = $request->title ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active')
        ]);

        // get list post;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'title' => $title,
            'item' => $item,
            'page' => $page
        ];
        $arrListTopic = $businessTopic->getListTopic($params);
        $pagination = $arrListTopic->appends($params)->links();

        return view('backend.utils.articletopic', compact('arrListCategory', 'arrListTopic', 'category_id', 'language_id', 'title', 'item', 'pagination'));
    }

    public function videoInfo(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required',
            'source' => 'required|in:' . implode(',', array_keys(config('cms.backend.media.source')))
        ], [
            'filename.required' => trans('validation.media.video.media_filename.required'),
            'source.required' => trans('validation.media.video.media_source.required'),
            'source.in' => trans('validation.media.video.media_source.invalid')
        ]);

        //get model
        $businessMedia = Globals::getBusiness('Media');
        
        $arrVideoInfo = $businessMedia->getVideoInfo($request->filename, $request->source);
        
        return response()->json($arrVideoInfo);
    }

    public function upload(Request $request)
    {
        if ($request->isMethod('post')) {
            $file = $request->file('file');
            $type = $request->type ?? 'image';

            if (!$file->isValid()) {
                return response()->json(['error' => 1, 'message' => $file->getErrorMessage()]);
            }

            $arrExt = explode(',', config('cms.backend.media.ext.' . $type));
            $limitSize = config('cms.backend.media.size.' . $type);

            // Get file info
            $fileName = $file->getClientOriginalName();
            $fileExt = $file->getClientOriginalExtension();
            $fileSize = $file->getClientSize();
            $maxFileSize = min($file->getMaxFilesize(), $limitSize);

            // Check extension valid or not
            if (!in_array($fileExt, $arrExt)) {
                return response()->json(['error' => 1, 'message' => trans_by_params('backend/validation.upload.ext_error', array($fileName, config('cms.backend.media.ext.' . $type)))]);
            }

            // Check size
            if ($fileSize > $maxFileSize) {
                return response()->json(['error' => 1, 'message' => trans_by_params('backend/validation.upload.size_error', array($fileName, $maxFileSize))]);
            }

            // Get file name exclude extension
            $fileName = str_slug(str_replace('.' . $fileExt, '', $fileName)) . '.' . $fileExt;

            try {
                // Get disk storage
                $disk = Storage::disk(config('cms.backend.media.storage'));

                // Copy file
                $disk->put(config('cms.backend.media.path') . '/tmp/' . $fileName, file_get_contents($file->getRealPath()));

                $arrInfo = [
                    'type' => $type,
                    'filename' => $fileName
                ];
                if ($type == 'image') {
                    $arrInfo['url'] = image_url($fileName);
                }

                return response()->json(['error' => 0, 'message' => 'Upload successed!', 'info' => $arrInfo]);
            } catch (FileException $ex) {
                return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
            }
        }

        return redirect(route('backend.index'));
    }
}
