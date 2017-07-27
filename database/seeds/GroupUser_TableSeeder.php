<?php

use Illuminate\Database\Seeder;
use App\Models\Services\Globals;

class GroupUser_TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author TienDQ
     * @return void
     */
    public function run()
    {
        $businessGroup = Globals::getBusiness('Group');
        $businessUser = Globals::getBusiness('User');
        $businessGroupUser = Globals::getBusiness('Group_User');

        $businessGroup->truncate();
        $businessUser->truncate();
        $businessGroupUser->truncate();

        //create group super admin
        $groupInfo = $businessGroup->create([
            'group_name' => 'Super Admin',
            'group_description' => 'This is a group has highest permission.',
            'parent_id' => 0,
            'status' => 1,
        ]);

        //create group user
        $businessGroup->create([
            'group_name' => 'User',
            'group_description' => 'This group using for normal user.',
            'parent_id' => 0,
            'status' => 1,
        ]);

        $userInfo = $businessUser->create([
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('Super@dm!n'),
            'fullname' => 'Super Administrator',
            'gender' => 1,
            'landing_page' => 'backend.log.index',
            'default_language' => config('cms.backend.default_locale'),
            'days_password_expired' => 365,
            'change_pass_first_login' => 0,
            'password_updated_at' => \Carbon\Carbon::now(),
            'status' => 1,
        ]);

        //update table group_user
        $businessGroupUser->addGroupUserByGroup($groupInfo->group_id, [$userInfo->getAccountId()]);
    }
}
