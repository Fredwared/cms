<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // regenerate the list of all classes.
        exec('php composer dump-auto');

        // create folder storage and sub
        $this->command->info('Create folder storage and sub');
        exec('mkdir storage');
        exec('mkdir storage/framework');
        exec('mkdir storage/framework/cache');
        exec('mkdir storage/framework/sessions');
        exec('mkdir storage/framework/views');
        exec('mkdir storage/logs');

        // change permission folder bootstrap and storage
        $this->command->info('Change permission folder bootstrap and storage');
        exec('chmod 0777 -R storage');
        exec('chmod 0777 -R bootstrap');

        // remove all file in folder cloud
        $this->command->info('Remove all file in sub folder of storage');
        exec('rm -rf storage/cloud/*');
        exec('rm -rf storage/framework/cache/*');
        exec('rm -rf storage/framework/sessions/*');
        exec('rm -rf storage/framework/views/*');
        exec('rm -rf storage/logs/*');

        // delete all tables
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $name) {
                DB::table($name)->delete();
            }
        }

        // import a DB dump
        $this->command->info('Run command: mysql -u' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' ' . env('DB_DATABASE') . ' < ' . database_path() . '/' . env('DB_DATABASE') . '.sql');
        exec('mysql -u' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' ' . env('DB_DATABASE') . ' < ' . database_path() . '/' . env('DB_DATABASE') . '.sql');
        $this->command->info('Load new DB version successful!');

        // Find and run all seeders
        $classes = require base_path() . '/vendor/composer/autoload_classmap.php';
        foreach ($classes as $class) {
            if (strpos($class, 'TableSeeder') !== false) {
                $seederClass = substr(last(explode('/', $class)), 0, -4);
                $this->call($seederClass);
            }
        }
	
        $this->command->info('Time execute: ' . (microtime(true) - LARAVEL_START) . ' seconds.');
        $this->command->info('Seeding database done!');
        $this->command->comment('You can login with user/pass: superadmin@gmail.com/Super@dm!n');
    }
}
