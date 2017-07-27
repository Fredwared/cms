<?php
namespace App\Console\Commands;

use App\Models\Services\Globals;
use Illuminate\Console\Command;

class ClearLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clearlog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log on database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get modal
        $businessLog = Globals::getBusiness('Log');

        //remove log table has old
        $result = $businessLog->clearLog(config('cms.backend.log.day_limit'), config('cms.backend.log.type'));

        if (env('APP_ENV') != 'local' && $result > 0) {
            $pushBullet = Globals::getPushBullet();
            $pushBullet->all()->note(config('app.name') . ': Clear log result!', 'There is ' . $result . ' row(s) be deleted.');
        }

        return $result;
    }
}
