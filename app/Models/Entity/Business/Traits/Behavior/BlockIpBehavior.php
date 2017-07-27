<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockIpBehavior
{
    public function scopeStatus($query, $condition)
    {
        return $query->where('status', '=', $condition);
    }

    public function getListBlockIp($params = [])
    {
        $params = array_merge([
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        if (!empty($params['status'])) {
            $query = $this->status($params['status'])->orderBy('updated_at', 'DESC');
        } else {
            $query = $this->orderBy('updated_at', 'DESC');
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
    
    public function checkIp($ip_address)
    {
        return $this->where('status', '=', 1)->where('ip_address', '=', $ip_address)->first();
    }
}
