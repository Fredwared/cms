<?php
namespace App\Http\Controllers\Backend\Media;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Media;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class VideoController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $media_source = $request->media_source ?? null;
        $label = $request->label ?? [];
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $type = config('cms.backend.media.type.video');

        //get model
        $businessMedia = Globals::getBusiness('Media');

        // get list block ip active
        $params = [
            'media_type' => $type,
            'media_source' => $media_source,
            'label' => $label,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $arrListVideo = $businessMedia->getListMedia($params);

        if ($arrListVideo->total() > 0) {
            $maxPage = ceil($arrListVideo->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.media.video.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        unset($params['media_type']);
        $pagination = $arrListVideo->appends($params)->links();

        return view('backend.media.video.index', compact('arrListVideo', 'pagination', 'item', 'type', 'media_source', 'label', 'date_from', 'date_to'));
    }
    
    public function create(Request $request)
    {
        $type = config('cms.backend.media.type.video');
        
        return view('backend.media.video.create', compact('type'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'media_filename' => 'required|max:500|unique:media,media_filename,null,media_id,media_type,' . config('cms.backend.media.type.video') . ',deleted,0',
            'media_source' => 'required|in:' . implode(',', array_keys(config('cms.backend.media.source')))
        ], [
            'media_filename.required' => trans('validation.media.video.media_filename.required'),
            'media_filename.max' => trans('validation.media.video.media_filename.maxlength'),
            'media_filename.unique' => trans('validation.media.video.media_filename.unique'),
            'media_source.required' => trans('validation.media.video.media_source.required'),
            'media_source.in' => trans('validation.media.video.media_source.invalid')
        ]);

        //get model
        $businessMedia = Globals::getBusiness('Media');
        
        //get video info
        $media_info = $businessMedia->getVideoInfo($request->media_filename, $request->media_source);
        $media_label = $request->media_label ?? null;
        if (empty($media_label)) {
            $media_label = [];
        }

        $params = [
            'media_title' => $media_info['title'],
            'media_filename' => $request->media_filename,
            'media_source' => $request->media_source,
            'media_info' => json_encode($media_info),
            'media_type' => config('cms.backend.media.type.video'),
            'media_label' => implode(',', $media_label),
        ];
        $businessMedia->create($params);
        
        //insert into table media_label if not exist
        if (!empty($media_label)) {
            $businessMediaLabel = Globals::getBusiness('Media_Label');

            foreach ($media_label as $label) {
                $businessMediaLabel->updateOrCreate([
                    'label_name' => $label,
                    'media_type' => config('cms.backend.media.type.video')
                ], [
                    'label_name' => $label
                ]);
            }
        }
        
        flash()->success(trans('common.messages.media.video_created'));
        
        return redirect(route('backend.media.video.index'));
    }

    public function edit(Business_Media $videoInfo)
    {
        return view('backend.media.video.edit', compact('videoInfo'));
    }

    public function update(Request $request, Business_Media $videoInfo)
    {
        $this->validate($request, [
            'media_filename' => 'required|max:500|unique:media,media_filename,' . $videoInfo->media_id . ',media_id,media_type,' . $videoInfo->media_type . ',deleted,0',
            'media_source' => 'required|in:' . implode(',', array_keys(config('cms.backend.media.source')))
        ], [
            'media_filename.required' => trans('validation.media.video.media_filename.required'),
            'media_filename.max' => trans('validation.media.video.media_filename.maxlength'),
            'media_filename.unique' => trans('validation.media.video.media_filename.unique'),
            'media_source.required' => trans('validation.media.video.media_source.required'),
            'media_source.in' => trans('validation.media.video.media_source.invalid')
        ]);

        //get model
        $businessMedia = Globals::getBusiness('Media');

        //get video info
        $media_info = $businessMedia->getVideoInfo($request->media_filename, $request->media_source);
        $media_label = $request->media_label ?? null;
        if (empty($media_label)) {
            $media_label = [];
        }
        
        $params = [
            'media_title' => $media_info['title'],
            'media_filename' => $request->media_filename,
            'media_source' => $request->media_source,
            'media_info' => json_encode($media_info),
            'media_label' => implode(',', $media_label),
        ];
        $videoInfo->update($params);
        
        //insert into table media_label if not exist
        if (!empty($media_label)) {
            //get model
            $businessMediaLabel = Globals::getBusiness('Media_Label');

            foreach ($media_label as $label) {
                $businessMediaLabel->updateOrCreate([
                    'label_name' => $label,
                    'media_type' => config('cms.backend.media.type.video')
                ], [
                    'label_name' => $label
                ]);
            }
        }
        
        flash()->success(trans('common.messages.media.video_updated'));
        
        return redirect(route('backend.media.video.index'));
    }
    
    public function destroy(Business_Media $videoInfo)
    {
        if ($videoInfo->media_type != config('cms.backend.media.type.video')) {
            return redirect('404');
        }
        
        if ($videoInfo->articles->count() > 0) {
            flash()->error(trans('common.messages.media.video_delete_error'));
            
            return redirect(route('backend.media.video.index'));
        }
        
        $videoInfo->delete();

        flash()->success(trans('common.messages.media.video_deleted'));
        
        return redirect(route('backend.media.video.index'));
    }
}
