<?php
/**
 * 所属项目 110.
 * 开发者: 陈一枭
 * 创建日期: 2014-11-28
 * 创建时间: 11:37
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Ucenter\Controller;


class CollectionController extends BaseController{
    public function index($type='forum',$page=1)
    {
        $this->requireLogin();
        $totalCount=0;
        $list=$this->_getList($type,$totalCount,$page);

        $this->assign('totalCount', $totalCount);
        $this->assign('list', $list);
        //设置Tab
        $this->defaultTabHash('collection');
        $this->assign('type', $type);
        $this->setTitle(L('_MY_FAVORITES_'));
        $this->display($type);
    }

    public function _getList($type='forum',&$totalCount=0,$page=1,$r=15)
    {
        $map['uid']=is_login();
        switch ($type) {
            case 'forum':
                $forums = $this->getForumList();
                $forum_key_value = array();
                foreach ($forums as $f) {
                    $forum_key_value[$f['id']] = $f;
                }
                $post_ids=D('ForumBookmark')->where($map)->field('post_id')->select();
                $post_ids=array_column($post_ids,'post_id');
                $map_forum=array('id'=>array('in',$post_ids),'status'=>1);
                $model=D('ForumPost');
                $list=$model->where($map_forum)->page($page,$r)->order('update_time desc')->select();
                $totalCount=$model->where($map_forum)->count();
                foreach ($list as &$v) {
                    $v['forum'] = $forum_key_value[$v['forum_id']];
                }
                break;
           case 'group':
                $groups = $this->getGroupList();
                $group_key_value = array();
                foreach ($groups as $f) {
                    $group_key_value[$f['id']] = $f;
                }
                $post_ids=D('GroupBookmark')->where($map)->field('post_id')->select();
                $post_ids=array_column($post_ids,'post_id');
                $map_group=array('id'=>array('in',$post_ids),'status'=>1);
                $model=D('GroupPost');
                $list=$model->where($map_group)->page($page,$r)->order('update_time desc')->select();
                $totalCount=$model->where($map_group)->count();
                foreach ($list as &$v) {
                    $v['group'] = $group_key_value[$v['group_id']];
                }
               break;
            default:
                $this->error(L('_ERROR_ILLEGAL_OPERATE_').L('_EXCLAMATION_'));
               break;
        }
        return $list;
    }
    private function getForumList()
    {
        $forum_list = S('forum_list');
        if (empty($forum_list)) {
            //读取板块列表
            $forum_list = D('Forum/Forum')->where(array('status' => 1))->order('sort asc')->select();
            S('forum_list', $forum_list, 300);
        }
        return $forum_list;
    }

    private function getGroupList()
    {
        $group_list = S('group_list');
        if (empty($group_list)) {
            //读取板块列表
            $group_list = D('Group/Group')->where(array('status' => 1))->order('sort asc')->select();
            S('group_list', $group_list, 300);
        }
        return $group_list;
    }
} 