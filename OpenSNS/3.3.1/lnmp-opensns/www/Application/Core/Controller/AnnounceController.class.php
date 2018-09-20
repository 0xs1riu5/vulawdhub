<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-23
 * Time: 下午4:01
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Core\Controller;


use Think\Controller;

class AnnounceController extends Controller{

    /**
     * 设置公告已确认收到
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setArrive()
    {
        $aAnnounceId=I('post.announce_id',0,'intval');
        if(!$aAnnounceId){
            return false;
        }
        $map['uid']=is_login();
        $map['announce_id']=$aAnnounceId;
        $announceArriveModel=D('Common/AnnounceArrive');
        if(!$announceArriveModel->getData($map)){
            $data=$map;
            $data['create_time']=time();
            $announceArriveModel->addData($data);
        }
        return true;
    }

    /**
     * 发布公告后，给所有用户发送公告消息
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function sendAnnounceMessage()
    {
        $aToken = I('get.token','','text');
        $aTime = I('get.time',0,'intval');

        if($aTime + 30  < time()){
            exit('Error');
        }
        if($aToken != md5($aTime.C('DATA_AUTH_KEY'))){
            exit('Error');
        }
        ignore_user_abort(true); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
        set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去

        $aId=I('get.announce_id',0,'intval');

        $announceModel=D('Announce');
        $announce=$announceModel->getData($aId);
        if($announce){
            $memberModel=M('Member');
            $uids=$memberModel->where(array('status'=>1))->field('uid')->select();
            $uids=array_column($uids,'uid');

            $content=array(
                'keyword1'=>$announce['content'],
                'keyword2'=>$announce['create_time'],
            );
            $messageModel=D('Message');
            $messageModel->sendALotOfMessageWithoutCheckSelf($uids,$announce['title'],$content,$announce['link'],null,-1,'Common_announce','Common_announce');
        }
        return true;
    }
} 