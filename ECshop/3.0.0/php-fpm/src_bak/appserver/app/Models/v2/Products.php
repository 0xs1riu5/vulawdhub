<?php

namespace App\Models\v2;
use App\Models\BaseModel;
use App\Helper\Token;


class Products extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'products';
    public    $timestamps = false;

    protected $visible = ['id','goods_id','goods_attr', 'stock_number','goods_attr_price'];

    protected $appends = ['id','goods_attr', 'stock_number','goods_attr_price'];

    protected $guarded = [];


    public function getIdAttribute()
    {
        return $this->product_id;
    }
    public function getGoodsattrAttribute()
    {
        return $this->attributes['goods_attr'];
    }

    public function getStocknumberAttribute()
    {
        return $this->product_number;
    }

    public function getGoodsattrpriceAttribute()
    {
        return self::getTotalPrice($this->attributes['goods_attr']);
    }

    private function getTotalPrice($goods_attr)
    {
    	$attr_ids = (explode('|',$goods_attr));
    	$total = UserRank::getMemberRankPriceByGid($this->goods_id);
    	if (!$attr_ids) {
    		return $total;
    	}
    	foreach ($attr_ids as $key => $attr_id) {
    		$price = GoodsAttr::where('goods_attr_id',$attr_id)->value('attr_price');

    		if (!($price)) {
    			$price = 0;
    		}
			$total += floatval($price);

    	}
		return $total;

    }

    /**
     * 取指定规格的货品信息
     *
     * @access      public
     * @param       string      $goods_id
     * @param       array       $spec_goods_attr_id
     * @return      array
     */
    public static function  get_products_info($goods_id, $spec_goods_attr_id)
    {
        $return_array = array();

        if (empty($spec_goods_attr_id) || !is_array($spec_goods_attr_id) || empty($goods_id))
        {
            return $return_array;
        }

        $goods_attr_array = Attribute::sort_goods_attr_id_array($spec_goods_attr_id);

        if(isset($goods_attr_array['sort']))
        {
            $goods_attr = implode('|', $goods_attr_array['sort']);

            $return_array = self::where('goods_id',$goods_id)->where('goods_attr',$goods_attr)->first();

	    // if (!empty($return_array)) {
            //     $return_array = $return_array->toArray();
            // }
        }

        return $return_array;
    }
}
