<?php
//
return [
    'token' => [
        'invalid' => 'Token 无效',
        'expired' => 'Token 过期'
    ],
    'sign' => [
        'invalid' => 'Sign 无效',
        'expired' => 'Sign 过期'
    ],
    'license' => [
        'invalid' => 'License Invalid',
        'unauthorized' => 'Service Unauthorized'
     ],
    'error' => [
        'unknown' => '未知错误',
        '404'     => '您请求的资源不存在',
        'unauthorized' => '没有权限',
        'request_encrypt' => '请求的参数加密错误'
    ],
    'member' => [
        'created'  => '账号创建成功',
        'exists'   => '用户名或邮箱已经存在',
        'password' => [
            'updated'   => '密码更新成功',
            'reset'      => '密码修改链接已发送至 :email',
            'old_password' => '旧密码错误',
        ],
        'failed'   => '用户名或密码错误',
        '404'      => '您输入的账号不存在',
        'mobile' => [
            '404'   => '手机号未注册',
            'exists'   => '手机号已经存在',
            'code' => [
                'error' => '无效的短信验证码'
            ],        
            'send' => [
                'error' => '短信验证码发送失败，请重试'
            ]
        ],
        'auth' => [
            'error' => 'OAuth授权失败'
        ],
        'account' => '推荐会员ID %s ( %s ) 注册送积分',
    ],
    'email' => [
        'error' => '您输入的邮箱不存在',
        'reset' => [
            'subject' => '密码找回邮件',
        ]
    ],
    'good' => [
        'not_alone'         => '不能单独销售',
        'off_sale'          => '商品已下架',
        'out_storage'       => '库存不足',
        'only_addon'        => '主商品不存在',
        'property'          => 'property格式不正确',
        'min_goods_amount'  => '商品总额未达到最低限购金额',
        'max_quality_limit' => '商品达到最大限购数量',
    ],
    'cart' => [
        'json_invalid' => 'json格式不正确',
        'no_goods' => '购物车中没有商品',
        'cart_goods_error' => '购物车中没有此商品',
        'property_error' => '商品属性不正确'
    ],

    'consignee' => [
        'region' => '区域有误',
        'not_found' => '请填写联系人地址',
    ],

    'products' => [
        'error' => '商品有误',
    ],

    'address' => [
        'error' => '收货地址有误',
    ],

    'shipping' => [
        'error' => '此地址不在配送范围内',
        '404' => '配送方式不存在'
    ],

    'score' => [
        'pay'         => '支付',
        'register'    => '注册赠送积分',
        'cancel'      => '取消',
        'order'       => '订单赠送积分',
    ],
    'order' => [
        'reviewed'         => '只能对订单评价一次',
    ],
    'config' => [
        'oauth' => [
            'wechat' => '微信登录参数配置有误',
	    'qq'     => 'QQ登录参数配置有误',
        ],
    ],
    'coupon' => [
        'error' => '优惠券无效'
    ],
    'cloud' => [
        'config' => '云服务配置无效'
    ],
    'teegon' => [
        'channel' => '请选择支付方式'
    ],

    'payment' => [
        'balance' => '余额不足'
    ],
    'account' => [
        'amount' => '您要申请提现的金额超过了您现有的余额，此操作将不可进行！',
        'process' => '此次操作失败，请返回重试！',
        'increment' => '增加',
        'decrement' => '减少',
        'cash' => '请输入大于0的数字',
    ],
    'affiliate' => [
        'intro' => [
            'separate_by_0' =>  '　　本网店为鼓励推荐新用户注册，现开展<b>推荐注册分成</b>活动，活动流程如下：

　　１、将本站提供给您的推荐代码，发送到论坛、博客上。
　　２、访问者点击链接，访问网店。
　　３、在访问者点击链接的 <b>%d%s</b> 内，若该访问者在本站注册，即认定该用户是您推荐的，您将获得等级积分 <b>%d</b> 的奖励 (当您的等级积分超过 <b>%d</b> 时，不再获得奖励)。
　　４、该用户今后在本站的一切消费，您均能获得一定比例的提成。目前实行的提成总额为订单金额的 <b>%s</b> 、积分的 <b>%s</b> ，分配给您、推荐您的人等，具体分配规则请参阅 <b>我推荐的会员</b>。
　　５、提成由管理员人工审核发放，请您耐心等待。
　　６、您可以通过分成明细来查看您的介绍、分成情况。',
        'separate_by_1' =>  '　　本网店为鼓励推荐新用户注册，现开展<b>推荐订单分成</b>活动，活动流程如下：

　　１、在浏览商品时，点击推荐此商品，获得推荐代码，将其发送到论坛、博客上。
　　２、访问者点击链接，访问网店。
　　３、在访问者点击链接的 <b>%d%s</b> 内，若该访问者在本站有订单，即认定该订单是您推荐的。
　　４、您将获得该订单金额的 <b>%s</b> 、积分的 <b>%s</b>的奖励。
　　５、提成由管理员人工审核发放，请您耐心等待。
　　６、您可以通过分成明细来查看您的介绍、分成情况。',
        ],
        'expire' => [
            'hour' => '小时',
            'day' => '天',
            'week' => '周',
        ],
    ],
];
