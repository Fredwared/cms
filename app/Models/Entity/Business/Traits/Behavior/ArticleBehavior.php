<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use App\Models\Services\Globals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait ArticleBehavior
{
    public function getListArticle($params)
    {
        $params = array_merge([
            'category_id' =>  null,
            'language_id' => config('app.locale'),
            'status' => null,
            'title' => null,
            'user_id' => null,
            'date_from' => null,
            'date_to' => null,
            'exclude' => [],
            'item' => 0,
            'page' => 1
        ], $params);
        
        $query = $this->orderBy('article_score', 'DESC')->orderBy('article_id', 'DESC');
        
        if (auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            $query->where('user_id', '<>', config('cms.backend.super_admin_id'));
        }

        if (!empty($params['category_id'])) {
            $businessCategory = Globals::getBusiness('Category');
            $arrCategoryId = $businessCategory->getFullChildId($params['category_id']);
            $arrCategoryId[] = $params['category_id'];

            $query->whereIn('category_id', $arrCategoryId);
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        if (!empty($params['title'])) {
            $query->where('article_title', 'like', '%' . $params['title'] . '%');
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        if (!empty($params['user_id'])) {
            $query->whereIn('user_id', (array)$params['user_id']);
        }

        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
            $query->where('published_at', '>=', $params['date_from']);
        }

        if (!empty($params['date_to'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:29');
            $query->where('published_at', '<=', $params['date_to']);
        }

        if (!empty($params['exclude'])) {
            $query->whereNotIn('article_id', (array)$params['exclude']);
        }

        $query->with('medias')->with('user')->with('category')->with('categories')->with('langmaps')->with('references');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function processImage($arrSrcImage, $content, $thumbnail_url = null, $thumbnail_url2 = null)
    {
        //get model
        $businessMedia = Globals::getBusiness('Media');

        $arrReturn = [
            'content' => $content,
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_url2' => $thumbnail_url2,
            'media' => []
        ];
        
        if (empty($arrSrcImage) && empty($thumbnail_url) && empty($thumbnail_url2)) {
            return $arrReturn;
        }

        $arrImages = [];
        $arrValidExt = explode(',', config('cms.backend.media.ext.image'));

        // Get disk storage
        $disk = Storage::disk(config('cms.backend.media.storage'));
        $typeName = config('cms.backend.media.name.1');

        //get current date to make directory
        $date_year = date('Y');
        $date_month = date('m');
        $date_day = date('d');

        foreach ($arrSrcImage as $src) {
            if (!starts_with($src, config('app.url') . '/images/original/')) {
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

                    $url = image_url($fileName);
                    $arrImages[] = $url;

                    if (strpos($src, '?')) {
                        $src = str_replace('&', '&amp;', $src);
                    } else {
                        $src = urldecode($src);
                    }

                    //update new url to content of article
                    $content = str_replace($src, $url, $content);

                    $srcEnCode = str_replace(array('(', ')', ' '), array('%28', '%29', '%20'), $src);
                    $content = str_replace($srcEnCode, $url, $content);

                    $srcEnCode = str_replace(array('[', ']'), array('%5B', '%5D'), $srcEnCode);
                    $content = str_replace($srcEnCode, $url, $content);

                    $srcEnCode = str_replace(array('@'), array('%40'), $srcEnCode);
                    $content = str_replace($srcEnCode, $url, $content);

                    $media_info = array_merge([
                        'size' => image_size($typeName. '/' . $fileName),
                        'ext' => $ext,
                        'mimetype' => $disk->mimeType(config('cms.backend.media.path') . '/' . $typeName . '/' . $fileName)
                    ], image_info($fileName));

                    $mediaInfo = $businessMedia->create([
                        'media_title' => str_limit($name, 250, ''),
                        'media_filename' => $fileName,
                        'media_info' => json_encode($media_info),
                        'media_type' => config('cms.backend.media.type.image'),
                        'status' => 1
                    ]);

                    $arrReturn['media'][] = [
                        'media_id' => $mediaInfo->media_id,
                        'type' => 'content'
                    ];
                }
            } else {
                $fullname = basename($src);

                //get name without extension
                $name = substr($fullname, 0, strrpos($fullname, '.'));

                $fileName = str_replace(config('app.url') . '/images/original/', '', $src);

                //get extension
                $ext = strtolower(substr(strrchr($fileName, '.'), 1));

                $media_info = array_merge([
                    'size' => image_size($typeName. '/' . $fileName),
                    'ext' => $ext,
                    'mimetype' => $disk->mimeType(config('cms.backend.media.path') . '/' . $typeName . '/' . $fileName)
                ], image_info($fileName));

                $mediaInfo = $businessMedia->create([
                    'media_title' => str_limit($name, 250, ''),
                    'media_filename' => $fileName,
                    'media_info' => json_encode($media_info),
                    'media_type' => config('cms.backend.media.type.image'),
                    'status' => 1
                ]);

                $arrReturn['media'][] = [
                    'media_id' => $mediaInfo->media_id,
                    'type' => 'content'
                ];
            }
        }
        
        $arrReturn['content'] = $content;
        
        $arrSrcImage = [
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_url2' => $thumbnail_url2
        ];
        foreach ($arrSrcImage as $type => $src) {
            if (empty($src)) {
                continue;
            }

            if (!starts_with($src, config('app.url') . '/images/original/')) {
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

                    $mediaInfo = $businessMedia->create([
                        'media_title' => str_limit($name, 250, ''),
                        'media_filename' => $fileName,
                        'media_info' => json_encode($media_info),
                        'media_type' => config('cms.backend.media.type.image'),
                        'status' => 1
                    ]);

                    $arrReturn['media'][] = [
                        'media_id' => $mediaInfo->media_id,
                        'type' => 'thumbnail'
                    ];
                    $arrReturn[$type] = image_url($fileName);
                }
            } else {
                $fullname = basename($src);

                //get name without extension
                $name = substr($fullname, 0, strrpos($fullname, '.'));

                $fileName = str_replace(config('app.url') . '/images/original/', '', $src);

                //get extension
                $ext = strtolower(substr(strrchr($fileName, '.'), 1));

                $media_info = array_merge([
                    'size' => image_size($typeName. '/' . $fileName),
                    'ext' => $ext,
                    'mimetype' => $disk->mimeType(config('cms.backend.media.path') . '/' . $typeName . '/' . $fileName)
                ], image_info($fileName));

                $mediaInfo = $businessMedia->create([
                    'media_title' => str_limit($name, 250, ''),
                    'media_filename' => $fileName,
                    'media_info' => json_encode($media_info),
                    'media_type' => config('cms.backend.media.type.image'),
                    'status' => 1
                ]);

                $arrReturn['media'][] = [
                    'media_id' => $mediaInfo->media_id,
                    'type' => 'thumbnail'
                ];
                $arrReturn[$type] = image_url($fileName);
            }
        }
        
        return $arrReturn;
    }
}
