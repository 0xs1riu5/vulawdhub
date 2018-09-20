<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-26
 * Time: 下午4:29
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */

namespace Common\Model;

use Think\Model;

class VerifyModel extends Model
{
    protected $tableName = 'verify';
    protected $_auto = array(array('create_time', NOW_TIME, self::MODEL_INSERT));



    public function addVerify($account, $type, $uid = 0,$check_verify = 1)
    {


        if($check_verify){
            $aVerify = I('post.verify', '', 'text');
            if (empty($aVerify)) {
                $this->error = '验证码不能为空';
                return false;
            }

            $verify_id = $type=='email'? 3 : 2;
            $verify = new \Think\Verify();
            if (! $verify->check($aVerify,$verify_id)) {
                $this->error =  '验证码验证错误~';
                return false;
            }
        }


        $return = check_action_limit('send_verify', 'Ucenter',0, 1, false);//通过行为限制在全站层面防止频繁发送验证码
        if ($return && !$return['state']) {
            $this->error = $return['info'];
            return false;
        }
        action_log('send_verify', 'Ucenter',-1,1);

        
        $uid = $uid ? $uid : is_login();
        if ($type == 'mobile' || (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 2 && $type == 'email')) {
            $verify = create_rand(6, 'num');
        } else {
            $verify = create_rand(32);
        }
        $this->where(array('account' => $account, 'type' => $type))->delete();
        $data['verify'] = $verify;
        $data['account'] = $account;
        $data['type'] = $type;
        $data['uid'] = $uid;
        $data = $this->create($data);
        $res = $this->add($data);
        if (!$res) {
            $this->error = '';
            return false;
        }
        return $verify;
    }

    public function getVerify($id)
    {
        $verify = $this->where(array('id' => $id))->getField('verify');
        return $verify;
    }

    public function checkVerify($account, $type, $verify, $uid)
    {
        $verify1 = $this->where(array('account' => $account, 'type' => $type, 'verify' => $verify, 'uid' => $uid))->select();
        if (!$verify1) {
            return false;
        }

        $this->where(array('account' => $account, 'type' => $type))->delete();
        //$this->where('create_time <= '.get_some_day(1))->delete();

        return true;
    }

}















