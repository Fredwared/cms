<?php
namespace App\Http\Controllers\Backend\Media;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Media;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class ImageController extends BackendController
{
    public function index(Request $request)
    {
        //get model
        $businessMedia = Globals::getBusiness('Media');

        if (!$request->ajax() || ($request->has('modal') && $request->modal == 1)) {
            $label = $request->label ?? [];
            $date_from = $request->date_from ?? null;
            $date_to = $request->date_to ?? null;
            $type = config('cms.backend.media.type.image');
            $typeName = config('cms.backend.media.name.' . $type);
            $modal = $request->modal ?? 0;
            $page = $request->page ?? 1;
            $item = config('cms.backend.media.pagination');
            
            //get list image of folder
            $params = [
                'media_type' => $type,
                'label' => $label,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'item' => $item,
                'page' => $page
            ];
            $arrListMedia = $businessMedia->getListMedia($params);
            $pagination = $arrListMedia->appends($params)->links();

            $upload_config = [
                'url' => route('backend.media.upload', [$type]),
                'maxFileAllowed' => 20,
                'allowedTypes' => config('cms.backend.media.ext.' . $typeName), //seperate with ','
                'maxFileSize' => config('cms.backend.media.size.' . $typeName), //in byte
                'maxFileAllowedErrorStr' => trans('validation.upload.maxfile_error'),
                'dragDropStr' => trans('common.messages.media.dragdrop'),
                'dragDropErrorStr' => trans('validation.upload.dragdrop_error'),
                'uploadErrorStr' => trans('validation.upload.upload_error'),
                'extErrorStr' => trans('validation.upload.ext_error'),
                'sizeErrorStr' => trans('validation.upload.size_error'),
                'mediaUrl' => image_url('', 'medium')
            ];

            if (!$modal) {
                return view('backend.media.index', compact('type', 'typeName', 'arrListMedia', 'label', 'date_from', 'date_to', 'upload_config', 'pagination', 'item'));
            } else {
                $multi = $request->multi ?? 0;
                $source = $request->source ?? 'ckeditor';
                $editor_name = $request->editor_name ?? '';

                if ($source != 'ckeditor') {
                    return view('backend.media.modal', compact('type', 'typeName', 'arrListMedia', 'label', 'date_from', 'date_to', 'upload_config', 'multi', 'source', 'editor_name', 'pagination', 'item'));
                } else {
                    $CKEditorFuncNum = $request->CKEditorFuncNum ?? 0;

                    return view('backend.media.ckeditor', compact('type', 'typeName', 'arrListMedia', 'upload_config', 'label', 'date_from', 'date_to', 'multi', 'source', 'editor_name', 'CKEditorFuncNum', 'pagination', 'item'));
                }
            }
        } else {
            $label = $request->label ?? [];
            $date_from = $request->date_from ?? null;
            $date_to = $request->date_to ?? null;
            $type = config('cms.backend.media.type.image');
            $typeName = config('cms.backend.media.name.' . $type);
            $page = $request->page ?? 1;
            $item = config('cms.backend.media.pagination');
            
            //get list image of folder
            $params = [
                'media_type' => $type,
                'label' => $label,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'item' => $item,
                'page' => $page
            ];
            $arrListMedia = $businessMedia->getListMedia($params);
            $pagination = $arrListMedia->appends($params)->links();
            
            $upload_config = [
                'url' => route('backend.media.upload', [$type]),
                'maxFileAllowed' => 20,
                'allowedTypes' => config('cms.backend.media.ext.' . $typeName), //seperate with ','
                'maxFileSize' => config('cms.backend.media.size.' . $typeName), //in byte
                'maxFileAllowedErrorStr' => trans('validation.media.maxfile_error'),
                'dragDropStr' => trans('common.messages.media.dragdrop'),
                'dragDropErrorStr' => trans('validation.media.dragdrop_error'),
                'uploadErrorStr' => trans('validation.media.upload_error'),
                'extErrorStr' => trans('validation.media.ext_error'),
                'sizeErrorStr' => trans('validation.media.size_error'),
                'mediaUrl' => image_url('', 'medium')
            ];
            
            return view('backend.media.image.index', compact('arrListMedia', 'type', 'typeName', 'label', 'date_from', 'date_to', 'upload_config', 'pagination', 'item'));
        }
        
    }
    
    public function edit(Business_Media $imageInfo)
    {
        return view('backend.media.image.edit', compact('imageInfo'));
    }
}
