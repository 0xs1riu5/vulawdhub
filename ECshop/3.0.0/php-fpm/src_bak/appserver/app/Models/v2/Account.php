<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;

class Account extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'account_log';

    protected $primaryKey = 'log_id';
    
    public    $timestamps = false;

    protected $appends = ['price', 'action', 'memo', 'create_at', 'status'];

    protected $visible = ['price', 'action', 'memo', 'create_at', 'status'];

    const IN = 1;         // 收入
    const OUT = 2;        // 支出

    public function getPriceAttribute()
    {
        return str_replace('-', '', $this->attributes['user_money']);
    }

    public function getActionAttribute()
    {
    	if ( $this->attributes['user_money'] > 0 ) {
    		return trans('message.account.increment');
    	} else {
            return trans('message.account.decrement');
        }
    }

    public function getMemoAttribute()
    {
        return $this->attributes['change_desc'];
    }

    public function getCreateAtAttribute()
    {
        return $this->attributes['change_time'];
    }

    public function getStatusAttribute()
    {
        if ( $this->attributes['user_money'] > 0 ) {
            return self::IN;
        } else {
            return self::OUT;
        }
    }

    public static function accountDetail(array $attributes)
    {
        extract($attributes);

        $user_id = Token::authorization();

        $status = !empty($status) ? $status : 0;

        $model = self::where('user_id', $user_id);

        if ($status == 0) {
            $model = $model->where('user_money', '<>', 0);
        } elseif ($status == 1) {
            $model = $model->where('user_money', '>', 0);
        } elseif ($status == 2) {
            $model = $model->where('user_money', '<', 0);
        }
        
        $total = $model->count();

        $lists = $model->orderBy('change_time', 'desc')->paginate($per_page)->toArray();

        $paged = self::formatpaged($page, $per_page, $total);
        
        return self::formatBody(['paged' => $paged, 'balances' => $lists['data']]);
    }

}