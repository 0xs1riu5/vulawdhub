<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 陈一枭
 * 创建日期: 6/9/14
 * 创建时间: 2:22 PM
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */

namespace Common\Model;

use Think\Model;
class TalkMessagePushModel extends Model{

    /**取得全部的推送消息
     * @return mixed
     * @auth 陈一枭
     */
    public function getAllPush(){
        $new_talks=$this->where(array('uid'=>get_uid(),'status'=>0))->select();

        foreach($new_talks as &$v){

            $message=D('TalkMessage')->find($v['source_id']);
            //$talk=D('Talk')->find($message['talk_id']);
            $v['talk_message']=$message;
        }
        unset($v);
        return $new_talks;
    }
}