<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;
use App\Services\Shopex\Logistics;
use Log;

class Shipping extends BaseModel
{
    protected $connection = 'shop';

    protected $table = 'shipping';

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = ['id', 'code', 'name', 'desc', 'price', 'fee', 'is_additional'];

    protected $visible = ['id', 'code', 'name', 'desc', 'price', 'fee', 'is_additional'];

    public static $attrs;

    public static function findAll(array $attributes)
    {
        self::$attrs = $attributes;
        extract($attributes);

        if( isset($products) == false || json_decode($products, true) == null )
        {
            return self::formatError(self::BAD_REQUEST, trans('message.products.error'));
        }

//        $products = json_decode($products, true);
//        foreach ($products as $key => $product) {
//            $is_shipping = Goods::where('goods_id', $product['goods_id'])->pluck('is_shipping')->first();
//            $products[$key]['is_shipping'] = $is_shipping;
//        }
//        self::$attrs = ['products' => json_encode($products)];

        $region_id_list = UserAddress::getRegionIdList($address);

        $model = Shipping::join('shipping_area', 'shipping_area.shipping_id', '=', 'shipping.shipping_id')
           ->join('area_region', 'area_region.shipping_area_id', '=', 'shipping_area.shipping_area_id')
           ->whereIn('area_region.region_id', $region_id_list)
           ->where('shipping.enabled', 1)
           ->get();

        if(count($model) > 0){
            $model = $model->toArray();
            return self::formatBody(['vendors' => $model]);
        }

        return self::formatError(self::BAD_REQUEST, trans('message.shipping.error'));
    }


    public static function findOne($address, $goods, $shipping_id)
    {
        //格式化，拿到需要的goods_id 和数量
        $products = [];
        $IsShippingFree = true;
	$goods_ids = array();

        foreach ($goods as $key => $value) {
            $products[$key]['goods_id'] = $value->goods_id;
            $products[$key]['num'] = $value->goods_number;
            $goods_ids[] = $value['goods_id'];
        }
	
        // 查看购物车中是否全为免运费商品，若是则把运费赋为零
        $is_shipping = Goods::whereIn('goods_id', $goods_ids)
            ->where('is_shipping', 0)
            ->count();

        if($is_shipping == 0){
            return 0;
	}
        $products = json_encode($products);
        self::$attrs = ['products' => $products];
        $region_id_list = UserAddress::getRegionIdList($address);

        $model = Shipping::join('shipping_area', 'shipping_area.shipping_id', '=', 'shipping.shipping_id')
           ->join('area_region', 'area_region.shipping_area_id', '=', 'shipping_area.shipping_area_id')
           ->whereIn('area_region.region_id', $region_id_list)
           ->where('shipping.enabled', 1)
           ->where('shipping.shipping_id', $shipping_id)
           ->first();

        if(count($model) > 0){
            return $model->fee;
        }
        return false;
    }


    public static function shipFee($address, $goods, $shipping_id)
    {
        //格式化，拿到需要的goods_id 和数量

        $products = [];
        $goods_ids = [];
        $fee = 0;

        foreach ($goods as $key => $value) {
            $products[$key]['goods_id'] = $value['goods_id'];
            $products[$key]['num'] = $value['num'];
            $goods_ids[] = $value['goods_id'];
        }
	
        // 查看购物车中是否全为免运费商品，若是则把运费赋为零
	$is_shipping = Goods::whereIn('goods_id', $goods_ids)
            ->where('is_shipping', 0)
            ->count();
        if($is_shipping == 0){
            return $fee;
        }

        $products = json_encode($products);
        self::$attrs = ['products' => $products];
        $region_id_list = UserAddress::getRegionIdList($address);

        $model = Shipping::join('shipping_area', 'shipping_area.shipping_id', '=', 'shipping.shipping_id')
           ->join('area_region', 'area_region.shipping_area_id', '=', 'shipping_area.shipping_area_id')
           ->whereIn('area_region.region_id', $region_id_list)
           ->where('shipping.enabled', 1)
           ->where('shipping.shipping_id', $shipping_id)
           ->first();

        if(count($model) > 0){
            return $model->fee;
        }
        return false;
    }

    //物流信息查询
    public static function getDeliveyInfo(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($order = Order::where('order_id', $order_id)->where('user_id',$uid)->first()) {

            $format_data = Logistics::info($order->order_sn);
            if (empty($format_data)) {
                $format_data = [];
            }
            return self::formatBody(['status' => $format_data, 'vendor_name' => $order->shipping_name, 'code' => $order->invoice_no ]);

        }
        return self::formatError(self::NOT_FOUND);
    }

    public function getIdAttribute()
    {
        return $this->attributes['shipping_id'];
    }

    public function getCodeAttribute()
    {
        return $this->attributes['shipping_code'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['shipping_name'];
    }


    public function getDescAttribute()
    {
        return $this->attributes['shipping_desc'];
    }

    public function getPriceAttribute()
    {
        $price['first'] = 0;
        $price['step'] = 0;


        $configure = self::getConfigure($this->configure);

        if ($configure['fee_compute_mode'] == 'by_number') {
            $price['first'] = $price['step'] = isset($configure['item_fee']) ? $configure['item_fee'] : 0;
        } else {
            $price['first'] = isset($configure['base_fee']) ? $configure['base_fee'] : 0;
            $price['step'] = isset($configure['step_fee']) ? $configure['step_fee'] : 0;
        }


        $price['first'] = floatval($price['first']);
        $price['step'] = floatval($price['step']);


        return $price;
    }

    public function getFeeAttribute()
    {
        $uid = Token::authorization();
        extract(self::$attrs);
        $weight = 0;
        $amount = 0;
        $number = 0;
	$fee    = 0;

	$goods_ids = array();

        if(isset($products)){
            $products = json_decode($products, true);
            foreach ($products as $product) {
                $goods_weight = Goods::where('goods_id', $product['goods_id'])->pluck('goods_weight')->first();                
                if($goods_weight){
                    $weight += $goods_weight * $product['num'];
                    $amount += Goods::get_final_price($product['goods_id'], $product['num']);
                    $number += $product['num'];
                }

                $goods_ids[] = $product['goods_id'];
            }
        }
	
        // 查看购物车中是否全为免运费商品，若是则把运费赋为零
        $is_shipping = Goods::whereIn('goods_id', $goods_ids)
            ->where('is_shipping', 0)
            ->count();
        if($is_shipping == 0){
            return $fee;
        }

        $configure = self::getConfigure($this->configure);
        $fee = self::calculate($configure, $this->shipping_code, $weight, $amount, $number);
        return $fee;
    }

    public function getIsAdditionalAttribute()
    {
        return true;
    }

    public function shippingArea()
    {
        return  $this->hasMany('App\Models\v2\ShippingArea', 'shipping_id', 'shipping_id');
    }

    /**
     * 取得某配送方式对应于某收货地址的区域配置信息
     *
     */
    private static function getConfigure($configure)
    {
        $data = [];
        $configure = unserialize($configure);
        foreach ($configure as $key => $val) {
            $data[$val['name']] = $val['value'];
        }

        return $data;
    }

    /**
     * 计算订单的配送费用的函数
     *
     */
    private static function calculate($configure, $shipping_code, $goods_weight, $goods_amount, $goods_number)
    {
        $fee = 0;
        if ($configure['free_money'] > 0 && $goods_amount * $goods_number >= $configure['free_money']) {
            return $fee;
        }

        switch ($shipping_code) {
            case 'city_express':
            case 'flat':
                $fee = isset($configure['base_fee']) ? $configure['base_fee'] : 0;
                break;

            case 'ems':
                $fee = isset($configure['base_fee']) ? $configure['base_fee'] : 0;
                $configure['fee_compute_mode'] = !empty($configure['fee_compute_mode']) ? $configure['fee_compute_mode'] : 'by_weight';

                if ($configure['fee_compute_mode'] == 'by_number') {
                    $fee = $goods_number * $configure['item_fee'];
                } else {
                    if ($goods_weight > 0.5) {
                        $fee += (ceil(($goods_weight - 0.5) / 0.5)) * $configure['step_fee'];
                    }
                }
                break;

            case 'post_express':
                $fee = isset($configure['base_fee']) ? $configure['base_fee'] : 0;
                $configure['fee_compute_mode'] = !empty($configure['fee_compute_mode']) ? $configure['fee_compute_mode'] : 'by_weight';

                if ($configure['fee_compute_mode'] == 'by_number') {
                    $fee = $goods_number * $configure['item_fee'];
                } else {
                    if ($goods_weight > 5) {
                        $fee += 8 * $configure['step_fee'];
                        $fee += (ceil(($goods_weight - 5) / 0.5)) * $configure['step_fee1'];
                    } else {
                        if ($goods_weight > 1) {
                            $fee += (ceil(($goods_weight - 1) / 0.5)) * $configure['step_fee'];
                        }
                    }
                }
                break;

            case 'post_mail':
                $fee = $configure['base_fee'] + $configure['pack_fee'];
                $configure['fee_compute_mode'] = !empty($configure['fee_compute_mode']) ? $configure['fee_compute_mode'] : 'by_weight';

                if ($configure['fee_compute_mode'] == 'by_number') {
                    $fee = $goods_number * ($configure['item_fee'] + $configure['pack_fee']);
                } else {
                    if ($goods_weight > 5) {
                        $fee += 4 * $configure['step_fee'];
                        $fee += (ceil(($goods_weight - 5))) * $configure['step_fee1'];
                    } else {
                        if ($goods_weight > 1) {
                            $fee += (ceil(($goods_weight - 1))) * $configure['step_fee'];
                        }
                    }
                }
                break;
            case 'presswork':
                $fee = $goods_weight * 4 + 3.4;

                if ($goods_weight > 0.1) {
                    $fee += (ceil(($goods_weight - 0.1) / 0.1)) * 0.4;
                }
                break;

            case 'sf_express':
            case 'sto_express':
            case 'yto':
            case 'zto':
                $fee = isset($configure['base_fee']) ? $configure['base_fee'] : 0;
                $configure['fee_compute_mode'] = !empty($configure['fee_compute_mode']) ? $configure['fee_compute_mode'] : 'by_weight';

                 if ($configure['fee_compute_mode'] == 'by_number') {
                     $fee = $goods_number * $configure['item_fee'];
                 } else {
                    if ($goods_weight > 1) {
                        $fee += (ceil(($goods_weight - 1))) * $configure['step_fee'];
                    }
                }
                break;

            default:
                $fee = 0;
                break;
        }
        $fee = floatval($fee);

        return $fee;
    }
}
