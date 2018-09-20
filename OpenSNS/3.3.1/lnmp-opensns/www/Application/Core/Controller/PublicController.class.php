<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Core\Controller;

use Think\Controller;

/**
 * Class PublicController  公共控制器
 * @package Core\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class PublicController extends Controller
{


    /**关注某人
     * @param int $uid
     * @auth 陈一枭
     */
    public function follow()
    {
        $aUid=I('post.uid',0,'intval');
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'info' => L("_PLEASE_")." ".L("_LOG_IN_")));
        }

        if (D('Follow')->follow($aUid)) {
            D('Member')->where(array('uid' => $aUid))->setInc('fans', 1);
            $this->ajaxReturn(array('status' => 1, 'info' => L("_FOLLOWERS_")." ".L('_SUCCESS_')));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => L("_FOLLOWERS_")." ".L("_FAIL_")));
        }
    }

    /**取消对某人的关注
     * @param int $uid
     * @auth 陈一枭
     */
    public function unfollow()
    {
        $aUid=I('post.uid',0,'intval');
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'info' => L("_PLEASE_")." ".L("_LOG_IN_")));
        }

        if (D('Follow')->unfollow($aUid)) {
            D('Member')->where(array('uid' => $aUid))->setDec('fans', 1);
            $this->ajaxReturn(array('status' => 1, 'info' =>  L("_CANCEL_")." ".L("_FOLLOWERS_")." ".L("_SUCCESS_")));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' =>  L("_CANCEL_")." ".L("_FOLLOWERS_")." ".L("_FAIL_")));
        }
    }


    /**
     * atWhoJson
     * @author:陈一枭
     */
    public function atWhoJson()
    {
        exit(json_encode($this->getAtWhoUsersCached()));
    }

    private function getAtWhoUsersCached()
    {
        $cacheKey = 'weibo_at_who_users';
        $atusers = S($cacheKey);
        if (empty($atusers[get_uid()])) {
            $atusers[get_uid()] = $this->getAtWhoUsers();
            S($cacheKey, $atusers, 600);
        }
        return $atusers[get_uid()];
    }

    /**
     * getAtWhoUsers  获取@列表
     * @return array
     * @author:陈一枭
     */
    private function getAtWhoUsers()
    {
        //获取能AT的人，UID列表
        $uid = get_uid();
        $follows = D('Follow')->where(array('who_follow' => $uid, 'follow_who' => $uid, '_logic' => 'or'))->select();
        $uids = array();
        foreach ($follows as &$e) {
            $uids[] = $e['who_follow'];
            $uids[] = $e['follow_who'];
        }
        unset($e);
        $uids = array_unique($uids);

        //加入拼音检索
        $users = array();
        foreach ($uids as $uid) {
            $user = query_user(array('nickname', 'id', 'avatar32'), $uid);
            $user['search_key'] = $user['nickname'] . D('PinYin')->Pinyin($user['nickname']);
            $users[] = $user;
        }

        //返回at用户列表
        return $users;
    }


    public function getVideo(){
        $aLink = I('post.link');
        $this->ajaxReturn(array('data'=>D('ContentHandler')->getVideoInfo($aLink)));
    }


    public function assignUser(){
        $aIds = I('post.ids','','text');
        $ids = explode(',',$aIds);
        foreach($ids as $v){
            $friends[] = query_user(array('avatar32', 'avatar64', 'space_url', 'nickname', 'uid', 'signature'), $v);
        }
        $this->ajaxReturn($friends);

    }

    /**
     * runSchedule 执行计划任务请求地址。
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function runSchedule(){

        $aToken = I('get.token','','text');
        $aTime = I('get.time',0,'intval');
        if($aTime + 30  < time()){
            exit('Error');
        }
        if($aToken != md5($aTime.C('DATA_AUTH_KEY'))){
            exit('Error');
        }
        D('Schedule')->run();
    }

    /**
     * firstUserRun 每日第一位访问的用户调用该函数（用于执行数据统计、连签重置等操作）
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function firstUserRun(){
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
        $this->_setConfig('_CONFIG_FIRST_USER_RUN',time_format(time(),'Y-m-d'));
        //执行操作start
        if(method_exists(D('Common/Schedule'), 'dealAbnormalStop')){
            D('Schedule')->dealAbnormalStop();
        }
        if(method_exists(D('Admin/Count'), 'dayCount')){
            D('Admin/Count')->dayCount();
        }
        if(method_exists(D('Addons://CheckIn/CheckIn'), 'resetConCheck')){
            D('Addons://CheckIn/CheckIn')->resetConCheck();
        }
        if(method_exists(A('Core/AutoExport'),'autoExportLog')){
            A('Core/AutoExport')->autoExportLog();
        }
        if(method_exists(A('Ucenter/Index'),'ranking')){
            A('Ucenter/Index')->ranking();
        }
        exit;
        //执行操作end
    }

    /**
     * 设置config
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _setConfig($name,$value)
    {
        $config['name'] =$name;// '_' . strtoupper(CONTROLLER_NAME) . '_' . strtoupper($k);
        $config['type'] = 0;
        $config['title'] = '';
        $config['group'] = 0;
        $config['extra'] = '';
        $config['remark'] = '';
        $config['create_time'] = time();
        $config['update_time'] = time();
        $config['status'] = 1;
        $config['value'] = is_array($value) ? implode(',', $value) : $value;
        $config['sort'] = 0;
        $configModel=M('Config');
        if ($configModel->add($config, null, true)) {
            $tag = 'conf' . $name;
            S($tag, null);
        }
        return true;
    }

    public function pushing()
    {

        $aToken = I('get.token', '', 'text');
        $aTime = I('get.time', 0, 'intval');
        if ($aTime + 30 < time()) {
            exit('Error');
        }
        if ($aToken != md5($aTime . C('DATA_AUTH_KEY'))) {
            exit('Error');
        }
        D('Pushing')->run();
    }



}
