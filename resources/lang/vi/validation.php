<?php

return [
    'auth' => [
        'email' => [
            'required' => 'Bạn chưa nhập email.',
            'email' => 'Email không hợp lệ.',
            'exists' => 'Email này không tồn tại, vui lòng kiểm tra lại hoặc liên hệ quản trị viên.'
        ],
        'password' => [
            'required' => 'Bạn chưa nhập mật khẩu.'
        ]
    ],
    'status' => [
        'required' => 'Bạn chưa chọn trạng thái.',
        'invalid' => 'Trạng thái không hợp lệ, vui lòng chọn lại.'
    ],
    'display_order' => [
        'required' => 'Bạn chưa nhập thứ tự hiển thị.',
        'number' => 'Thứ tự hiển thị phải là số.'
    ],
    'language' => [
        'required' => 'Bạn chưa chọn ngôn ngữ.',
        'invalid' => 'Ngôn ngữ không hợp lệ, vui lòng chọn lại.'
    ],
    'captcha' => [
        'required' => 'Bạn chưa nhập mã kiểm tra.',
        'invalid' => 'Mã kiểm tra không hợp lệ.'
    ],
    'upload' => [
        'required' => 'Bạn chưa chọn file upload.',
        'accept' => 'File upload phải là csv.',
        'dragdrop_error' => 'Trình duyệt của bạn không hỗ trợ kéo thả tập tin.',
        'upload_error' => 'Không được phép upload.',
        'maxfile_error' => 'Chỉ được tải lên tối đa <b>%s</b> tập tin.',
        'ext_error' => 'Tập tin <b>%s</b> không được hỗ trợ, chỉ hỗ trợ các dạng sau: %s.',
        'size_error' => 'Kích thước của tập tin <b>%s</b> vượt quá kích thước cho phép là %s.'
    ],
    'media' => [
        'video' => [
            'media_filename' => [
                'required' => 'Bạn chưa nhập link video.',
                'maxlength' => 'Link video không được dài hơn :max ký tự.',
                'unique' => 'Video này đã tồn tại, vui lòng chọn video khác.'
            ],
            'media_source' => [
                'required' => 'Bạn chưa chọn nguồn của video.',
                'invalid' => 'Nguồn của video không hợp lệ, vui lòng chọn lại.'
            ]
        ]
    ],
    'menu' => [
        'menu_name' => [
            'required' => 'Bạn chưa nhập tên menu.',
            'maxlength' => 'Tên menu không được dài hơn :max ký tự.'
        ],
        'menu_code' => [
            'maxlength' => 'Mã menu không được dài hơn :max ký tự.',
            'unique' => 'Mã menu này đã tồn tại, vui lòng chọn mã khác.'
        ],
        'parent_id' => [
            'required' => 'Bạn chưa chọn menu cha.',
            'not_exist' => 'Menu cha này không tồn tại.'
        ],
        'route_name' => [
            'maxlength' => 'Route name không được dài hơn :max ký tự.'
        ],
        'menu_icon' => [
            'maxlength' => 'Icon của menu không được dài hơn :max ký tự.'
        ]
    ],
    'role' => [
        'role_name' => [
            'required' => 'Bạn chưa nhập tên quyền.',
            'maxlength' => 'Tên quyền không được dài hơn :max ký tự.'
        ],
        'role_code' => [
            'required' => 'Bạn chưa chọn mã quyền.',
            'maxlength' => 'Mã quyền không được dài hơn :max ký tự.',
            'unique' => 'Mã quyền này đã tồn tại, vui lòng chọn mã khác.'
        ],
        'role_priority' => [
            'number' => 'Độ ưu tiên phải là số.'
        ]
    ],
    'group' => [
        'group_name' => [
            'required' => 'Bạn chưa nhập tên nhóm.',
            'maxlength' => 'Tên nhóm không được dài hơn :max ký tự.'
        ],
        'group_description' => [
            'maxlength' => 'Mô tả nhóm không được dài hơn :max ký tự.'
        ]
    ],
    'blockip' => [
        'ip_address' => [
            'required' => 'Bạn chưa nhập địa chỉ ip.',
            'invalid' => 'Địa chỉ ip không hợp lệ, vui lòng nhập lại.',
            'maxlength' => 'Địa chỉ ip không được dài hơn :max ký tự.',
            'unique' => 'Địa chỉ ip này đã tồn tại, vui lòng chọn địa chỉ ip khác.'
        ],
        'ip_description' => [
            'maxlength' => 'Mô tả không được dài hơn :max ký tự.'
        ]
    ],
    'user' => [
        'fullname' => [
            'required' => 'Bạn chưa nhập họ và tên.',
            'maxlength' => 'Họ và tên không được dài hơn :max ký tự.'
        ],
        'email' => [
            'required' => 'Bạn chưa nhập email.',
            'email' => 'Email không hợp lệ, vui lòng nhập đúng email.',
            'maxlength' => 'Email không được dài hơn :max ký tự.',
            'unique' => 'Email này đã tồn tại, vui lòng chọn email khác.'
        ],
        'address' => [
            'maxlength' => 'Địa chỉ không được dài hơn :max ký tự.'
        ],
        'phone' => [
            'number' => 'Điện thoại phải là số.',
            'maxlength' => 'Điện thoại không được dài hơn :max ký tự.'
        ],
        'group' => [
            'required' => 'Bạn chưa chọn nhóm.'
        ],
        'password' => [
            'required' => 'Bạn chưa nhập mật khẩu mới.',
            'rangelength' => 'Mật khẩu phải nằm trong khoảng từ :min đến :max ký tự.'
        ],
        're_password' => [
            'required' => 'Bạn chưa nhập lại mật khẩu.',
            'equalTo' => 'Nhập lại mật khẩu không khớp với mật khẩu.'
        ]
    ],
    'config' => [
        'field_name' => [
            'required' => 'Bạn chưa nhập tên cấu hình.',
            'maxlength' => 'Tên cấu hình không được dài hơn :max ký tự.',
            'unique' => 'Tên cấu hình này đã tồn tại, vui lòng chọn từ khóa khác.'
        ],
        'field_value' => [
            'required' => 'Bạn chưa nhập giá trị.',
            'maxlength' => 'Giá trị không được dài hơn :max ký tự.'
        ]
    ],
    'translate' => [
        'translate_code' => [
            'required' => 'Bạn chưa nhập mã phiên dịch.',
            'maxlength' => 'Mã phiên dịch không được dài hơn :max ký tự.',
            'unique' => 'Mã phiên dịch này đã tồn tại, vui lòng chọn mã phiên dịch khác.'
        ],
        'translate_mode' => [
            'required' => 'Bạn chưa chọn chế độ nhập.'
        ],
        'translate_content' => [
            'required' => 'Bạn chưa nhập nội dung ngôn ngữ.',
            'maxlength' => 'Nội dung không được dài hơn :max ký tự.'
        ]
    ],
    'category' => [
        'category_title' => [
            'required' => 'Bạn chưa nhập tên chuyên mục.',
            'maxlength' => 'Tên chuyên mục không được dài hơn :max ký tự.'
        ],
        'category_code' => [
            'required' => 'Bạn chưa nhập code.',
            'code' => 'Code không đúng, vui lòng nhập lại.',
            'maxlength' => 'Code không được dài hơn :max ký tự.',
            'unique' => 'Code này đã tồn tại, vui lòng chọn code khác.'
        ],
        'cateparent_id' => [
            'required' => 'Bạn chưa chọn chuyên mục cha.',
            'not_exist' => 'Chuyên mục cha này không tồn tại.'
        ],
        'category_source' => [
            'required' => 'Bạn chưa chọn chuyên mục gốc.',
            'not_exist' => 'Chuyên mục gốc này không tồn tại.'
        ],
        'category_order' => [
            'number' => 'Thứ tự phải là số.'
        ],
        'category_showfe' => [
            'invalid' => 'Hiển thị trên menu không hợp lệ, vui lòng chọn lại.'
        ],
        'category_icon' => [
            'maxlength' => 'Icon của chuyên mục không được dài hơn :max ký tự.'
        ]
    ],
    'article' => [
        'article_title' => [
            'required' => 'Bạn chưa nhập tiêu đề.',
            'maxlength' => 'Tiêu đề không được dài hơn :max ký tự.'
        ],
        'article_code' => [
            'required' => 'Bạn chưa nhập code.',
            'code' => 'Code không đúng, vui lòng nhập lại.',
            'maxlength' => 'Code không được dài hơn :max ký tự.',
            'unique' => 'Code này đã tồn tại, vui lòng chọn code khác.'
        ],
        'article_description' => [
            'required' => 'Bạn chưa nhập tóm tắt.',
            'maxlength' => 'Tóm tắt không được dài hơn :max ký tự.'
        ],
        'article_content' => [
            'required' => 'Bạn chưa nhập nội dung.'
        ],
        'category_id' => [
            'required' => 'Bạn chưa chọn chuyên mục.',
            'not_exist' => 'Chuyên mục này không tồn tại.'
        ],
        'article_source' => [
            'required' => 'Bạn chưa chọn bài viết gốc.',
            'not_exist' => 'Bài viết gốc này không tồn tại.'
        ]
    ],
    'crawler' => [
        'link' => [
            'required' => 'Bạn chưa nhập link.',
            'invalid' => 'Link không hợp lệ, vui lòng nhập lại.'
        ]
    ],
    'topic' => [
        'topic_title' => [
            'required' => 'Bạn chưa nhập tên chủ đề.',
            'maxlength' => 'Tên chủ đề không được dài hơn :max ký tự.',
            'unique' => 'Tên chủ đề này đã tồn tại, vui lòng chọn tiêu đề  khác.'
        ],
        'category_id' => [
            'required' => 'Bạn chưa chọn chuyên mục.',
            'not_exist' => 'Chuyên mục này không tồn tại.'
        ],
        'topic_source' => [
            'required' => 'Bạn chưa chọn chủ đề gốc.',
            'not_exist' => 'Chủ đề gốc này không tồn tại.'
        ],
        'max_article' => [
            'invalid' => 'Số bài viết không hợp lệ'
        ]
    ],
    'slide' => [
        'slide_title' => [
            'required' => 'Bạn chưa nhập tiêu đề slide.',
            'maxlength' => 'Tiêu đề slide không được dài hơn :max ký tự.'
        ],
        'slide_description' => [
            'maxlength' => 'Mô tả slide không được dài hơn :max ký tự.'
        ],
        'slide_link' => [
            'maxlength' => 'Link của slide không được dài hơn :max ký tự.',
            'invalid' => 'Link không hợp lệ, vui lòng nhập lại.'
        ],
        'slide_target' => [
            'required' => 'Bạn chưa chọn target cho link.'
        ],
        'slide_type' => [
            'required' => 'Bạn chưa chọn loại slide.',
            'invalid' => 'Lọai slide không hợp lệ, vui lòng chọn lại.'
        ]
    ],
    'location' => [
        'country' => [
            'country_name' => [
                'required' => 'Bạn chưa nhập tên quốc gia.',
                'maxlength' => 'Tên quốc gia không được dài hơn :max ký tự.'
            ],
            'country_code' => [
                'required' => 'Bạn chưa nhập mã quốc gia.',
                'maxlength' => 'Mã quốc gia không được dài hơn :max ký tự.'
            ]
        ],
        'city' => [
            'city_name' => [
                'required' => 'Bạn chưa nhập tên thành phố/tỉnh.',
                'maxlength' => 'Tên thành phố/tỉnh không được dài hơn :max ký tự.'
            ],
            'country_id' => [
                'required' => 'Bạn chưa chọn quốc gia.',
                'not_exist' => 'Quốc gia này không tồn tại.'
            ],
            'city_zipcode' => [
                'number' => 'Zip code phải là số.'
            ],
            'city_location' => [
                'maxlength' => 'Vị trí không được dài hơn :max ký tự.'
            ]
        ],
        'district' => [
            'district_name' => [
                'required' => 'Bạn chưa nhập tên quận/huyện/thị xã.',
                'maxlength' => 'Tên quận/huyện/thị xã không được dài hơn :max ký tự.'
            ],
            'city_id' => [
                'required' => 'Bạn chưa chọn thành phố/tỉnh.',
                'not_exist' => 'Thành phố/tỉnh này không tồn tại.'
            ],
            'district_location' => [
                'maxlength' => 'Vị trí không được dài hơn :max ký tự.'
            ]
        ],
        'ward' => [
            'ward_name' => [
                'required' => 'Bạn chưa nhập tên phường/xã.',
                'maxlength' => 'Tên phường/xã không được dài hơn :max ký tự.'
            ],
            'district_id' => [
                'required' => 'Bạn chưa chọn quận/huyện/thị xã.',
                'not_exist' => 'Quận/huyện/thị xã này không tồn tại.'
            ],
            'ward_location' => [
                'maxlength' => 'Vị trí không được dài hơn :max ký tự.'
            ]
        ]
    ],
    'block' => [
        'page' => [
            'page_name' => [
                'required' => 'Bạn chưa nhập tên trang.',
                'maxlength' => 'Tên trang không được dài hơn :max ký tự.'
            ],
            'page_code' => [
                'required' => 'Bạn chưa nhập code.',
                'maxlength' => 'Code không được dài hơn :max ký tự.',
                'unique' => 'Code này đã tồn tại, vui lòng nhập lại.'
            ],
            'page_layout' => [
                'required' => 'Bạn chưa chọn layout.'
            ],
            'page_url' => [
                'maxlength' => 'Url của trang không được dài hơn :max ký tự.',
                'invalid' => 'Url không đúng, vui lòng nhập lại.'
            ]
        ],
        'function' => [
            'function_name' => [
                'required' => 'Bạn chưa nhập tên function.',
                'maxlength' => 'Tên function không được dài hơn :max ký tự.'
            ],
            'function_params' => [
                'required' => 'Bạn chưa nhập parameters.'
            ]
        ],
        'template' => [
            'template_name' => [
                'required' => 'Bạn chưa nhập tên template.',
                'maxlength' => 'Tên template không được dài hơn :max ký tự.'
            ],
            'template_area' => [
                'required' => 'Bạn chưa chọn vị trí.'
            ],
            'template_view' => [
                'required' => 'Bạn chưa nhập file view.',
                'maxlength' => 'File view không được dài hơn :max ký tự.'
            ],
            'template_content' => [
                'required' => 'Bạn chưa nhập nội dung.'
            ],
            'template_thumbnail' => [
                'required' => 'Bạn chưa chọn hình đại diện.'
            ]
        ],
        'widget' => [
            'required' => 'Bạn chưa nhập/chọn giá trị.',
        ]
    ],
    'page' => [
        'page_title' => [
            'required' => 'Bạn chưa nhập tên trang.',
            'maxlength' => 'Tên trang không được dài hơn :max ký tự.'
        ],
        'page_code' => [
            'required' => 'Bạn chưa nhập code.',
            'code' => 'Code không đúng, vui lòng nhập lại.',
            'maxlength' => 'Code không được dài hơn :max ký tự.',
            'unique' => 'Code này đã tồn tại, vui lòng chọn code khác.'
        ],
        'parent_id' => [
            'required' => 'Bạn chưa chọn trang cha.',
            'not_exist' => 'Trang cha này không tồn tại.'
        ],
        'page_source' => [
            'required' => 'Bạn chưa chọn trang gốc.',
            'not_exist' => 'Trang gốc này không tồn tại.'
        ],
        'page_content' => [
            'required' => 'Bạn chưa nhập nội dung.'
        ]
    ],
    'navigation' => [
        'navigation_title' => [
            'required' => 'Bạn chưa nhập tên menu.',
            'maxlength' => 'Tên menu không được dài hơn :max ký tự.'
        ],
        'navigation_url' => [
            'invalid' => 'Url của menu không hợp lệ.',
            'unique' => 'Url này đã tồn tại, vui lòng chọn url khác.'
        ],
        'navigation_type_id' => [
            'required' => 'Phải chọn ít nhất một dòng.'
        ],
        'parent_id' => [
            'invalid' => 'Menu cha không hợp lệ, vui lòng chọn lại.'
        ]
    ]
];
