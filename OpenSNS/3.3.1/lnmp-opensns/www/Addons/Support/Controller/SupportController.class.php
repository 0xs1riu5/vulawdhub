<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 14-3-19
 * Time: 下午2:19
 */
namespace Addons\Support\Controller;

use Home\Controller\AddonsController;

class SupportController extends AddonsController
{


    /*
     *签到
     */

    public function doSupport()
    {
        if (!is_login()) {
            exit(json_encode(array('status' => 0, 'info' => '请登陆后再点赞。')));
        }
        $appname = I('POST.appname');
        $table = I('POST.table');
        $row = I('POST.row');
        $aJump = I('POST.jump');

        $message_uid = intval(I('POST.uid'));
        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $row;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {

            exit(json_encode(array('status' => 0, 'info' => '您已经赞过，不能再赞了。')));
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {

                $this->clearCache($support);

                $user = query_user(array('nickname'),get_uid());
                send_message($message_uid,$title = $user['nickname'] . '赞了您', '快去看看吧^……^！',  $aJump , array('id' => $row),-1,'Ucenter');
                exit(json_encode(array('status' => 1, 'info' => '感谢您的支持。')));
            } else {
                exit(json_encode(array('status' => 0, 'info' => '写入数据库失败。')));
            }

        }
    }

    /**
     * @param $support
     * @auth 陈一枭
     */
    private function clearCache($support)
    {
        D('Support')->clearCache($support['appname'], $support['table'], $support['row']);
    }
}

