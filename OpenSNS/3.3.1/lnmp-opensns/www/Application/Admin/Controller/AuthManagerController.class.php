<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Model\AuthRuleModel;
use Admin\Model\AuthGroupModel;

/**
 * 权限管理控制器
 * Class AuthManagerController
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class AuthManagerController extends AdminController
{

    /**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function updateRules()
    {
        //需要新增的节点必然位于$nodes
        $nodes = $this->returnNodes(false);

        $AuthRule = M('AuthRule');
        $map = array('module' => 'admin', 'type' => array('in', '1,2'));//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value) {
            $temp['name'] = $value['url'];
            $temp['title'] = $value['title'];
            $temp['module'] = 'admin';
            if ($value['pid'] > 0) {
                $temp['type'] = AuthRuleModel::RULE_URL;
            } else {
                $temp['type'] = AuthRuleModel::RULE_MAIN;
            }
            $temp['status'] = 1;
            $data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids = array();//保存需要删除的节点的id
        foreach ($rules as $index => $rule) {
            $key = strtolower($rule['name'] . $rule['module'] . $rule['type']);
            if (isset($data[$key])) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']] = $rule;
            } elseif ($rule['status'] == 1) {
                $ids[] = $rule['id'];
            }
        }
        if (count($update)) {
            foreach ($update as $k => $row) {
                if ($row != $diff[$row['id']]) {
                    $AuthRule->where(array('id' => $row['id']))->save($row);
                }
            }
        }
        if (count($ids)) {
            $AuthRule->where(array('id' => array('IN', implode(',', $ids))))->save(array('status' => -1));
            //删除规则是否需要从每个权限组的访问授权表中移除该规则?
        }
        if (count($data)) {
            $AuthRule->addAll(array_values($data));
        }
        if ($AuthRule->getDbError()) {
            trace('[' . __METHOD__ . ']:' . $AuthRule->getDbError());
            return false;
        } else {
            return true;
        }
    }


    /**
     * 权限管理首页
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function index()
    {
        $list = $this->lists('AuthGroup', array('module' => 'admin'), 'id asc');
        $list = int_to_string($list);
        $this->assign('_list', $list);
        $this->assign('_use_tip', true);
        $this->meta_title = L('_PRIVILEGE_MANAGEMENT_');
        $this->display();
    }

    /**
     * 创建管理员权限组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function createGroup()
    {
        if (empty($this->auth_group)) {
            $this->assign('auth_group', array('title' => null, 'id' => null, 'description' => null, 'rules' => null,));//排除notice信息
        }
        $this->meta_title = L('_NEW_USER_GROUP_');
        $this->display('creategroup');
    }

    /**
     * 编辑管理员权限组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function editGroup()
    {
        $auth_group = M('AuthGroup')->where(array('module' => 'admin', 'type' => AuthGroupModel::TYPE_ADMIN))
            ->find((int)$_GET['id']);
        $this->assign('auth_group', $auth_group);
        $this->meta_title = L('_EDIT_USER_GROUP_');
        $this->display();
    }




    /**
     * 管理员权限组数据写入/更新
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function writeGroup()
    {
        if (isset($_POST['rules'])) {
            sort($_POST['rules']);
            $_POST['rules'] = implode(',', array_unique($_POST['rules']));
        }
        $_POST['module'] = 'admin';
        $_POST['type'] = AuthGroupModel::TYPE_ADMIN;
        $AuthGroup = D('AuthGroup');
        $data = $AuthGroup->create();
        if ($data) {
            $oldGroup = $AuthGroup->find($_POST['id']);
            $data['rules'] = $this->getMergedRules($oldGroup['rules'], explode(',', $_POST['rules']), 'eq');
            if (empty($data['id'])) {
                $r = $AuthGroup->add($data);
            } else {
                $r = $AuthGroup->save($data);
            }
            if ($r === false) {
                $this->error('操作失败！'. $AuthGroup->getError());
            } else {
                $this->success('操作成功！');
            }
        } else {
            $this->error('操作失败！' . $AuthGroup->getError());
        }
    }
    /**
     * 修改权限组描述
     * 范佳炜 fjw@ourstu.com
     */
    public function descriptionGroup()
    {
        $title=$_POST['title'];
        $description=$_POST['description'];
        $id=$_POST['id'];
        $AuthGroup = D('AuthGroup');
        $data['description']=$description;
        $data['title']=$title;
        $data['end_time']=I('post.end_time',2000000000,'intval');
        $res=$AuthGroup->where('id='.$id)->save($data);
        if($res)
        {
            $this->success('修改成功!');
        }
        else{
            $this->error('修改失败!');
        }

    }
    /**
     * 状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method = null)
    {
        if (empty($_REQUEST['id'])) {
            $this->error(L('_PLEASE_CHOOSE_TO_OPERATE_THE_DATA_'));
        }
        switch (strtolower($method)) {
            case 'forbidgroup':
                $this->forbid('AuthGroup');
                break;
            case 'resumegroup':
                $this->resume('AuthGroup');
                break;
            case 'deletegroup':
                $this->delete('AuthGroup');
                break;
            default:
                $this->error($method .  '参数非法');
        }
    }

    /**
     * 权限组授权用户列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function user($group_id)
    {
        if (empty($group_id)) {
            $this->error(L('_PARAMETER_ERROR_'));
        }

        $auth_group = M('AuthGroup')->where(array('status' => array('egt', '0'), 'module' => 'admin', 'type' => AuthGroupModel::TYPE_ADMIN))
            ->getfield('id,id,title,rules');
        $prefix = C('DB_PREFIX');
        $l_table = $prefix . (AuthGroupModel::MEMBER);
        $r_table = $prefix . (AuthGroupModel::AUTH_GROUP_ACCESS);
        $model = M()->table($l_table . ' m')->join($r_table . ' a ON m.uid=a.uid');
        $_REQUEST = array();
        $list = $this->lists($model, array('a.group_id' => $group_id, 'm.status' => array('egt', 0)), 'm.uid asc', null, 'm.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status');
        int_to_string($list);
        $this->assign('_list', $list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = L('_MEMBER_AUTHORITY_');
        $this->display();
    }



    public function tree($tree = null)
    {
        $this->assign('tree', $tree);
        $this->display('tree');
    }

    /**
     * 将用户添加到权限组的编辑页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function group()
    {
        $uid = I('uid');
        $auth_groups = D('AuthGroup')->getGroups();
        $user_groups = AuthGroupModel::getUserGroup($uid);
        $ids = array();
        foreach ($user_groups as $value) {
            $ids[] = $value['group_id'];
        }
        $nickname = D('Member')->getNickName($uid);
        $this->assign('nickname', $nickname);
        $this->assign('auth_groups', $auth_groups);
        $this->assign('user_groups', implode(',', $ids));
        $this->display();
    }

    /**
     * 将用户添加到权限组,入参uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToGroup()
    {
        $uid = I('uid');
        $gid = I('group_id');
        if (empty($uid)) {
            $this->error(L('_PARAMETER_IS_INCORRECT_'));
        }
        $AuthGroup = D('AuthGroup');
        if (is_numeric($uid)) {
            if (is_administrator($uid)) {
                $this->error(L('_THE_USER_IS_A_SUPER_ADMINISTRATOR_'));
            }
            if (!M('Member')->where(array('uid' => $uid))->find()) {
                $this->error(L('_ADMIN_USER_DOES_NOT_EXIST_'));
            }
        }

        if ($gid && !$AuthGroup->checkGroupId($gid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToGroup($uid, $gid)) {
            $this->success(L('_SUCCESS_OPERATE_'));
        } else {
            $this->error($AuthGroup->getError());
        }
    }

    /**
     * 将用户从权限组中移除  入参:uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function removeFromGroup()
    {
        $uid = I('uid');
        $gid = I('group_id');
        if ($uid == UID) {
            $this->error(L('_NOT_ALLOWED_TO_RELEASE_ITS_OWN_AUTHORITY_'));
        }
        if (empty($uid) || empty($gid)) {
            $this->error(L('_PARAMETER_IS_INCORRECT_'));
        }
        $AuthGroup = D('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error(L('_USER_GROUP_DOES_NOT_EXIST_'));
        }
        if ($AuthGroup->removeFromGroup($uid, $gid)) {
            $this->success(L('_SUCCESS_OPERATE_'));
        } else {
            $this->error(L('_FAIL_OPERATE_'));
        }
    }

    /**
     * 将分类添加到权限组  入参:cid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToCategory()
    {
        $cid = I('cid');
        $gid = I('group_id');
        if (empty($gid)) {
            $this->error(L('_PARAMETER_IS_INCORRECT_'));
        }
        $AuthGroup = D('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error(L('_USER_GROUP_DOES_NOT_EXIST_'));
        }
        if ($cid && !$AuthGroup->checkCategoryId($cid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToCategory($gid, $cid)) {
            $this->success(L('_SUCCESS_OPERATE_'));
        } else {
            $this->error(L('_FAIL_OPERATE_'));
        }
    }

    /**
     * 将模型添加到权限组  入参:mid,group_id
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function addToModel()
    {
        $mid = I('id');
        $gid = I('get.group_id');
        if (empty($gid)) {
            $this->error(L('_PARAMETER_IS_INCORRECT_'));
        }
        $AuthGroup = D('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error(L('_USER_GROUP_DOES_NOT_EXIST_'));
        }
        if ($mid && !$AuthGroup->checkModelId($mid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToModel($gid, $mid)) {
            $this->success(L('_SUCCESS_OPERATE_'));
        } else {
            $this->error(L('_FAIL_OPERATE_'));
        }
    }

    public function addNode()
    {
        if (empty($this->auth_group)) {
            $this->assign('auth_group', array('title' => null, 'id' => null, 'description' => null, 'rules' => null,));//排除notice信息
        }
        if (IS_POST) {
            $Rule = D('AuthRule');
            $data = $Rule->create();
            if ($data) {
                if (intval($data['id']) == 0) {
                    $id = $Rule->add();
                } else {
                    $Rule->save($data);
                    $id = $data['id'];
                }

                if ($id) {
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    $this->success(L('_SUCCESS_EDIT_'));
                } else {
                    $this->error(L('_EDIT_FAILED_'));
                }
            } else {
                $this->error($Rule->getError());
            }
        } else {
            $aId = I('id', 0, 'intval');
            if ($aId == 0) {
                $info['module']=I('module','','op_t');
            }else{
                $info = D('AuthRule')->find($aId);
            }

            $this->assign('info', $info);
            //  $this->assign('info', array('pid' => I('pid')));
            $modules = D('Common/Module')->getAll();
            $this->assign('Modules', $modules);
            $this->meta_title = L('_NEW_FRONT_DESK_RIGHT_NODE_');
            $this->display();
        }

    }

    public function deleteNode(){
        $aId=I('id',0,'intval');
        if($aId>0){
            $result=   M('AuthRule')->where(array('id'=>$aId))->delete();
            if($result){
                $this->success(L('_DELETE_SUCCESS_'));
            }else{
                $this->error(L('_DELETE_FAILED_'));
            }
        }else{
            $this->error(L('_YOU_MUST_SELECT_THE_NODE_'));
        }
    }
    /**
     * 访问授权页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function access()
    {
        $this->updateRules();
        $auth_group = M('AuthGroup')->where(array('status' => array('egt', '0'), 'module' => 'admin', 'type' => AuthGroupModel::TYPE_ADMIN))
            ->getfield('id,id,title,rules');
        $node_list = $this->returnNodes();
        $map = array('module' => 'admin', 'type' => AuthRuleModel::RULE_MAIN, 'status' => 1);
        $main_rules = M('AuthRule')->where($map)->getField('name,id');
        $map = array('module' => 'admin', 'type' => AuthRuleModel::RULE_URL, 'status' => 1);
        $child_rules = M('AuthRule')->where($map)->getField('name,id');

        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list', $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = L('_ACCESS_AUTHORIZATION_');
        $this->display('');
    }

    public function accessUser()
    {
        $aId = I('get.group_id', 0, 'intval');

        if (IS_POST) {
            $aId = I('id', 0, 'intval');
            $aOldRule = I('post.old_rules', '', 'text');
            $aRules = I('post.rules', array());
            $rules = $this->getMergedRules($aOldRule, $aRules);
            $authGroupModel = M('AuthGroup');
            $group = $authGroupModel->find($aId);
            $group['rules'] = $rules;
            $result = $authGroupModel->save($group);
            if ($result) {
                $this->success(L('_RIGHT_TO_SAVE_SUCCESS_'));
            } else {
                $this->error(L('_RIGHT_SAVE_FAILED_'));
            }

        }
        $this->updateRules();
        $auth_group = M('AuthGroup')->where(array('status' => array('egt', '0'), 'type' => AuthGroupModel::TYPE_ADMIN))
            ->getfield('id,id,title,rules');
        $node_list = $this->getNodeListFromModule(D('Common/Module')->getAll());

        //  $node_list   =M('AuthRule')->where(array('module'=>array('neq','admin'),'type'=>AuthRuleModel::RULE_URL,'status'=>1))->select();

        $map = array('module' => array('neq', 'admin'), 'type' => AuthRuleModel::RULE_MAIN, 'status' => 1);
        $main_rules = M('AuthRule')->where($map)->getField('name,id');
        $map = array('module' => array('neq', 'admin'), 'type' => AuthRuleModel::RULE_URL, 'status' => 1);
        $child_rules = M('AuthRule')->where($map)->getField('name,id');

        $group = M('AuthGroup')->find($aId);
        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list', $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $group);

        $this->meta_title = L('_USER_FRONT_DESK_AUTHORIZATION_');
        $this->display('');
    }

    private function getMergedRules($oldRules, $rules, $isAdmin = 'neq')
    {
        $map = array('module' => array($isAdmin, 'admin'), 'status' => 1);
        $otherRules = M('AuthRule')->where($map)->field('id')->select();
        $oldRulesArray = explode(',', $oldRules);
        $otherRulesArray = getSubByKey($otherRules, 'id');

        //1.删除全部非Admin模块下的权限，排除老的权限的影响
        //2.合并新的规则
        foreach ($otherRulesArray as $key => $v) {
            if (in_array($v, $oldRulesArray)) {
                $key_search = array_search($v, $oldRulesArray);
                if ($key_search !== false)
                    array_splice($oldRulesArray, $key_search, 1);
            }
        }

        return str_replace(',,', ',', implode(',', array_unique(array_merge($oldRulesArray, $rules))));


    }

    //预处理规则，去掉未安装的模块
    public function getNodeListFromModule($modules)
    {
        $node_list = array();
        foreach ($modules as $module) {
            if ($module['is_setup']) {

                $node = array('name' => $module['name'], 'alias' => $module['alias']);
                $map = array('module' => $module['name'], 'type' => AuthRuleModel::RULE_URL, 'status' => 1);

                $node['child'] = M('AuthRule')->where($map)->select();
                $node_list[] = $node;
            }

        }
        return $node_list;
    }
}
