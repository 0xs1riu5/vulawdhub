<?php

namespace Addons\CheckIn\Model;
use Think\Model;

/**
 * Class CheckInModel 签到模型
 * @package Addons\CheckIn\Model
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class CheckInModel extends Model{
    protected $tableName = 'checkin';
    public function getCheck($uid){
        $time = get_some_day(0);
        $res = S('check_in_'.$uid.'_'.$time);
        if(empty($res)){
            $res = $this->where(array('uid'=>$uid,'create_time'=>array('egt',$time)))->find();
            $check = query_user(array('con_check','total_check'),$uid);
            $res = array_merge($res,$check);
            S('check_in_'.$uid.'_'.$time,$res,60*60*24);
        }
        return $res;
    }

    public function addCheck($uid){
        $data['uid'] = $uid;
        $data['create_time'] = time();
        return $this->add($data);
    }

    public function resetConCheck()
    {
        $memberModel = D('Member');
        $time = get_some_day(0);
        $time_yesterday = get_some_day(1);
        $users = $memberModel->where(array('con_check' => array('gt', 0)))->field('uid')->select();
        foreach($users as $val) {
            $check = $this->where(array('uid' => $val['uid'], 'create_time' => array('between', array($time_yesterday, $time))))->find();
            if(!$check) {
                $memberModel->where(array('uid' => $val['uid']))->setField('con_check', 0);
            }
        }
    }

    public function getRank($type){
        $time = get_some_day(0);
        $time_yesterday = get_some_day(1);
        $memberModel = D('Member');
        switch($type){
            case 'today' :
                $list = $this->where(array('create_time'=>array('egt',$time)))->order('create_time asc')->limit(5)->select();
                break;
            case 'con' :
                $uids = $this->where(array('create_time'=>array('egt',$time_yesterday)))->field('uid')->select();
                $uids = getSubByKey($uids,'uid');
                $list = $memberModel ->where(array('uid'=>array('in',$uids)))->field('uid,con_check')->order('con_check desc,uid asc')->limit(5)->select();
                break;
            case 'total' :
                $list = $memberModel ->field('uid,total_check')->order('total_check desc,uid asc')->limit(5)->select();
                break;
        }

        foreach($list as &$v){
            $v['user'] = query_user(array('avatar32','avatar64','space_url', 'nickname', 'uid',), $v['uid']);
        }
        unset($v);
        return $list;
    }

}
