<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;


class OrderAction extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'order_action';
    protected $primaryKey = 'action_id';
    public    $timestamps = false;

    protected $guarded = [];

    public static function toCreateOrUpdate($order_id, $order_status, $shipping_status, $pay_status, $action_note = '')
    {
        $uid = Order::where(['order_id' => $order_id])->value('user_id');

        $data = [
            'order_id' => $order_id,
            'action_user' => Member::where(['user_id' => $uid])->value('user_name'),
            'order_status' => $order_status,
            'shipping_status' => $shipping_status,
            'pay_status' => $pay_status,
            'action_place' => 0,
            'action_note' => $action_note,
            'log_time' => time(),
        ];

        self::updateOrCreate(['order_id' => $order_id, 'shipping_status' => $shipping_status, 'shipping_status' => $shipping_status, 'pay_status' => $pay_status], $data);
    }
}
