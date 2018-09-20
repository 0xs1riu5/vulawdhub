<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/11/8
 * Time: 15:59
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Ucenter\Model;


use Think\Model;

class AttestModel extends Model
{
    public function editData($data)
    {
        $data['update_time']=time();
        if($data['id']){
            $res=$this->save($data);
        }else{
            $data['create_time']=time();
            $res=$this->add($data);
        }
        return $res;
    }

    public function getData($map)
    {
        $data=$this->where($map)->find();
        return $data;
    }

    public function getListPage($map,$page,$order='create_time desc',$r=10)
    {
        $totalCount=$this->where($map)->count();
        if($totalCount){
            $list=$this->where($map)->page($page,$r)->order($order)->select();
        }
        return array($list,$totalCount);
    }

    public function getListByMap($map)
    {
        $list=$this->where($map)->select();
        return $list;
    }

    public function deleteApply($id)
    {
        $map['uid']=is_login();
        $map['id']=$id;
        $res=$this->where($map)->setField('status',-1);
        return $res;
    }

    public function getListLimit()
    {
        $map['status']=1;
        $list=$this->where($map)->order('create_time desc')->limit(4)->select();
        foreach ($list as &$val){
            $val['user']=query_user(array('uid','avatar_html32','attest','nickname','space_url','signature'),$val['uid']);
        }
        unset($val);
        return $list;
    }
}