<?php
namespace App\Models\Services\Jobs;

use App\Jobs\Job;
use App\Models\Services\Globals;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLog extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $businessLog = Globals::getBusiness('Log');
        $businessLog->writeLog($this->params);
    }
}
