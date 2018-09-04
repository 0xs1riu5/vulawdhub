<?php

/**
 * ECSHOP LICENSE 相关函数类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_certificate.php 16336 2009-06-24 07:09:13Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class certificate
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct(){
        include_once(ROOT_PATH."admin/includes/oauth/oauth2.php");
        $openapi_key = array('key'=>OPENAPI_KEY,'secret'=>OPENAPI_SECRET,'site'=>OPENAPI_SITE,'oauth'=>OPENAPI_OAUTH);
        $openapi_key_old = array('key'=>OPENAPI_KEY_OLD,'secret'=>OPENAPI_SECRET_OLD,'site'=>OPENAPI_SITE,'oauth'=>OPENAPI_OAUTH);
        $this->oauth = isset($_COOKIE['use_oldkey'])?new oauth2($openapi_key):new oauth2($openapi_key_old);
        include_once(ROOT_PATH."includes/cls_transport.php");
        $this->transport = new transport();
    }

    /**
     * 获得网店 certificate 信息
     *
     * @access  public
     *
     * @return  array
     */
    function get_shop_certificate()
    {
        // 取出网店 certificate
        $sql = "select code,value from ".$GLOBALS['ecs']->table('shop_config')." where code='certificate'";
        $certificate_info = $GLOBALS['db']->getRow($sql);
        $certificate = unserialize($certificate_info['value']);
        return $certificate;
    }

    /**
    * 设置 certificate 信息
    */
    function set_shop_certificate($data){
        $certificate = $this->get_shop_certificate();
        $codes = array('certificate_id','token','node_id','passport_uid','use_yunqi_login','yunqi_code','use_yunqi_authority','yunqiexp_active');
        foreach($codes as $k=>$code){
            if($data[$code]){
                    $certificate[$code] = $data[$code];
            }
        }
        $sql = "select code,value from ".$GLOBALS['ecs']->table('shop_config')." where code ='certificate'";
        $row = $GLOBALS['db']->getRow($sql);
        if($row){
            $sql = "UPDATE ".$GLOBALS['ecs']->table('shop_config')." set value='".serialize($certificate)."' where code='certificate'";
        }else{
            $sql = "insert into ".$GLOBALS['ecs']->table('shop_config')." set parent_id=2,code='certificate',type='hidden',value='".serialize($certificate)."'";
        }
        $GLOBALS['db']->query($sql);
        return true;
    }

    /**
    * 功能： 设置云起收银账号
    * @param  array $data
    * @return bool
    */
    function set_yunqi_account($data){
        $data['status'] = true;
        $sql = "insert into ".$GLOBALS['ecs']->table('shop_config')." set parent_id=2,code='yunqi_account',type='hidden',value='".serialize($data)."'";
        $GLOBALS['db']->query($sql,SILENT);
    }

    /**
    * 功能： 获取云起收银账号
    * @return array
    */
    function get_yunqi_account(){
        $sql = "select value from ".$GLOBALS['ecs']->table('shop_config')." where code='yunqi_account'";
        $row = $GLOBALS['db']->getOne($sql);
        return $row?unserialize($row):false;
    }

    /**
    * 功能： 删除证书
    * @return array
    */
    function delete_cert(&$msg){
        $sql = "select passport_uid from ".$GLOBALS['ecs']->table('admin_user')." where passport_uid is not null or passport_uid!=''";
        $passport_uid = $GLOBALS['db']->getOne($sql);
        if(!$passport_uid || $passport_uid!=$_SESSION['admin_name']){
            $msg = '请使用激活的云起账号登录后再进行删除操作';
            return false;
        }
        $delete_sql = "delete from ".$GLOBALS['ecs']->table('shop_config')." where code in ('certificate','snlist','yunqi_account')";
        $user_delete = "delete from ".$GLOBALS['ecs']->table('admin_user')." where user_name='".$passport_uid."'";
        $GLOBALS['db']->query($delete_sql);
        return $GLOBALS['db']->query($user_delete);
    }

    /**
     * 功能：生成certi_ac验证字段
     * @param   string     POST传递参数
     * @param   string     证书token
     * @return  string
     */
    function make_shopex_ac($post_params, $token='')
    {
        if (!is_array($post_params))
        {
            return;
        }
        ksort($post_params);
        if($token==''){
            $certificate = $this->get_shop_certificate();
            $token = $certificate['token'];
        }
        $str = '';
        foreach($post_params as $key=>$value){
            if($key != 'certi_ac')
            {
                $str .= $value;
            }
        }
        return md5($str . $token);
    }

    /**
     * 功能：oauth根据token获取证书
     *
     * @param   string     $token
     * @return  array
     */
     function get_oauth_certificate($token){
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/auth/license.add';
        $base_url = $GLOBALS['ecs']->url();
        $params['certi_url'] = $base_url;
        $params['certi_session'] = STORE_KEY;
        $params['certi_validate_url'] = $base_url."yunqi_check.php?type=validate";
        $params['shop_version'] = '1.0';
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        return $response;
     }

     /**
     * 功能：oauth根据token验证证书
     *
     * @param   string     $token
     * @return  array
     */
     function check_oauth_certificate($token){
        $type = OAUTH_API_PATH.'/auth/license.check';
        $certificate = $this->get_shop_certificate();
        $params['license_id'] = $certificate['certificate_id'];
        $params['certi_url'] = $GLOBALS['ecs']->url();
        $params['certi_session'] = STORE_KEY;
        $params['ac'] = $this->make_shopex_ac($params,$certificate['token']);
        $rall = $this->oauth->request($token)->post($type,$params);
        $response = $rall->parsed();
        return $response;
     }

    /**
    * 功能：oauth根据token获取物流和短信的永久token
    *
    * @param   strint     $token
    * @return  array
    */
    function get_yunqi_code($token){
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/auth/auth.gettoken';
        $params['product_code'] = PRODUCT_CODE;
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        return $response;
    }

    /**
    * 功能：oauth获取token
    *
    * @param   string     $code
    * @return  string
    */
    function get_token($code){
        return $this->oauth->get_token($code);
    }

    function logout_url($callback=''){
        !$callback and $callback = $GLOBALS['ecs']->url()."admin/privilege.php?act=logout&type=yunqi";
        return $this->oauth->logout_url($callback);
    }

     /**
     * 功能：oauth的登录地址
     *
     * @param   string     $callback
     * @return  string
     */
     function get_authorize_url($callback){
        return $this->oauth->authorize_url($callback)."&view=auth_ecshop";
     }

    /**
     * 功能：中心授权地址
     *
     * @return  string
     */
    function get_authority_url($single_page='detail'){
        $certificate = $this->get_shop_certificate();     
        $params = array(
            'license_id' => $certificate['certificate_id'],
            'client_id' => OPENAPI_KEY
        );
        $ac = $this->make_shopex_ac($params,$certificate['token']);
        $url = AUTH_USER_URL.'/?c=auth&m='.$single_page.'&license_id='.$certificate['certificate_id'].'&ac='.$ac.'&client_id='.OPENAPI_KEY;
        return $url;
    }

    /**
    * 功能：矩阵申请绑定节点接口
    *
    * @param   array     $params   
    * @param   string    $node_type 绑定类型
    * @return  array
    */
    function applyNodeBind($params,$node_type='shopex'){
        $base_url = $GLOBALS['ecs']->url();
        $post = array(
                'app'=>'app.applyNodeBind',
                'node_id'=>$params['node_id'],
                'from_certi_id'=>$params['certi_id'],
                'callback'=>$base_url."matrix_callback.php",
                'api_url'=>$base_url."api.php",
                'node_type'=>$node_type,
                'to_node'=>$params['to_node'],
                'to_token'=>$params['to_token'],
                'shop_name'=>$params['shop_name']
            );
        $post['certi_ac'] = $this->make_shopex_ac($post,$params['token']);
        return $this->read_shopex_applyNodeBind($post);
    }

    /**
    * 功能：请求矩阵
    *
    * @param   array     $post
    * @return  array
    */
    function read_shopex_applyNodeBind($post){
        $url = MATRIX_HOST."/api.php";
        $response = $this->transport->request($url,$post);
        return json_decode($response['body'],1);
    }

    /**
    * 功能：oauth 获取云起开通的产品列表
    *
    * @param   string     $token
    * @param   array      $params
    * @return  array
    */
    function getsnlistoauth($token,$params){
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/online/getsnlistoauth';
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        return $response;
    }

    /**
    * 功能：保存云起开通的产品列表
    *
    * @param   array     $data
    */
    function save_snlist($data){
        foreach($data as $value){
            $_data[] = $value['goods_code'];
        }
        $_data['time'] = date("Y-m-d",time());
        $sql = "insert into ".$GLOBALS['ecs']->table('shop_config')." set parent_id=2,code='snlist',type='hidden',value='".json_encode($_data)."'";
        $GLOBALS['db']->query($sql,SILENT);
    }

    function get_snlist(){
        $row = $this->shop_config('snlist');
        return $row['value'];
    }

    function shop_config($code){
        if(is_array($code)){
            $where  =  " where code in (".implode(',', $code).")";
        }else{
            $where  = " where code = '".$code."'";
        }
        $sql = "select code,value from ".$GLOBALS['ecs']->table('shop_config').$where;
        if(is_array($code)){
            return $GLOBALS['db']->getAll($sql);
        }else{
            return $GLOBALS['db']->getRow($sql);
        }
    }

    /**
    * 功能：检测是否开通云起产品
    *
    * @param   string     $goods_name  产品名：erp
     * @return  bool
    */
    function is_open_sn($goods_name){
        $sql = "select `value` from ".$GLOBALS['ecs']->table('shop_config')." where code='snlist'";
        $row = $GLOBALS['db']->getRow($sql);
        if(empty($row)) return false;
        $snlist = json_decode($row['value'],1);

        $sql = "select `value` from ".$GLOBALS['ecs']->table('shop_config')." where code='snlist_code'";
        $row = $GLOBALS['db']->getRow($sql);
        if(empty($row)) return false;
        $snlist_code = json_decode($row['value'],1);

        if(in_array($snlist_code[$goods_name],$snlist)){
            return true;
        }
        return false;
    }


    /**
     * 功能：是否绑定检测云起产品
     *
     * @param   string     $name  产品名or绑定类型
     * @return  bool
     */
    function is_bind_sn($name,$type='bind_type'){
        $sql = "select `value` from ".$GLOBALS['ecs']->table('shop_config')." where code='bind_list'";
        $row = $GLOBALS['db']->getRow($sql);
        if(empty($row)) return false;
        $bind_list=json_decode($row['value'],1);
        $bind_type = $name;
        if($type=='goods_name') $bind_type = $this->bind_sn($goods_name);
        if(in_array($bind_type,$bind_list)){
            return true;
        }
        return false;
    }

    /**
     * 功能：获取产品对应的矩阵绑定类型
     *
     * @param   string     $goods_name  产品名：erp
     * @return  string     bind_type 矩阵绑定类型
     */
    function bind_sn($goods_name){
        $bind_sn = array(
                'taoda'=>'taodali',
                'erp'=>'ecos.ome',
                'crm'=>'ecos.taocrm'
            );
        return $bind_sn[$goods_name];
    }

    function oauth_set_callback($code,&$res){
        $res = $this->get_token($code);
        if($res['token'] and $res['params']){
            if (isset($res['params']['data']) && $res['params']['data']) {
                foreach ($res['params']['data'] as $d_key => $d_value) {
                    $res['params'][$d_key] = $d_value;
                }
                unset($res['params']['data']);
            }
            include_once(ROOT_PATH.'includes/lib_passport.php');
            $result = set_yunqi_passport($res['params']['passport_uid']);
            $this->set_shop_certificate(array('use_yunqi_login'=>true));
            $this->check_certi($res);
            return true;
        }
    }

    /**
     * 功能：检查是否有证书，获取开通的产品列表，获取短信物流token
     */
    function check_certi($res){
        //检查证书
        $certificate = $this->get_shop_certificate();
        //获取证书,设置证书
        if((!$certificate['certificate_id'] || !$certificate['token'] || !$certificate['node_id']) && $_SERVER['HTTP_HOST']!='localhost'){
            $response = $this->get_oauth_certificate($res['token']);
            $response['status']=='success' and $rs = $this->set_shop_certificate($response['data']);
        }
        //设置passport_uid
        if(!$certificate['passport_uid']){
            $data['passport_uid'] = $res['params']['passport_uid'];
            $this->set_shop_certificate($data); 
        }
        //获取云起开通的产品列表
        $snlist = $this->get_snlist();
        if(!isset($snlist['time']) ||$snlist['time'] != date("Y-m-d",time())){
            $result = $this->getsnlistoauth($res['token'],array());
            $result['status']=='success' and $this->save_snlist($result['data']);
        }
        //end
        //获取短信物流token
        if(!$certificate['yunqi_code']){
            $code_result = $this->get_yunqi_code($res['token']);
            $code_result['status']=='success' and $this->set_shop_certificate(array('yunqi_code'=>$code_result['data']['token']));
        }
        //获取短信物流token end
        //激活云起物流
        if(!$certificate['yunqiexp_active']){
            $yunqiexp_result = $this->yqexp_exp_active();
            $yunqiexp_result['status']=='success' and $this->set_shop_certificate(array('yunqiexp_active'=>true));
        }
        //激活云起物流 end
        //获取云起收银账号
        $yunqi_account = $this->get_yunqi_account();
        if(!$yunqi_account || !$yunqi_account['status']){
            $yqaccount_result = $this->yqaccount_appget();
            $yqaccount_result['status']=='success' and $this->set_yunqi_account(array('appkey'=>$yqaccount_result['data']['appkey'],'appsecret'=>$yqaccount_result['data']['appsecret'],'status'=>true));
            //安装云起收银
            $this->install_yqpayment();
        }
        //获取云起收银账号 end
    }

    function install_yqpayment(){
        include_once(ROOT_PATH.'includes/lib_payment.php');
        $payment = get_payment('yunqi');
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('payment') . " WHERE pay_code = 'yunqi'";

        if ($GLOBALS['db']->GetOne($sql) > 0)
        {
            /* 该支付方式已经安装过, 将该支付方式的状态设置为 enable */
            $sql = "UPDATE " . $GLOBALS['ecs']->table('payment') .
                   "SET pay_name = '".$payment['pay_name']."'," .
                   "    pay_desc = '".$payment['pay_desc']."'," .
                   "    pay_config = '".$payment['pay_config']."'," .
                   "    pay_fee    =  '0', ".
                   "    enabled = '1' " .
                   "WHERE pay_code = 'yunqi' LIMIT 1";
            $GLOBALS['db']->query($sql);
        }
        else
        {
            $payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/yunqi.php';
            include_once($payment_lang);
            $payment['pay_name'] = $GLOBALS['_LANG']['yunqi'];
            $payment['pay_desc'] = $GLOBALS['_LANG']['yunqi_desc'];
            $payment['pay_config'] = serialize(array());
            /* 该支付方式没有安装过, 将该支付方式的信息添加到数据库 */
            $sql = "INSERT INTO " . $GLOBALS['ecs']->table('payment') . " (pay_code, pay_name, pay_desc, pay_config, is_cod, pay_fee, enabled, is_online)" .
                   "VALUES ('yunqi', '".$payment['pay_name']."', '".$payment['pay_desc']."', '".$payment['pay_config']."', '0', '0', 1, '1')";
            $GLOBALS['db']->query($sql);
        }
    }

    /**
    * 功能：云起物流 查询
    */
    function yqexp_exp_get($data){
        if($this->open_logistics_trace()===false) return array();
        $certificate = $this->get_shop_certificate();
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/yqexp/exp/get';
        $params['shopexid'] = $certificate['passport_uid'];
        $params['token'] = $certificate['yunqi_code'];
        $params['product_code'] = PRODUCT_CODE;
        $params['appid'] = 'kdniao';
        $params['method'] = 'express.explogistics';
        $params['expno'] = $data['expno'];
        $params['expcode'] = $data['expcode'];
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        if($response['status'] == 'error' and $response['code'] == '400004'){//token失效,删除token
            delete_yunqi_code();
            $sql = "update ".$GLOBALS['ecs']->table('shop_config')." set value='0' where code='logistics_trace'";
            $GLOBALS['db']->query($sql);
        }
        if(EC_CHARSET != 'utf-8' and $response['data']['Traces']){
            foreach ($response['data']['Traces'] as $key => $value) {
                $response['data']['Traces'][$key]['AcceptStation'] = iconv('utf-8', EC_CHARSET, $value['AcceptStation']);
            }
        }
        return $response['status'] == 'success'?$response['data']['Traces']:array();
    }

    /**
    * 功能：云起物流 激活
    */
    function yqexp_exp_active(){
        $certificate = $this->get_shop_certificate();
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/yqexp/exp/active';
        $params['shopexid'] = $certificate['passport_uid'];
        $params['token'] = $certificate['yunqi_code'];
        $params['product_code'] = PRODUCT_CODE;
        $params['appid'] = 'kdniao';
        $params['method'] = 'express.expactive';
        $params['siteurl'] = $GLOBALS['ecs']->url();
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        return $response;
    }

    /**
    * 功能：云起收银 获取账号
    */
    function yqaccount_appget(){
        $certificate = $this->get_shop_certificate();
        $r = $this->oauth->request()->get('api/platform/timestamp');
        $time = $r->parsed();
        $type = OAUTH_API_PATH.'/yqaccount/passport/appget';
        $params['shopexid'] = $certificate['passport_uid'];
        $params['token'] = $certificate['yunqi_code'];
        $params['app'] = 'teegon';
        $rall = $this->oauth->request($token)->post($type,$params,$time);
        $response = $rall->parsed();
        return $response; 
    }

    /**
    * 功能：绑定矩阵物流追踪
    * @return  bool
    */
    function open_logistics_trace(){
        return get_certificate_info('yunqiexp_active');
    }

    function get_push_count($type){
        $sql = "select * from ".$GLOBALS['ecs']->table('shop_config')." where code='".$type."'";
        $row = $GLOBALS['db']->getRow($sql);
        if($row){
            return $row['value'];
        }else{
            return '0';
        }
    }

    function crm_get_count($type){
        if($type == 'order'){
            $sql = "select count(*) from ". $GLOBALS['ecs']->table('order_info');
        }else{
            $sql = "select count(*) from ". $GLOBALS['ecs']->table('users');
        }
        $row = $GLOBALS['db']->getRow($sql);
        return $row['count(*)'];
    }



}
?>