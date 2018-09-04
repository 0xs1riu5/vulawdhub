<?php

namespace App\Models\v2;
use App\Models\BaseModel;
use App\Helper\Token;
use DB;
use App\Services\Shopex\Erp;
use Log;

class Order extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'order_info';
    protected $primaryKey = 'order_id';
    public    $timestamps = false;

    protected $guarded = [];
    protected $appends = ['id', 'sn', 'total', 'payment', 'shipping', 'invoice', 'coupon', 'score','use_score', 'cashgift', 'consignee', 'status', 'created_at', 'updated_at', 'canceled_at', 'paied_at', 'shipping_at', 'finish_at','promos'];
    protected $visible = ['id', 'sn', 'total', 'goods', 'payment', 'shipping', 'invoice', 'coupon', 'score', 'use_score', 'cashgift', 'consignee', 'status', 'created_at', 'updated_at', 'canceled_at', 'paied_at', 'shipping_at', 'finish_at','discount_price','promos'];

    // ECM 订单状态
    const STATUS_CREATED     = 0; // 待付款
    const STATUS_PAID        = 1; // 已付款
    const STATUS_DELIVERING  = 2; // 发货中
    const STATUS_DELIVERIED  = 3; // 已收货，待评价
    const STATUS_FINISHED    = 4; // 已完成
    const STATUS_CANCELLED   = 5; // 已取消

    /* 订单状态 */
    const OS_UNCONFIRMED     = 0; // 未确认
    const OS_CONFIRMED       = 1; // 已确认
    const OS_CANCELED        = 2; // 已取消
    const OS_INVALID         = 3; // 无效
    const OS_RETURNED        = 4; // 退货
    const OS_SPLITED         = 5; // 已分单
    const OS_SPLITING_PART   = 6; // 部分分单

    /* 支付状态 */
    const PS_UNPAYED         = 0; // 未付款
    const PS_PAYING          = 1; // 付款中
    const PS_PAYED           = 2; // 已付款

    /* 配送状态 */
    const SS_UNSHIPPED       = 0; // 未发货
    const SS_SHIPPED         = 1; // 已发货
    const SS_RECEIVED        = 2; // 已收货
    const SS_PREPARING       = 3; // 备货中
    const SS_SHIPPED_PART    = 4; // 已发货(部分商品)
    const SS_SHIPPED_ING     = 5; // 发货中(处理分单)
    const OS_SHIPPED_PART    = 6; // 已发货(部分商品)

    public static $reason    = ['改选其他商品', '改选其他配送方式', '不想买了', '其他原因'];

    public static function getReasonList()
    {
        $data = [];
        foreach (self::$reason as $key => $value) {
            $data[] = [
                'id' => $key + 1,
                'name' =>  $value
            ];
        }

        return self::formatBody(['reasons' => $data]);
    }

    public static function getReasonByID($id)
    {
        $id = $id - 1;
        return self::$reason[$id];
    }

    public static function findUnpayedBySN($sn)
    {
        return self::where(['order_sn' => $sn, 'pay_status' => Order::PS_UNPAYED])->first();
    }

    public static function getList(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        $model = self::where(['user_id' => $uid]);

        if (isset($status)) {
            switch ($status) {

                case self::STATUS_CREATED:
                    $model->whereIn('pay_status', [self::PS_UNPAYED, self::PS_PAYING]);
                    $model->whereNotIn('order_status', [self::OS_CANCELED, self::OS_INVALID, self::OS_RETURNED]);
                    break;

                case self::STATUS_PAID:
                    $model->whereIn('pay_status', [self::PS_PAYED]);
                    $model->whereIn('shipping_status', [self::SS_UNSHIPPED, self::SS_PREPARING, self::SS_SHIPPED_ING]);
                    break;

                case self::STATUS_DELIVERING:
                    $model->whereIn('shipping_status', [self::SS_SHIPPED, self::SS_SHIPPED_PART, self::OS_SHIPPED_PART]);
                    break;

                case self::STATUS_DELIVERIED:
                    $ids = OrderReview::where('user_id', $uid)->groupBy('order_id')->pluck('order_id')->toArray();
                    $model->whereIn('shipping_status', [self::SS_RECEIVED])->whereNotIn('order_id', $ids);
                    break;

                case self::STATUS_FINISHED:
                    $ids = OrderReview::where('user_id', $uid)->groupBy('order_id')->pluck('order_id')->toArray();
                    $model->whereIn('shipping_status', [self::SS_RECEIVED])->whereIn('order_id', $ids);
                    break;
            }
        }

        $total = $model->count();

        $data = $model
            ->with('goods')
            ->orderBy('add_time', 'DESC')
            ->paginate($per_page)->toArray();

        return self::formatBody(['orders' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public static function getInfo(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($model = self::where(['user_id' => $uid, 'order_id' => $order])->with('goods')->first()) {
            $model->toArray();

            $discount_price = 0;
            $order_goods = OrderGoods::where('order_id',$order)->get();
            foreach ($order_goods as $key => $order_good) {
                $val = Goods::where('goods_id',$order_good['goods_id'])->first();
                
                $volume_price  = $val['shop_price']; //商品优惠价格 如果不存在优惠价格列表 价格为店铺价格
                //取得商品优惠价格列表
                $price_list   = Goods::get_volume_price_list($order_good['goods_id'], '1');
                if (!empty($price_list))
                {
                    foreach ($price_list as $value)
                    {
                        if ($order_good['goods_number'] >= $value['number'])
                        {
                            $volume_price = $value['price'];
                        }
                    }
                }
                $discount_price = ($val['shop_price'] - $volume_price) * $order_good['goods_number'];
            }

            $model['discount_price'] = $discount_price;
            return self::formatBody(['order' => $model]);
        }

        return self::formatError(self::UNKNOWN_ERROR);
    }

    public static function confirm(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($model = self::where(['user_id' => $uid, 'order_id' => $order])->whereIn('shipping_status', [self::SS_SHIPPED, self::SS_SHIPPED_PART, self::OS_SHIPPED_PART])->first()) {
            //修改订单状态
            $model->shipping_status = self::SS_RECEIVED;
            if ($model->save())
            {
                OrderAction::toCreateOrUpdate($model->order_id, $model->order_status, self::SS_RECEIVED, $model->pay_status);
                Erp::order($model->order_sn);
                return self::formatBody(['order' => $model->toArray()]);
            }
        }

        return self::formatError(self::NOT_FOUND);
    }

    public static function price(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        $total  = array('real_goods_count' => 0,
                        'gift_amount'      => 0,
                        'goods_price'      => 0,
                        'market_price'     => 0,
                        'discount'         => 0,
                        'pack_fee'         => 0,
                        'card_fee'         => 0,
                        'shipping_fee'     => 0,
                        'shipping_insure'  => 0,
                        'integral_money'   => 0,
                        'bonus'            => 0,
                        'surplus'          => 0,
                        'cod_fee'          => 0,
                        'pay_fee'          => 0,
                        'discount_price'   => 0,
                        'tax'              => 0);
        $weight = 0;
        /* 商品总价 */
        if (!$order_products = json_decode($order_product,true)) {
            return self::formatError(self::UNKNOWN_ERROR);
        }

        $good_arr = [];
        $good_num = 0;
        foreach ($order_products as $key => $product) {
            $val = Goods::where('goods_id',$product['goods_id'])->first();
            
            $volume_price  = $val['shop_price']; //商品优惠价格 如果不存在优惠价格列表 价格为店铺价格
            //取得商品优惠价格列表
            $price_list   = Goods::get_volume_price_list($product['goods_id'], '1');
            if (!empty($price_list))
            {
                foreach ($price_list as $value)
                {
                    if ($product['num'] >= $value['number'])
                    {
                        $volume_price = $value['price'];
                    }
                }
            }

            $order_products[$key]['is_shipping'] = $val['is_shipping'];

            $good_arr[] = $product['goods_id'];
            /* 统计实体商品的个数 */
            if ($val['is_real'])
            {
                $total['real_goods_count']++;
            }

            $total['goods_price']  += Goods::get_final_price($product['goods_id'],$product['num'],true, $product['property']) * $product['num']; 
            $total['discount_price'] += ($val['shop_price'] - $volume_price) * $product['num'] ;
            $total['market_price'] += $val['market_price'] * $product['num'];
        }
        $goods = Goods::whereIn('goods_id',$good_arr)->get();

        $total['saving']    = $total['market_price'] - $total['goods_price'];
        $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;

        $total['goods_price_formated']  = Goods::price_format($total['goods_price'], false);
        $total['market_price_formated'] = Goods::price_format($total['market_price'], false);
        $total['saving_formated']       = Goods::price_format($total['saving'], false);

        /* 折扣 */
        $total['discount'] = Cart::compute_discount($order_products);
        if ($total['discount'] > $total['goods_price'])
        {
            $total['discount'] = $total['goods_price'];
        }
        $total['discount_formated'] = Goods::price_format($total['discount'], false);
        /* 税额 */
        /* 包装费用 */
        /* 贺卡费用 */
        /* 红包 */
        if (isset($cashgift))
        {
            $bonus          = BonusType::bonus_info($cashgift);
            $total['bonus'] = $bonus['type_money'];

        }
        $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

        /* 配送费用 */
        $shipping_cod_fee = NULL;
        if (!isset($consignee)) {
            $total['shipping_fee_formated']    = Goods::price_format(0, false);
        }else{
            $consignee_info = UserAddress::get_consignee($consignee);
            if (isset($shipping)) {
                if ($shipping > 0 && $total['real_goods_count'] > 0)
                {
                    $region['country']  = $consignee_info['country'];
                    $region['province'] = $consignee_info['province'];
                    $region['city']     = $consignee_info['city'];
                    $region['district'] = $consignee_info['district'];
                    $total['shipping_fee'] = Shipping::shipFee($consignee, $order_products,$shipping);

                }
                $total['shipping_fee_formated']    = Goods::price_format($total['shipping_fee'], false);
            }else{
                $total['shipping_fee_formated']    = Goods::price_format(0, false);
            }
        }
        // 购物车中的商品能享受红包支付的总额
        $bonus_amount = Cart::compute_discount($order_products);
        // 红包和积分最多能支付的金额为商品总额
        $max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;
        /* 计算订单总额 */
        $order = [];
        $order['extension_code'] = '';
        $order['integral'] = 0;

        if ($order['extension_code'] == 'group_buy' && $group_buy['deposit'] > 0)
        {
            $total['amount'] = $total['goods_price'];
        }
        else
        {
            $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +
                $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];

            // 减去红包金额
            $use_bonus        = min($total['bonus'], $max_amount); // 实际减去的红包金额

            if(isset($total['bonus_kill']))
            {
                $use_bonus_kill   = min($total['bonus_kill'], $max_amount);
                $total['amount'] -=  $price = $total['bonus_kill']; // 还需要支付的订单金额
            }

            $total['bonus']   = $use_bonus;
            $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

            $total['amount'] -= $use_bonus; // 还需要支付的订单金额
            $max_amount      -= $use_bonus; // 积分最多还能支付的金额

        }

        /* 积分 */
        if ($total['amount'] > 0 && $max_amount > 0 && isset($score) && $score > 0)
        {
            $integral_money = self::value_of_integral($score);

            // 使用积分支付
            $use_integral            = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
            $total['amount']        -= $use_integral;
            $total['integral_money'] = $use_integral;
            $order['integral']       = self::integral_of_value($use_integral);
        }
        else
        {
            $total['integral_money'] = 0;
            $order['integral']       = 0;
        }
        $total['integral'] = $order['integral'];
        $total['integral_formated'] = Goods::price_format($total['integral_money'], false);

        /* 保存订单信息 */

        /* 支付费用 */

        $total['pay_fee_formated'] = Goods::price_format($total['pay_fee'], false);

        $total['amount']           += $total['pay_fee']; // 订单总额累加上支付费用
        $total['amount_formated']  = Goods::price_format($total['amount'], false);

        /* 取得可以得到的积分和红包 */
        if ($order['extension_code'] == 'group_buy')
        {
            $total['will_get_integral'] = $group_buy['gift_integral'];
        }
        elseif ($order['extension_code'] == 'exchange_goods')
        {
            $total['will_get_integral'] = 0;
        }
        else
        {
            $total['will_get_integral'] = Cart::get_give_integral($goods);
        }
        $total['will_get_bonus']        = $order['extension_code'] == 'exchange_goods' ? 0 : Goods::price_format(self::get_total_bonus(), false);

        $total['formated_goods_price']  = Goods::price_format($total['goods_price'], false);
        $total['formated_market_price'] = Goods::price_format($total['market_price'], false);
        $total['formated_saving']       = Goods::price_format($total['saving'], false);

        $order_price['total_price'] = Goods::price_format($total['amount'], false);
        $order_price['discount_price'] = Goods::price_format($total['discount_price'], false);
        $order_price['product_price'] = Goods::price_format($total['goods_price_formated'] + $total['discount_price'], false);
        $order_price['shipping_price'] = Goods::price_format($total['shipping_fee_formated'], false);
        $order_price['promos'] = [];
        // if (isset($cashgift)) {
            $order_price['promos'][] = ['promo' => 'cashgift','price' => $total['bonus_formated']];
        // }

        // if (isset($score)) {
            $order_price['promos'][] = ['promo' => 'score','price' => $total['integral_formated']];
        // }
        if ($total['discount_formated'] > 0) {
            $order_price['promos'][] = ['promo' => 'preferential','price' => $total['discount_formated']];
        }
        return self::formatBody(['order_price' => $order_price]);
   
    }

    public static function cancel(array $attributes)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($model = self::where(['user_id' => $uid, 'order_id' => $order])->first()) {
            // 订单状态只能是“未确认”或“已确认”
            if ($model->order_status != self::OS_UNCONFIRMED && $model->order_status != self::OS_CONFIRMED)
            {
                return self::formatError(self::NOT_FOUND);
            }

            // 发货状态只能是“未发货”
            if ($model->shipping_status != self::SS_UNSHIPPED)
            {
                return self::formatError(self::NOT_FOUND);
            }

            // 如果付款状态是“已付款”、“付款中”，不允许取消，要取消和商家联系
            if ($model->pay_status != self::PS_UNPAYED)
            {
                return self::formatError(self::NOT_FOUND);
            }

            //修改订单状态
            $model->order_status = self::OS_CANCELED;
            self::return_user_integral_bonus($order);

            if ($model->save())
            {
                OrderAction::toCreateOrUpdate($model->order_id, self::OS_CANCELED, $model->shipping_status, $model->pay_status, self::getReasonByID($reason));
                Erp::order($model->order_sn);
                return self::formatBody(['order' => $model->toArray()]);
            }
        }

        return self::formatError(self::NOT_FOUND);
    }

    public static function review(array $attributes, $items)
    {
        extract($attributes);
        $uid = Token::authorization();

        if ($model = self::where(['user_id' => $uid, 'order_id' => $order])->first()) {
            // 判断订单状态
            if ($model->shipping_status != self::SS_RECEIVED) {
                return self::formatError(self::NOT_FOUND);
            }

            // 已经评价过了
            if (OrderReview::where(['user_id' => $uid, 'order_id' => $order])->first()) {
                return self::formatError(self::BAD_REQUEST, trans('message.order.reviewed'));
            }
            foreach ($items as $key => $value) {
                // 提交评价
                if ($comment = Comment::toCreate($uid, $value, $is_anonymous)) {
                    OrderReview::toUpdate($uid, $order, $value['goods']);
                }
            }

            return self::formatBody();
        }
        return self::formatError(self::NOT_FOUND);
    }

    public static function subtotal()
    {
        $uid = Token::authorization();
        $ids = OrderReview::where('user_id', $uid)->groupBy('order_id')->pluck('order_id')->toArray();
        $model = self::where(['user_id' => $uid]);

        $data = [
            'created' => Order::where('user_id', $uid)->whereIn('pay_status', [self::PS_UNPAYED, self::PS_PAYING])->whereNotIn('order_status', [self::OS_CANCELED, self::OS_INVALID, self::OS_RETURNED])->count(),
            'paid' => Order::where('user_id', $uid)->whereIn('pay_status', [self::PS_PAYED])->whereIn('shipping_status', [self::SS_UNSHIPPED, self::SS_PREPARING, self::SS_SHIPPED_ING])->count(),
            'delivering' => Order::where('user_id', $uid)->whereIn('shipping_status', [self::SS_SHIPPED, self::SS_SHIPPED_PART, self::OS_SHIPPED_PART])->count(),
            'deliveried' => $model->whereIn('shipping_status', [self::SS_RECEIVED])->whereNotIn('order_id', $ids)->count(),
            'finished' => $model->whereIn('shipping_status', [self::SS_RECEIVED])->whereIn('order_id', $ids)->count(),
            'cancelled' => Order::where('user_id', $uid)->whereIn('order_status', [self::OS_CANCELED, self::OS_INVALID, self::OS_RETURNED])->count(),
        ];

        return self::formatBody(['subtotal' => $data]);
    }

    public function getIdAttribute()
    {
        return $this->attributes['order_id'];
    }

    public function getSnAttribute()
    {
        return $this->attributes['order_sn'];
    }

    public function getTotalAttribute()
    {
        return ($this->attributes['order_amount'] + $this->attributes['money_paid']);
    }

    public function getPaymentAttribute()
    {
        return [
            'name' => $this->attributes['pay_name']
        ];
    }

    public function getShippingAttribute()
    {
        return [
            'code' => $this->attributes['invoice_no'],
            'name' => $this->attributes['shipping_name'],
            'desc' => $this->attributes['shipping_name'],
            'price' => $this->attributes['shipping_fee'],
            'tracking' => $this->attributes['invoice_no']
        ];
    }

    public function getInvoiceAttribute()
    {
        return [
            'type' => $this->attributes['inv_type'],
            'content' => $this->attributes['inv_content'],
            'title' =>  $this->attributes['inv_payee']
        ];
    }

    public function getCouponAttribute()
    {
        return null;
    }

    public function getScoreAttribute()
    {
        $order_goods_arr = OrderGoods::where('order_id', $this->attributes['order_id'])->get();
        $sum = 0;
        foreach ($order_goods_arr as $order_goods) {
	    if ($goods = Goods::where('goods_id', $order_goods->goods_id)->select('shop_price','give_integral')->first()) {
                $goods_score = ($goods->give_integral == -1) ? $goods->shop_price:$goods->give_integral;
                $sum += $goods_score * $order_goods->goods_number;
            }
        }
        return ['score' => $sum];
    }

    public function getUseScoreAttribute()
    {
        return ['score' => intval($this->attributes['integral'])];
    }

    public function getCashgiftAttribute()
    {
        return ['price' => floatval($this->attributes['bonus'])];
    }

    public function getConsigneeAttribute()
    {
        return [
            'name' => $this->attributes['consignee'],
            'mobile' => $this->attributes['mobile'],
            'tel' => $this->attributes['tel'],
            'zip_code' => $this->attributes['zipcode'],
            'regions' => Region::getRegionName($this->attributes['district']),
            'address' => $this->attributes['address'],
        ];
    }

    public function getStatusAttribute()
    {
        return self::convertOrderStatus(
            $this->attributes['order_id'],
            $this->attributes['order_status'],
            $this->attributes['pay_status'],
            $this->attributes['shipping_status']
        );
    }

    public function getCreatedAtAttribute()
    {
        return $this->attributes['add_time'];
    }

    public function getUpdatedAtAttribute()
    {
        return null;
    }

    public function getCanceledAtAttribute()
    {
        if ($this->attributes['order_status'] == self::OS_CANCELED || $this->attributes['order_status'] == self::OS_INVALID) {
            return OrderAction::where(['order_id' => $this->attributes['order_id'], 'order_status' => $this->attributes['order_status']])->value('log_time');
        }
        return null;
    }

    public function getPaiedAtAttribute()
    {
        return $this->attributes['pay_time'] ? $this->attributes['pay_time'] : null;
    }

    public function getShippingAtAttribute()
    {
        return $this->attributes['shipping_time'] ? $this->attributes['shipping_time'] : null;
    }

    public function getFinishAtAttribute()
    {
        if ($this->attributes['shipping_status'] == self::SS_RECEIVED) {
            return OrderAction::where(['order_id' => $this->attributes['order_id'], 'shipping_status' => $this->attributes['shipping_status']])->value('log_time');
        }
        return null;
    }


    public function getPromosAttribute()
    {
        $scale = ShopConfig::findByCode('integral_scale');
        return [
            ['promo' => 'cashgift' , 'price' => number_format($this->attributes['bonus'], 2, '.', '')],
            ['promo' => 'score' , 'price' => number_format($this->attributes['integral'] * ($scale / 100), 2, '.', '')],
            ['promo' => 'preferential' , 'price' => $this->attributes['discount']],
        ];
    }

    public function goods()
    {
        return $this->hasMany('App\Models\v2\OrderGoods','order_id','order_id');
    }

    private static function convertOrderStatus($order_id, $order_status, $pay_status, $shipping_status)
    {
        // 已取消
        if (in_array($order_status, [self::OS_CANCELED, self::OS_INVALID, self::OS_RETURNED])) {
            return self::STATUS_CANCELLED;
        }

        // 已发货
        if (in_array($shipping_status, [self::SS_SHIPPED, self::SS_SHIPPED_PART, self::OS_SHIPPED_PART])) {
            return self::STATUS_DELIVERING;
        }

        // 已收货
        if (in_array($shipping_status, [self::SS_RECEIVED])) {
            // 判断评价状态 已完成
            if (OrderReview::where('order_id', $order_id)->count()) {
                return self::STATUS_FINISHED;
            }
            return self::STATUS_DELIVERIED;
        }

        // 未付款
        if (in_array($pay_status, [self::PS_UNPAYED, self::PS_PAYING])) {
            return self::STATUS_CREATED;
        }

        // 已付款
        if (in_array($pay_status, [self::PS_PAYED])) {
            return self::STATUS_PAID;
        }
    }


    /**
     * 取得某配送方式对应于某收货地址的区域信息
     * @param   int     $shipping_id        配送方式id
     * @param   array   $region_id_list     收货人地区id数组
     * @return  array   配送区域信息（config 对应着反序列化的 configure）
     */
    public static function shipping_area_info($shipping_id, $region_id_list)
    {
        $row = Shipping::join('shipping_area', 'shipping_area.shipping_id', '=', 'shipping.shipping_id')
           ->join('area_region', 'area_region.shipping_area_id', '=', 'shipping_area.shipping_area_id')
           ->whereIn('area_region.region_id', $region_id_list)
           ->where('shipping.enabled', 1)
           ->first();

        if (!empty($row))
        {
            $shipping_config = unserialize_config($row['configure']);
            if (isset($shipping_config['pay_fee']))
            {
                if (strpos($shipping_config['pay_fee'], '%') !== false)
                {
                    $row['pay_fee'] = floatval($shipping_config['pay_fee']) . '%';
                }
                else
                {
                     $row['pay_fee'] = floatval($shipping_config['pay_fee']);
                }
            }
            else
            {
                $row['pay_fee'] = 0.00;
            }
        }
        return $row;
    }
    /**
     * 获得订单中的费用信息
     *
     * @access  public
     * @param   array   $order
     * @param   array   $goods
     * @param   array   $consignee
     * @param   bool    $is_gb_deposit  是否团购保证金（如果是，应付款金额只计算商品总额和支付费用，可以获得的积分取 $gift_integral）
     * @return  array
     */
    public static function order_fee($order, $goods, $consignee,$cart_good_id = 0,$shipping,$consignee_id)
    {
        /* 初始化订单的扩展code */
        if (!isset($order['extension_code']))
        {
            $order['extension_code'] = '';
        }

        $total  = array('real_goods_count' => 0,
                        'gift_amount'      => 0,
                        'goods_price'      => 0,
                        'market_price'     => 0,
                        'discount'         => 0,
                        'pack_fee'         => 0,
                        'card_fee'         => 0,
                        'shipping_fee'     => 0,
                        'shipping_insure'  => 0,
                        'integral_money'   => 0,
                        'bonus'            => 0,
                        'surplus'          => 0,
                        'cod_fee'          => 0,
                        'pay_fee'          => 0,
                        'tax'              => 0);
        $weight = 0;
        /* 商品总价 */
        foreach ($goods AS $val)
        {
            /* 统计实体商品的个数 */
            if ($val['is_real'])
            {
                $total['real_goods_count']++;
            }

            $total['goods_price']  += $val['goods_price'] * $val['goods_number'];
            $total['market_price'] += $val['market_price'] * $val['goods_number'];
        }

        $total['saving']    = $total['market_price'] - $total['goods_price'];
        $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;

        $total['goods_price_formated']  = Goods::price_format($total['goods_price'], false);
        $total['market_price_formated'] = Goods::price_format($total['market_price'], false);
        $total['saving_formated']       = Goods::price_format($total['saving'], false);
        /* 折扣 */
        $total['discount'] = Cart::compute_discount_check($goods);
        if ($total['discount'] > $total['goods_price'])
        {
            $total['discount'] = $total['goods_price'];
        }

        $total['discount_formated'] = Goods::price_format($total['discount'], false);

        /* 税额 */
        if (!empty($order['need_inv']) && $order['inv_type'] != '')
        {
            /* 查税率 */
            $rate = 0;
            foreach ($GLOBALS['_CFG']['invoice_type']['type'] as $key => $type)
            {
                if ($type == $order['inv_type'])
                {
                    $rate = floatval($GLOBALS['_CFG']['invoice_type']['rate'][$key]) / 100;
                    break;
                }
            }
            if ($rate > 0)
            {
                $total['tax'] = $rate * $total['goods_price'];
            }
        }
        $total['tax_formated'] = Goods::price_format($total['tax'], false);

        /* 包装费用 */

        /* 贺卡费用 */

        /* 红包 */

        if (!empty($order['bonus_id']))
        {
            $bonus          = BonusType::bonus_info($order['bonus_id']);
            $total['bonus'] = $bonus['type_money'];
        }
        $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

        /* 线下红包 */
         if (!empty($order['bonus_kill']))
        {
            $bonus          = BonusType::bonus_info(0,$order['bonus_kill']);
            $total['bonus_kill'] = $order['bonus_kill'];
            $total['bonus_kill_formated'] = Goods::price_format($total['bonus_kill'], false);
        }


        /* 配送费用 */
        $shipping_cod_fee = NULL;

        if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
        {
            $region['country']  = $consignee['country'];
            $region['province'] = $consignee['province'];
            $region['city']     = $consignee['city'];
            $region['district'] = $consignee['district'];
            $total['shipping_fee'] = Shipping::shipFee($consignee_id, $goods, $shipping);
        }
        $total['shipping_fee_formated']    = Goods::price_format($total['shipping_fee'], false);

        // 购物车中的商品能享受红包支付的总额
        $bonus_amount = Cart::compute_discount_check($goods);
        // 红包和积分最多能支付的金额为商品总额
        $max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;

        /* 计算订单总额 */
        if ($order['extension_code'] == 'group_buy' && $group_buy['deposit'] > 0)
        {
            $total['amount'] = $total['goods_price'];
        }
        else
        {
            $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +$total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];

            // 减去红包金额
            $use_bonus        = min($total['bonus'], $max_amount); // 实际减去的红包金额
            if(isset($total['bonus_kill']))
            {
                $use_bonus_kill   = min($total['bonus_kill'], $max_amount);
                $total['amount'] -=  $price = number_format($total['bonus_kill'], 2, '.', ''); // 还需要支付的订单金额
            }

            $total['bonus']   = $use_bonus;
            $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

            $total['amount'] -= $use_bonus; // 还需要支付的订单金额
            $max_amount      -= $use_bonus; // 积分最多还能支付的金额

        }

        /* 积分 */
        $order['integral'] = $order['integral'] > 0 ? $order['integral'] : 0;
        if ($total['amount'] > 0 && $max_amount > 0 && $order['integral'] > 0)
        {
            $integral_money = self::value_of_integral($order['integral']);

            // 使用积分支付
            $use_integral            = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
            $total['amount']        -= $use_integral;
            $total['integral_money'] = $use_integral;
            $order['integral']       = self::integral_of_value($use_integral);
        }
        else
        {
            $total['integral_money'] = 0;
            $order['integral']       = 0;
        }
        $total['integral'] = $order['integral'];
        $total['integral_formated'] = Goods::price_format($total['integral_money'], false);

        $se_flow_type = isset($_SESSION['flow_type']) ? $_SESSION['flow_type'] : '';

        /* 取得可以得到的积分和红包 */
        if ($order['extension_code'] == 'group_buy')
        {
            $total['will_get_integral'] = $group_buy['gift_integral'];
        }
        elseif ($order['extension_code'] == 'exchange_goods')
        {
            $total['will_get_integral'] = 0;
        }
        else
        {
            $total['will_get_integral'] = Cart::get_give_integral($goods);
        }
        $total['will_get_bonus']        = $order['extension_code'] == 'exchange_goods' ? 0 : Goods::price_format(self::get_total_bonus(), false);

        $total['formated_goods_price']  = Goods::price_format($total['goods_price'], false);
        $total['formated_market_price'] = Goods::price_format($total['market_price'], false);
        $total['formated_saving']       = Goods::price_format($total['saving'], false);
        
        return $total;
    }

    /**
     * 获得快速购买的费用信息
     *
     */
    public static function purchase_fee($order, $goods, $property_price,$goods_price,$amount,$consignee,$shipping,$consignee_id)
    {

        /* 初始化订单的扩展code */
        if (!isset($order['extension_code']))
        {
            $order['extension_code'] = '';
        }

        $total  = array('real_goods_count' => 0,
                        'gift_amount'      => 0,
                        'goods_price'      => 0,
                        'market_price'     => 0,
                        'discount'         => 0,
                        'pack_fee'         => 0,
                        'card_fee'         => 0,
                        'shipping_fee'     => 0,
                        'shipping_insure'  => 0,
                        'integral_money'   => 0,
                        'bonus'            => 0,
                        'surplus'          => 0,
                        'cod_fee'          => 0,
                        'pay_fee'          => 0,
                        'tax'              => 0);
        $weight = 0;
        /* 商品总价 */

            /* 统计实体商品的个数 */
        $total['real_goods_count'] = $amount;

        $total['goods_price']  += $goods_price * $amount;
        $total['market_price'] += $goods['price'] * $amount;

        $total['saving']    = $total['market_price'] - $total['goods_price'];
        $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;

        $total['goods_price_formated']  = Goods::price_format($total['goods_price'], false);
        $total['market_price_formated'] = Goods::price_format($total['market_price'], false);
        $total['saving_formated']       = Goods::price_format($total['saving'], false);

        /* 折扣 */
        $goods['num'] = $amount;
        $total['discount'] = Cart::compute_purchase_discount($goods);
        if ($total['discount'] > $total['goods_price'])
        {
            $total['discount'] = $total['goods_price'];
        }
        $total['discount_formated'] = Goods::price_format($total['discount'], false);
        /* 税额 */
        if (!empty($order['need_inv']) && $order['inv_type'] != '')
        {
            /* 查税率 */
            $rate = 0;
            foreach ($GLOBALS['_CFG']['invoice_type']['type'] as $key => $type)
            {
                if ($type == $order['inv_type'])
                {
                    $rate = floatval($GLOBALS['_CFG']['invoice_type']['rate'][$key]) / 100;
                    break;
                }
            }
            if ($rate > 0)
            {
                $total['tax'] = $rate * $total['goods_price'];
            }
        }
        $total['tax_formated'] = Goods::price_format($total['tax'], false);

        /* 包装费用 */

        /* 贺卡费用 */

        /* 红包 */

        if (!empty($order['bonus_id']))
        {
            $bonus          = BonusType::bonus_info($order['bonus_id']);
            $total['bonus'] = $bonus['type_money'];
        }
        $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

        /* 线下红包 */
         if (!empty($order['bonus_kill']))
        {
            $bonus          = BonusType::bonus_info(0,$order['bonus_kill']);
            $total['bonus_kill'] = $order['bonus_kill'];
            $total['bonus_kill_formated'] = Goods::price_format($total['bonus_kill'], false);
        }


        /* 配送费用 */
        $shipping_cod_fee = NULL;

        if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
        {
            $region['country']  = $consignee['country'];
            $region['province'] = $consignee['province'];
            $region['city']     = $consignee['city'];
            $region['district'] = $consignee['district'];
            //规范goods 格式
            $goods['goods_id'] = $goods['id'];
            $goods_array[] = $goods;
            $total['shipping_fee'] = Shipping::shipFee($consignee_id, $goods_array, $shipping);
        }
        $total['shipping_fee_formated']    = Goods::price_format($total['shipping_fee'], false);

        // 购物车中的商品能享受红包支付的总额
        $bonus_amount = $total['discount'];
        // 红包和积分最多能支付的金额为商品总额
        $max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;

        /* 计算订单总额 */
        if ($order['extension_code'] == 'group_buy' && $group_buy['deposit'] > 0)
        {
            $total['amount'] = $total['goods_price'];
        }
        else
        {
            $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +
                $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];

            // 减去红包金额
            $use_bonus        = min($total['bonus'], $max_amount); // 实际减去的红包金额
            if(isset($total['bonus_kill']))
            {
                $use_bonus_kill   = min($total['bonus_kill'], $max_amount);
                $total['amount'] -=  $price = number_format($total['bonus_kill'], 2, '.', ''); // 还需要支付的订单金额
            }

            $total['bonus']   = $use_bonus;
            $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

            $total['amount'] -= $use_bonus; // 还需要支付的订单金额
            $max_amount      -= $use_bonus; // 积分最多还能支付的金额

        }

        /* 积分 */
        $order['integral'] = $order['integral'] > 0 ? $order['integral'] : 0;
        if ($total['amount'] > 0 && $max_amount > 0 && $order['integral'] > 0)
        {
            $integral_money = self::value_of_integral($order['integral']);

            // 使用积分支付
            $use_integral            = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
            $total['amount']        -= $use_integral;
            $total['integral_money'] = $use_integral;
            $order['integral']       = self::integral_of_value($use_integral);
        }
        else
        {
            $total['integral_money'] = 0;
            $order['integral']       = 0;
        }
        $total['integral'] = $order['integral'];
        $total['integral_formated'] = Goods::price_format($total['integral_money'], false);

        /* 保存订单信息 */
        // $_SESSION['flow_order'] = $order;

        $se_flow_type = isset($_SESSION['flow_type']) ? $_SESSION['flow_type'] : '';

        /* 支付费用 */
        // if (!empty($order['pay_id']) && ($total['real_goods_count'] > 0 || $se_flow_type != CART_EXCHANGE_GOODS))
        // {
        //     $total['pay_fee']      = pay_fee($order['pay_id'], $total['amount'], $shipping_cod_fee);
        // }

        // $total['pay_fee_formated'] = price_format($total['pay_fee'], false);

        // $total['amount']           += $total['pay_fee']; // 订单总额累加上支付费用
        // $total['amount_formated']  = price_format($total['amount'], false);

        /* 取得可以得到的积分和红包 */
        if ($order['extension_code'] == 'group_buy')
        {
            $total['will_get_integral'] = $group_buy['gift_integral'];
        }
        elseif ($order['extension_code'] == 'exchange_goods')
        {
            $total['will_get_integral'] = 0;
        }
        else
        {
            $total['will_get_integral'] = Cart::get_give_integral($goods);
        }
        $total['will_get_bonus']        = $order['extension_code'] == 'exchange_goods' ? 0 : Goods::price_format(self::get_total_bonus(), false);

        $total['formated_goods_price']  = Goods::price_format($total['goods_price'], false);
        $total['formated_market_price'] = Goods::price_format($total['market_price'], false);
        $total['formated_saving']       = Goods::price_format($total['saving'], false);

        return $total;
    }
    /**floatval
     * 计算积分的价值（能抵多少钱）
     * @param   int     $integral   积分
     * @return  float   积分价值
     */
    public static function value_of_integral($integral)
    {
        // $scale = floatval($GLOBALS['_CFG']['integral_scale']);
        $scale = floatval(ShopConfig::findByCode('integral_scale'));

        return $scale > 0 ? round(($integral / 100) * $scale, 2) : 0;
    }

    /**
     * 计算指定的金额需要多少积分
     *
     * @access  public
     * @param   integer $value  金额
     * @return  void
     */
    public static function integral_of_value($value)
    {
        $scale = floatval(ShopConfig::findByCode('integral_scale'));

        return $scale > 0 ? round($value / $scale * 100) : 0;
    }

    /**
     * 得到新订单号
     * @return  string
     */
    public static function get_order_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);

        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }


    /**
     * 取得当前用户应该得到的红包总额
     */
    public static function get_total_bonus()
    {
        $day    = getdate();
        $today  = self::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        /* 按商品发的红包 */
        // $sql = "SELECT SUM(c.goods_number * t.type_money)" .
        //         "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, "
        //                 . $GLOBALS['ecs']->table('bonus_type') . " AS t, "
        //                 . $GLOBALS['ecs']->table('goods') . " AS g " .
        //         "WHERE c.session_id = '" . SESS_ID . "' " .
        //         "AND c.is_gift = 0 " .
        //         "AND c.goods_id = g.goods_id " .
        //         "AND g.bonus_type_id = t.type_id " .
        //         "AND t.send_type = '" . SEND_BY_GOODS . "' " .
        //         "AND t.send_start_date <= '$today' " .
        //         "AND t.send_end_date >= '$today' " .
        //         "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";
        // $goods_total = floatval($GLOBALS['db']->getOne($sql));
        $all_goods_total = Cart::join('goods','goods.goods_id','=','cart.goods_id')
                        ->join('bonus_type','bonus_type.type_id','=','goods.bonus_type_id')
                        ->where('bonus_type.send_type',UserBonus::SEND_BY_GOODS)
                        ->where('bonus_type.send_start_date','<',$today)
                        ->where('bonus_type.send_end_date','>',$today)
                        ->where('cart.rec_type',Cart::CART_GENERAL_GOODS)
                        ->get();
        $goods_total = 0;

        if ($all_goods_total) {
            foreach ($all_goods_total as $key => $value) {
                $goods_total += $value->goods_number * $value->type_money;
            }
        }
        /* 取得购物车中非赠品总金额 */
        // $sql = "SELECT SUM(goods_price * goods_number) " .
        //         "FROM " . $GLOBALS['ecs']->table('cart') .
        //         " WHERE session_id = '" . SESS_ID . "' " .
        //         " AND is_gift = 0 " .
        //         " AND rec_type = '" . CART_GENERAL_GOODS . "'";
        // $amount = floatval($GLOBALS['db']->getOne($sql));

        $all_amounts = Cart::where('is_gift',0)
                        ->where('rec_type',Cart::CART_GENERAL_GOODS)
                        ->get();
        $amount = 0;

        if ($all_amounts) {
            foreach ($all_amounts as $key => $value) {
                $amount += $value->goods_price * $value->goods_number;
            }
        }
        /* 按订单发的红包 */
        // $sql = "SELECT FLOOR('$amount' / min_amount) * type_money " .
        //         "FROM " . $GLOBALS['ecs']->table('bonus_type') .
        //         " WHERE send_type = '" . SEND_BY_ORDER . "' " .
        //         " AND send_start_date <= '$today' " .
        //         "AND send_end_date >= '$today' " .
        //         "AND min_amount > 0 ";
        // $order_total = floatval($GLOBALS['db']->getOne($sql));
        $all_order_total = BonusType::where('send_type',UserBonus::SEND_BY_ORDER)
                ->where('send_start_date','<',$today)
                ->where('send_end_date','>',$today)
                ->where('min_amount','>',0)
                ->get();

        $order_total = 0;

        if ($all_order_total) {
            foreach ($all_order_total as $key => $value) {
                $order_total += floor($amount/$value->min_amount) * $value->type_money;
            }
        }
        return $goods_total + $order_total;
    }


    public static function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
    {
        $timezone = ShopConfig::findByCode('timezone') ;

        /**
        * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
        * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
        **/
        $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

        return $time;
    }

    /**
     * 改变订单中商品库存
     * @param   int     $order_id   订单号
     * @param   bool    $is_dec     是否减少库存
     * @param   bool    $storage     减库存的时机，1，下订单时；0，发货时；
     */
    public static function change_order_goods_storage($order_id, $is_dec = true, $storage = 0)
    {
        /* 查询订单商品信息 */
        switch ($storage)
        {
            case 0 :
                // $sql = "SELECT goods_id, SUM(send_number) AS num, MAX(extension_code) AS extension_code, product_id FROM " . $GLOBALS['ecs']->table('order_goods') .
                //         " WHERE order_id = '$order_id' AND is_real = 1 GROUP BY goods_id, product_id";
                $res = OrderGoods::where('order_id',$order_id)->where('is_real',1)
                        ->groupBy('goods_id')
                        ->groupBy('product_id')
                        ->selectRaw('sum(send_number) as num,goods_id,max(extension_code) as extension_code,product_id')
                        ->get();
            break;

            case 1 :
                // $sql = "SELECT goods_id, SUM(goods_number) AS num, MAX(extension_code) AS extension_code, product_id FROM " . $GLOBALS['ecs']->table('order_goods') .
                //         " WHERE order_id = '$order_id' AND is_real = 1 GROUP BY goods_id, product_id";
                $res = OrderGoods::where('order_id',$order_id)->where('is_real',1)
                        ->groupBy('goods_id')
                        ->groupBy('product_id')
                        ->selectRaw('sum(goods_number) as num,goods_id,max(extension_code) as extension_code,product_id')
                        ->get();
            break;
        }
        foreach ($res as $key => $row) {

            if ($row['extension_code'] != "package_buy")
            {

                if ($is_dec)
                {
                    self::change_goods_storage($row['goods_id'], $row['product_id'], - $row['num']);
                }
                else
                {
                    self::change_goods_storage($row['goods_id'], $row['product_id'], $row['num']);
                }
                // $GLOBALS['db']->query($sql);
            }
            else
            {   //package_buy

                // $sql = "SELECT goods_id, goods_number" .
                //        " FROM " . $GLOBALS['ecs']->table('package_goods') .
                //        " WHERE package_id = '" . $row['goods_id'] . "'";
                // $res_goods = $GLOBALS['db']->query($sql);
                // while ($row_goods = $GLOBALS['db']->fetchRow($res_goods))
                // {
                //     $sql = "SELECT is_real" .
                //        " FROM " . $GLOBALS['ecs']->table('goods') .
                //        " WHERE goods_id = '" . $row_goods['goods_id'] . "'";
                //     $real_goods = $GLOBALS['db']->query($sql);
                //     $is_goods = $GLOBALS['db']->fetchRow($real_goods);

                //     if ($is_dec)
                //     {
                //         self::change_goods_storage($row_goods['goods_id'], $row['product_id'], - ($row['num'] * $row_goods['goods_number']));
                //     }
                //     elseif ($is_goods['is_real'])
                //     {
                //         self::change_goods_storage($row_goods['goods_id'], $row['product_id'], ($row['num'] * $row_goods['goods_number']));
                //     }
                // }
            }
        }

    }


    /**
     * 商品库存增与减 货品库存增与减
     *
     * @param   int    $good_id         商品ID
     * @param   int    $product_id      货品ID
     * @param   int    $number          增减数量，默认0；
     *
     * @return  bool               true，成功；false，失败；
     */
    public static function change_goods_storage($good_id, $product_id, $number = 0)
    {

        if ($number == 0)
        {
            return true; // 值为0即不做、增减操作，返回true
        }

        if (empty($good_id) || empty($number))
        {
            return false;
        }

        $number = ($number > 0) ? '+ ' . $number : $number;
        /* 处理货品库存 */
        $products_query = true;
        if (!empty($product_id))
        {
            // $sql = "UPDATE " . $GLOBALS['ecs']->table('products') ."
            //         SET product_number = product_number $number
            //         WHERE goods_id = '$good_id'
            //         AND product_id = '$product_id'
            //         LIMIT 1";
            // $products_query = $GLOBALS['db']->query($sql);
            $products_query = Products::where('goods_id',$good_id)
                                    ->where('product_id',$product_id)
                                    ->limit(1)
                                    ->increment('product_number' ,$number);
        }

        /* 处理商品库存 */
        // $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') ."
        //         SET goods_number = goods_number $number
        //         WHERE goods_id = '$good_id'
        //         LIMIT 1";
        // $query = $GLOBALS['db']->query($sql);
        $query = Goods::where('goods_id',$good_id)
                      ->limit(1)
                      ->increment('goods_number' ,$number);
        if ($query && $products_query)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 退回积分、红包（取消、无效、退货时），把订单使用余额、积分、红包设为0
     * @param   array   $order  订单信息
     */
    public static function return_user_integral_bonus($order_id)
    {
        $uid = Token::authorization();
        if($order = self::where(['user_id' => $uid, 'order_id' => $order_id])->first()){

            /* 处理积分 */
            if($order->user_id >0 && $order->integral >0)
            {
                AccountLog::logAccountChange( 0, 0, 0, $order->integral, trans('message.score.cancel').$order_id.trans('message.score.order'));
            }

            /* 处理红包 */
            if($order->bonus_id >0)
            {
                UserBonus::unuseBonus($order->bonus_id);
            }

            $order->bonus_id = 0;
            $order->bonus    = 0;
            $order->integral = 0;
            $order->integral_money = 0;
            $order->surplus = 0;

            return $order->save();
        }

        return false;
    }
}
