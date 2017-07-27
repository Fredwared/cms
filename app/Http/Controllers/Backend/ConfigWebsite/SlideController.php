<?php
namespace App\Http\Controllers\Backend\ConfigWebsite;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Slide;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends BackendController
{
    public function index(Request $request)
    {
        $type = $request->type ?? config('cms.backend.slide.default');
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $language_id = check_language($request->language_id);
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessSlide = Globals::getBusiness('Slide');

        // get list post;
        $params = [
            'language_id' => $language_id,
            'type' => $type,
            'status' => $status,
            'title' => $title,
            'item' => $item,
            'page' => $page
        ];
        $arrListSlide = $businessSlide->getListSlide($params);

        if ($arrListSlide->total() > 0) {
            $maxPage = ceil($arrListSlide->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.configwebsite.slide.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListSlide->appends($params)->links();

        $arrLanguage = config('laravellocalization.supportedLocales');

        return view('backend.configwebsite.slide.index', compact('arrListSlide', 'arrLanguage', 'status', 'language_id', 'type', 'pagination', 'item'));
    }

    public function create(Request $request, $language = null, $type = null)
    {
        $language = check_language($language);

        $arrLanguage = config('laravellocalization.supportedLocales');

        $upload_config = [
            'url' => route('backend.utils.upload'),
            'maxFileAllowed' => 20,
            'allowedTypes' => config('cms.backend.media.ext.image'), //seperate with ','
            'maxFileSize' => config('cms.backend.media.size.image'), //in byte
            'maxFileAllowedErrorStr' => trans('validation.upload.maxfile_error'),
            'dragDropStr' => trans('common.messages.media.dragdrop'),
            'dragDropErrorStr' => trans('validation.upload.dragdrop_error'),
            'uploadErrorStr' => trans('validation.upload.upload_error'),
            'extErrorStr' => trans('validation.upload.ext_error'),
            'sizeErrorStr' => trans('validation.upload.size_error')
        ];

        return view('backend.configwebsite.slide.create', compact('arrLanguage', 'language', 'type', 'upload_config'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'slide_type' => 'required|in:' . implode(',', config('cms.backend.slide.type')),
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status'))),
            'slide_title.*' => 'bail|required|max:250',
            'slide_description.*' => 'bail|max:1000',
            'slide_link.*' => 'bail|max:250|url',
            'slide_target.*' => 'bail|required_with:slide_link,in:_blank,_parent,_self,_top',
        ], [
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'slide_type.required' => trans('validation.slide.slide_type.required'),
            'slide_type.in' => trans('validation.slide.slide_type.invalid'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid'),
            'slide_title.*.required' => trans('validation.slide.slide_title.required'),
            'slide_title.*.max' => trans('validation.slide.slide_title.maxlength'),
            'slide_description.*.max' => trans('validation.slide.slide_description.maxlength'),
            'slide_link.*.max' => trans('validation.slide.slide_link.maxlength'),
            'slide_link.*.url' => trans('validation.slide.slide_link.url'),
            'slide_target.*.required_with' => trans('validation.slide.slide_target.required'),
            'slide_target.*.in' => trans('validation.slide.slide_target.required'),
        ]);

        if (!empty($request->slide_title)) {
            $businessSlide = Globals::getBusiness('Slide');

            foreach ($request->slide_title as $index => $slide_title) {
                $slide_image = $this->downloadImage($request->slide_image[$index]);

                if ($slide_image) {
                    $slide_order = $businessSlide->getLastOrder($request->language_id);

                    $businessSlide->create([
                        'slide_title' => clean($slide_title, 'notags'),
                        'slide_description' => clean($request->slide_description[$index], 'notags'),
                        'slide_image' => $slide_image,
                        'slide_link' => $request->slide_link[$index],
                        'slide_target' => $request->slide_target[$index],
                        'slide_type' => $request->slide_type,
                        'slide_order' => $slide_order,
                        'language_id' => $request->language_id,
                        'status' => $request->status,
                    ]);
                }
            }

            flash()->success(trans('common.messages.slide.created'));
        }

        return redirect(route('backend.configwebsite.slide.index', ['language_id' => $request->language_id]));
    }

    public function edit(Request $request, Business_Slide $slideInfo)
    {
        $upload_config = [
            'url' => route('backend.utils.upload'),
            'maxFileAllowed' => 20,
            'allowedTypes' => config('cms.backend.media.ext.image'), //seperate with ','
            'maxFileSize' => config('cms.backend.media.size.image'), //in byte
            'maxFileAllowedErrorStr' => trans('validation.upload.maxfile_error'),
            'dragDropStr' => trans('common.messages.media.dragdrop'),
            'dragDropErrorStr' => trans('validation.upload.dragdrop_error'),
            'uploadErrorStr' => trans('validation.upload.upload_error'),
            'extErrorStr' => trans('validation.upload.ext_error'),
            'sizeErrorStr' => trans('validation.upload.size_error')
        ];

        return view('backend.configwebsite.slide.edit', compact('slideInfo', 'upload_config'));
    }

    public function update(Request $request, Business_Slide $slideInfo)
    {
        $this->validate($request, [
            'slide_title.*' => 'bail|required|max:250',
            'slide_description.*' => 'bail|max:1000',
            'slide_link.*' => 'bail|max:250|url'
        ], [
            'slide_title.*.required' => trans('validation.slide.slide_title.required'),
            'slide_title.*.max' => trans('validation.slide.slide_title.maxlength'),
            'slide_description.*.max' => trans('validation.slide.slide_description.maxlength'),
            'slide_link.*.max' => trans('validation.slide.slide_link.maxlength'),
            'slide_link.*.url' => trans('validation.slide.slide_link.url'),
        ]);
    }

    public function destroy(Request $request, Business_Slide $slideInfo)
    {
        $slideInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.slide.deleted'));

            return redirect(route('backend.configwebsite.slide.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.slide.deleted')]);
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
            $businessSlide = Globals::getBusiness('Slide');

            foreach ($arrId as $id) {
                $slideInfo = $businessSlide->find($id);
                $slideInfo->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }

    public function sort(Request $request, $language, $type)
    {
        //get model
        $businessSlide = Globals::getBusiness('Slide');

        if ($request->isMethod('get')) {
            return view('backend.configwebsite.slide.sort', compact('language', 'type'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
        }
    }

    private function downloadImage($src)
    {
        $arrValidExt = explode(',', config('cms.backend.media.ext.image'));

        //get current date to make directory
        $date_year = date('Y');
        $date_month = date('m');
        $date_day = date('d');

        $fullname = basename($src);

        //get name without extension
        $name = substr($fullname, 0, strrpos($fullname, '.'));

        //get extension
        $ext = strtolower(substr(strrchr($fullname, '.'), 1));

        //check ext
        if (!in_array($ext, $arrValidExt)) {
            $ext = 'jpg';
        }

        //remove square bracket and its content
        $name = preg_replace('/\[.*\]/', '', urldecode($name));
        $name = preg_replace('/_(\d{10})/i', '', $name);

        //Cut 30 character
        $name = str_limit($name, 30, '');

        //generate seo string
        $name = str_slug(urldecode($name));

        //make new name {oldname}-{time}.{ext}
        $newname = $name . '-' . rand(1111, 9999) . '-' . time() . '.' . $ext;

        // Get disk storage
        $disk = Storage::disk(config('cms.backend.media.storage'));

        //save file to new path
        $result = $disk->put(config('cms.backend.media.path') . '/' . config('cms.backend.media.name.1') . '/slide/' . $date_year . '/' . $date_month . '/' . $date_day . '/' . $newname, Globals::getContent($src));

        if ($result) {
            return 'slide/' . $date_year . '/' . $date_month . '/' . $date_day . '/' . $newname;
        }

        return false;
    }
}
