<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;
use \DB;
use App\Services\Shopex\Erp;

class Goods extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'goods';

    public    $timestamps = false;

    protected $primaryKey = 'goods_id';

    protected $guarded = [];

    protected $appends = [
                          'id', 'category', 'brand', 'shop', 'sku', 'default_photo', 'photos', 'name', 'price', 'current_price', 'discount', 'sales_count','score','good_stock',
                          'comment_count', 'is_liked', 'review_rate', 'intro_url', 'share_url', 'created_at', 'updated_at'
                         ];

    protected $visible = [
                          'id', 'category', 'brand', 'shop', 'tags', 'default_photo', 'photos','sku', 'name', 'price', 'current_price', 'discount', 'is_shipping', 'promos','stock','properties','sales_count', 'attachments','goods_desc','score','comments','good_stock','comment_count', 'is_liked', 'review_rate', 'intro_url', 'share_url', 'created_at', 'updated_at'
                         ];

    // protected $with = [];

    const NOSORT     = 0;
    const PRICE      = 1;
    const POPULAR    = 2;
    const CREDIT     = 3;
    const SALE       = 4;
    const DATE       = 5;

    const ASC        = 1;
    const DESC       = 2;

    public static function findAll(array $attributes)
    {
        extract($attributes);


        $model = self::where(['is_delete' => 0]);

        $total = $model->count();

        $data = $model
            ->orderBy('sort_order', 'ASC')->orderBy('goods_id', 'DESC')
            ->paginate($per_page)->toArray();

        return self::formatBody(['products' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    /**
     * 首页商品列表
     */    
    public static function getHomeList()
    {
        return self::formatBody([
            'hot_products'  => count(self::getRecommendGoods('is_hot')) == 0 ? null : self::getRecommendGoods('is_hot'),
            'recently_products'  => count(self::getRecommendGoods('is_new')) == 0 ? null : self::getRecommendGoods('is_new'),
            'good_products' => count(self::getRecommendGoods('is_best')) == 0 ? null : self::getRecommendGoods('is_best'),
        ]);
    }

    public static function getRecommendGoods($type)
    {
        $model = self::where(['is_delete' => 0, 'is_on_sale' => 1, 'is_alone_sale' => 1]);
        return $model->where($type, 1)->orderBy('sort_order')->orderBy('last_update', 'desc')->with('properties')->get();
    }

    /**
     * 商品列表
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    public static function getList(array $attributes)
    {
        extract($attributes);
        $prefix = DB::connection('shop')->getTablePrefix();

        //全站商品
        $model = self::where(['is_delete' => 0, 'is_on_sale' => 1]);

        if (isset($keyword) && $keyword) {
            $keyword = trim($keyword);
            $keyword = strip_tags($keyword);
            $model->where(function ($query) use ($keyword) {
                // keywords
                 $query->where('goods.goods_name', 'like', '%'.strip_tags($keyword).'%')->orWhere('keywords', strip_tags($keyword))->orWhere('goods.goods_id', strip_tags($keyword));
            });
            // 搜索历史
            Keywords::updateHistory($keyword);
        }

        if (isset($brand) && $brand) {
            $model->where('brand_id', $brand);
        }

        if (isset($category) && $category) {
            $model->where(function ($query) use ($category) {
                $query->whereIn('goods.cat_id', GoodsCategory::getCategoryIds($category));
            });
        }
	
	$total = $model->count();

        if (isset($sort_key)) {

            switch ($sort_value) {
                case '1':
                    $sort = 'ASC';
                    break;
                
                case '2':
                    $sort = 'DESC';
                    break;

                default:
                    $sort = 'DESC';
                    break;
            }

            switch ($sort_key) {

                case self::NOSORT:
                    $model->orderBy('sort_order', $sort);
                    break;

                case self::PRICE:
                    $model->orderBy('shop_price', $sort);
                    break;

                case self::POPULAR:
                    $model->orderBy('click_count', $sort);
                    break;

                case self::CREDIT:
                    // 按照评论数
                    $model->select('*',DB::raw($prefix.'goods.goods_id, concat( sum(comment_rank)/(count(id_value) * 5)) AS goods_rank_rate'))
                        ->leftJoin('comment', 'goods.goods_id', '=', 'comment.id_value')
                        ->groupBy('goods.goods_id')
                        ->orderBy('goods_rank_rate', $sort);
			
			$total = count($model->get()->toArray());
                    break;
		    
		case self::SALE:		
		   $model->select('goods.*',DB::raw('sum('.$prefix.'order_goods.goods_number) AS total_sales'))
                            ->leftJoin('order_goods', 'goods.goods_id', '=', 'order_goods.goods_id')
                            ->groupBy('order_goods.goods_id')
                            ->orderBy('total_sales', $sort);
                    $total = count($model->get()->toArray());
                    break;

                case self::DATE:
		    $model->orderBy('add_time', $sort);
                    break;

                default:
                    $model->orderBy('sort_order', 'DESC');
                    break;
            }
        } else {
            $model->orderBy('sort_order', 'DESC');
        }
	
	$data = $model
            ->with('properties')
            ->paginate($per_page)->toArray();

        return self::formatBody(['products' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }
    
    /**
     * 推荐商品列表
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    public static function getRecommendList(array $attributes)
    {
        extract($attributes);

        //全站商品
        $model = Goods::where(['is_delete' => 0, 'is_on_sale' => 1]);

        if (isset($brand) && $brand) {
            $model->where('brand', Brand::getBrandById($brand));
        }

        if (isset($category) && $category) {
            $model->where(function ($query) use ($category) {
                $query->whereIn('goods.cat_id', GoodsCategory::getCategoryIds($category));
            });
        }

        if (isset($product) && $product) {
            $model->where(function ($query) use ($product) {
                $query->whereIn('goods.goods_id', LinkGoods::getLinkGoodIds($product));
            });
        }
        $total = $model->count();

        $data = $model->orderBy('sort_order', 'DESC')->orderBy('goods_id', 'DESC')
            ->paginate($per_page)->toArray();

        return self::formatBody(['products' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    /**
     * 商品配件列表
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    public static function getAccessoryList(array $attributes)
    {
        extract($attributes);

        //全站商品
        $model = Goods::where(['is_delete' => 0]);

        if (isset($product) && $product) {
            $model->where(function ($query) use ($product) {
                $query->whereIn('goods.goods_id', GoodsGroup::getAccessories($product));
            });
        }
        $total = $model->count();

        $data = $model
            ->with(['properties','tags','stock','attachments'])
            ->orderBy('sort_order', 'DESC')->orderBy('goods_id', 'DESC')
            ->paginate($per_page)->toArray();

        return self::formatBody(['products' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public static function getInfo(array $attributes)
    {
        extract($attributes);

        $model = Goods::where(['is_delete' => 0, 'goods_id' => $product]);

        $data = $model->with(['properties','tags','stock','attachments'])->first();

        if (!$data) {
            return self::formatError(self::NOT_FOUND);
        }

        if (!$data->is_on_sale) {
            return self::formatError(self::BAD_REQUEST, trans('message.good.off_sale'));
        }
	// $current_price = UserRank::getMemberRankPriceByGid($product);
        $data['promos'] = FavourableActivity::getPromoByGoods($product,$data->cat_id, $data->brand_id);

//        if ($data->promote_price == 0) {
//            $current_price = UserRank::getMemberRankPriceByGid($product);
//            return self::formatBody(['product' => array_merge($data->toArray(), ['current_price' => $current_price])]);
//        }
        return self::formatBody(['product' => $data->toArray()]);
    }


    public static function getIntro($id)
    {
        if ($model = self::where('goods_id', $id)->first()) {
            $pattern = '/(https?|ftp|mms)?:\/\/([A-z0-9]+[_\-]?[A-z0-9]+\.)*[A-z0-9]+\-?[A-z0-9]+\.[A-z]{2,}(\/.*)*\/?(\/images\/upload\/)/';
            if(!preg_match($pattern, $model->goods_desc)){
                $model->goods_desc = str_replace('/images/upload', config('app.shop_url').'/images/upload', $model->goods_desc);
            }
            return view('goods.intro', ['goods' => $model->toArray()]);
        }
    }

    public static function getShare($id)
    {
        if ($model = self::where('goods_id', $id)->first()) {
            $reviews = Comment::with('author')->where(['comment_type' => Comment::GOODS,'id_value' => $id])->get();
            $shop = ShopConfig::getSiteInfo();
            return view('goods.share', ['goods' => $model->toArray(), 'reviews' => $reviews->toArray(), 'shop' => $shop]);
        }
    }

    public function getIdAttribute()
    {
        return $this->goods_id;
    }

    public function getCategoryAttribute()
    {
        return $this->cat_id;
    }

    public function getScoreAttribute()
    {
        $scale = ShopConfig::findByCode('integral_scale');
        if($scale > 0){
            return $this->integral / ($scale / 100);
        }
        return 0;
    }

    public function getBrandAttribute()
    {
        return $this->brand_id;
    }

    public function getShopAttribute()
    {
        $data = [];
        // $data['name'] = ShopConfig::findByCode('shop_name');
        $data['id'] = 1;
        return $data['id'] ;
    }

    public function tags()
    {
        return $this->hasMany('App\Models\v2\Tags', 'goods_id', 'goods_id');

    }

    // public function promos()
    // {
    //     return $this->hasMany('App\Models\v2\GoodsActivity', 'goods_id', 'goods_id');

    // }

    public function properties()
    {
        return $this->belongsToMany('App\Models\v2\Attribute','goods_attr','goods_id','attr_id')->where('attribute.attr_type', '!=',0)->groupBy('attr_id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\v2\GoodsGroup', 'parent_id', 'goods_id');
    }

    public function stock()
    {
        return $this->hasMany('App\Models\v2\Products', 'goods_id', 'goods_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\v2\Comment', 'id_value', 'goods_id')->where('comment.comment_type', 0)->where('comment_rank','>',3); //商品
    }

    public function getSkuAttribute()
    {
        return $this->goods_sn;
    }

    public function getNameAttribute()
    {
        return $this->goods_name;
    }

    public function getGoodstockAttribute()
    {
        return $this->goods_number;
    }

    public function getPriceAttribute()
    {
        return $this->market_price;
    }

    public function getCurrentpriceAttribute()
    {
        $promote_price = self::bargain_price($this->promote_price, $this->promote_start_date, $this->promote_end_date);
        if (!empty($promote_price)) {
            return $promote_price;
        }
	
	$user_price = UserRank::getMemberRankPriceByGid($this->goods_id);
        // $user_rank = UserRank::getUserRankByUid();
        // $user_price = MemberPrice::getMemberPriceByUid($user_rank['rank_id'], $this->goods_id);

        if (!empty($user_price)) {
            return $user_price;
        }

        $current_price = UserRank::getMemberRankPriceByGid($this->goods_id);
	
        return self::price_format($current_price, false);
    }
    public function getDiscountAttribute()
    {
        $price = self::bargain_price($this->promote_price, $this->promote_start_date, $this->promote_end_date);
        if ($price > 0) {
            return [
                "price"         => $price,                                  // 促销价格
                "start_at"      => $this->promote_start_date,               // 开始时间
                "end_at"        => $this->promote_end_date,                 // 结束时间
            ];

        } else {
            return null;
        }
    }
    public function getShareUrlAttribute()
    {
        $uid = Token::authorization();
        if ($uid) {
            return config('app.shop_h5').'/?u=' .$uid. '#/product/?product=' . $this->goods_id;
        }
        return config('app.shop_h5').'/#/product/?product='.$this->goods_id;
    }

    public function getIslikedAttribute()
    {
        return CollectGoods::getIsLiked($this->goods_id) ? 1 : 0;
    }

    public function getSalescountAttribute()
    {
        return OrderGoods::getSalesCountById($this->goods_id);
        //return $this->virtual_sales;
    }
    public function getCommentcountAttribute()
    {
        return Comment::getCommentCountById($this->goods_id);
    }
    public function getPhotosAttribute()
    {
//        $goods =  Goods::where('goods_id', $this->goods_id)->first();
//
//        $goods_images = formatPhoto($goods->goods_img, $goods->goods_thumb);
//
//        $arr = GoodsGallery::getPhotosById($this->goods_id);
//
//        if (!empty($goods_images)) {
//            array_unshift($arr, $goods_images);
//        }
//
//        if (empty($arr)) {
//            return null;
//        }
//
//        return $arr;
        return GoodsGallery::getPhotosById($this->goods_id);
    }

    public function getDefaultPhotoAttribute()
    {
        return formatPhoto($this->goods_img);
    }

    public function getReviewrateAttribute()
    {
        return Comment::getCommentRateById($this->goods_id).'%';
    }

    public function getIntrourlAttribute()
    {
        if(empty($this->goods_desc))
        {
            return null;
        }
        return url('/v2/product.intro.'.$this->goods_id);
    }
    public function getCreatedatAttribute()
    {
        return $this->add_time;
    }
    public function getUpdatedatAttribute()
    {
        return $this->last_update;
    }

    /**
     * 取得商品最终使用价格
     *
     * @param   string  $goods_id      商品编号
     * @param   string  $goods_num     购买数量
     * @param   boolean $is_spec_price 是否加入规格价格
     * @param   mix     $property          规格ID的数组或者逗号分隔的字符串
     *
     * @return  商品最终购买价格
     */
    public static function get_final_price($goods_id, $goods_num = '1', $is_spec_price = false, $property = array())
    {
        $final_price   = '0'; //商品最终购买价格
        $volume_price  = '0'; //商品优惠价格
        $promote_price = '0'; //商品促销价格
        $user_price    = '0'; //商品会员价格

        //取得商品优惠价格列表
        $price_list   = self::get_volume_price_list($goods_id, '1');
        if (!empty($price_list))
        {
            foreach ($price_list as $value)
            {
                if ($goods_num >= $value['number'])
                {
                    $volume_price = $value['price'];
                }
            }
        }
        //取得商品促销价格列表
        /* 取得商品信息 */
        $goods = Goods::where('goods.goods_id',$goods_id)->where('goods.is_delete',0)->leftJoin('member_price',function($query){
            $query->on('member_price.goods_id', '=', 'goods.goods_id');
        })->first(['goods.promote_price','goods.promote_start_date','goods.promote_end_date','member_price.user_price']);
	
	$member_price = UserRank::getMemberRankPriceByGid($goods_id);
	$user_rank = UserRank::getUserRankByUid();
	$user_price = MemberPrice::getMemberPriceByUid($user_rank['rank_id'],$goods_id);
        // $goods['user_price'] = $user_price;
        $goods['shop_price'] = isset($user_price) ? $user_price : $member_price;
        /* 计算商品的促销价格 */
        if ($goods->promote_price > 0) {
            $promote_price = self::bargain_price($goods->promote_price, $goods->promote_start_date, $goods->promote_end_date);
        }else{
            $promote_price = 0;
        }

        //取得商品会员价格列表
        $user_price    = $goods['shop_price'];

        //比较商品的促销价格，会员价格，优惠价格
        if (empty($volume_price) && empty($promote_price))
        {
            //如果优惠价格，促销价格都为空则取会员价格
            $final_price = $user_price;
        }
        elseif (!empty($volume_price) && empty($promote_price))
        {
            //如果优惠价格为空时不参加这个比较。
            $final_price = min($volume_price, $user_price);
        }
        elseif (empty($volume_price) && !empty($promote_price))
        {
            //如果促销价格为空时不参加这个比较。
            $final_price = min($promote_price, $user_price);
        }
        elseif (!empty($volume_price) && !empty($promote_price))
        {
            //取促销价格，会员价格，优惠价格最小值
            $final_price = min($volume_price, $promote_price, $user_price);
        }
        else
        {
            $final_price = $user_price;
        }

        //如果需要加入规格价格
        if ($is_spec_price)
        {
            if (!empty($property))
            {
                $property_price   = GoodsAttr::property_price($property);
                $final_price += $property_price;

            }
        }
        //返回商品最终购买价格
        return $final_price;
    }


    /**
     * 取得商品优惠价格列表
     *
     * @param   string  $goods_id    商品编号
     * @param   string  $price_type  价格类别(0为全店优惠比率，1为商品优惠价格，2为分类优惠比率)
     *
     * @return  优惠价格列表
     */
    public static function get_volume_price_list($goods_id, $price_type = '1')
    {
        $volume_price = array();
        $temp_index   = '0';

        // $sql = "SELECT `volume_number` , `volume_price`".
        //        " FROM " .$GLOBALS['ecs']->table('volume_price'). "".
        //        " WHERE `goods_id` = '" . $goods_id . "' AND `price_type` = '" . $price_type . "'".
        //        " ORDER BY `volume_number`";

        // $res = $GLOBALS['db']->getAll($sql);

        $res = VolumePrice::where('goods_id',$goods_id)->where('price_type',$price_type)->orderBy('volume_number')->get();

        foreach ($res as $k => $v)
        {
            $volume_price[$temp_index]                 = array();
            $volume_price[$temp_index]['number']       = $v['volume_number'];
            $volume_price[$temp_index]['price']        = $v['volume_price'];
            $volume_price[$temp_index]['format_price'] = self::price_format($v['volume_price']);
            $temp_index ++;
        }
        return $volume_price;
    }


    /**
     * 判断某个商品是否正在特价促销期
     *
     * @access  public
     * @param   float   $price      促销价格
     * @param   string  $start      促销开始日期
     * @param   string  $end        促销结束日期
     * @return  float   如果还在促销期则返回促销价，否则返回0
     */
    public static function bargain_price($price, $start, $end)
    {
        if ($price == 0)
        {
            return 0;
        }
        else
        {
            $time = time();
            // $time = gmtime();
            if ($time >= $start && $time <= $end)
            {
                return $price;
            }
            else
            {
                return 0;
            }
        }
    }


    /**
     * 格式化商品价格
     *
     * @access  public
     * @param   float   $price  商品价格
     * @return  string
     */
    public static function price_format($price, $change_price = true)
    {
        $price_format = 1;
        if($price === '')
        {
         $price = 0;
        }
        if ($change_price )
        {
            switch ($price_format)
            {
                case 0:
                    $price = number_format($price, 2, '.', '');
                    break;
                case 1: // 保留不为 0 的尾数
                    $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                    if (substr($price, -1) == '.')
                    {
                        $price = substr($price, 0, -1);
                    }
                    break;
                case 2: // 不四舍五入，保留1位
                    $price = substr(number_format($price, 2, '.', ''), 0, -1);
                    break;
                case 3: // 直接取整
                    $price = intval($price);
                    break;
                case 4: // 四舍五入，保留 1 位
                    $price = number_format($price, 1, '.', '');
                    break;
                case 5: // 先四舍五入，不保留小数
                    $price = round($price);
                    break;
            }
        }
        else
        {
            $price = number_format($price, 2, '.', '');
        }

        // return sprintf('￥%s元', $price);
        return $price;
    }


    /**
     * 立即购买
     * @param     int     $shop            // 店铺ID(无)
     * @param     int     $consignee       // 收货人ID
     * @param     int     $shipping        // 快递ID
     * @param     string  $invoice_type    // 发票类型，如：公司、个人
     * @param     string  $invoice_content // 发票内容，如：办公用品、礼品
     * @param     string  $invoice_title   // 发票抬头，如：xx科技有限公司
     * @param     int     $coupon          // 优惠券ID (无)
     * @param     int     $cashgift        // 红包ID
     * @param     int     $comment         // 留言
     * @param     int     $score           // 积分
     * @param     int     $product    // 商品ID
     * @param     string  $property    // 用户选择的属性ID
     * @param     int     $amount    // 数量
     */

    public static function purchase(array $attributes)
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

            if(!is_array($property)){
                return self::formatError(self::BAD_REQUEST);
            }
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
            $product_info = json_encode(array('product_number' => '', 'product_id' => 0));
            $product_info = json_decode($product_info);
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
                if ($amount > $product_info['product_number'])
                {
                    return self::formatError(self::BAD_REQUEST,trans('message.good.out_storage'));
                }
            }
        }

        /* 计算商品的促销价格 */
        $property_price         = GoodsAttr::property_price($property);
        $goods_price            = Goods::get_final_price($product, $amount, true, $property);
        $good['market_price']  += $property_price;
        $good['goods_number']  = $amount;
        $good['goods_price']   = $goods_price;
        $goods_attr             = Attribute::get_goods_attr_info($property);
        $goods_attr_id          = join(',', $property);
        /* 初始化要插入购物车的基本件数据 */

        //-- 完成所有订单操作，提交到数据库

        /* 取得购物类型 */
        $flow_type = Cart::CART_GENERAL_GOODS;

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
            $scale = ShopConfig::findByCode('integral_scale');
            $total_integral = $good['integral'] * $amount;

            if ($total_integral) {
                $flow_points = $total_integral / ($scale / 100);
            } else {
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

            if (empty($bonus) || $bonus['user_id'] != $user_id || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > $goods_price * $amount)
            {
                $order['bonus_id'] = 0;
            }
        }

        /* 订单中的商品 */

        /* 检查商品总额是否达到最低限购金额 */
	if ($flow_type == Cart::CART_GENERAL_GOODS && $goods_price < ShopConfig::findByCode('min_goods_amount')) // Cart::cart_amount(true, Cart::CART_GENERAL_GOODS)
        {
            return self::formatError(self::BAD_REQUEST,trans('message.good.min_goods_amount'));
        }
        /* 收货人信息 */
        $order['consignee'] = $consignee_info->consignee;
        $order['mobile'] = $consignee_info->mobile;
        $order['tel'] = $consignee_info->tel;
        $order['zipcode'] = $consignee_info->zipcode;
        $order['district'] = $consignee_info->district;
        $order['address'] = $consignee_info->address;

       /* 判断是不是实体商品 */
        /* 统计实体商品的个数 */
        if ($good['is_real'])
        {
            $is_real_good=1;
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
        $total = Order::purchase_fee($order, $good->toArray() ,$property_price,$goods_price, $amount ,$consignee_info, $shipping,$consignee);
        /* 红包 */
        if (!empty($order['bonus_id']))
        {
            $bonus          = BonusType::bonus_info($order['bonus_id']);
            $total['bonus'] = $bonus['type_money'];
        }

        $order['bonus']        = isset($bonus)? $bonus['type_money'] : '';

        $order['goods_amount'] = $total['goods_price'];
        $order['discount']     = $total['discount'];
        $order['surplus']      = $total['surplus'];
        $order['tax']          = $total['tax'];

        // 购物车中的商品能享受红包支付的总额
        $discount_amout = $total['discount_formated'];

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
        $order['order_sn'] = Order::get_order_sn(); //获取新订单号
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

        /* 计算商品的促销价格 */
        $property_price         = GoodsAttr::property_price($property);
        $goods_price            = Goods::get_final_price($product, $amount, true, $property);
        $good['market_price']  += $property_price;
        $goods_attr             = Attribute::get_goods_attr_info($property);
        $goods_attr_id          = join(',', $property);

        /* 插入订单商品 */

            $order_good                 = new OrderGoods;
            $order_good->order_id       = $new_order_id;

            $order_good->goods_id       = $product;
            $order_good->goods_name     = $good->goods_name;
            $order_good->goods_sn       = $good->goods_sn;
            $order_good->product_id     = $product_info->product_id;
            $order_good->goods_number   = $amount;
            $order_good->market_price   = $good->market_price;
            $order_good->goods_price    = $goods_price;
            $order_good->goods_attr     = $goods_attr;
            $order_good->is_real        = $good->is_real;
            $order_good->extension_code = $good->extension_code;
            $order_good->parent_id      = 0;
            $order_good->is_gift        = 0;
            $order_good->goods_attr_id  = $goods_attr_id;
            $order_good->save();

        /* 修改拍卖活动状态 */

        /* 处理余额、积分、红包 */
        if ($order['user_id'] > 0 && $order['integral'] > 0)
        {
            AccountLog::logAccountChange( 0, 0, 0, $order['integral'] * (-1), trans('message.score.pay'), $order['order_sn']);
        }


        if ($order['bonus_id'] > 0 && $temp_amout > 0)
        {
            UserBonus::useBonus($order['bonus_id'], $new_order_id);
        }

        /* 如果使用库存，且下订单时减库存，则减少库存 */
        if (ShopConfig::findByCode('use_storage') == '1' && ShopConfig::findByCode('stock_dec_time') == Cart::SDT_PLACE)
        {
            Order::change_order_goods_storage($order['order_id'], true, Cart::SDT_PLACE);
        }

        /* 给商家发邮件 */
        /* 增加是否给客服发送邮件选项 */
        /* 如果需要，发短信 */
        /* 如果订单金额为0 处理虚拟卡 */

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
}
