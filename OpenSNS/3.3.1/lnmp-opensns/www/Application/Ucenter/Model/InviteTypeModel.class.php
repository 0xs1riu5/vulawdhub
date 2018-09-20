<?php
/**
 * 邀请注册类型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-24
 * Time: 上午11:02
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class InviteTypeModel extends Model
{
    protected $_validate = array(
        array('title', 'require', '标题不能为空。', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '', '标题已经存在。', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_UPDATE),
        array('status', '1', self::MODEL_BOTH),
    );
    protected $insertFields = 'title,length,time,cycle_num,cycle_time,roles,auth_groups,pay_score,pay_score_type,income_score,income_score_type,is_follow,status,create_time';
    protected $updateFields = 'id,title,length,time,cycle_num,cycle_time,roles,auth_groups,pay_score,pay_score_type,income_score,income_score_type,is_follow,status,update_time';


    /**
     * 保存邀请码类型信息
     * @param array $data
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function saveData($data = array())
    {
        $data = $this->_initSaveData($data);
        $data = $this->create($data);
        if (!$data) return false;
        $result = $this->save($data);
        return $result;
    }

    /**
     * 添加邀请码类型信息
     * @param array $data
     * @return bool|mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function addData($data = array())
    {
        $data = $this->_initSaveData($data);
        $data = $this->create($data);
        if (!$data) return false;
        $result = $this->add($data);
        return $result;
    }

    /**
     * 获取邀请码类型
     * @param array $map
     * @return array|mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getData($map = array())
    {
        $data = $this->where($map)->find();
        if($data){
            $data = $this->_initSelectData($data);
        }
        return $data;
    }

    /**
     * 获取简易结构邀请码类型
     * @param array $map
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getSimpleData($map=array())
    {
        $data = $this->where($map)->find();
        if($data){
            if($data['roles']!=''){
                $data['roles']=str_replace('[','',$data['roles']);
                $data['roles']=str_replace(']','',$data['roles']);
                $data['roles']=explode(',',$data['roles']);
            }else{
                $data['roles']=array();
            }
        }
        return $data;
    }

    /**
     * 获取邀请码类型列表
     * @param array $map
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getList($map = array())
    {
        if (count($map)) {
            $data = $this->where($map)->select();
        } else {
            $data = $this->select();
        }
        foreach ($data as &$val) {
            $val = $this->_initSelectData($val);
        }
        return $data;
    }

    /**
     * 获取简易结构邀请码类型列表
     * @param array $map
     * @param string $field
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getSimpleList($map = array(), $field = 'id,title')
    {
        if (count($map)) {
            $data = $this->where($map)->field($field)->select();
        } else {
            $data = $this->field($field)->select();
        }
        return $data;
    }

    /**
     * 真删除邀请码
     * @param array $ids id列表
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function deleteIds($ids=array())
    {
        $this->where(array('id'=>array('in',$ids)))->delete();
        return true;
    }

    public function getUserTypeSimpleList($field = 'id,title'){
        $group_ids=D('AuthGroupAccess')->where(array('uid'=>is_login()))->field('group_id')->select();
        foreach($group_ids as &$val){
            $val='%['.$val['group_id'].']%';
        }
        unset($val);
        if(count($group_ids)){
            $group_ids=array_merge(array(''),$group_ids);
        }else{
            $group_ids=array('');
        }
        $map['auth_groups']=array('like',$group_ids);
        $map['status']=1;
        $list=$this->where($map)->field($field)->select();
        return $list;
    }

    /**
     * 获取用户可兑换邀请码类型
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getUserTypeList()
    {
        $group_ids=D('AuthGroupAccess')->where(array('uid'=>is_login()))->field('group_id')->select();
        foreach($group_ids as &$val){
            $val='%['.$val['group_id'].']%';
        }
        unset($val);
        if(count($group_ids)){
            $group_ids=array_merge(array(''),$group_ids);
        }else{
            $group_ids=array('');
        }
        $map['auth_groups']=array('like',$group_ids);
        $map['status']=1;
        $list=$this->where($map)->select();
        $scoreTypeModel=D('UcenterScoreType');
        $roleModel=D('Role');
        $inviteUserInfoModel=D('Ucenter/InviteUserInfo');
        $showRole=$roleModel->where(array('status'=>1))->count();
        foreach($list as &$val){
            if($showRole){//网站超过1个角色
                if ($val['roles'] != '') {
                    $val['roles']=str_replace('[','',$val['roles']);
                    $val['roles']=str_replace(']','',$val['roles']);
                    $val['roles'] = $val['roles_show'] = explode(',', $val['roles']);
                    $role_list = $roleModel->where(array('id' => array('in', $val['roles_show'])))->field('id,title')->select();
                    $role_list = array_combine(array_column($role_list, 'id'), $role_list);
                    foreach ($val['roles_show'] as &$vl) {
                        $vl = $role_list[$vl]['title'];
                    }
                    unset($vl);
                    $val['roles_show'] = implode(',', $val['roles_show']);
                }
            }
            $scoreTypes = $scoreTypeModel->where(array('id' => array('in', array($val['pay_score_type'], $val['income_score_type']))))->field('id,title,unit')->select();
            $scoreTypes = array_combine(array_column($scoreTypes, 'id'), $scoreTypes);
            $val['pay'] = $scoreTypes[$val['pay_score_type']]['title'] . ' ' . $val['pay_score'] . ' ' . $scoreTypes[$val['pay_score_type']]['unit'];
            $val['income'] = $scoreTypes[$val['income_score_type']]['title'] . ' ' . $val['income_score'] . ' ' . $scoreTypes[$val['income_score_type']]['unit'];
            $val['cycle'] ='每 '. unitTime_to_showUnitTime($val['cycle_time']).L('_UP_TO_BUY_').$val['cycle_num'].L('_PLACES_');
            $userInfo=$inviteUserInfoModel->getInfo(array('uid'=>is_login(),'invite_type'=>$val['id']));
            if($userInfo){
                $val['can_num']=$userInfo['num'];
                $val['already_num']=$userInfo['already_num'];
                $val['success_num']=$userInfo['success_num'];
            }
        }
        unset($val);
        return $list;
    }

    /**
     * 初始化查询邀请码类型
     * @param array $data
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initSelectData($data = array())
    {
        $data['roles']=str_replace('[','',$data['roles']);
        $data['roles']=str_replace(']','',$data['roles']);

        $data['auth_groups']=str_replace('[','',$data['auth_groups']);
        $data['auth_groups']=str_replace(']','',$data['auth_groups']);

        $data['time_show'] = unitTime_to_showUnitTime($data['time']);
        $data['cycle_time_show'] = unitTime_to_showUnitTime($data['cycle_time']);
        $scoreTypes = D('UcenterScoreType')->where(array('id' => array('in', array($data['pay_score_type'], $data['income_score_type']))))->field('id,title,unit')->select();
        $scoreTypes = array_combine(array_column($scoreTypes, 'id'), $scoreTypes);
        $data['pay'] = $scoreTypes[$data['pay_score_type']]['title'] . ' ' . $data['pay_score'] . ' ' . $scoreTypes[$data['pay_score_type']]['unit'];
        $data['income'] = $scoreTypes[$data['income_score_type']]['title'] . ' ' . $data['income_score'] . ' ' . $scoreTypes[$data['income_score_type']]['unit'];
        if ($data['roles'] != '') {
            $data['roles'] = $data['roles_show'] = explode(',', $data['roles']);
            $role_list = D('Role')->where(array('id' => array('in', $data['roles_show'])))->field('id,title')->select();
            $role_list = array_combine(array_column($role_list, 'id'), $role_list);
            foreach ($data['roles_show'] as &$val) {
                $val = $role_list[$val]['title'];
            }
            unset($val);
            $data['roles_show'] = implode(',', $data['roles_show']);
        }else{
            $data['roles']=array();
        }

        if ($data['auth_groups'] != '') {
            $data['auth_groups'] = $data['auth_groups_show'] = explode(',', $data['auth_groups']);
            $auth_group_list = D('AuthGroup')->where(array('id' => array('in', $data['auth_groups_show'])))->field('id,title')->select();
            $auth_group_list = array_combine(array_column($auth_group_list, 'id'), $auth_group_list);
            foreach ($data['auth_groups_show'] as &$val) {
                $val = $auth_group_list[$val]['title'];
            }
            unset($val);
            $data['auth_groups_show'] = implode(',', $data['auth_groups_show']);
        }else{
            $data['auth_groups']=array();
        }
        return $data;
    }

    /**
     * 初始化保存邀请码类型
     * @param array $data
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initSaveData($data = array())
    {
        $data['time'] = $data['time_num'] . ' ' . $data['time_unit'];
        $data['cycle_time'] = $data['cycle_time_num'] . ' ' . $data['cycle_time_unit'];
        foreach($data['roles'] as &$val){
            $val='['.$val.']';
        }
        unset($val);
        $data['roles'] = implode(',', $data['roles']);
        foreach($data['auth_groups'] as &$val){
            $val='['.$val.']';
        }
        unset($val);
        $data['auth_groups'] = implode(',', $data['auth_groups']);
        return $data;
    }
} 