<?php

return [
    'layout' => [
        'home_title' => 'Trang chủ',
        'sidebar_header' => 'THANH ĐIỀU HƯỚNG CHÍNH',
        'changepass' => 'Đổi mật khẩu',
        'logout' => 'Thoát',
        'index' => 'Danh sách',
        'create' => 'Thêm mới',
        'edit' => 'Chỉnh sửa thông tin',
        'profile' => 'Phân quyền',
        'userinfo' => 'Thông tin cá nhân',
        'message' => 'Thông báo',
        'querylog' => 'Query log',
        'layout' => 'Layout'
    ],
    'auth' => [
        'email' => 'Email',
        'password' => 'Mật khẩu mới',
        're_password' => 'Nhập lại mật khẩu mới',
        'language' => 'Ngôn ngữ',
        'remember' => 'Nhớ tôi',
        'signin' => 'Đăng nhập',
        'forgotpass' => 'Quên mật khẩu',
        'resetpass' => 'Lấy lại mật khẩu',
        'status' => [
            'active' => 'Kích hoạt',
            'inactive' => 'Chờ kích hoạt',
            'forgotpass' => 'Chờ cấp lại mật khẩu',
            'banned' => 'Khóa tài khoản',
            '1' => 'Kích hoạt',
            '2' => 'Chờ kích hoạt',
            '3' => 'Chờ cấp lại mật khẩu',
            '4' => 'Khóa tài khoản'
        ]
    ],
    'status' => [
        'title' => 'Trạng thái',
        'all' => 'Tất cả',
        'active' => 'Kích hoạt',
        'inactive' => 'Ẩn',
        '1' => 'Kích hoạt',
        '2' => 'Ẩn'
    ],
    'action' => [
        'title' => 'Hành động',
        'add' => 'Thêm mới',
        'edit' => 'Sửa',
        'resetpass' => 'Reset mật khẩu',
        'delete' => 'Xóa',
        'delete_selected' => 'Xóa chọn',
        'upload' => 'Upload',
        'import' => 'Nhập dữ liệu',
        'sort' => 'Sắp xếp',
        'log' => 'Xem log'
    ],
    'button' => [
        'save' => 'Lưu',
        'cancel' => 'Hủy',
        'delete' => 'Xóa',
        'update' => 'Cập nhật',
        'upload' => 'Upload',
        'back' => 'Quay lại'
    ],
    'folder' => [
        'create' => 'Tạo thư mục',
		'rename' => 'Đổi tên',
		'delete' => 'Xóa',
		'refresh' => 'Làm mới',
		'edit_file' => 'Xem chi tiết',
		'delete_file' => 'Xóa',
        'download_file' => 'Download'
    ],
    'article' => [
        'priority' => [
            'low' => 'Thấp',
            'normal' => 'Bình thường',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            'very_high' => 'Rất cao',
            '5' => 'Thấp',
            '6' => 'Bình thường',
            '7' => 'Trung bình',
            '8' => 'Cao',
            '9' => 'Rất cao'
        ]
    ],
    'utils' => [
        'crawler' => [
            'parselink' => [
                'title' => 'Lấy bài viết từ nguồn bên ngoài',
                'note' => 'Chỉ hỗ trợ link từ các nguồn sau: vnexpress.net, tuoitre.vn, dantri.com.vn, soha.vn, ngoisao.net',
                'button' => 'Lấy dữ liệu'
            ]
        ]
    ],
    'slide' => [
        'type' => [
            'home' => 'Trang chủ',
            'catnews' => 'Danh mục tin tức',
            'news' => 'Chi tiết tin tức',
            'catproduct' => 'Danh mục sản phẩm',
            'product' => 'Chi tiết sản phẩm'
        ]
    ],
    'block' => [
        'page' => [
            'layout' => [
                '1' => 'Layout 1 cột',
                '2' => 'Layout 2 cột',
                '3' => 'Layout 3 cột'
            ],
            'widget' => [
                'title' => 'Tiêu đế',
                'share_url' => 'Url',
                'article_type' => 'Loại bài viết',
                'category_id' => 'Chuyên mục',
                'topic_id' => 'Chủ đề',
                'limit' => 'Số lượng tin cần hiển thị',
                'offset' => 'Vị trí bắt đầu lấy tin',
                'exclude' => 'Loại trừ tin trùng trên trang',
                'child' => 'Hiển thị chuyên mục con'
            ]
        ]
    ],
    'messages' => [
        'nodata' => 'Chưa có dữ liệu.',
        'delete_selected' => 'Bạn có chắc là muốn xóa những dòng đã chọn?',
        'changestatus_success' => 'Cập nhật trạng thái thành công.',
        'changestatus_error' => 'Cập nhật trạng thái bị lỗi, vui lòng thử lại.',
        'sort_success' => 'Cập nhật thứ tự thành công.',
        'auth' => [
            'resetpass' => 'Một email hướng dẫn cách lấy lại mật khẩu đã được gửi đến mail của bạn, vui lòng kiểm tra và làm theo hướng dẫn trong mail.',
            'key' => [
                'invalid' => 'Key cấp lại mật khẩu của bạn không đúng, vui lòng xem lại.',
                'expired' => 'Thời gian cấp lại mật khẩu của bạn đã hết hạn, vui lòng xin cấp lại mật khẩu khác.'
            ],
            'changepass_success' => 'Bạn đã thay đổi mật khẩu thành công, hãy về trang đăng nhập để đăng nhập lại.'
        ],
        'log' => [
            'delete' => 'Bạn có chắc là muốn xóa log không?',
            'deleted' => 'Xóa log thành công.'
        ],
        'menu' => [
            'created' => 'Thêm menu thành công.',
            'updated' => 'Chỉnh sửa thông tin menu thành công.',
            'delete' => 'Bạn có chắc là muốn xóa menu này không?',
            'deleted' => 'Xóa menu thành công.'
        ],
        'role' => [
            'created' => 'Thêm quyền thành công.',
            'updated' => 'Chỉnh sửa thông tin quyền thành công.',
            'delete' => 'Bạn có chắc là muốn xóa quyền này không?',
            'deleted' => 'Xóa quyền thành công.'
        ],
        'group' => [
            'created' => 'Thêm nhóm thành công.',
            'updated' => 'Chỉnh sửa thông tin nhóm thành công.',
            'delete' => 'Bạn có chắc là muốn xóa nhóm này không?',
            'deleted' => 'Xóa nhóm thành công.'
        ],
        'blockip' => [
            'created' => 'Thêm block ip thành công.',
            'updated' => 'Chỉnh sửa thông tin block ip thành công.',
            'delete' => 'Bạn có chắc là muốn xóa địa chỉ ip này không?',
            'deleted' => 'Xóa địa chỉ ip thành công.'
        ],
        'user' => [
            'created' => 'Thêm người dùng thành công.',
            'updated' => 'Chỉnh sửa thông tin người dùng thành công.',
            'delete' => 'Bạn có chắc là muốn xóa người dùng này không?',
            'deleted' => 'Xóa người dùng thành công.',
            'changepass' => 'Thay đổi mật khẩu thành công.'
        ],
        'config' => [
            'created' => 'Thêm thiết lập cấu hình thành công.',
            'updated' => 'Chỉnh sửa thông tin thiết lập cấu hình thành công.',
            'delete' => 'Bạn có chắc là muốn xóa thiết lập cấu hình này không?',
            'deleted' => 'Xóa thiết lập cấu hình thành công.'
        ],
        'translate' => [
            'created' => 'Thêm thiết lập ngôn ngữ thành công.',
            'updated' => 'Chỉnh sửa thông tin thiết lập ngôn ngữ thành công.',
            'delete' => 'Bạn có chắc là muốn xóa thiết lập ngôn ngữ này không?',
            'deleted' => 'Xóa thiết lập ngôn ngữ thành công.'
        ],
        'media' => [
            'dragdrop' => 'Kéo &amp; thả tập tin vào đây.',
            'image_delete' => 'Bạn có chắc là muốn xóa hình "%s"?',
            'image_deleted' => 'Xóa hình thành công.',
            'image_delete_error' => 'Không thể xóa hình do đã có bài viết liên kết đến hình này.',
            'file_delete' => 'Bạn có chắc là muốn xóa tập tin "%s"?',
            'file_deleted' => 'Xóa tập tin thành công.',
            'file_delete_error' => 'Không thể xóa tập tin do đã có bài viết liên kết đến tập tin này.',
            'video_created' => 'Thêm video thành công.',
            'video_updated' => 'Chỉnh sửa thông tin video thành công.',
            'video_delete' => 'Bạn có chắc là muốn xóa video này không?',
            'video_deleted' => 'Xóa video thành công.',
            'video_delete_error' => 'Không thể xóa video do đã có bài viết liên kết đến video này.',
            'label_updated' => 'Cập nhật nhãn thành công.'
        ],
        'folder' => [
            'updated' => 'Đổi tên thư mục thành công.',
            'delete' => 'Bạn có chắc là muốn xóa thư mục "%s"?',
            'deleted' => 'Xóa thư mục thành công.',
            'error_delete_haschild' => 'Thư mục này đang có thư mục con nên không thể xóa.',
            'error_delete_hasfile' => 'Thư mục này đang có tập tin nên không thể xóa.',
            'error_create' => 'Có lỗi khi tạo thư mục, vui lòng thử  lại.'
        ],
        'category' => [
            'created' => 'Thêm chuyên mục thành công.',
            'updated' => 'Chỉnh sửa thông tin chuyên mục thành công.',
            'delete' => 'Bạn có chắc là muốn xóa chuyên mục này không?',
            'deleted' => 'Xóa chuyên mục thành công.'
        ],
        'article' => [
            'created' => 'Thêm bài viết thành công.',
            'updated' => 'Chỉnh sửa thông tin bài viết thành công.',
            'delete' => 'Bạn có chắc là muốn xóa bài viết này không?',
            'deleted' => 'Xóa bài viết thành công.'
        ],
        'topic' => [
            'created' => 'Thêm chủ đề thành công.',
            'updated' => 'Chỉnh sửa thông tin chủ đề thành công.',
            'delete' => 'Bạn có chắc là muốn xóa chủ đề này không?',
            'deleted' => 'Xóa chủ đề thành công.'
        ],
        'slide' => [
            'created' => 'Thêm slide thành công.',
            'updated' => 'Chỉnh sửa thông tin slide thành công.',
            'delete' => 'Bạn có chắc là muốn xóa slide này không?',
            'deleted' => 'Xóa slide thành công.'
        ],
        'location' => [
            'country' => [
                'created' => 'Thêm quốc gia thành công.',
                'updated' => 'Chỉnh sửa thông tin quốc gia thành công.',
                'delete' => 'Bạn có chắc là muốn xóa quốc gia này không?',
                'deleted' => 'Xóa quốc gia thành công.'
            ],
            'city' => [
                'created' => 'Thêm thành phố/tỉnh thành công.',
                'updated' => 'Chỉnh sửa thông tin thành phố/tỉnh thành công.',
                'delete' => 'Bạn có chắc là muốn xóa thành phố/tỉnh này không?',
                'deleted' => 'Xóa thành phố/tỉnh thành công.'
            ],
            'district' => [
                'created' => 'Thêm quận/huyện/thị xã thành công.',
                'updated' => 'Chỉnh sửa thông tin quận/huyện/thị xã thành công.',
                'delete' => 'Bạn có chắc là muốn xóa quận/huyện/thị xã này không?',
                'deleted' => 'Xóa quận/huyện/thị xã thành công.'
            ],
            'ward' => [
                'created' => 'Thêm phường/xã thành công.',
                'updated' => 'Chỉnh sửa thông tin phường/xã thành công.',
                'delete' => 'Bạn có chắc là muốn xóa phường/xã này không?',
                'deleted' => 'Xóa phường/xã thành công.'
            ]
        ],
        'buildtop' => [
            'updated' => 'Cập nhật tin thành công.',
            'deleted' => 'Xóa tin thành công.'
        ],
        'block' => [
            'page' => [
                'created' => 'Thêm trang thành công.',
                'updated' => 'Chỉnh sửa thông tin trang thành công.',
                'delete' => 'Bạn có chắc là muốn xóa trang này không?',
                'deleted' => 'Xóa trang thành công.'
            ],
            'function' => [
                'created' => 'Thêm function thành công.',
                'updated' => 'Chỉnh sửa thông tin function thành công.',
                'delete' => 'Bạn có chắc là muốn xóa function này không?',
                'deleted' => 'Xóa function thành công.'
            ],
            'template' => [
                'created' => 'Thêm template thành công.',
                'updated' => 'Chỉnh sửa thông tin template thành công.',
                'delete' => 'Bạn có chắc là muốn xóa template này không?',
                'deleted' => 'Xóa template thành công.'
            ],
            'widget' => [
                'status' => 'Bạn có chắc là muốn thay đổi trạng thái widget này không?',
                'delete' => 'Bạn có chắc là muốn xóa widget này không?',
                'deleted' => 'Xóa widget thành công.'
            ]
        ],
        'page' => [
            'created' => 'Thêm trang thành công.',
            'updated' => 'Chỉnh sửa thông tin trang thành công.',
            'delete' => 'Bạn có chắc là muốn xóa trang này không?',
            'deleted' => 'Xóa trang thành công.'
        ],
        'navigation' => [
            'created' => 'Thêm menu thành công.',
            'updated' => 'Chỉnh sửa thông tin menu thành công.',
            'delete' => 'Bạn có chắc là muốn xóa menu này không?',
            'deleted' => 'Xóa menu thành công.'
        ]
    ]
];
