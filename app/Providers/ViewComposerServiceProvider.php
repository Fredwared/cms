<?php

namespace App\Providers;

use App\Models\Services\Globals;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;

/**
 * This is provider for using view share
 * @author AnPCD
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Call function composer
        $this->composer();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Composer
     */
    private function composer()
    {
        view()->composer('*', function ($view) {
            //detect device
            $agent = new Agent();
            $device_env = 3;

            if ($agent->isTablet()) {
                $device_env = 2;
            } elseif ($agent->isMobile()) {
                $device_env = 1;
            }

            $view->with(compact('device_env'));
        });
        
        view()->composer(['backend.partials.content_header', 'backend.partials.sidebar'], function ($view) {
            //get controller and action
            $action = app('request')->route()->getAction();
            $controller = class_basename($action['controller']);
            
            $namespace = strtolower(class_basename($action['namespace']));
            if ($namespace != 'backend') {
                $namespace = 'backend_' . $namespace;
            }
            
            list($controller, $action) = explode('@', $controller);
            $controller = strtolower(str_replace('Controller', '', $controller));
            
            //get menu by menu code
            $modelMenu = Globals::getBusiness('Menu');
            $menuInfo = $modelMenu->findByAttributes([
               'menu_code' => $namespace . '_' . $controller . '_index'
            ]);
            $arrListMenu = $modelMenu->getListMenu(['status' => 1]);
            $arrMenuByUser = $modelMenu->getListMenuByUser();

            $view->with(compact('namespace', 'controller', 'action', 'menuInfo', 'arrListMenu', 'arrMenuByUser'));
        });

        view()->composer('frontend.*', function ($view) {
            $modelCategory = Globals::getModel('Category');
            $arrListCategory = $modelCategory->getList([
                'language_id' => 'vi',
                'category_type' => 1
            ]);

            $view->with(compact('arrListCategory'));
        });
    }
}
