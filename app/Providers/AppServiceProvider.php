<?php

namespace App\Providers;

use App\Models\Services\Jobs\UpdateLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // tracking query log
        $request = app('request');

        DB::listen(function ($query) use ($request) {
            //  $query is an object with the properties:
            //  sql: The query
            //  bindings: the sql query variables
            //  time: The execution time for the query
            //  connectionName: The name of the connection

            try {
                $strQuery = $this->parseQueryString($query);

                if (!empty($strQuery)) {
                    $params = [
                        'log_type' => 'query',
                        'log_url' => $request->url(),
                        'log_content' => $strQuery,
                        'log_method' => strtolower($request->method()),
                        'query_time' => $query->time,
                        'log_ip' => $request->getClientIp(),
                        'user_agent' => $request->header('User-Agent')
                    ];
                    if (auth('backend')->check()) {
                        $params['user_id'] = auth('backend')->user()->getAccountId();
                        $params['cookie_val'] = auth('backend')->getSession()->getId();
                    }

                    //write log to db
                    dispatch(new UpdateLog($params));
                }
            } catch (\Exception $e) {
                $params = [
                    'log_type' => 'error',
                    'log_content' => [
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
                $params['log_ip'] = $request->getClientIp();
                $params['user_agent'] = $request->header('User-Agent');
                if (auth('backend')->check()) {
                    $params['user_id'] = auth('backend')->user()->getAccountId();
                    $params['cookie_val'] = auth('backend')->getSession()->getId();
                }

                //write log to db
                dispatch(new UpdateLog($params));
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }

    private function parseQueryString($query)
    {
        // check Object
        if ($query instanceof QueryExecuted) {
            // ********* Setter SQL query ********* //
            // Process the sql and the bindings:
            foreach ($query->bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $query->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $query->bindings[$i] = "'$binding'";
                    }
                }
            }

            // Insert bindings into query
            $strQuery = str_replace(array('%', '?'), array('%%', '%s'), $query->sql);

            // Return a formatted string
            $strQuery = vsprintf($strQuery, $query->bindings);

            if (stripos($strQuery, 'select count(*) as aggregate') !== false || stripos($strQuery, 'select * from `log`') !== false || stripos($strQuery, 'update `log`') !== false || stripos($strQuery, 'delete from `log`') !== false) {
                return null;
            }
        }

        // return
        return $strQuery ?? null;
    }
}
