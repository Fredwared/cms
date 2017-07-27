<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use Carbon\Carbon;

trait TokenBehavior
{
    public function insertTokenKey($params)
    {
        //first delete all token match token type and user id
        $this->deleteByAttributes([
            'token_type' => $params['token_type'],
            'user_id' => $params['user_id']
        ]);
        
        $token_key = str_random(32);
        $this->create([
            'token_type' => $params['token_type'],
            'token_key' => $token_key,
            'user_id' => $params['user_id']
        ]);
        
        return $token_key;
    }

    public function getTokenKey($params)
    {
        return $this->where('token_type', '=', $params['token_type'])
            ->where('user_id', '=', $params['user_id'])
            ->where('created_at', '>=', Carbon::now()->subDay(1)->format('Y-m-d H:i:s'))
            ->first();
    }

    public function deleteTokenKey($params)
    {
        return $this->deleteByAttributes([
            'token_type' => $params['token_type'],
            'user_id' => $params['user_id']
        ]);
    }
}
