<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helper\Token;
use App\Models\v2\Shipping;
use App\Models\v2\Goods;
use App\Models\v2\Comment;
use App\Models\v2\GoodsCategory;
use App\Models\v2\CollectGoods;
use App\Models\v2\Products;
use Log;

class GoodsController extends Controller
{
    /**
     * POST /ecapi.home.product.list
     */
    public function home()
    {
        $data = Goods::getHomeList();

        return $this->json($data);
    }

    /**
     * POST /ecapi.product.list
     */
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
            'brand'           => 'integer|min:1',
            'category'        => 'integer|min:1',
            'shop'            => 'integer|min:1',
            'keyword'         => 'string|min:1',
            'sort_key'        => 'string|min:1',
            'sort_value'      => 'required_with:sort_key|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Goods::getList($this->validated);

        return $this->json($data);
    }

    /**
     * POST /ecapi.search.product.list
     */
    public function search()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
            'brand'           => 'integer|min:1',
            'category'        => 'integer|min:1',
            'shop'            => 'integer|min:1',
            'keyword'         => 'string|min:1',
            'sort_key'        => 'string|min:1',
            'sort_value'      => 'required_with:sort_key|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Goods::getList($this->validated);

        return $this->json($data);
    }

    /**
     * POST /ecapi.product.like
     */
    public function setLike()
    {
        $rules = [
            'product' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = CollectGoods::setLike($this->validated);

        return $this->json($data);
    }

    /**
     * POST /ecapi.product.unlike
     */
    public function setUnlike()
    {
        $rules = [
            'product' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = CollectGoods::setUnlike($this->validated);

        return $this->json($data);
    }

    /**
     * POST /ecapi.product.liked.list
     */
    public function likedList()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = CollectGoods::getList($this->validated);

        return $this->json($data);
    }

    /**
     * 商品评价
     * @return [type] [description]
     */
    public function review()
    {

        $rules = [
            'page'     => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
            'product'  => 'required|integer|min:1',
            'grade'    => 'integer|in:0,1,2,3',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }
        $data = Comment::getReview($this->validated);

        return $this->json($data);
    }

    /**
     * 评价统计
     * @return [type] [description]
     */
    public function subtotal()
    {

        $rules = [
            'product'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }
        $data = Comment::getSubtotal($this->validated);

        return $this->json($data);
    }


    public function recommendList()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
            'product'         => 'integer|min:1',
            'brand'           => 'integer|min:1',
            'category'        => 'integer|min:1',
            'shop'            => 'integer|min:1',
            'sort_key'        => 'string|min:1',
            'sort_value'      => 'required_with:sort_key|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Goods::getRecommendList($this->validated);

        return $this->json($data);
    }

    public function accessoryList()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
            'product'         => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Goods::getAccessoryList($this->validated);

        return $this->json($data);
    }


    public function brandList()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
        ];

    }

    public function category()
    {
        $rules = [
            'page'     => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
            'category' => 'integer|min:1',
            'shop'     => 'integer|min:1',
            // 'paging'     => 'required',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = GoodsCategory::getList($this->validated);

        return $this->json($data);
    }


    public function categorySearch()
    {
        $rules = [
            'page'     => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
            'category' => 'integer|min:1',
            'shop'     => 'integer|min:1',
            'keyword'  => 'string',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = GoodsCategory::getList($this->validated);

        return $this->json($data);
    }

    public function info()
    {
        $rules = [
            'product' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Goods::getInfo($this->validated);
        
        return $this->json($data);
    }

    public function intro($id)
    {
        return Goods::getIntro($id);
    }

    public function share($id)
    {
        return Goods::getShare($id);
    }

    public function purchase()
    {
        $rules = [
            "shop"             => "integer|min:1",          // 店铺ID
            "consignee"        => "required|integer|min:1", // 收货人ID
            "shipping"         => "required|integer|min:1", // 快递ID
            "invoice_type"     => "string|min:1",           // 发票类型ID，如：公司、个人
            "invoice_content"  => "string|min:1",           // 发票内容ID，如：办公用品、礼品
            "invoice_title"    => "string|min:1",           // 发票抬头，如：xx科技有限公司
            "coupon"           => "string|min:1",           // 优惠券ID
            "cashgift"         => "string|min:1",           // 红包ID
            "comment"          => "string|min:1",           // 留言
            "score"            => "integer",                 // 积分
            "property"         => "required|string",         // 用户选择的属性ID
            "product"          => "required|integer|min:1",         // 商品ID
            "amount"           => "required|integer|min:1",         // 数量
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Goods::purchase($this->validated);

        return $this->json($response);
    }


    public function checkProduct()
    {
        $rules = [
            "product"  => "required|json",
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Products::validateProducts($this->validated);

        return $this->json($response);
    }
}
