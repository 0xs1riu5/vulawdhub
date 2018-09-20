<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:40
 */

namespace Ucenter\Controller;

use Think\Controller;

class VerifyController extends Controller
{


    /**
     * sendVerify 发送验证码
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendVerify()
    {

        $aAccount = $cUsername = I('post.account', '', 'op_t');
        $aAccount=str_replace(array('"',"'",'`',',',')','(','='),'',$aAccount);//过滤防止被注入攻击

        $aType = I('post.type', '', 'op_t');
        $aType = $aType == 'mobile' ? 'mobile' : 'email';
        $aAction = I('post.action', 'config', 'op_t');

        if (!check_reg_type($aType)) {
            $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
            $this->error($str . L('_ERROR_OPTIONS_CLOSED_').L('_EXCLAMATION_'));
        }

        if (empty($aAccount)) {
            $this->error(L('_ERROR_ACCOUNT_CANNOT_EMPTY_'));
        }
        check_username($cUsername, $cEmail, $cMobile);
        $time = time();
        if($aType == 'mobile'){
            $resend_time =  modC('SMS_RESEND','60','USERCONFIG');
            if($time <= session('verify_time')+$resend_time ){
                $this->error(L('_ERROR_WAIT_1_').($resend_time-($time-session('verify_time'))).L('_ERROR_WAIT_2_'));
            }
        }


        if ($aType == 'email' && empty($cEmail)) {
            $this->error(L('_ERROR__EMAIL_'));
        }
        if ($aType == 'mobile' && empty($cMobile)) {
            $this->error(L('_ERROR_PHONE_'));
        }

        $checkIsExist = UCenterMember()->where(array($aType => $aAccount))->find();
        if ($checkIsExist) {
            $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
            $this->error(L('_ERROR_USED_1_') . $str . L('_ERROR_USED_2_').L('_EXCLAMATION_'));
        }

        $verify = D('Verify')->addVerify($aAccount, $aType);
        if (!$verify) {
            $error =  D('Verify')->getError();
            $this->error($error ? $error :L('_ERROR_FAIL_SEND_').L('_EXCLAMATION_'));
        }

        $res =  A(ucfirst($aAction))->doSendVerify($aAccount, $verify, $aType);
        if ($res === true) {
            if($aType == 'mobile'){
                session('verify_time',$time);
            }
            $this->success(L('_ERROR_SUCCESS_SEND_'));
        } else {
            $this->error($res);
        }

    }


    public function sendVerifyFindPsw()
    {

        $aAccount = $cUsername = I('post.account', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        $aType = $aType == 'mobile' ? 'mobile' : 'email';
        $aAction = I('post.action', 'config', 'op_t');

        if (!check_reg_type($aType)) {
            $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
            $this->error($str . L('_ERROR_OPTIONS_CLOSED_').L('_EXCLAMATION_'));
        }


        if (empty($aAccount)) {
            $this->error(L('_ERROR_ACCOUNT_CANNOT_EMPTY_'));
        }

        check_username($cUsername, $cEmail, $cMobile);
        $time = time();
        if($aType == 'mobile'){
            $resend_time =  modC('SMS_RESEND','60','USERCONFIG');
            if($time <= session('verify_time')+$resend_time ){
                $this->error(L('_ERROR_WAIT_1_').($resend_time-($time-session('verify_time'))).L('_ERROR_WAIT_2_'));
            }
        }


        if ($aType == 'email' && empty($cEmail)) {
            $this->error(L('_ERROR__EMAIL_'));
        }
        if ($aType == 'mobile' && empty($cMobile)) {
            $this->error(L('_ERROR_PHONE_'));
        }

        /*        $checkIsExist = UCenterMember()->where(array($aType => $aAccount))->find();
                if ($checkIsExist) {
                    $str = $aType == 'mobile' ? L('PHONE') : L('EMAIL');
                    $this->error(L('_ERROR_USED_1_') . $str . L('_ERROR_USED_2_').L('EXCLAMATION'));
                }*/
        $mobVerify=M('Verify')->where(array('type'=>$aType,'account'=>$aAccount))->find();
        if((time()-$mobVerify['create_time'])<60){
            $this->error('操作频繁，60秒可再次发送');
        }
        $verify = D('Verify')->addVerify($aAccount, $aType);
        if (!$verify) {
            $error =  D('Verify')->getError();
            $this->error($error ? $error :L('_ERROR_FAIL_SEND_').L('_EXCLAMATION_'));
        }

        $res =  A(ucfirst($aAction))->doSendVerify($aAccount, $verify, $aType);
        if ($res === true) {
            if($aType == 'mobile'){
                session('verify_time',$time);
            }
            $this->success(L('_ERROR_SUCCESS_SEND_'));
        } else {
            $this->error($res);
        }

    }

}