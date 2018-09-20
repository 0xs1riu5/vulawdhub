<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-25
 * Time: 下午6:55
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class InviteUserInfoModel extends Model
{

    /**
     * 添加兑换邀请名额记录
     * @param int $type_id
     * @param int $num
     * @return bool|mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function addNum($type_id=0,$num=0)
    {
        $map['uid']=is_login();
        $map['invite_type']=$type_id;
        if($this->where($map)->count()){
            $res=$this->where($map)->setInc('num',$num);
        }else{
            $data['uid']=is_login();
            $data['invite_type']=$type_id;
            $data['num']=$num;
            $data['already_num']=0;
            $data['success_num']=0;
            $res=$this->add($data);
        }
        return $res;
    }

    /**
     * 降低可邀请名额，增加已邀请名额
     * @param int $type_id
     * @param int $num
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function decNum($type_id=0,$num=0){
        $map['uid']=is_login();
        $map['invite_type']=$type_id;
        $res=$this->where($map)->setDec('num',$num);//减少可邀请数目
        $this->where($map)->setInc('already_num',$num);//增加已邀请数目
        return $res;
    }

    /**
     * 保存数据
     * @param array $data
     * @param int $id
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function saveData($data=array(),$id=0)
    {
        $result=$this->where(array('id'=>$id))->save($data);
        return $result;
    }

    /**
     * 邀请成功后数据变更
     * @param int $type_id
     * @param int $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function addSuccessNum($type_id=0,$uid=0){
        $map['uid']=$uid;
        $map['invite_type']=$type_id;
        $res=$this->where($map)->setInc('success_num');//增加邀请成功数目
        return $res;
    }

    /**
     * 获取用户邀请信息
     * @param string $map
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getInfo($map='')
    {
        $data=$this->where($map)->find();
        return $data;
    }

    /**
     * 获取用户邀请信息列表
     * @param array $map
     * @param int $page
     * @param int $r
     * @param string $order
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getList($map=array(),$page=1,$r=20,$order='uid asc,invite_type asc')
    {
        if(count($map)){
            $totalCount=$this->where($map)->count();
            if($totalCount){
                $list=$this->where($map)->page($page,$r)->order($order)->select();
            }
        }else{
            $totalCount=$this->count();
            if($totalCount){
                $list=$this->page($page,$r)->order($order)->select();
            }
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
        }
        unset($val);
        return $list;
    }

    /**
     * 降低可邀请名额，增加已邀请名额
     * @param int $type_id
     * @param int $num
     * @return bool
     * @author 路飞<lf@ourstu.com>
     */
    public function decNumber($type_id=0,$num=0)
    {
        $map['uid'] = session('temp_login_uid');
        $map['invite_type'] = $type_id;
        $res = $this->where($map)->setDec('num',$num);//减少可邀请数目
        $this->where($map)->setInc('already_num',$num);//增加已邀请数目
        return $res;
    }
}