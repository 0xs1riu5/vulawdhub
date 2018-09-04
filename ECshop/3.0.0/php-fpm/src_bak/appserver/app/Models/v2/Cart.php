<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;
use DB;
use App\Services\Shopex\Erp;


class Cart extends BaseModel
{
    protected $connection = 'shop';
    protected $table = 'cart';
    public $timestamps = false;
    protected $primaryKey = 'rec_id';

    protected $appends = ['id','amount','property','price','attr_stock','attrs'];
    protected $visible = ['id','amount','product','property','price','attr_stock','attrs','subtotal','goods_id'];
    /* 购物车商品类型 */
    const CART_GENERAL_GOODS        = 0; // 普通商品
    const CART_GROUP_BUY_GOODS      = 1; // 团购商品
    const CART_AUCTION_GOODS        = 2; // 拍卖商品
    const CART_SNATCH_GOODS         = 3; // 夺宝奇兵
    const CART_EXCHANGE_GOODS       = 4; // 积分商城

    /* 减库存时机 */
    const SDT_SHIP                  = 0; // 发货时
    const SDT_PLACE                 = 1; // 下订单时

    /**
     * 添加商品到购物车
     *
     * @access  public
     * @param   integer $goods_id   商品编号
     * @param   integer $num        商品数量
     * @param   json   $property       规格值对应的id json数组
     * @param   integer $parent     基本件
     * @return  boolean
     */
    public static function add(array $attributes)
    {
        extract($attributes);
        //获取商品信息
        $good = Goods::where(['goods_id' => $product, 'is_delete' => 0])->first();

        if (!$good) {
            // 商品不存在
            return self::formatError(self::NOT_FOUND);
        }

        /* 是否正在销售 */
        if ($good['is_on_sale'] == 0) {
            return self::formatError(self::BAD_REQUEST,trans('message.good.off_sale'));
        }

        /* 不是配件时检查是否允许单独销售 */
        if ($good['is_alone_sale'] == 0) {
            //不能单独销售
            return self::formatError(self::BAD_REQUEST,trans('message.good.not_alone'));
        }
        if (isset($property) && json_decode($property,true)) {
            $property = json_decode($property,true);
        }else{
            $property = [];
        }

        /* 如果商品有规格则取规格商品信息 配件除外 property */
        $prod = Products::where('goods_id',$product)->first();

        if (Attribute::is_property($property) && !empty($prod))
        {
            $product_info = Products::get_products_info($product, $property);
        }
        if (empty($product_info))
        {
            $product_info = array('stock_number' => 0, 'id' => 0);
        }
        /* 检查：库存 */
        //检查：商品购买数量是否大于总库存
        if ($amount > $good['goods_number'])
        {
            return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
        }
        //商品存在规格 是货品 检查该货品库存
        if (Attribute::is_property($property) && !empty($prod))
        {
            if (!empty($property))
            {
                /* 取规格的货品库存 */
                if ($amount > $prod->product_number)
                {
                    return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
                }
            }
        }
        /* 计算商品的促销价格 */
        $property_price         = GoodsAttr::property_price($property);
        $goods_price            = Goods::get_final_price($product, $amount, true, $property);
        $good['market_price']  += $property_price;
        $goods_attr             = Attribute::get_goods_attr_info($property);
        $goods_attr_id          = join(',', $property);
        /* 初始化要插入购物车的基本件数据 */

        $parent = array(
            'user_id'       => Token::authorization(), //uid
            // 'session_id'    => SESS_ID,
            'goods_id'      => $product,
            'goods_sn'      => addslashes($good['goods_sn']),
            'product_id'    => $product_info['id'],
            'goods_name'    => addslashes($good['goods_name']),
            'market_price'  => $good['market_price'],
            'goods_attr'    => addslashes($goods_attr),
            'goods_attr_id' => $goods_attr_id,
            'is_real'       => $good['is_real'],
            'extension_code'=> $good['extension_code'],
            'is_gift'       => 0,
            'is_shipping'   => $good['is_shipping'],
            'rec_type'      => Cart::CART_GENERAL_GOODS,
        );
        /* 如果该配件在添加为基本件的配件时，所设置的“配件价格”比原价低，即此配件在价格上提供了优惠， */
        /* 则按照该配件的优惠价格卖，但是每一个基本件只能购买一个优惠价格的“该配件”，多买的“该配件”不享 */
        /* 受此优惠 */
        $basic_list = array();
 
        $res = GoodsGroup::where('goods_id',$product)->where('goods_price','<',$goods_price)->where('parent_id',$parent)->orderBy('goods_price')->get(['parent_id','goods_price']);
        foreach ($res as $key => $row) {
            $basic_list[$row['parent_id']] = $row['goods_price'];
        }

        /* 取得购物车中该商品每个基本件的数量 */
        $basic_count_list = array();
        if ($basic_list)
        {
            $res = Cart::where('parent_id',0)
            ->where('extension_code','!=','package_buy')
            ->whereIn('goods_id',array_keys($basic_list))
            ->groupBy('goods_id')
            ->selectRaw('sum(goods_number) as count')
            ->get();

            foreach ($res as $key => $row) {
                $basic_count_list[$row['goods_id']] = $row['count'];
            }
        }

        /* 取得购物车中该商品每个基本件已有该商品配件数量，计算出每个基本件还能有几个该商品配件 */
        /* 一个基本件对应一个该商品配件 */
        if ($basic_count_list)
        {
            $res = Cart::where('parent_id',0)
            ->where('extension_code','!=','package_buy')
            ->where('goods_id',$product)
            ->whereIn('parent_id',array_keys($basic_count_list))
            ->groupBy('parent_id')
            ->selectRaw('sum(goods_number) as count')
            ->get();

            foreach ($res as $key => $row) {
                $basic_count_list[$row['goods_id']] = $row['count'];
            }
        }

        /* 循环插入配件 如果是配件则用其添加数量依次为购物车中所有属于其的基本件添加足够数量的该配件 */
        foreach ($basic_list as $parent_id => $fitting_price)
        {
            /* 如果已全部插入，退出 */
            if ($amount <= 0)
            {
                break;
            }

            /* 如果该基本件不再购物车中，执行下一个 */
            if (!isset($basic_count_list[$parent_id]))
            {
                continue;
            }

            /* 如果该基本件的配件数量已满，执行下一个基本件 */
            if ($basic_count_list[$parent_id] <= 0)
            {
                continue;
            }

            /* 作为该基本件的配件插入 */
            $parent['goods_price']  = max($fitting_price, 0) + $property_price; //允许该配件优惠价格为0
            $parent['goods_number'] = min($amount, $basic_count_list[$parent_id]);
            $parent['parent_id']    = $parent_id;

            /* 添加 */
            Cart::insert($parent);
            /* 改变数量 */
            $amount -= $parent['goods_number'];
        }
        /* 如果数量不为0，作为基本件插入 */

        if ($amount > 0)
        {
            $user_id = Token::authorization();

            $row = Cart::where('goods_id',$product)->where('user_id',$user_id)->where('parent_id',0)->where('goods_attr',Attribute::get_goods_attr_info($property))->where('extension_code','!=','package_buy')->where('rec_type',self::CART_GENERAL_GOODS)->first();

            if($row) //如果购物车已经有此物品，则更新
            {
                $amount += $row['goods_number'];
                if(Attribute::is_property($property) && !empty($prod) )
                {
                 $goods_storage = $product_info['stock_number'];
                }
                else
                {
                    $goods_storage = $good['goods_number'];
                }
                // if ($GLOBALS['_CFG']['use_storage'] == 0 || $amount <= $goods_storage)
                if ($amount <= $goods_storage)
                {
                    $goods_final_price = Goods::get_final_price($product, $amount, true, $property);
                    Cart::where('goods_id' , $product)
                        ->where('user_id',$user_id)
                        ->where('parent_id' , 0)
                        ->where('goods_attr' ,Attribute::get_goods_attr_info($property))
                        ->where('extension_code','!=','package_buy')
                        ->where('rec_type',self::CART_GENERAL_GOODS)
                        ->update(['goods_number' => $amount,'goods_price' => $goods_final_price]);
                }
                else
                {
                    return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
                }
            }
            else //购物车没有此物品，则插入
            {
                $goods_price = Goods::get_final_price($product, $amount, true, $property);
                $parent['goods_price']  = max($goods_price, 0);
                $parent['goods_number'] = $amount;
                $parent['parent_id']    = 0;
                Cart::insert($parent);
            }
        }
        /* 把赠品删除 */
        Cart::where('is_gift','!=',0)->delete();

        return self::formatBody(['goods' => $parent]);
    }


    /**
     * 购物车结算
     * @param     int     $shop            // 店铺ID(无)
     * @param     int     $consignee       // 收货人ID
     * @param     int     $shipping        // 快递ID
     * @param     string     $invoice_type    // 发票类型，如：公司、个人
     * @param     string     $invoice_content // 发票内容，如：办公用品、礼品
     * @param     string     $invoice_title   // 发票抬头，如：xx科技有限公司
     * @param     int     $coupon          // 优惠券ID (无)
     * @param     int     $cashgift        // 红包ID
     * @param     int     $comment         // 留言
     * @param     int     $score           // 积分
     * @param     int     $cart_good_id    // 购物车商品id数组
     */

    public static function checkout(array $attributes)
    {
        extract($attributes);
        //-- 完成所有订单操作，提交到数据库
        /* 取得购物类型 */
        $flow_type = self::CART_GENERAL_GOODS;

        /* 检查购物车中是否有商品 */

        if (json_decode($cart_good_id,true)) {
            $cart_good_ids = json_decode($cart_good_id,true);
        }else{
            return self::formatError(self::BAD_REQUEST,trans('message.cart.json_invalid'));
        }
        if (count($cart_good_ids) > 0) {
            foreach ($cart_good_ids as $key => $cart_id) {
                if (!Cart::find($cart_id)) {
                    return self::formatError(self::BAD_REQUEST,trans('message.cart.cart_goods_error'));
                }
            }
        }else{
            return self::formatError(self::BAD_REQUEST,trans('message.cart.no_goods'));
        }
        /* 检查商品库存 */
        /* 如果使用库存，且下订单时减库存，则减少库存 */
        if (ShopConfig::findByCode('use_storage') == '1')
        {
            $cart_goods_stock = self::get_cart_goods($cart_good_ids);
            $_cart_goods_stock = array();
            foreach ($cart_goods_stock['goods_list'] as $value)
            {
                $_cart_goods_stock[$value['rec_id']] = $value['goods_number'];
            }           

            if (!self::flow_cart_stock($_cart_goods_stock)) {
                return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
            }
            
            unset($cart_goods_stock, $_cart_goods_stock);
        }


        $consignee_info = UserAddress::get_consignee($consignee);

        if (!$consignee_info) {
            return self::formatError(self::BAD_REQUEST,trans('message.consignee.not_found'));
        }

        $inv_type = isset($invoice_type) ? $invoice_type : ShopConfig::findByCode('invoice_type') ;
        $inv_payee = isset($invoice_title) ? $invoice_title : ShopConfig::findByCode('invoice_title');//发票抬头
        $inv_content = isset($invoice_content) ? $invoice_content : ShopConfig::findByCode('invoice_content') ;
        $postscript = isset($comment) ? $comment : '';
        $user_id = Token::authorization();

        $order = array(
            'shipping_id'     => intval($shipping),
            'pay_id'          => intval(0),
            'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,//包装id
            'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,//贺卡id
            'card_message'    => '',//贺卡内容
            'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
            'integral'        => isset($score) ? intval($score) : 0,//使用的积分的数量,取用户使用积分,商品可用积分,用户拥有积分中最小者
            'bonus_id'        => isset($cashgift) ? intval($cashgift) : 0,//红包ID
            // 'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
            'inv_type'        => $inv_type,
            'inv_payee'       => trim($inv_payee),
            'inv_content'     => $inv_content,
            'postscript'      => trim($postscript),
            'how_oos'         => '',//缺货处理
            // 'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
            // 'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
            'user_id'         => $user_id,
            'add_time'        => time(),
            'order_status'    => Order::OS_UNCONFIRMED,
            'shipping_status' => Order::SS_UNSHIPPED,
            'pay_status'      => Order::PS_UNPAYED,
            'agency_id'       => 0 ,//办事处的id
            );

        /* 扩展信息 */
            $order['extension_code'] = '';
            $order['extension_id'] = 0;

        /* 订单中的商品 */
        $cart_goods = self::cart_goods($flow_type, $cart_good_ids);
        if (empty($cart_goods))
        {
            return self::formatError(self::BAD_REQUEST, trans('message.cart.no_goods'));
        }

        /* 检查积分余额是否合法 */
        if ($user_id > 0)
        {
            $user_info = Member::user_info($user_id);

            $order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
            if ($order['surplus'] < 0)
            {
                $order['surplus'] = 0;
            }

            // 查询用户有多少积分
            $total_integral = 0;
            foreach ($cart_goods as $goods) {
                $integral = Goods::where('goods_id', $goods['goods_id'])->value('integral');
                $total_integral = $total_integral + $integral * $goods['goods_number'];
            }

            $scale = ShopConfig::findByCode('integral_scale');

            if($scale > 0){
                $flow_points = $total_integral / ($scale / 100);
            }else{
                $flow_points = 0;
            }

            $user_points = $user_info['pay_points']; // 用户的积分总数

            $order['integral'] = min($order['integral'], $user_points, $flow_points);
            if ($order['integral'] < 0)
            {
                $order['integral'] = 0;
            }
        }
        else
        {
            $order['surplus']  = 0;
            $order['integral'] = 0;
        }

        /* 检查红包是否存在 */
        if ($order['bonus_id'] > 0)
        {
            $bonus = BonusType::bonus_info($order['bonus_id']);

            if (empty($bonus) || $bonus['user_id'] != $user_id || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > self::cart_amount(true, $flow_type))
            {
                $order['bonus_id'] = 0;
            }
        }

        /* 订单中的商品 */
        $cart_goods = self::cart_goods($flow_type,$cart_good_ids);
        if (empty($cart_goods))
        {
            return self::formatError(self::BAD_REQUEST,trans('message.cart.no_goods'));
        }

        /* 检查商品总额是否达到最低限购金额 */
        // app和web有区别，购物车到结算不同
        // app 可以选择要结算的商品
        if ($flow_type == self::CART_GENERAL_GOODS && self::getCartAmount($cart_good_ids) < ShopConfig::findByCode('min_goods_amount'))
        {
            return self::formatError(self::BAD_REQUEST,trans('message.good.min_goods_amount'));
        }
        /* 收货人信息 */
        $order['consignee'] = $consignee_info->consignee;
        $order['country'] = $consignee_info->country;
        $order['province'] = $consignee_info->province;
        $order['city'] = $consignee_info->city;
        $order['mobile'] = $consignee_info->mobile;
        $order['tel'] = $consignee_info->tel;
        $order['zipcode'] = $consignee_info->zipcode;
        $order['district'] = $consignee_info->district;
        $order['address'] = $consignee_info->address;
       /* 判断是不是实体商品 */
        foreach ($cart_goods AS $val)
        {
            /* 统计实体商品的个数 */
            if ($val['is_real'])
            {
                $is_real_good=1;
            }
        }
        if(isset($is_real_good))
        {
            $shipping_is_real = Shipping::where('shipping_id',$order['shipping_id'])->where('enabled',1)->first();
            if(!$shipping_is_real)
            {
                return self::formatError(self::BAD_REQUEST, '您必须选定一个配送方式');
            }
        }
        /* 订单中的总额 */
        $total = Order::order_fee($order, $cart_goods, $consignee_info,$cart_good_id,$shipping,$consignee);
        /* 红包 */
        if (!empty($order['bonus_id']))
        {
            $bonus          = BonusType::bonus_info($order['bonus_id']);
            $total['bonus'] = $bonus['type_money'];
        }
        // $total['bonus_formated'] = Goods::price_format($total['bonus'], false);

        $order['bonus']        = isset($bonus)? $bonus['type_money'] : '';

        $order['goods_amount'] = $total['goods_price'];
        $order['discount']     = $total['discount'];
        $order['surplus']      = $total['surplus'];
        $order['tax']          = $total['tax'];

        // 购物车中的商品能享受红包支付的总额
        $discount_amout = self::compute_discount_amount($cart_good_ids);
        // 红包和积分最多能支付的金额为商品总额
        $temp_amout = $order['goods_amount'] - $discount_amout;

        if ($temp_amout <= 0)
        {
            $order['bonus_id'] = 0;
        }

        /* 配送方式 */
        if ($order['shipping_id'] > 0)
        {
            $shipping = Shipping::where('shipping_id',$order['shipping_id'])
                                ->where('enabled',1)
                                ->first();
            $order['shipping_name'] = addslashes($shipping['shipping_name']);
        }
        $order['shipping_fee'] = $total['shipping_fee'];
        $order['insure_fee']   = 0;
        /* 支付方式 */
        if ($order['pay_id'] > 0)
        {
            $payment = payment_info($order['pay_id']);
            $order['pay_name'] = addslashes($payment['pay_name']);
        }
        $order['pay_fee'] = $total['pay_fee'];
        $order['cod_fee'] = $total['cod_fee'];

        /* 商品包装 */

        /* 祝福贺卡 */

        /* 如果全部使用余额支付，检查余额是否足够 没有余额支付*/
        $order['order_amount']  = number_format($total['amount'], 2, '.', '');

        /* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
        if ($order['order_amount'] <= 0)
        {
            $order['order_status'] = Order::OS_CONFIRMED;
            $order['confirm_time'] = time();
            $order['pay_status']   = Order::PS_PAYED;
            $order['pay_time']     = time();
            $order['order_amount'] = 0;
        }

         $order['integral_money']   = $total['integral_money'];
         $order['integral']         = $total['integral'];

        $order['parent_id'] = 0;
        
        // 获取新订单号 验证订单号重复
        do {
            $order['order_sn'] = Order::get_order_sn();

            $order_sn = Order::where('order_sn', $order['order_sn'])->first();

        } while (!empty($order_sn));

        /* 插入订单表 */

        unset($order['timestamps']);
        unset($order['perPage']);
        unset($order['incrementing']);
        unset($order['dateFormat']);
        unset($order['morphClass']);
        unset($order['exists']);
        unset($order['wasRecentlyCreated']);
        unset($order['cod_fee']);
        // unset($order['surplus']);
        $new_order_id = Order::insertGetId($order);
        $order['order_id'] = $new_order_id;
	
        /* 插入订单商品 */
        $cart_goods = Cart::whereIn('rec_id',$cart_good_ids)->where('rec_type',$flow_type)->get();
        foreach ($cart_goods as $key => $cart_good) {
            $order_good                 = new OrderGoods;
            $order_good->order_id       = $new_order_id;
            $order_good->goods_id       = $cart_good->goods_id;
            $order_good->goods_name     = $cart_good->goods_name;
            $order_good->goods_sn       = $cart_good->goods_sn;
            $order_good->product_id     = $cart_good->product_id;
            $order_good->goods_number   = $cart_good->goods_number;
            $order_good->market_price   = $cart_good->market_price;
            $order_good->goods_price    = $cart_good->goods_price;
            $order_good->goods_attr     = $cart_good->goods_attr;
            $order_good->is_real        = $cart_good->is_real;
            $order_good->extension_code = $cart_good->extension_code;
            $order_good->parent_id      = $cart_good->parent_id;
            $order_good->is_gift        = $cart_good->is_gift;
            $order_good->goods_attr_id  = $cart_good->goods_attr_id;
            $order_good->save();
        }

        /* 修改拍卖活动状态 */

        /* 处理余额、积分、红包 */

        if ($order['user_id'] > 0 && $order['integral'] > 0)
        {
            AccountLog::logAccountChange(0, 0, 0, $order['integral'] * (-1), trans('message.score.pay'), $order['order_sn']);
        }


        if ($order['bonus_id'] > 0 && $temp_amout > 0)
        {
            UserBonus::useBonus($order['bonus_id'], $new_order_id);
        }

        /* 如果使用库存，且下订单时减库存，则减少库存 */
        if (ShopConfig::findByCode('use_storage') == '1' && ShopConfig::findByCode('stock_dec_time') == self::SDT_PLACE)
        {
            Order::change_order_goods_storage($order['order_id'], true, self::SDT_PLACE);
        }

        /* 给商家发邮件 */
        /* 增加是否给客服发送邮件选项 */
        /* 如果需要，发短信 */
        /* 如果订单金额为0 处理虚拟卡 */
        if ($order['order_amount'] <= 0)
        {
            $res = self::where('is_real',0)
                        ->where('extension_code','virtual_card')
                        ->where('rec_type','flow_type')
                        ->selectRaw('goods_id,goods_name,goods_number as num')
                        ->get();

            $virtual_goods = array();
            foreach ($res AS $row)
            {
                $virtual_goods['virtual_card'][] = array('goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']);
            }

            if ($virtual_goods AND $flow_type != self::CART_GROUP_BUY_GOODS)
            {
                /* 虚拟卡发货 */
                if (virtual_goods_ship($virtual_goods,$msg, $order['order_sn'], true))
                {
                    /* 如果没有实体商品，修改发货状态，送积分和红包 */
                    $get_count = OrderGoods::where('order_id',$order['order_id'])
                    ->where('is_real',1)
                    ->count();

                    if ($get_count <= 0)
                    {
                        /* 修改订单状态 */
                        update_order($order['order_id'], array('shipping_status' => SS_SHIPPED, 'shipping_time' => time()));

                        /* 如果订单用户不为空，计算积分，并发给用户；发红包 */
                        if ($order['user_id'] > 0)
                        {
                            /* 取得用户信息 */
                            $user = Member::user_info($order['user_id']);

                            /* 计算并发放积分 */
                            $integral = integral_to_give($order);
                            AccountLog::logAccountChange( 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), trans('message.score.register'), $order['order_sn']);

                            /* 发放红包 */
                            send_order_bonus($order['order_id']);
                        }
                    }
                }
            }

        }
        /* 清空购物车 */
        self::clear_cart_ids($cart_good_ids,$flow_type);

        /* 插入支付日志 */
        // $order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);


        if(!empty($order['shipping_name']))
        {
            $order['shipping_name']=trim(stripcslashes($order['shipping_name']));
        }
        $orderObj = Order::find($new_order_id);

        Erp::order($orderObj->order_sn, 'order_create');

        return self::formatBody(['order' => $orderObj]);
    }

    /**
     * 清空购物车
     * 用戶退出時自動清空
     * @param   int     $type   类型：默认普通商品
     */
    public static function clear()
    {
        $uid = Token::authorization();
        Cart::where('rec_type',self::CART_GENERAL_GOODS)->where('user_id',$uid)->delete();

        return self::formatBody();
    }

    /**
     * 刪除購物車商品
     * @return [type] [description]
     */
    public static function remove(array $attributes)
    {
        extract($attributes);
        Cart::where('rec_type',self::CART_GENERAL_GOODS)->where('rec_id',$good)->delete();

        return self::formatBody();
    }


    /**
     * 修改購物車商品数量
     * @return [type] [description]
     */
    public static function updateAmount(array $attributes)
    {
        extract($attributes);
        Cart::where('rec_type',self::CART_GENERAL_GOODS)->where('rec_id',$good)->update(['goods_number' => $amount]);

        return self::formatBody();
    }


    /**
     * 購物車列表
     * @return [type] [description]
     */
    public static function getList()
    {
        $uid = Token::authorization();
	
        // ----------------------
        $goods = self::join('goods', 'goods.goods_id', '=', 'cart.goods_id')
            ->where('user_id', $uid)
            ->where('goods.is_on_sale',1)
            ->where('goods.is_delete', 0)
            ->get();

        $use_storage = ShopConfig::where('code', 'use_storage')->value('value');

        foreach ($goods as $key => $value) 
        {
            // if (intval($use_storage) > 0 && $value->extension_code != 'package_buy')
            // {

            //     $goods_row = Goods::where('goods_id', $value->goods_id)->first();
            //     if (($goods_row->goods_number) < ($value->goods_number))
            //     {
            //         return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
            //     }
            //     /* 是货品 */
            //     if (!empty($value->product_id))
            //     {
            //         $product_row = Products::where('goods_id', $value->goods_id)->where('product_id', $value->product_id)->first();
            //         if (($product_row->product_number) < ($value->goods_number))
            //         {
            //             return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
            //         }
            //     }
            // }

            //订货数量大于0
            if ($value->goods_number > 0)
            {
                $attr_id    = empty($value->goods_attr_id) ? array() : explode(',', $value->goods_attr_id);
                
                $goods_price = Goods::get_final_price($value->goods_id, $value->goods_number, true, $attr_id);

                //更新购物车中的商品数量
                self::where('rec_id', $value->rec_id)->update(['goods_price' => $goods_price]);
            }

        }
        // ----------------------
	
        $data = [];
        $goods = self::findAllByUid($uid);
        if ($goods->count() > 0) {
            $data[0]['shop']['name'] = ShopConfig::findByCode('shop_name');
            $data[0]['shop']['id'] = 1;
            $data[0]['goods'] = $goods->toArray();
            $data[0]['total_price'] = self::TotalPrice();
            $data[0]['total_amount'] = self::TotalAmount();
        }

        return self::formatBody(['goods_groups' => $data]);
    }


    public static function findAllByUid($uid)
    {
        $ids =  self::leftJoin('goods', 'goods.goods_id', '=', 'cart.goods_id')->where('cart.user_id',$uid)->where('goods.is_on_sale',1)->lists('rec_id');
        return self::with('product')->whereIn('rec_id', $ids)->orderBy('rec_id','DESC')->get();
    }

    public static function TotalAmount()
    {
        $uid = Token::authorization();
        return self::where('user_id',$uid)->orderBy('rec_id','DESC')->sum('goods_number');
    }

    public static function TotalPrice()
    {
        $uid = Token::authorization();

        $goods =  self::where('user_id',$uid)->orderBy('rec_id','DESC')->get();
        $total = 0;
        foreach ($goods as $key => $good) {
            $total += ($good['goods_number'] * $good['goods_price']);
        }
        return (float)$total;
    }


    public function getIdAttribute()
    {
        return $this->rec_id;
    }

    public function getAmountAttribute()
    {
        return $this->goods_number;
    }

    public function getPropertyAttribute()
    {
            return preg_replace("/(?:\[)(.*)(?:\])/i", '', $this->goods_attr);
    }

    public function getAttrsAttribute()
    {
            return $this->goods_attr_id;
    }
    public function getPriceAttribute()
    {
        $property = explode(',', $this->goods_attr_id);
        $property_price         = GoodsAttr::property_price($property);
        if ($this->goods_attr_id) {
            $goods_price            = Goods::get_final_price($this->goods_id, 1, true, $property);//带属性 物品单价
            return $goods_price;
        }
        return Goods::get_final_price($this->goods_id, 1, true, $property);
    }
    public function getAttrstockAttribute()
    {
        if ($this->goods_attr_id) {
            $property_arr = explode(',', $this->goods_attr_id);
            $product_info = Products::get_products_info($this->goods_id, $property_arr);
            if ($product_info) {
                return $product_info['stock_number'];
            }
        }

        return Goods::where('goods_id',$this->goods_id)->value('goods_number');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\v2\Goods', 'goods_id', 'goods_id')->with('properties');
    }


    public function properties()
    {
        return $this->belongsToMany('App\Models\v2\Attribute','goods_attr','goods_id','attr_id')->where('attribute.attr_type', '!=',0)->groupBy('attr_id');
    }


    /**
     * 获得购物车中的商品
     *
     * @access  public
     * @return  array
     */
    public static function get_cart_goods($cart_good_ids = [])
    {
        /* 初始化 */
        $goods_list = array();
        $total = array(
            'goods_price'  => 0, // 本店售价合计（有格式）
            'market_price' => 0, // 市场售价合计（有格式）
            'saving'       => 0, // 节省金额（有格式）
            'save_rate'    => 0, // 节省百分比
            'goods_amount' => 0, // 本店售价合计（无格式）
        );

        /* 循环、统计 */
        // $sql = "SELECT *, IF(parent_id, parent_id, goods_id) AS pid " .
        //         " FROM " . $GLOBALS['ecs']->table('cart') . " " .
        //         " WHERE session_id = '" . SESS_ID . "' AND rec_type = '" . CART_GENERAL_GOODS . "'" .
        //         " ORDER BY pid, parent_id";
        // $res = $GLOBALS['db']->query($sql);

        $res = self::where('rec_type',self::CART_GENERAL_GOODS)->whereIn('rec_id', $cart_good_ids)->orderBy('parent_id')->get();
        /* 用于统计购物车中实体商品和虚拟商品的个数 */
        $virtual_goods_count = 0;
        $real_goods_count    = 0;
        foreach ($res as $key => $row) {

            $total['goods_price']  += $row['goods_price'] * $row['goods_number'];
            $total['market_price'] += $row['market_price'] * $row['goods_number'];

            $row['subtotal']     = Goods::price_format($row['goods_price'] * $row['goods_number'], false);
            $row['goods_price']  = Goods::price_format($row['goods_price'], false);
            $row['market_price'] = Goods::price_format($row['market_price'], false);

            /* 统计实体商品和虚拟商品的个数 */
            if ($row['is_real'])
            {
                $real_goods_count++;
            }
            else
            {
                $virtual_goods_count++;
            }

            /* 查询规格 */

            /* 增加是否在购物车里显示商品图 */

            $goods_list[] = $row;
        }
        $total['goods_amount'] = $total['goods_price'];
        $total['saving']       = Goods::price_format($total['market_price'] - $total['goods_price'], false);
        if ($total['market_price'] > 0)
        {
            $total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) *
            100 / $total['market_price']).'%' : 0;
        }
        $total['goods_price']  = Goods::price_format($total['goods_price'], false);
        $total['market_price'] = Goods::price_format($total['market_price'], false);
        $total['real_goods_count']    = $real_goods_count;
        $total['virtual_goods_count'] = $virtual_goods_count;

        return array('goods_list' => $goods_list, 'total' => $total);
    }

    /**
     * 检查订单中商品库存
     *
     * @access  public
     * @param   array   $arr
     *
     * @return  void
     */
    public static function flow_cart_stock($arr)
    {
        foreach ($arr AS $key => $val)
        {
            $val = intval(make_semiangle($val));
            if ($val <= 0 || !is_numeric($key))
            {
                continue;
            }
	    
            $goods = self::where('rec_id',$key)->first(['goods_id','goods_attr_id','extension_code']);

            $row = Goods::join('cart','goods.goods_id', '=', 'cart.goods_id')
                    ->where('cart.rec_id', $key)
                    ->select(
                        'goods.goods_name',
                        'goods.goods_number',
                        'cart.product_id'
                    )->first();
            //系统启用了库存，检查输入的商品数量是否有效
            if (intval(ShopConfig::findByCode('use_storage')) > 0 && $goods['extension_code'] != 'package_buy')
            {
                if ($row['goods_number'] < $val)
                {
                    return false;
                }

                /* 是货品 */
                $row['product_id'] = trim($row['product_id']);
                if (!empty($row['product_id']))
                {
                    $product_number = Products::where('goods_id',$goods['goods_id'])->where('product_id',$row['product_id'])->value('product_number');
                    if ($product_number < $val)
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 取得购物车总金额
     * @params  boolean $include_gift   是否包括赠品
     * @param   int     $type           类型：默认普通商品
     * @return  float   购物车总金额
     */
    public static function cart_amount($include_gift = true, $type = CART_GENERAL_GOODS)
    {
    
        $user_id = Token::authorization();
        $res = self::where('rec_type',$type)->where('user_id', $user_id);
        if (!$include_gift)
        {
            $res->where('is_gift',0)->where('goods_id','>',0);
        }
        $total = $res->selectRaw('sum(goods_price * goods_number) as total')
                     ->value('total');

        return (float)($total);
    }


    /**
     * 取得购物车商品
     * @param   int     $type   类型：默认普通商品
     * @return  array   购物车商品数组
     */
    public static function cart_goods($type = CART_GENERAL_GOODS,$cart_good_ids)
    {
        $arr = self::where('rec_type',$type)->whereIn('rec_id',$cart_good_ids)->get();

        /* 格式化价格及礼包商品 */
        foreach ($arr as $key => $value)
        {
            $arr[$key]['formated_market_price'] = Goods::price_format($value['market_price'], false);
            $arr[$key]['formated_goods_price']  = Goods::price_format($value['goods_price'], false);
            $arr[$key]['formated_subtotal']     = Goods::price_format($value['goods_price'] * $value['goods_number'], false);

            // if ($value['extension_code'] == 'package_buy')
            // {
            //     $arr[$key]['package_goods_list'] = get_package_goods($value['goods_id']);
            // }
        }

        return $arr;
    }


    /**
     * 计算购物车中的商品能享受红包支付的总额
     * @return  float   享受红包支付的总额
     */
    public static function compute_discount($order_products)
    {
        /* 查询优惠活动 */
        $now = time();
        $user_rank = UserRank::getUserRankByUid();
        $user_rank = ',' . $user_rank['rank_id'] . ',';
        $favourable_list = FavourableActivity::where('start_time','<=',$now)
                            ->where('end_time','>=',$now)
                            ->where(DB::raw("CONCAT(',', user_rank, ',')"), 'LIKE', "%".$user_rank."%")
                            ->whereIn('act_type',array(FavourableActivity::FAT_DISCOUNT, FavourableActivity::FAT_PRICE))
                            ->get()->toArray();
        if (!$favourable_list)
        {
            return 0;
        }
         $goods_list = $order_products;
         foreach ($goods_list as $key => $good) {
            $goods_list[$key]['price'] = Goods::get_final_price($good['goods_id'],$good['num'],true,$good['property']) ;
            $goods_list[$key]['amount'] = $good['num'];
         }
        if (!$goods_list)
        {
            return 0;
        }

        /* 初始化折扣 */
        $discount = 0;
        $favourable_name = array();

        /* 循环计算每个优惠活动的折扣 */
        foreach ($favourable_list as $favourable)
        {
            $total_amount = 0;
            if ($favourable['act_range'] == FavourableActivity::FAR_ALL)
            {
                foreach ($goods_list as $goods)
                {
                    $total_amount += $goods['price'] * $goods['amount'];
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_CATEGORY)
            {
	    	foreach ($goods_list as $goods)
                {
                    $cat_id = Goods::where('goods_id', $goods['goods_id'])->value('cat_id');

                    $cat_parent_id = Category::where('cat_id', $cat_id)->value('parent_id');

                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_id . ',') !== false || strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_parent_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_BRAND)
            {
                foreach ($goods_list as $goods)
                {
                    $brand_id = Goods::where('goods_id',$goods['goods_id'])->value('brand_id');
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $brand_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_GOODS)
            {
                foreach ($goods_list as $goods)
                {
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];                        
                    }
                }
            }
            else
            {
                continue;
            }
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
            {
                if ($favourable['act_type'] == FavourableActivity::FAT_DISCOUNT)
                {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
                }
                elseif ($favourable['act_type'] == FavourableActivity::FAT_PRICE)
                {
                    $discount += $favourable['act_type_ext'];
                }
            }
        }
        return $discount;
    }

    /**
     * 计算购物车中的商品能享受红包支付的总额
     * @return  float   享受红包支付的总额
     */
    public static function compute_discount_check($order_products)
    {
        /* 查询优惠活动 */
        $now = time();
        $user_rank = UserRank::getUserRankByUid();
        $user_rank = ',' . $user_rank['rank_id'] . ',';
        $favourable_list = FavourableActivity::where('start_time','<=',$now)
                            ->where('end_time','>=',$now)
                            ->where(DB::raw("CONCAT(',', user_rank, ',')"), 'LIKE', "%".$user_rank."%")
                            ->whereIn('act_type',array(FavourableActivity::FAT_DISCOUNT, FavourableActivity::FAT_PRICE))
                            ->get()->toArray();
        if (!$favourable_list)
        {
            return 0;
        }
         $goods_list = $order_products;
         foreach ($goods_list as $key => $good) {
            $good_property = [];
            if ($good['goods_attr_id']) {
                $good_property = explode(',', $good['goods_attr_id']);

            }
            $goods_list[$key]['price'] = Goods::get_final_price($good['goods_id'],$good['goods_number'],true,$good_property) ;
            $goods_list[$key]['amount'] = $good['goods_number'];

         }
        if (!$goods_list)
        {
            return 0;
        }

        /* 初始化折扣 */
        $discount = 0;
        $favourable_name = array();

        /* 循环计算每个优惠活动的折扣 */
        foreach ($favourable_list as $favourable)
        {
            $total_amount = 0;
            if ($favourable['act_range'] == FavourableActivity::FAR_ALL)
            {
                foreach ($goods_list as $goods)
                {
                    $total_amount += $goods['goods_price'] * $goods['goods_number'];

                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_CATEGORY)
            {
	    	foreach ($goods_list as $goods)
                {
                    $cat_id = Goods::where('goods_id', $goods['goods_id'])->value('cat_id');

                    $cat_parent_id = Category::where('cat_id', $cat_id)->value('parent_id');

                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_id . ',') !== false || strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_parent_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_BRAND)
            {
                foreach ($goods_list as $goods)
                {
                    $brand_id = Goods::where('goods_id',$goods['goods_id'])->value('brand_id');
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $brand_id . ',') !== false)
                    {
                        $total_amount += $goods['goods_price'] * $goods['goods_number'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_GOODS)
            {
                foreach ($goods_list as $goods)
                {
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false)
                    {
                        $total_amount += $goods['goods_price'] * $goods['goods_number'];
                    }
                }
            }
            else
            {
                continue;
            }
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
            {
                if ($favourable['act_type'] == FavourableActivity::FAT_DISCOUNT)
                {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
                }
                elseif ($favourable['act_type'] == FavourableActivity::FAT_PRICE)
                {
                    $discount += $favourable['act_type_ext'];
                }
            }
        }

        return $discount;
    }
    /**
     * 计算快速购买中的商品能享受红包支付的总额
     * @return  float   享受红包支付的总额
     */
    public static function compute_purchase_discount($order_products)
    {
        /* 查询优惠活动 */
        $now = time();
        $user_rank = UserRank::getUserRankByUid();
        $user_rank = ',' . $user_rank['rank_id'] . ',';
        $favourable_list = FavourableActivity::where('start_time','<=',$now)
                            ->where('end_time','>=',$now)
                            ->where(DB::raw("CONCAT(',', user_rank, ',')"), 'LIKE', "%".$user_rank."%")
                            ->whereIn('act_type',array(FavourableActivity::FAT_DISCOUNT, FavourableActivity::FAT_PRICE))
                            ->get()->toArray();
        if (!$favourable_list)
        {
            return 0;
        }
         $goods_list = [$order_products];
         foreach ($goods_list as $key => $good) {
            $goods_list[$key]['price'] = Goods::get_final_price($good['id'],$good['num']);
            $goods_list[$key]['amount'] = $good['num'];
         }
        if (!$goods_list)
        {
            return 0;
        }

        /* 初始化折扣 */
        $discount = 0;
        $favourable_name = array();

        /* 循环计算每个优惠活动的折扣 */
        foreach ($favourable_list as $favourable)
        {
            $total_amount = 0;
            if ($favourable['act_range'] == FavourableActivity::FAR_ALL)
            {
                foreach ($goods_list as $goods)
                {
                    $total_amount += $goods['price'] * $goods['amount'];
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_CATEGORY)
            {
            foreach ($goods_list as $goods)
                {
                    $cat_id = Goods::where('goods_id', $goods['id'])->value('cat_id');

                    $cat_parent_id = Category::where('cat_id', $cat_id)->value('parent_id');

                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_id . ',') !== false || strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_parent_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_BRAND)
            {
                foreach ($goods_list as $goods)
                {
                    $brand_id = Goods::where('goods_id',$goods['id'])->value('brand_id');
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $brand_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_GOODS)
            {
                foreach ($goods_list as $goods)
                {
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['id'] . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];                        
                    }
                }
            }
            else
            {
                continue;
            }
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
            {
                if ($favourable['act_type'] == FavourableActivity::FAT_DISCOUNT)
                {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
                }
                elseif ($favourable['act_type'] == FavourableActivity::FAT_PRICE)
                {
                    $discount += $favourable['act_type_ext'];
                }
            }
        }
        return $discount;
    }


    /**
     * 计算购物车中的商品能享受红包支付的总额
     * @return  float   享受红包支付的总额
     */
    public static function compute_discount_amount($cart_good_ids)
    {
        /* 查询优惠活动 */
        $now = time();
        $user_rank = UserRank::getUserRankByUid();
        $user_rank = ',' . $user_rank['rank_id'] . ',';

        $favourable_list = FavourableActivity::where('start_time','<=',$now)
                            ->where('end_time','>=',$now)
                            ->where(DB::raw("CONCAT(',', user_rank, ',')"), 'LIKE', "%".$user_rank."%")
                            ->whereIn('act_type',array(FavourableActivity::FAT_DISCOUNT, FavourableActivity::FAT_PRICE))
                            ->get()->toArray();
        if (!$favourable_list)
        {
            return 0;
        }

        $goods_list = Cart::join('goods','cart.goods_id','=','goods.goods_id')
                    ->where('cart.parent_id',0)
                    ->whereIn('cart.rec_id',$cart_good_ids)
                    ->where('cart.is_gift',0)
                    ->where('cart.rec_type',Cart::CART_GENERAL_GOODS)
                    ->get()->toArray();
        if (!$goods_list)
        {
            return 0;
        }

        /* 初始化折扣 */
        $discount = 0;
        $favourable_name = array();

        /* 循环计算每个优惠活动的折扣 */
        foreach ($favourable_list as $favourable)
        {

            $total_amount = 0;
            if ($favourable['act_range'] == FavourableActivity::FAR_ALL)
            {
                foreach ($goods_list as $goods)
                {
                    $total_amount += $goods['price'] * $goods['amount'];
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_CATEGORY)
            {
                foreach ($goods_list as $goods)
                {
                    $cat_id = Goods::where('goods_id', $goods['goods_id'])->value('cat_id');

                    $cat_parent_id = Category::where('cat_id', $cat_id)->value('parent_id');

                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_id . ',') !== false || strpos(',' . $favourable['act_range_ext'] . ',', ',' . $cat_parent_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_BRAND)
            {
                foreach ($goods_list as $goods)
                {

                    $brand_id = Goods::where('goods_id',$goods['id'])->value('brand_id');
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $brand_id . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            elseif ($favourable['act_range'] == FavourableActivity::FAR_GOODS)
            {
                foreach ($goods_list as $goods)
                {
                    if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['id'] . ',') !== false)
                    {
                        $total_amount += $goods['price'] * $goods['amount'];
                    }
                }
            }
            else
            {
                continue;
            }
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
            {
                if ($favourable['act_type'] == FavourableActivity::FAT_DISCOUNT)
                {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
                }
                elseif ($favourable['act_type'] == FavourableActivity::FAT_PRICE)
                {
                    $discount += $favourable['act_type_ext'];
                }
            }
        }

        return $discount;
    }

    /**
     * 取得购物车该赠送的积分数
     * @return  int     积分数
     */
    public static function get_give_integral()
    {
            // $sql = "SELECT SUM(c.goods_number * IF(g.give_integral > -1, g.give_integral, c.goods_price))" .
            //         "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " .
            //                   $GLOBALS['ecs']->table('goods') . " AS g " .
            //         "WHERE c.goods_id = g.goods_id " .
            //         "AND c.session_id = '" . SESS_ID . "' " .
            //         "AND c.goods_id > 0 " .
            //         "AND c.parent_id = 0 " .
            //         "AND c.rec_type = 0 " .
            //         "AND c.is_gift = 0";
            $allIntegral = Cart::join('goods','goods.goods_id','=','cart.goods_id')
                ->where('cart.goods_id','>',0)
                ->where('cart.parent_id','=',0)
                ->where('cart.rec_type','=',0)
                ->where('cart.is_gift','=',0)
                ->get();
            $sum = 0;
            foreach ($allIntegral as $key => $value) {
                if ($value->give_integral > -1 ) {
                    $sum += $value->give_integral * $value->goods_number;
                }else{
                    $sum += $value->goods_price * $value->goods_number;
                }
            }
            return $sum;
    }


    /**
     * 清空购物车
     * @param   int     $type   类型：默认普通商品
     */
    public static function clear_cart($type = CART_GENERAL_GOODS)
    {
        $uid = Token::authorization();
        self::where('rec_type',$type)->where('user_id',$uid)->delete();
    }

    /**
     * 清空指定购物车
     * @param   arr     $arr   购物车id
     * @param   int     $type   类型：默认普通商品
     */
    public static function clear_cart_ids($arr,$type = CART_GENERAL_GOODS)
    {
        $uid = Token::authorization();
        self::whereIn('rec_id',$arr)->where('rec_type',$type)->where('user_id',$uid)->delete();
    }

    /**
     * 获取已选择商品的价格
     */
    public static function getCartAmount($arr, $include_gift = true, $type = self::CART_GENERAL_GOODS)
    {
        $uid = Token::authorization();
        $carts = self::whereIn('rec_id',$arr)->where('rec_type',$type)->where('user_id',$uid);
        if (!$include_gift)
        {
            $carts->where('is_gift',0)->where('goods_id','>',0);
        }
        $total = $carts->selectRaw('sum(goods_price * goods_number) as total')
                     ->value('total');

        return (float)($total);
    }
}
