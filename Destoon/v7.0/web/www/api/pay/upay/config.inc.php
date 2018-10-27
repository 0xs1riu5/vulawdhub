<?php
defined('IN_DESTOON') or exit('Access Denied');
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径 acp_prod_sign.pfx
define('SDK_SIGN_CERT_PATH', DT_ROOT.'/api/pay/'.$bank.'/'.($PAY[$bank]['cert'] ? $PAY[$bank]['cert'] : 'zhengshu.pfx'));

// 签名证书密码
define('SDK_SIGN_CERT_PWD', $PAY[$bank]['keycode']);

// 密码加密证书（这条一般用不到的请随便配）
define('SDK_ENCRYPT_CERT_PATH', DT_ROOT.'/api/pay/'.$bank.'/acp_prod_enc.cer');

// 验签证书路径（请配到文件夹，不要配到具体文件）
define('SDK_VERIFY_CERT_DIR', DT_ROOT.'/api/pay/'.$bank.'/');

// 前台请求地址
define('SDK_FRONT_TRANS_URL', 'https://gateway.95516.com/gateway/api/frontTransReq.do');

// 后台请求地址
define('SDK_BACK_TRANS_URL', 'https://gateway.95516.com/gateway/api/backTransReq.do');

// 批量交易
define('SDK_BATCH_TRANS_URL', 'https://gateway.95516.com/gateway/api/batchTrans.do');

//单笔查询请求地址
define('SDK_SINGLE_QUERY_URL', 'https://gateway.95516.com/gateway/api/queryTrans.do');

//文件传输请求地址
define('SDK_FILE_QUERY_URL', 'https://filedownload.95516.com/');

//有卡交易地址
define('SDK_Card_Request_Url', 'https://gateway.95516.com/gateway/api/cardTransReq.do');

//App交易地址
define('SDK_App_Request_Url', 'https://gateway.95516.com/gateway/api/appTransReq.do');

// 前台通知地址 (商户自行配置通知地址)
define('SDK_FRONT_NOTIFY_URL', $receive_url);

// 后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
define('SDK_BACK_NOTIFY_URL', DT_PATH.'api/pay/'.$bank.'/'.($PAY[$bank]['notify'] ? $PAY[$bank]['notify'] : 'notify.php'));

//文件下载目录 
define('SDK_FILE_DOWN_PATH', DT_ROOT.'/api/pay/'.$bank.'/file/');

//日志 目录 
define('SDK_LOG_FILE_PATH', DT_ROOT.'/api/pay/'.$bank.'/logs/');

//日志级别，关掉的话改PhpLog::OFF
define('SDK_LOG_LEVEL', 'PhpLog::OFF');


/** 以下缴费产品使用，其余产品用不到，无视即可 */
// 前台请求地址
define('JF_SDK_FRONT_TRANS_URL', 'https://gateway.95516.com/jiaofei/api/frontTransReq.do');
// 后台请求地址
define('JF_SDK_BACK_TRANS_URL', 'https://gateway.95516.com/jiaofei/api/backTransReq.do');
// 单笔查询请求地址
define('JF_SDK_SINGLE_QUERY_URL', 'https://gateway.95516.com/jiaofei/api/queryTrans.do');
// 有卡交易地址
define('JF_SDK_CARD_TRANS_URL', 'https://gateway.95516.com/jiaofei/api/cardTransReq.do');
// App交易地址
define('JF_SDK_APP_TRANS_URL', 'https://gateway.95516.com/jiaofei/api/appTransReq.do');

?>