<?php
namespace App\Http\Controllers\Backend\Media;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Media;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MediaController extends BackendController
{
    public function upload(Request $request, $type = 1)
    {
        $file = $request->file('file');
        
        if (!$file->isValid()) {
            return response()->json(['error' => 1, 'message' => $file->getErrorMessage()]);
        }

        $typeName = config('cms.backend.media.name.' . $type);
        $arrExt = explode(',', config('cms.backend.media.ext.' . $typeName));
        $limitSize = config('cms.backend.media.size.' . $typeName);

        // Get file info
        $fileName = $file->getClientOriginalName();
        $fileExt = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getClientSize();
        $maxFileSize = min($file->getMaxFilesize(), $limitSize);
        $fileMimeType = $file->getClientMimeType();

        // Check extension valid or not
        if (!in_array($fileExt, $arrExt)) {
            return response()->json(['error' => 1, 'message' => trans_by_params('validation.upload.ext_error', array($fileName, config('cms.backend.media.ext.' . $typeName)))]);
        }

        // Check size
        if ($fileSize > $maxFileSize) {
            return response()->json(['error' => 1, 'message' => trans_by_params('validation.upload.size_error', array($fileName, $maxFileSize))]);
        }

        // Get file name exclude extension
        $fileName = str_replace('.' . $fileExt, '', $fileName);
        if ($type == 1) {
            $fileExt = 'jpg';
        }
        $fileNameNew = str_slug($fileName) . '_' . rand(1111, 9999) . '-' . time() . '.' . $fileExt;
        $folderDate = date('Y/m/d');

        try {
            // Get disk storage
            $disk = Storage::disk(config('cms.backend.media.storage'));

            // Copy file
            $disk->put(config('cms.backend.media.path') . '/' . $typeName . '/' . $folderDate . '/' . $fileNameNew, file_get_contents($file->getRealPath()));
        } catch (FileException $ex) {
            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }

        $media_info = [
            'size' => $fileSize,
            'ext' => $fileExt,
            'mimetype' => $fileMimeType
        ];

        if ($type == 1) { //image
            $media_info = array_merge($media_info, image_info($folderDate . '/' . $fileNameNew));
        }

        //get model
        $businessMedia = Globals::getBusiness('Media');

        //save to db
        $params = [
            'media_title' => str_limit($fileName, 250, ''),
            'media_filename' => $folderDate . '/' . $fileNameNew,
            'media_info' => json_encode($media_info),
            'media_type' => $type,
            'status' => 1,
        ];
        $media = $businessMedia->create($params);

        return response()->json(['error' => 0, 'message' => 'Upload successed!', 'info' => $media->toArray()]);
    }

    public function destroy(Business_Media $mediaInfo)
    {
        if ($mediaInfo->media_type == config('cms.backend.media.type.video')) {
            return redirect('404');
        }
        
        $mediaType = $mediaInfo->media_type == 1 ? 'image' : 'file';

        if ($mediaInfo->articles->count() > 0) {
            return response()->json(['error' => 1, 'message' => trans('common.messages.media.' . $mediaType . '_delete_error')]);
        }
        
        $mediaInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.media.' . $mediaType . '_deleted')]);
    }

    public function menu(Request $request, Business_Media $mediaInfo)
    {
        $type = $request->type ?? 'menu';
        $typeName = config('cms.backend.media.name.' . $mediaInfo->media_type);
        
        return view('backend.media.menu', compact('mediaInfo', 'typeName', 'type'));
    }

    public function updateLabel(Request $request, Business_Media $mediaInfo)
    {
        $media_label = $request->media_label ?? null;
        
        if (empty($media_label)) {
            $media_label = [];
        }
        
        //update media label
        $mediaInfo->update([
            'media_label' => implode(',', $media_label)
        ]);
        
        //insert into table media_label if not exist
        if (!empty($media_label)) {//get model
            $businessMediaLabel = Globals::getBusiness('Media_Label');

            foreach ($media_label as $label) {
                $businessMediaLabel->updateOrCreate([
                    'label_name' => $label,
                    'media_type' => $mediaInfo->media_type
                ], [
                    'label_name' => $label,
                    'media_type' => $mediaInfo->media_type
                ]);
            }
        }
        
        return response()->json(['error' => 0, 'message' => trans('common.messages.media.label_updated')]);
    }
}
