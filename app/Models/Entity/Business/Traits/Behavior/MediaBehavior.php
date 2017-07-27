<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use App\Models\Services\Globals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Madcoda\Youtube\Facades\Youtube;

trait MediaBehavior
{
    public function scopeType($query, $condition)
    {
        return $query->where('media_type', '=', $condition);
    }

    public function getListMedia($params = [])
    {
        $params = array_merge([
            'media_title' => null,
            'media_type' => 1,
            'media_source' => null,
            'label' => null,
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1,
        ], $params);
        
        $query = $this->type($params['media_type'])->orderBy('updated_at', 'DESC');

        if (!empty($params['media_source'])) {
            $query->where('media_source', '=', $params['media_source']);
        }

        if ($params['media_title'] >= 0) {
            $query->where('media_title', 'like', '%' . $params['media_title'] . '%');
        }
        
        if (!empty($params['label'])) {
            $params['label'] = (array)$params['label'];
            
            $query->where(function ($query) use ($params) {
                foreach ($params['label'] as $label) {
                    $query->orWhereRaw('FIND_IN_SET("' . $label . '", media_label)');
                }
            });
        }
        
        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
            $query->where('created_at', '>=', $params['date_from']);
        }
        
        if (!empty($params['date_to'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:29');
            $query->where('created_at', '<=', $params['date_to']);
        }
        
        $query->with('articles');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
    
    public function getVideoInfo($name, $source)
    {
        preg_match_all('/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/', $name, $result);
    
        if (is_array($result) && count($result) > 1) {
            $vid = $result[1][0];
            $videoInfo = Youtube::getVideoInfo($vid);

            if ($videoInfo) {
                return [
                    'id' => $videoInfo->id,
                    'title' => $videoInfo->snippet->title,
                    'description' => $videoInfo->snippet->description,
                    'thumbnail' => $videoInfo->snippet->thumbnails->high->url,
                    'duration' => $videoInfo->contentDetails->duration,
                    'dimension' => $videoInfo->contentDetails->dimension,
                    'definition' => $videoInfo->contentDetails->definition,
                    'linkembed' => config('cms.backend.media.source.' . $source) . $videoInfo->id
                ];
            }
        }
    
        return [];
    }

    /**
     * insert into table media from crawler
     * @param array $arrImage
     */
    public function insertMediaFromCrawler($arrImage = [])
    {
        $arrResult = [];

        if (!empty($arrImage)) {
            $arrValidExt = explode(',', config('cms.backend.media.ext.image'));
            $typeName = config('cms.backend.media.name.1');

            // Get disk storage
            $disk = Storage::disk(config('cms.backend.media.storage'));

            //get current date to make directory
            $date_year = date('Y');
            $date_month = date('m');
            $date_day = date('d');

            foreach ($arrImage as $image) {
                $src = preg_replace('/(.*)src="(.[^=]*)"(.*)/', '$2', $image);
                $src = str_replace('_660x0', '', $src);
                $caption = htmlspecialchars_decode(preg_replace('/(.*)data-component-caption="(<p( class="Normal")?>)?(.*)(<\/p>)?"(.*)/', '$4', $image));
                if (!$caption) {
                    $caption = preg_replace('/(.*)title="(.*)"(.*)/', '$2', $image);
                }

                /* begin download image and insert to db */
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
                $newName = $name . '-' . rand(1111, 9999) . '-' . time() . '.' . $ext;

                //save file to new path
                $result = $disk->put(config('cms.backend.media.path') . '/' . $typeName . '/' . $date_year . '/' . $date_month . '/' . $date_day . '/' . $newName, Globals::getContent($src));

                //save file to new path
                if ($result) {
                    $fileName = $date_year . '/' . $date_month . '/' . $date_day . '/' . $newName;

                    $media_info = array_merge([
                        'size' => image_size($typeName. '/' . $fileName),
                        'ext' => $ext,
                        'mimetype' => $disk->mimeType(config('cms.backend.media.path') . '/' . $typeName . '/' . $fileName)
                    ], image_info($fileName));

                    $mediaInfo = $this->create([
                        'media_title' => str_limit($name, 250, ''),
                        'media_filename' => $fileName,
                        'media_info' => json_encode($media_info),
                        'media_type' => config('cms.backend.media.type.image'),
                        'status' => 1
                    ]);

                    $arrResult[$mediaInfo->media_id] = [
                        'filename' => $fileName,
                        'caption' => clean($caption, 'notags')
                    ];
                    $arrResult['media'][] =[
                        'media_id' => $mediaInfo->media_id,
                        'type' => 'content'
                    ];
                }
            }
        }

        return $arrResult;
    }
}
