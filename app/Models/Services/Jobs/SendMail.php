<?php
namespace App\Models\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $template;
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($template, $params)
    {
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\Models\Services\SendMail::send($this->template, $this->params);
    }
}
