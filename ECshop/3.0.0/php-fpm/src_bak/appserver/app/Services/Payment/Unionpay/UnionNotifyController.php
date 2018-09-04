<?php
//
namespace App\Services\Payment\Unionpay;

//---------------------------------------------------------
//即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------


// use \Input;
// use \Message;
use DB;
use Log;
use Request;
use Config;
use Response;
use ResponseHandler;
use RequestHandler;
use App\Http\Requests;
use Illuminate\Validation\Validator;
use App\Http\Controllers\Controller;
use \Illuminate\Database\Eloquent\Collection as Collection;
use \Trade;
use \Callback;
use \Payment;
use \Union;

class UnionNotifyController extends BaseController
{
    public function postNotify()
    {
        $unionpay = new Union;
        $unionpay->config = Config::get('unionpay');; //上面给出的配置参数
        $postStr = $_POST;
        $unionpay->params = $_POST;
        
        if(!$unionpay->verifySign()) {
            echo 'fail';
            exit;
        }
        if($unionpay->params['respCode'] == '00') {
            $out_trade_no = $unionpay->params['queryId'];
            $order_sn = $unionpay->params['orderId'];

           //业务代码
            $this->pay($order_sn,$out_trade_no);

            $this->callback(json_encode($unionpay->params),$order_sn,$out_trade_no);
        }
        echo 'success';
    }


    /**
     * 支付后后台通知，根据回调修改订单状态
     * @param  [type] $order_sn [系统订单号]
     * @param  [type] $transaction_id [银联订单号]
     */
    private function pay($order_sn,$transaction_id)
    {
        //修改订单状态
        $orders = Trade::where('sn', $order_sn)->firstOrFail();
        $orders->payment_type       = Payment::UNIONPAY;
        $orders->payment_time         =  time();
        $orders->payment_sn         = $transaction_id;
        $orders->paid_at            = time();
        $orders->status             = Trade::CONTRACT_PENDING;
        $orders->save();
       
        return $orders->id;
    }


    /**
     * [交易流水记录]
     * @param  [type]   $order_sn [系统订单号]
     * @param  [type]   $trade_no [银联交易号]
     * @return function           [交易流水记录]
     */
    private function callback($json,$order_sn,$trade_no)
    {
        $user_id = Trade::where('sn',$order_sn)->first()->user_id;
        $order_id = Trade::where('sn',$order_sn)->first()->id;
        
        $callback   = new Callback;
        $callback->order_id = $order_id;
        $callback->user_id = $user_id;
        $callback->payment_sn = $trade_no;
        $callback->source = Payment::UNIONPAY;
        $callback->callback_info = $json;

        $callback->save();
    }

}