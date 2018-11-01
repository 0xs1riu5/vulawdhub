<?php
/* *
 * 配置文件
 * 版本：3.3
 * 日期：2012-07-19
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
 * 提示：如何获取安全校验码和合作身份者id
 * 1.用您的签约支付宝账号登录支付宝网站(www.alipay.com)
 * 2.点击“商家服务”(https://b.alipay.com/order/myorder.htm)
 * 3.点击“查询合作者身份(pid)”、“查询安全校验码(key)”
	
 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
$cache_payway = cache::get('payway');
$payway = unserialize($cache_payway['alipay']['payway_config']);
//合作身份者id，以2088开头的16位纯数字
$alipay_config['partner']		= $payway['alipay_pid'];
//安全检验码，以数字和字母组成的32位字符
$alipay_config['key']			= $payway['alipay_key'];
//卖家支付宝帐户
$seller_email  = $payway['alipay_name'];
//商户订单号，商户网站订单系统中唯一订单号(必填)
$out_trade_no = $order['order_id'];
//订单名称(必填)
$subject = $order['order_name'];
//订单描述
$body = $order['order_text'];
//商品展示地址
$show_url = '';
//支付类型(必填)
$payment_type = '1';
//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//签名方式 不需修改
$alipay_config['sign_type']    = strtoupper('MD5');
//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');
//ca证书路径地址，用于curl中ssl校验，请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = "{$pe['path_root']}include/plugin/payway/alipay/cacert.pem";
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

/**************************双接口(担保交易)额外参数**************************/
//付款金额(必填)
$price = $order['order_productmoney'];
//商品数量(必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品)
$quantity = '1';
//物流费用(必填，即运费)
$logistics_fee = $order['order_wlmoney'];
//物流类型(必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）)
$logistics_type = 'EXPRESS';
//物流支付方式(必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）)
$logistics_payment = $order['order_wlmoney'] ? 'BUYER_PAY' : 'SELLER_PAY';
//收货人姓名(如：张三)
$receive_name = $order['user_tname'];
//收货人地址(如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号)
$receive_address = $order['user_address'];
//收货人邮编(如：123456)
$receive_zip = '';
//收货人电话(如：0571-88158090)
$receive_phone = $order['user_tel'];
//收货人手机(如：13312341234)
$receive_mobile = $order['user_phone'];
/***************************及时到账额外参数**************************/
//付款金额(必填)
$total_fee = $order['order_money'];
//防钓鱼时间戳(若要使用请调用类文件submit中的query_timestamp函数)
$anti_phishing_key = '';
//客户端的IP地址(非局域网的外网IP地址，如：221.0.0.1)
$exter_invoke_ip = '';
?>