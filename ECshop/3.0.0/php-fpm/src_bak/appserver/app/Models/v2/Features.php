<?php
//
namespace App\Models\v2;
use App\Models\BaseModel;

class Features extends BaseModel {

    public static $features = [
        "invoice"          => true,  // 是否支持发票
        "cashgift"         => true,  // 是否支持红包
        "score"            => true,  // 是否支持积分
        "coupon"           => false,  // 是否支持优惠券
        "address.default"  => true,   // 是否支持默认地址
        'signin.qq'        => true,
        'signin.weibo'     => true,
        'signin.weixin'    => true,
        'signin.wxa'       => true,
        'signin.mobile'    => true,
        'signin.default'   => true,
        'share.qq'         => true,
        'share.weibo'      => true,
        'share.weixin'     => true,
        'pay.alipay'       => true,
        'pay.weixin'       => true,
        'pay.unionpay'     => true,
        'pay.wxweb'        => true,
        'pay.alipaywap'    => true, // 支付宝手机网站支付
        'pay.wxa'          => true,//小程序支付
        'balance'          => true,//余额支付
        'signup.mobile'    => true,
        'signup.default'   => true,
        'findpass.mobile'  => true,
        'findpass.default' => true,
        'logistics'        => true,
        'push'             => true,
        'qiniu'            => true,
        'statistics'       => true,
    ];

    public static function getList()
    {
        return self::formatFeature(self::$features);
    }

    public static function check($code)
    {
        if(self::getStatus($code) === false)
        {
            return self::formatError(self::UNAUTHORIZED, trans('message.error.unauthorized'));
        }
    }

    public static function getStatus($code)
    {
        $arr = self::formatFeature(self::$features);
        return isset($arr[$code]) ? $arr[$code] : null;
    }

    private static function formatFeature($data)
    {
        return $data;
    }

}
