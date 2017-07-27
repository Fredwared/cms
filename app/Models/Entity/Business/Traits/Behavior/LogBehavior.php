<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use Carbon\Carbon;

trait LogBehavior
{
    public function writeLog($params)
    {
        $params = array_merge([
            'log_type' => null,
            'model_name' => null,
            'model_id' => null,
            'log_ip' => null,
            'log_url' => null,
            'log_content' => null,
            'log_method' => null,
            'query_time' => null,
            'user_id' => null,
            'user_agent' => null,
            'cookie_val' => null,
        ], $params);

        $this->create([
            'log_type' => $params['log_type'],
            'model_name' => $params['model_name'] ?? null,
            'model_id' => $params['model_id'] ?? null,
            'log_ip' => $params['log_ip'],
            'log_url' => $params['log_url'],
            'log_content' => json_encode($params['log_content']),
            'log_method' => $params['log_method'],
            'query_time' => $params['query_time'],
            'user_id' => $params['user_id'],
            'user_agent' => $params['user_agent'],
            'cookie_val' => $params['cookie_val'],
        ]);
    }

    public function getListLog($params = [])
    {
        $params = array_merge([
            'log_type' => null,
            'user_id' => null,
            'item_module' => null,
            'model_id' => null,
            'log_ip' => null,
            'user_id' => null,
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->where('log_type', '<>', 'query')->orderBy('log_id', 'DESC');
        
        if (auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            $query->where('user_id', '<>', config('cms.backend.super_admin_id'));
        }
        
        if (!empty($params['log_type'])) {
            $query->where('log_type', '=', $params['log_type']);
        }
        
        if (!empty($params['user_id'])) {
            $query->where('user_id', '=', $params['user_id']);
        }
        
        if (!empty($params['model_name'])) {
            $query->where('model_name', '=', $params['model_name']);
        }
        
        if (!empty($params['model_id'])) {
            $query->where('model_id', '=', $params['model_id']);
        }
        
        if (!empty($params['log_ip'])) {
            $query->where('log_ip', 'like', '%' . $params['log_ip'] . '%');
        }
        
        if (!empty($params['user_id'])) {
            $query->whereIn('user_id', (array)$params['user_id']);
        }

        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
            $query->where('created_at', '>=', $params['date_from']);
        }

        if (!empty($params['date_to'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:29');
            $query->where('created_at', '<=', $params['date_to']);
        }
        
        $query->with('user');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function getListLogQuery($params = [])
    {
        $params = array_merge([
            'log_url' => null,
            'log_ip' => null,
            'log_method' => null,
            'user_id' => null,
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->where('log_type', '=', 'query')->orderBy('log_id', 'DESC');

        if (auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            $query->where('user_id', '<>', config('cms.backend.super_admin_id'));
        }

        if (!empty($params['log_url'])) {
            $query->where('log_url', 'like', '%' . $params['log_url'] . '%');
        }

        if (!empty($params['log_ip'])) {
            $query->where('log_ip', 'like', '%' . $params['log_ip'] . '%');
        }

        if (!empty($params['log_method'])) {
            $query->where('log_method', '=', $params['log_method']);
        }

        if (!empty($params['user_id'])) {
            $query->whereIn('user_id', (array)$params['user_id']);
        }

        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
            $query->where('created_at', '>=', $params['date_from']);
        }

        if (!empty($params['date_to'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:29');
            $query->where('created_at', '<=', $params['date_to']);
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function clearLog($days = 60, $log_type = null)
    {
        if (empty($log_type)) {
            $log_type = config('cms.backend.log.type');
        }
        $query = $this->whereIn('log_type', (array) $log_type);

        if ($days > 0) {
            $query->whereDate('created_at', '<=', Carbon::now()->subDays($days));
        } else {
            $query->whereDate('created_at', '<=', Carbon::now());
        }

        return $query->forceDelete();
    }
}
