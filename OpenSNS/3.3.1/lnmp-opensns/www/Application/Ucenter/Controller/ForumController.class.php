<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM3:34
 */

namespace Ucenter\Controller;
use Think\Controller;

class ForumController extends BaseController {
    public function myTopic($page=1,$uid=0) {
        //默认获取自己的主题列表
        if(!$uid) {
            $uid = is_login();
            $this->requireLogin();
        }

        //获取我的主题列表
        $model = D('Forum/ForumPost');
        $map = array('uid'=>$uid,'status'=>1);
        $list = $model->where($map)->order('create_time desc')->page($page)->select();
        $totalCount = $model->where($map)->count();

        //读取人称
        $call = $this->getCall($uid);

        //显示页面
        $this->defaultTabHash('my-topic');
        $this->assign('uid', $uid);
        $this->assign('totalCount',$totalCount);
        $this->assign('list',$list);
        $this->assign('call',$call);
        $this->display();
    }

    public function myTakePartIn($page=1,$uid=0) {
        //默认获取自己的主题列表
        if(!$uid) {
            $uid = is_login();
            $this->requireLogin();
        }

        //读取我的帖子列表
        $model = D('Forum/ForumPostReply');
        $map = array('uid'=>$uid, 'status'=>1);
        $list = $model->where($map)->page($page)->order('create_time desc')->field('distinct post_id')->select();
        $totalCount = $model->where($map)->count('distinct post_id');

        //依次读取帖子详情
        foreach($list as &$e) {
            $e = D('Forum/ForumPost')->where(array('id'=>$e['post_id']))->find();
        }

        //获取人称
        $call = $this->getCall($uid);

        //显示页面
        $this->defaultTabHash('my-take-part-in');
        $this->assign('totalCount', $totalCount);
        $this->assign('list', $list);
        $this->assign('call', $call);
        $this->display();
    }

    public function myBookmark($page=1) {
        //要求已经登录
        $this->requireLogin();

        //获取自己的UID
        $uid = is_login();

        //读取数据库获取已经收藏的帖子
        $map = array('uid'=>$uid);
        $model = D('ForumBookmark');
        $list = $model->where($map)->order('create_time desc')->page($page)->select();
        $totalCount = $model->where($map)->count();

        //读取帖子的详情
        foreach($list as &$e) {
            $e = D('ForumPost')->where(array('id'=>$e['post_id']))->find();
        }

        //显示页面
        $this->defaultTabHash('my-bookmark');
        $this->assign('totalCount', $totalCount);
        $this->assign('list', $list);
        $this->assign('call', $this->getCall($uid));
        $this->display();
    }
}