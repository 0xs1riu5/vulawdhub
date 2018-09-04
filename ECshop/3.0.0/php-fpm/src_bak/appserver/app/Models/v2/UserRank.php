<?php

namespace App\Models\v2;
use App\Models\BaseModel;
use App\Helper\Token;

class UserRank extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'user_rank';
    public    $timestamps = false;

    protected $guarded = [];
    protected $appends = ['name', 'desc', 'score_min', 'score_max'];
    protected $visible = ['name', 'desc', 'score_min', 'score_max'];

    public static function findByPoints($points)
    {
        return self::where('min_points', '<=', $points)->where('max_points', '>', $points)->first();
    }

    public static function findRankByUid($user_id)
    {
        $user = Member::where('user_id',$user_id)->first();
        if ($user->user_rank == 0) {
            return self::findByPoints($user->rank_points);
        }
        return self::where('rank_id', $user->user_rank)->first();
    }

    public static function getRankDiscountById($rank_id)
    {
        return self::where('rank_id',$rank_id)->value('discount');
    }

    public static function getMemberRankPriceByGid($goods_id)
    {
        $user_rank = self::getUserRankByUid();

        $shop_price = Goods::where('goods_id',$goods_id)->value('shop_price');

        if ($user_rank) {
            if ($price = MemberPrice::getMemberPriceByUid($user_rank['rank_id'], $goods_id)) {
                return $price;
            }
            if ($user_rank['discount']) {
                $member_price = $shop_price * $user_rank['discount'];
            }else{
                $member_price = $shop_price;
            }
            return $member_price;
        } else {
            return $shop_price;
        }
    }
    
    public static function getUserRankByUid()
    {
        $uid = Token::authorization();
        $user = Member::where('user_id',$uid)->first();
        $data = null;
        if (!$user) {
            $data = null;
        } else {
            if ($user->user_rank == 0) {
                $user_rank = self::where('special_rank', '=', 0)->Where(function($query) use($user) {
                    $query->where('min_points', '<=', $user->rank_points)->where('max_points', '>', $user->rank_points);
                })->first();
                $data['rank_id'] = $user_rank->rank_id;
                $data['discount'] = $user_rank->discount * 0.01;
            } else {
                $user_rank = self::where('rank_id', $user->user_rank)->first();
                if($user_rank){
                    $data['rank_id'] = $user_rank->rank_id;
                    $data['discount'] = $user_rank->discount * 0.01;
                }
            }
        }
	return $data;
    }

    public function getNameAttribute()
    {
        return $this->attributes['rank_name'];
    }

    public function getDescAttribute()
    {
        return $this->attributes['discount'].'%';
    }

    public function getScoreMinAttribute()
    {
        return $this->attributes['min_points'];
    }

    public function getScoreMaxAttribute()
    {
        return $this->attributes['max_points'];
    }
}