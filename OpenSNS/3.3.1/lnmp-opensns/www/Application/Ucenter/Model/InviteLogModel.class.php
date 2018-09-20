<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-27
 * Time: 下午4:45
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class InviteLogModel extends Model
{

    /**
     * 添加邀请注册成功日志
     * @param array $data
     * @param int $role
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function addData($data=array(),$role=0)
    {
        $inviter_user=get_nickname($data['inviter_id']);
        $user=get_nickname($data['uid']);
        $role=D('Role')->where(array('id'=>$role))->find();
        $data['content']="{$user} 接受了 {$inviter_user} 的邀请，注册了 {$role['title']} 身份。";
        $data['create_time']=time();

        $result=$this->add($data);
        return $result;
    }

    /**
     * 分页获取邀请注册日志列表
     * @param int $page
     * @param int $r
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getList($page=1,$r=20)
    {
        $totalCount=$this->count();
        if($totalCount){
            $list=$this->page($page,$r)->order('create_time desc')->select();
        }
        $list=$this->_initSelectData($list);
        return array($list,$totalCount);
    }

    /**
     * 初始化查询数据
     * @param array $list
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initSelectData($list=array())
    {
        $inviteTypeModel=D('Ucenter/InviteType');
        foreach($list as &$val){
            $inviteType=$inviteTypeModel->getSimpleData(array('id'=>$val['invite_type']));
            $val['invite_type_title']=$inviteType['title'];
            $val['user']=get_nickname($val['uid']);
            $val['user']='['.$val['uid'].']'.$val['user'];
            $val['inviter']=get_nickname($val['inviter_id']);
            $val['inviter']='['.$val['inviter_id'].']'.$val['inviter'];
        }
        unset($val);
        return $list;
    }
} 