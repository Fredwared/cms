<?php
namespace App\Models\Traits;

use App\Models\Services\Jobs\UpdateCache;
use App\Models\Services\Jobs\UpdateLog;

trait TrackingLog
{
    public static function bootTrackingLog()
    {
        static::created(function ($model) {
            $params = [
                'log_type' => 'insert',
                'log_content' => [
                    'new' => $model->attributes
                ],
                'model_name' => $model->table,
                'model_id' => $model->attributes[$model->primaryKey],
                'log_ip' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent')
            ];
            if (auth('backend')->check()) {
                $params['user_id'] = auth('backend')->user()->getAccountId();
                $params['cookie_val'] = auth('backend')->getSession()->getId();
            }

            // call log
            dispatch(new UpdateLog($params));

            // call job update cache
            dispatch(new UpdateCache([
                'table' => $model->table,
                'id' => $model->attributes[$model->primaryKey],
                'type' => 'create'
            ]));
        });
        static::updated(function ($model) {
            $params = [
                'log_type' => 'update',
                'log_content' => [
                    'old' => array_diff_assoc($model->original, $model->attributes),
                    'new' => array_diff_assoc($model->attributes, $model->original)
                ],
                'model_name' => $model->table,
                'model_id' => $model->original[$model->primaryKey],
                'log_ip' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent')
            ];
            if (auth('backend')->check()) {
                $params['user_id'] = auth('backend')->user()->getAccountId();
                $params['cookie_val'] = auth('backend')->getSession()->getId();
            }

            // call log
            dispatch(new UpdateLog($params));

            // call job update cache
            dispatch(new UpdateCache([
                'table' => $model->table,
                'id' => $model->original[$model->primaryKey],
                'type' => 'update'
            ]));
        });
        static::deleted(function ($model) {
            $params = [
                'log_type' => 'delete',
                'log_content' => [
                    'old' => array_diff_assoc($model->original, $model->attributes),
                    'new' => array_diff_assoc($model->attributes, $model->original)
                ],
                'model_name' => $model->table,
                'model_id' => $model->original[$model->primaryKey],
                'log_ip' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent')
            ];
            if (auth('backend')->check()) {
                $params['user_id'] = auth('backend')->user()->getAccountId();
                $params['cookie_val'] = auth('backend')->getSession()->getId();
            }

            // call log
            dispatch(new UpdateLog($params));

            // call job update cache
            dispatch(new UpdateCache([
                'table' => $model->table,
                'id' => $model->original[$model->primaryKey],
                'type' => 'delete'
            ]));
        });
    }
}
