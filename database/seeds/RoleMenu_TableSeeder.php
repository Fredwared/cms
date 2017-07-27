<?php

use Illuminate\Database\Seeder;
use App\Models\Services\Globals;

class RoleMenu_TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author TienDQ
     * @return void
     */
    public function run()
    {
        $businessRole = Globals::getBusiness('Role');
        $businessMenu = Globals::getBusiness('Menu');
        $businessProfile = Globals::getBusiness('Profile');

        $businessRole->truncate();
        $businessMenu->truncate();
        $businessProfile->truncate();

        //create role
        $businessRole->create([
            'role_code' => 'view',
            'role_name' => json_encode(['vi' => 'Xem']),
            'role_priority' => 1,
            'action_applied' => 'index,listArticle,profile,queryLog,download,menu,listProduct,userInfo,layout,getTemplate,detailTemplate,detailFunction',
            'status' => 1,
        ]);

        $businessRole->create([
            'role_code' => 'insert',
            'role_name' => json_encode(['vi' => 'Thêm']),
            'role_priority' => 2,
            'action_applied' => 'create,store,save,upload,storeWidgetStatic,storeWidgetDynamic',
            'status' => 1,
        ]);

        $businessRole->create([
            'role_code' => 'update',
            'role_name' => json_encode(['vi' => 'Sửa']),
            'role_priority' => 3,
            'action_applied' => 'edit,update,changeStatus,sort,updateSort,updateLabel,avatar,updateSortWidget,editWidget,updateWidget,changeStatusWidget',
            'status' => 1,
        ]);

        $businessRole->create([
            'role_code' => 'delete',
            'role_name' => json_encode(['vi' => 'Xóa']),
            'role_priority' => 4,
            'action_applied' => 'destroy,destroyWidget',
            'status' => 1,
        ]);

        //create menu
        $arrMenuId = [];

        //menu for article
        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Tin tức']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-book',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý chuyên mục']),
            'menu_code' => 'backend_article_category_index',
            'route_name' => 'backend.article.category.index',
            'menu_icon' => 'fa-navicon',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý bài viết']),
            'menu_code' => 'backend_article_article_index',
            'route_name' => 'backend.article.index',
            'menu_icon' => 'fa-newspaper-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý chủ đề']),
            'menu_code' => 'backend_article_topic_index',
            'route_name' => 'backend.article.topic.index',
            'menu_icon' => 'fa-object-group',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Tin tức nổi bật']),
            'menu_code' => 'backend_article_buildtop_index',
            'route_name' => 'backend.article.buildtop.index',
            'menu_icon' => 'fa-check-square-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        //menu for product
        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Sản phẩm']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-product-hunt',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý chuyên mục']),
            'menu_code' => 'backend_product_category_index',
            'route_name' => 'backend.product.category.index',
            'menu_icon' => 'fa-navicon',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Danh sách nhà sản xuất']),
            'menu_code' => 'backend_product_manufacturer_index',
            'route_name' => 'backend.product.manufacturer.index',
            'menu_icon' => 'fa-industry',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Bảng thông số kỹ thuật']),
            'menu_code' => 'backend_product_spectification_index',
            'route_name' => 'backend.product.spectification.index',
            'menu_icon' => 'fa-list-ol',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Danh sách sản phẩm']),
            'menu_code' => 'backend_product_product_index',
            'route_name' => 'backend.product.index',
            'menu_icon' => 'fa-shopping-cart',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Sản phẩm nổi bật']),
            'menu_code' => 'backend_product_buildtop_index',
            'route_name' => 'backend.product.buildtop.index',
            'menu_icon' => 'fa-check-square-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý block']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-th-large',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Page']),
            'menu_code' => 'backend_block_page_index',
            'route_name' => 'backend.block.page.index',
            'menu_icon' => 'fa-th-large',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Function']),
            'menu_code' => 'backend_block_function_index',
            'route_name' => 'backend.block.function.index',
            'menu_icon' => 'fa-th-large',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Template']),
            'menu_code' => 'backend_block_template_index',
            'route_name' => 'backend.block.template.index',
            'menu_icon' => 'fa-th-large',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        //menu for config website
        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Cấu hình website']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-desktop',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Thiết lập trang']),
            'menu_code' => 'backend_configwebsite_page_index',
            'route_name' => 'backend.configwebsite.page.index',
            'menu_icon' => 'fa-file-text-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Thiết lập menu']),
            'menu_code' => 'backend_configwebsite_navigation_index',
            'route_name' => 'backend.configwebsite.navigation.index',
            'menu_icon' => 'fa-list',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Thiết lập ngôn ngữ']),
            'menu_code' => 'backend_configwebsite_translate_index',
            'route_name' => 'backend.configwebsite.translate.index',
            'menu_icon' => 'fa-language',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Thiết lập cấu hình']),
            'menu_code' => 'backend_configwebsite_config_index',
            'route_name' => 'backend.configwebsite.config.index',
            'menu_icon' => 'fa-cog',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Slide']),
            'menu_code' => 'backend_configwebsite_slide_index',
            'route_name' => 'backend.configwebsite.slide.index',
            'menu_icon' => 'fa-slideshare',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        //menu for media
        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý tập tin']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-cloud',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý file']),
            'menu_code' => 'backend_media_file_index',
            'route_name' => 'backend.media.file.index',
            'menu_icon' => 'fa-file-text-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý hình ảnh']),
            'menu_code' => 'backend_media_image_index',
            'route_name' => 'backend.media.image.index',
            'menu_icon' => 'fa-file-image-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý video']),
            'menu_code' => 'backend_media_video_index',
            'route_name' => 'backend.media.video.index',
            'menu_icon' => 'fa-file-video-o',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        //menu for config system
        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Cấu hình hệ thống']),
            'menu_code' => null,
            'route_name' => null,
            'menu_icon' => 'fa-cogs',
            'parent_id' => 0,
            'status' => 1,
        ]);
        $arrMenuId[] = $inParentId = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý menu']),
            'menu_code' => 'backend_menu_index',
            'route_name' => 'backend.menu.index',
            'menu_icon' => 'fa-list',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý quyền']),
            'menu_code' => 'backend_role_index',
            'route_name' => 'backend.role.index',
            'menu_icon' => 'fa-shield',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý nhóm']),
            'menu_code' => 'backend_group_index',
            'route_name' => 'backend.group.index',
            'menu_icon' => 'fa-group',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý người dùng']),
            'menu_code' => 'backend_user_index',
            'route_name' => 'backend.user.index',
            'menu_icon' => 'fa-user',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý block ip']),
            'menu_code' => 'backend_blockip_index',
            'route_name' => 'backend.blockip.index',
            'menu_icon' => 'fa-ban',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý địa điểm']),
            'menu_code' => 'backend_location_location_index',
            'route_name' => 'backend.location.index',
            'menu_icon' => 'fa-map-marker',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        $menuInfo = $businessMenu->create([
            'menu_name' => json_encode(['vi' => 'Quản lý log']),
            'menu_code' => 'backend_log_index',
            'route_name' => 'backend.log.index',
            'menu_icon' => 'fa-history',
            'parent_id' => $inParentId,
            'status' => 1,
        ]);
        $arrMenuId[] = $menuInfo->menu_id;

        //insert into table profile
        foreach ($arrMenuId as $intMenuId) {
            $businessProfile->addProfileAdminByMenu($intMenuId);
        }
    }
}
