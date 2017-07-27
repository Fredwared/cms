<?php

return [
    'backend' => [
        'languages' => [
            'vi' => ['name' => 'Vietnamese', 'script' => 'Latn', 'native' => 'Tiếng Việt', 'regional' => 'vi_VN', 'flag' => 'vn'],
        ],
        'default_locale' => 'vi',
        'google_translate' => ['ja', 'zh', 'ko'],
        'super_admin_group_id' => 1,
        'super_admin_id' => 1,
        'default_password' => 'Aa@123456',
        'pagination' => [
            'list' => [10, 20, 30, 50, 100, 200, 300, 500],
            'default' => 20
        ],
        'media' => [
            'storage' => env('MEDIA_STORAGE', 'local'),
            'path' => env('MEDIA_PATH', 'cloud'),
            'type' => [
                'image' => 1,
                'file' => 2,
                'video' => 3
            ],
            'name' => [
                1 => 'image',
                2 => 'file',
                3 => 'video'
            ],
            'ext' => [
                'image' => 'jpg,jpeg,png',
                'file' => 'doc,docx,xls,xlsx,pdf'
            ],
            'size' => [
                'image' => 5242880,
                'file' => 5242880
            ],
            'source' => [
                'youtube' => 'https://www.youtube.com/embed/',
                /*'facebook' => '',
                'other' => ''*/
            ],
            'pagination' => 24,
        ],
        'category' => [
            'type' => [
                'article' => 1,
                'product' => 2
            ],
            'name' => [
                '1' => 'article',
                '2' => 'product'
            ],
            'max_level' => [
                'article' => 3,
                'product' => 3
            ]
        ],
        'article' => [
            'priority' => [
                'low' => 5,
                'normal' => 6,
                'medium' => 7,
                'high' => 8,
                'very_high' => 9
            ],
            'share_url' => [
                'vi' => '/tin-tuc/',
                'en' => '/news/'
            ]
        ],
        'product' => [
            'share_url' => [
                'vi' => '/san-pham/',
                'en' => '/product/'
            ]
        ],
        'import' => [
            'type' => 'csv',
            'size' => 5242880
        ],
        'user' => [
            'status' => [
                'active' => 1,
                'inactive' => 2,
                'forgotpass' => 3,
                'banned' => 4
            ]
        ],
        'status' => [
            'active' => 1,
            'inactive' => 2
        ],
        'buildtop' => [
            'type' => [
                'article' => 'article',
                'product' => 'product'
            ]
        ],
        'slide' => [
            'type' => ['home', 'catnews', 'news', 'catproduct', 'product'],
            'default' => 'home'
        ],
        'log' => [
            'type' => ['login', 'logout', 'insert', 'update', 'delete', 'error', 'query'],
            'day_limit' => 7
        ],
        'block' => [
            'page' => [
                'layout' => [1, 2, 3]
            ],
            'type' => ['static', 'dynamic'],
            'area' => ['top', 'bottom', 'left', 'center', 'right'],
            'function' => [
                'type' => ['article', 'product']
            ]
        ]
    ],
    'limit_pagination' => 2,
    'token' => [
        'api' => 'yKxOg0zozdqabddJOHGc03a20JhnKlT4SAxc',
        'expired' => 86400,
        'type' => [
            'active_user' => 1,
            'reset_password' => 2
        ]
    ],
    'cache' => [
	    'storage' => class_exists('Memcache') ? 'memcached' : 'file',
        'time_expired' => '1440',
        'key' => [
            'config' => 'config_%s',
            'translate' => 'translate_%s_%s',
            'category' => [
                'list' => 'category_list_%s_%s',
                'detail' => 'category_detail_%s'
            ],
            'article' => [
                'list' => 'article_list_%s',
                'detail' => 'article_detail_%s'
            ]
        ]
    ]
];
