<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:40
 */

namespace Ucenter\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        $uid = intval($_REQUEST['uid']) ? intval($_REQUEST['uid']) : is_login();
        if (!$uid) {
            $this->error(L('_ERROR_NEED_LOGIN_'));
        }
        $this->assign('uid', $uid);
        $this->mid = is_login();
    }

    protected function defaultTabHash($tabHash)
    {
        $tabHash = op_t($_REQUEST['tabHash']) ?  op_t($_REQUEST['tabHash']): $tabHash;
        $this->assign('tabHash', $tabHash);
    }

    protected function getCall($uid)
    {
        if ($uid == is_login()) {
            return L('_I_');
        } else {
            $apiProfile = callApi('User/getProfile', array($uid));
            return $apiProfile['sex'] == 'm' ? L('_HE_') : L('_SHE_');
        }
    }

    protected function ensureApiSuccess($result)
    {
        if (!$result['success']) {
            $this->error($result['message'], $result['url']);
        }
    }

    protected function requireLogin()
    {
        if (!is_login()) {
            $this->error(L('_ERROR_MUST_LOGIN_'));
        }
    }
}