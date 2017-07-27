<?php

if (!function_exists('format_date')) {

    /**
     * Function format date from a string
     *
     * @param string $str
     * @param string $format
     *
     * @return string
     */
    function format_date($strTime, $format = 'default')
    {
        if (empty($strTime)) {
            return '';
        }

        $masks = [
            'default' => 'd/m/Y H:i:s', // 20/10/2008 12:37:21
            'shortDate' => 'd/m/y', // 20/10/08
            'mediumDate' => 'd M, Y', // 20 Tháng 10, 2008
            'longDate' => 'd F, Y', // 20 Tháng mười, 2008
            'fullDate' => 'D, d F, Y', // Chủ nhật, 20 Tháng mười, 2008
            'shortTime' => 'H:i', // 5:46
            'mediumTime' => 'H:i:s', // 5:46:21
        ];

        if (array_key_exists($format, $masks)) {
            $format = $masks[$format];
        }

        return date($format, strtotime($strTime));
    }
}

if (!function_exists('trans_by_locale')) {

    /**
     * Function get content language by locale
     *
     * @param string $content
     * @param string $locale
     *
     * @return string
     */
    function trans_by_locale($content, $locale)
    {
        $content = json_decode($content, true);

        if (!isset($content[$locale]) || empty($content[$locale])) {
            return $content[config('app.locale')];
        }

        return $content[$locale];
    }
}

if (!function_exists('check_permission')) {

    /**
     * Check permission user
     *
     * @param string $menu
     * @param string $role
     * @param string $group
     *
     * @return boolean
     */
    function check_permission($menu, $role, $group = null)
    {
        //get model
        $modelMenu = \App\Models\Services\Globals::getBusiness('Menu');
        $modelProfile = App\Models\Services\Globals::getBusiness('Profile');

        if (empty($group)) {
            $group = auth('backend')->user()->groups->pluck('group_id')->toArray();
        }

        if (is_string($menu)) {
            //get namespace
            $action = app('request')->route()->getAction();
            $namespace = strtolower(class_basename($action['namespace']));
            if ($namespace != 'backend') {
                $namespace = 'backend_' . $namespace;
            }

            $menu_id = $modelMenu->getMenuIdByCode($namespace . '_' . $menu . '_index');
        } else {
            $menu_id = $menu;
        }

        return $modelProfile->checkPermission($menu_id, $role, $group);
    }
}

if (!function_exists('trans_by_params')) {

    /**
     * Function translate content with params
     *
     * @param string $content
     * @param array $params
     *
     * @return string
     */
    function trans_by_params($content, $params)
    {
        return vsprintf(trans($content), $params);
    }
}

if (!function_exists('image_url')) {

    /**
     * Function show image
     *
     * @param string $image
     * @param string $template
     *
     * @return string
     */
    function image_url($image, $template = 'original')
    {
        if (is_string($image)) {
            $file_name = $image;
        } else {
            $file_name = $image->media_filename;
        }
        $file_name = ltrim($file_name, '/');

        if (config('cms.backend.media.storage') == 'local') {
            return config('app.url') . '/images/' . $template . '/' . $file_name;
        }

        return \Illuminate\Support\Facades\Storage::disk(config('cms.backend.media.storage'))->url(config('cms.backend.media.path') . '/image/' . $file_name);
    }
}

if (!function_exists('image_info')) {

    /**
     * Return info of image
     *
     * @author TienDQ
     */
    function image_info($path)
    {
        try {
            $imageinfo = getimagesize(image_url($path));

            return [
                'width' => $imageinfo[0],
                'height' => $imageinfo[1],
                'bits' => $imageinfo['bits'] ?? null,
                'channels' => $imageinfo['channels'] ?? null
            ];
        } catch (\Exception $e) {
            return [
                'width' => 0,
                'height' => 0,
                'bits' => null,
                'channels' => null
            ];
        }
    }
}

if (!function_exists('image_size')) {

    /**
     * Return sizes of image
     *
     * @author TienDQ
     */
    function image_size($path)
    {
        $disk = \Illuminate\Support\Facades\Storage::disk(config('cms.backend.media.storage'));

        try {
            return $disk->size(config('cms.backend.media.path') . '/' . ltrim($path, '/'));
        } catch (\Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('file_url')) {

    /**
     * Function show file
     *
     * @param string $file
     *
     * @return string
     */
    function file_url($file)
    {
        if (is_string($file)) {
            $file_name = $file;
        } else {
            $file_name = $file->media_filename;
        }
        $file_name = ltrim($file_name, '/');

        return \Illuminate\Support\Facades\Storage::disk(config('cms.backend.media.storage'))->url(config('cms.backend.media.path') . '/file/' . $file_name);
    }
}

if (!function_exists('covert_size')) {

    /**
     * Function convert size
     *
     * @param string $size
     *
     * @return string
     */
    function covert_size($size)
    {
        //to KB
        $unit = ' KB';
        $result = $size / 1024;

        if ($result >= 1024) { //check if > 1024 KB
            $unit = ' MB';
            $result = $result / 1024;

            if ($result >= 1024) { //check if > 1024 MB
                $unit = ' GB';
                $result = $result / 1024;
            }
        }

        return round($result, 2) . $unit;
    }
}

if (!function_exists('get_config')) {

    /**
     * Function get config by name
     *
     * @param string $field_name
     *
     * @return mixed
     */
    function get_config($field_name)
    {
        //get model
        $modelConfig = \App\Models\Services\Globals::getModel('Config');
        $data = json_decode($modelConfig->getConfigByName(['field_name' => $field_name]), true);

        return $data[config('app.locale')];
    }
}

if (!function_exists('get_translate')) {

    /**
     * Function get tranlate by code and mode
     *
     * @param string $translate_code
     * @param string $translate_mode
     *
     * @return mixed
     */
    function get_translate($translate_code, $translate_mode)
    {
        //get model
        $modelTranslate = \App\Models\Services\Globals::getModel('Translate');
        $data = json_decode($modelTranslate->getTranslate([
            'translate_code' => $translate_code,
            'translate_mode' => $translate_mode
        ]), true);

        return $data[config('app.locale')];
    }
}

if (!function_exists('language_switcher')) {

    /**
     * Function show language link
     *
     * @param string $style
     * @param string $separate
     *
     * @return mixed
     */
    function language_switcher($style = 'flag', $separate = '&nbsp;&nbsp;')
    {
        $output = array();
        $arrLanguage = config('laravellocalization.supportedLocales');
        $baseUrl = request()->getUri();

        if (preg_match('/language_id=([a-z]{2})/', $baseUrl)) {
            $baseUrl = preg_replace('/language_id=([a-z]{2})/', 'language_id=%s', $baseUrl);
        } else {
            if (strstr($baseUrl, '?') !== false) {
                $baseUrl = $baseUrl . '&language_id=%s';
            } else {
                $baseUrl = $baseUrl . '?language_id=%s';
            }
        }

        switch ($style) {
            case 'flag':
                $label = '<span class="flag-icon flag-icon-%s"></span>';
                break;
            case 'locale':
                $label = '%s';
                break;
            default:
                $label = '%s';
                break;
        }

        foreach ($arrLanguage as $language => $data) {
            $output[] = '<a title="' . $data['native'] . '" href="' . sprintf($baseUrl, $language) . '">' . sprintf($label, $data['flag']) . '</a>';
        }

        return implode($separate, $output);
    }
}

if (!function_exists('url_static')) {

    /**
     * Function get link static
     *
     * @param string $type
     * @param string $style
     * @param string $filename
     *
     * @return mixed
     */
    function url_static($type, $style, $filename = '')
    {
        $arrStatic = [
            '3rd' => '3rd',
            'be' => 'backend',
            'fe' => 'frontend',
            'error' => 'error'
        ];

        return asset('static/' . $arrStatic[$type] . '/' . $style . '/' . $filename, env('APP_SECURE', false));
    }
}

if (!function_exists('translation_item')) {

    /**
     * Function get detail of all language of item
     *
     * @param string $item_id
     * @param string $type
     *
     * @return mixed
     */
    function translation_item($item_id, $type)
    {
        $arrLanguage = config('laravellocalization.supportedLocales');
        //get model
        $businessLangMap = \App\Models\Services\Globals::getBusiness('LangMap');

        $arrResult = array();
        foreach ($arrLanguage as $language => $data) {
            $arrResult[$language] = null;
        }

        $arrTranslation = $businessLangMap->getTranslations($item_id, $type);

        if (!empty($arrTranslation)) {
            foreach ($arrTranslation as $translation) {
                $arrResult[$translation->language_id] = $translation;
            }
        }

        return $arrResult;
    }
}

if (!function_exists('check_paging')) {

    /**
     * Function to check item paging valid or not
     *
     * @param int $item
     *
     * @return int
     */
    function check_paging($item = null)
    {
        if ($item && in_array($item, config('cms.backend.pagination.list'))) {
            return $item;
        }

        return config('cms.backend.pagination.default');
    }
}

if (!function_exists('check_language')) {

    /**
     * Function to check language valid or not
     *
     * @param int $item
     *
     * @return int
     */
    function check_language($language = null)
    {
        if ($language && in_array($language, array_keys(config('laravellocalization.supportedLocales')))) {
            return $language;
        }

        return config('app.locale');
    }
}

if (!function_exists('crop_string')) {

    /**
     * Function cut string
     *
     * @param string $text
     *
     * @param int $limit
     *
     * @param int $limit
     *
     * @return string
     */
    function crop_string($text, $limit = 15, $count = 25)
    {
        $len = strlen($text);

        if ($len < $count) {
            return $text;
        }

        return str_limit($text, $limit);
    }
}

if (!function_exists('crop_word')) {

    /**
     * Function cut word
     *
     * @param string $text
     *
     * @param int $numWord
     *
     * @return string
     */
    function crop_word($text, $numWord = 25)
    {
        //Dem tu
        $wordCount = str_word_count(str_slug($text, ' '));

        if ($wordCount <= $numWord) {
            return $text;
        } else {
            $arrWord = explode(' ', $text);
            foreach ($arrWord as $word) {
                if ($word) {
                    $tmp[] = $word;
                }
            }
            $str = implode(' ', array_slice($tmp, 0, $numWord));

            return $str . '...';
        }
    }
}
