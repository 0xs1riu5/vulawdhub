<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;
use DB;

class Comment extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'comment';
    public    $timestamps = false;

    protected $appends = ['id','grade','content', 'is_anonymous', 'created_at','updated_at'];

    protected $visible = ['id','author','grade','content', 'is_anonymous', 'created_at','updated_at'];

    protected $primaryKey = 'comment_id';

    protected $guarded = [];


    const GOODS = 0;
    const ARTICLE = 1;

    const BAD     = 1;            // 差评
    const MEDIUM  = 2;            // 中评
    const GOOD    = 3;            // 好评

    /**
    * 获取商品评论总数
    *
    * @access public
    * @param integer $goods_id
    * @return integer
    */
    public static function getCommentCountById($goods_id)
    {
         return self::where(['id_value' => $goods_id])->where(['comment_type' => self::GOODS])->count();
    }

    /**
    * 获取商品评论好评率
    *
    * @access public
    * @param integer $goods_id
    * @return integer
    */
    public static function getCommentRateById($goods_id)
    {
        $rate = self::select('*',DB::raw('concat( sum(comment_rank)/(count(id_value) * 5)) AS goods_rank_rate'))->where('id_value', $goods_id)->where('comment_type' ,self::GOODS)->value('goods_rank_rate');
        return round($rate * 100);
    }

    public static function getReview(array $attributes)
    {
        extract($attributes);
        $model = self::where(['comment_type' => self::GOODS, 'id_value' => $product])->orderBy('add_time', 'DESC');
        if (isset($grade) && is_numeric($grade)) {

            if ($grade == self::BAD) {
                $model->where( function ($query) {
                    $query->where('comment_rank', '<','3')->where('comment_rank', '>', 0);
                });
            }elseif($grade == self::MEDIUM){
                $model->where('comment_rank', '=', '3');
            }elseif($grade == self::GOOD){
                $model->where( function ($query) {
                    $query->where('comment_rank', '>', '3')->orWhere('comment_rank', 0);
                });
            }
        }

        $total = $model->count();

        $data = $model
            ->with('author')
            ->paginate($per_page)->toArray();

        return self::formatBody(['reviews' => $data['data'], 'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public static function getSubtotal(array $attributes)
    {
        extract($attributes);

        $bad = self::where(['comment_type' => self::GOODS, 'id_value' => $product])
                    ->where(function ($query) {
                        $query->where('comment_rank', '<','3')->where('comment_rank', '>', 0);
                    })
                    ->count();

        $medium = self::where(['comment_type' => self::GOODS, 'id_value' => $product])->where('comment_rank', '=', 3)->count();

        $good = self::where(['comment_type' => self::GOODS, 'id_value' => $product])
                    ->where(function ($query) {
                        $query->where('comment_rank', '>', 3)->orWhere('comment_rank', 0);
                    })
                    ->count();

        $total = self::where(['comment_type' => self::GOODS, 'id_value' => $product])->count();

        return self::formatBody(['subtotal' => ['total' => $total, 'bad' => $bad, 'medium' => $medium, 'good' => $good]]);

    }

    public static function toCreate($uid, array $attributes, $is_anonymous)
    {
        extract($attributes);

        if ($member = Member::where('user_id', $uid)->first()) {
            return self::create([
                'comment_type' => 0,
                'id_value' => $goods,
                'email' => $member->email,
                //匿名时 用户名默认为ecshop
                'user_name' => ( $is_anonymous == 0 ) ? $member->user_name : 'ecshop',
                'content' => $content,
                'comment_rank' => ($grade == 2) ? 3 : (($grade == 3) ? 5 : 1),
                'add_time' => time(),
                'ip_address' => app('request')->ip(),
                'status' => 1,
                'parent_id' => 0,
                'user_id' => $uid,
            ]);
        }

        return false;
    }

    public function author()
    {
        return $this->belongsTo('App\Models\v2\Member', 'user_id', 'user_id');
    }


    public function getIdAttribute()
    {
        return $this->attributes['comment_id'];
    }


    public function getGradeAttribute()
    {
        $rank = $this->attributes['comment_rank'];
        if ($rank > 0 && $rank < 3 ) {
            return self::BAD;
        }
        if ($rank == 3) {
            return self::MEDIUM;
        }
        if ($rank > 3 && $rank < 6) {
            return self::GOOD;
        }
    }

    public function getContentAttribute()
    {
        return $this->attributes['content'];
    }

    public function getIsAnonymousAttribute()
    {
        if($this->attributes['user_name'] == 'ecshop'){
            return 1;
        }
        return 0;
    }

    public function getCreatedatAttribute()
    {
        return $this->attributes['add_time'];
    }

    public function getUpdatedatAttribute()
    {
        return $this->attributes['add_time'];
    }

}
