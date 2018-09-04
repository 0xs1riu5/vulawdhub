<?php

namespace App\Models\v2;

use App\Models\BaseModel;

class MemberPrice extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'member_price';

    public    $timestamps = false;


    public static function getMemberPriceByUid($rank,$goods_id)
    {
        return self::where('user_rank',$rank)->where('goods_id',$goods_id)->value('user_price');
    }

}
