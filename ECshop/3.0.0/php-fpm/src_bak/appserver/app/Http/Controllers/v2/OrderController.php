<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\v2\Order;
use App\Models\v2\Payment;
use App\Models\v2\Features;
class OrderController extends Controller
{
    /**
     * POST /ecapi.order.list
     */
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'per_page'        => 'required|integer|min:1',
            'status'          => 'integer|min:0',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Order::getList($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.order.get
     */
    public function view()
    {
        $rules = [
            'order' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Order::getInfo($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.order.confirm
     */
    public function confirm()
    {
        $rules = [
            'order' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Order::confirm($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.order.cancel
     */
    public function cancel()
    {
        $rules = [
            'order'  => 'required|integer|min:1',
            'reason' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Order::cancel($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.order.price
     */
    public function price()
    {
        $rules = [
            "shop"             => "integer|min:1",          // 店铺ID
            "consignee"        => "integer|min:1",          // 收货人ID
            "shipping"         => "integer|min:1",          // 快递ID
            "coupon"           => "string|min:1",           // 优惠券ID
            "cashgift"         => "string|min:1",           // 红包ID
            "score"            => "integer",                // 积分
            "order_product"    => "required|string",        // 商品id数组
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Order::price($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.order.review
     */
    public function review()
    {
        $rules = [
            'order' => 'required|integer|min:1',
            'review' => 'required|json',
            'is_anonymous' => 'required|integer|in:0,1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $items = json_decode($this->validated['review'], true);

        $items_rules = [
            '*.goods'   => 'required|integer|min:1',
            '*.grade'   => 'required|integer|in:1,2,3',
            '*.content' => 'string'
        ];

        if ($error = $this->customValidate($items, $items_rules)) {
            return $error;
        }

        $data = Order::review($this->validated, $items);
        return $this->json($data);
    }

    /**
     * POST /ecapi.payment.types.list
     */
    public function paymentList()
    {
        $rules = [
            'shop' => 'integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Payment::getList($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.payment.pay
     */
    public function pay()
    {
        $rules = [
            'order' => 'required|integer|min:1',
            'code' => 'required|string|in:alipay.app,wxpay.app,unionpay.app,cod.app,wxpay.web,teegon.wap,alipay.wap,wxpay.wxa,balance',            
            'openid' => 'required_if:code,wxpay.web|string',
            'channel' => 'string',
            'referer' => 'string',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        extract($this->validated);
        $payarr = ['alipay.app' => 'pay.alipay', 'wxpay.app' => 'pay.weixin', 'unionpay.app' => 'pay.unionpay', 'cod.app' => 'pay.cod','wxpay.web' => 'pay.wxweb','teegon.wap' => 'pay.teegon','alipay.wap' => 'pay.alipaywap','wxpay.wxa'=>'pay.wxa','balance'=>'balance'];

        // if($res = Features::check($payarr[$code]))
        // {
        //     return $this->json($res);
        // }

        $data = Payment::pay($this->validated);
        return $this->json($data);
    }

    /**
     * POST /order/notify/:code
     */
    public function notify($code)
    {
        Payment::notify($code);
    }

    /**
     * POST /ecapi.order.reason.list
     */
    public function reasonList()
    {
        return $this->json(Order::getReasonList());
    }

    /**
     * POST /ecapi.order.subtotal
     */
    public function subtotal()
    {
        return $this->json(Order::subtotal());
    }
}
