<?php
namespace App\Models\Services\Jobs;

use App\Jobs\Job;
use App\Models\Services\Caching;
use App\Models\Services\Globals;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCache extends Job implements ShouldQueue
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
        switch ($this->params['table']) {
            case 'article':
                Caching::getInstance()->deleteAllCache();
                break;
            case 'category':
                Caching::getInstance()->deleteAllCache();
                break;
            case 'config':
                $businessConfig = Globals::getBusiness('Config');
                $arrData = $businessConfig->find($this->params['id']);

                $modelConfig = Globals::getModel('Config');
                $modelConfig->getConfigByName([
                    'field_name' => $arrData->field_name,
                    'pre_cache' => true,
                    'type' => $this->params['type']
                ]);
                break;
            case 'translate':
                $businessTranslate = Globals::getBusiness('Translate');
                $arrData = $businessTranslate->withTrashed()->find($this->params['id']);

                $modelTranslate = Globals::getModel('Translate');
                $modelTranslate->getTranslate([
                    'translate_code' => $arrData->translate_code,
                    'translate_mode' => $arrData->translate_mode,
                    'pre_cache' => true,
                    'type' => $this->params['type']
                ]);
                break;
            default:
                break;
        }
    }
}
