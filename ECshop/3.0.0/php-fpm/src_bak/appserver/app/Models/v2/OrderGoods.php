<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;


class OrderGoods extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'order_goods';
    protected $primaryKey = 'rec_id';
    public    $timestamps = false;

    protected $guarded = [];
    protected $appends = ['id', 'product', 'property', 'attachment', 'total_amount', 'total_price', 'product_price', 'is_reviewed'];
    protected $visible = ['id', 'product', 'property', 'attachment', 'total_amount', 'total_price', 'product_price', 'is_reviewed'];


    /**
    * 获取商品出售总数
    *
    * @access public
    * @param integer $goods_id
    * @return integer
    */
    public static function getSalesCountById($goods_id)
    {
        // return self::where(['goods_id' => $goods_id])->sum('goods_number');

        // 查询该商品销量

        return self::leftJoin('order_info', 'order_info.order_id', '=', 'order_goods.order_id')
            ->where('goods_id', $goods_id)
            ->where('order_status', 5)
	    ->sum('goods_number');
    }

    /**
     * 取得某订单应该赠送的积分数
     * @param   array   $order  订单
     * @return  int     积分数
     */
    public static function integralToGive($order_id)
    {
        $model = self::select('goods.give_integral', 'order_goods.goods_price', 'order_goods.goods_number', 'goods.goods_id', 'order_goods.goods_id', 'goods.goods_name', 'order_goods.goods_attr')->join('goods', 'goods.goods_id', '=', 'order_goods.goods_id')
                ->where('order_goods.order_id', $order_id)
                ->where('order_goods.goods_id', '>', 0)
                ->where('order_goods.parent_id', 0)
                ->where('order_goods.is_gift', 0)
                ->where('order_goods.extension_code', '<>', 'package_buy')
                ->get();

        if(count($model)>0)
        {
            $integral = 0;
            $sum = 0;

            foreach ($model as $order_goods) {
                $integral = $order_goods->goods_price;
                if($order_goods->give_integral > -1){
                    $integral = $order_goods->give_integral;
                }
                $sum += $order_goods->goods_number *$integral;
            }

            return $sum;
        }

        return false;
    }

    public function orderinfo()
    {
        return $this->belongsTo('App\Models\v2\OrderInfo','order_id','order_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes['goods_id'];
    }

    public function getProductAttribute()
    {
        return [
            'id' => $this->attributes['goods_id'],
            'name' => $this->attributes['goods_name'],
            'price' => $this->attributes['goods_price'],
            'photos' => GoodsGallery::getPhotosById($this->attributes['goods_id'])
        ];
    }

    public function getPropertyAttribute()
    {
        return preg_replace("/(?:\[)(.*)(?:\])/i", '', $this->attributes['goods_attr']);
    }

    public function getAttachmentAttribute()
    {
        return null;
    }

    public function getTotalAmountAttribute()
    {
        return $this->attributes['goods_number'];
    }

    public function getTotalPriceAttribute()
    {
        return number_format($this->attributes['goods_price'] * $this->attributes['goods_number'], 2, '.', '');
    }

    public function getProductPriceAttribute()
    {
        // TODO 获取选择商品属性的价格
        return $this->attributes['goods_price'];
    }

    public function getIsReviewedAttribute()
    {
        return false;
    }
}
