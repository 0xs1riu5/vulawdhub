<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 陈一枭
 * 创建日期: 3/21/14
 * 创建时间: 10:17 AM
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */

namespace Common\Model;


use Think\Model;

class FollowModel extends Model
{

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT));

    /**关注
     * @param $uid
     * @return int|mixed
     */
    public function follow($uid)
    {
        $follow['who_follow'] = is_login();
        $follow['follow_who'] = $uid;

        if ($follow['who_follow'] == $follow['follow_who']) {
            //禁止关注和被关注都为同一个人的情况。
            return 0;
        }
        if ($this->where($follow)->count() > 0) {
            return 0;
        }
        $follow = $this->create($follow);
        $arr[]=modC('USER_SHOW_ORDER_FIELD0','friend','people');
        $arr[]=modC('USER_SHOW_ORDER_FIELD3','rand','people');
        $arr[]='star';
        foreach ($arr as $a){
            S($a.'_follow_cache_'. $follow['who_follow'],null);
        }unset($a);
        $this->S($follow['who_follow'], $follow['follow_who'], null);

        clean_query_user_cache($uid, 'fans');
        clean_query_user_cache(is_login(), 'following');
        S('atUsersJson_' . is_login(), null);
        /**
         * @param $to_uid 接受消息的用户ID
         * @param string $content 内容
         * @param string $title 标题，默认为  您有新的消息
         * @param $url 链接地址，不提供则默认进入消息中心
         * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
         * @param int $type 消息类型，0系统，1用户，2应用
         */
        $user = query_user(array('id', 'nickname', 'space_url'));

        D('Common/Message')->sendMessage($uid, L('_FANS_NUMBER_INCREASED_'), $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_'), 'Ucenter/Index/index', array('uid' => is_login()),-1,'Ucenter');
        return $this->add($follow);
    }

    /**取消关注
     * @param $uid
     * @return mixed
     */
    public function unfollow($uid)
    {
        $follow['who_follow'] = is_login();
        $follow['follow_who'] = $uid;
        $this->S($follow['who_follow'], $follow['follow_who'], null);
        $arr[]=modC('USER_SHOW_ORDER_FIELD0','friend','people');
        $arr[]=modC('USER_SHOW_ORDER_FIELD3','rand','people');
        $arr[]='star';
        foreach ($arr as $a){
            S($a.'_follow_cache_'. $follow['who_follow'],null);
        }unset($a);
        clean_query_user_cache($uid, 'fans');
        clean_query_user_cache(is_login(), 'following');
        S('atUsersJson_' . is_login(), null);
        $user = query_user(array('id', 'nickname', 'space_url'));

        D('Message')->sendMessage($uid, L('_NUMBER_OF_FANS_'), $user['nickname'] . L('_CANCEL_YOUR_ATTENTION_WITH_PERIOD_'), 'Ucenter/Index/index', array('uid' => is_login()),-1,'Ucenter');

        return $this->where($follow)->delete();
    }

    public function isFollow($who_follow, $follow_who)
    {
        $follow = $this->S($who_follow, $follow_who);
        if (empty($follow)||$follow===false) {
            $follow = D('Follow')->where(array('who_follow' => $who_follow, 'follow_who' => $follow_who))->count();
            $this->S($who_follow, $follow_who, $follow);
        }
        return intval($follow);
    }

    public function getFollow($who_follow, $follow_who)
    {
        $follow = $this->where(array('who_follow' => $who_follow, 'follow_who' => $follow_who))->find();
        return $follow;
    }

    public function S($who_follow, $follow_who, $data = '')
    {
        return S('Core_follow_' . $who_follow . '_' . $follow_who, $data);
    }


    public function getFans($uid, $page, $fields, &$totalCount)
    {
        $map['follow_who'] = $uid;
        $fans = $this->where($map)->field('who_follow')->order('create_time desc')->page($page, 10)->select();
        $totalCount = $this->where($map)->field('who_follow')->order('create_time desc')->count();
        foreach ($fans as &$user) {
            $user['user'] = query_user($fields, $user['who_follow']);
        }
        unset($user);
        return $fans;
    }

    public function getFollowing($uid, $page, $fields, &$totalCount)
    {
        $map['who_follow'] = $uid;
        $fans = $this->where($map)->field('follow_who')->order('create_time desc')->page($page, 10)->select();
        $totalCount = $this->where($map)->field('follow_who')->order('create_time desc')->count();

        foreach ($fans as &$user) {
            $user['user'] = query_user($fields, $user['follow_who']);
        }
        unset($user);
        return $fans;
    }


    /**显示全部的好友
     * @param int $uid
     * @return mixed
     * @auth 陈一枭
     */
    public function getAllFriends($uid = 0)
    {
        if ($uid == 0) {
            $uid = is_login();
        }
        $model_follow = D('Follow');
        $i_follow = $model_follow->where(array('who_follow' => $uid))->limit(999)->select();
        foreach ($i_follow as $key => $user) {
            if ($model_follow->where(array('follow_who' => $uid, 'who_follow' => $user['follow_who']))->count()) {
                continue;
            } else {
                unset($i_follow[$key]);
            }
        }
        return $i_follow;
    }


    /**
     * getMyFriends  获取所有我的好友ID
     * @param int $uid
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getMyFriends($uid = 0){

        $uid = $uid ==0 ?is_login():$uid;
        $model_follow = D('Follow');
        $my_follow = $model_follow->where(array('who_follow' => $uid))->field('follow_who')->select();
        $follow_me =  $model_follow->where(array('follow_who' => $uid))->field('who_follow')->select();
        $my_follow_ids = getSubByKey($my_follow,'follow_who');
        $follow_me_ids =  getSubByKey($follow_me,'who_follow');

        $rs = array_intersect($my_follow_ids,$follow_me_ids);
        return $rs;

    }


    public function getFollowList()
    {
        //获取我关注的人
        $result = $this->where(array('who_follow' => get_uid()))->select();
        foreach ($result as &$e) {
            $e = $e['follow_who'];
        }
        unset($e);
        $followList = $result;
        $followList[] = is_login();
        return $followList;
    }


    /**关注
     * @param $who_follow
     * @param $follow_who
     * @return int|mixed
     */
    public function addFollow($who_follow, $follow_who, $invite = 0)
    {
        $follow['who_follow'] = $who_follow;
        $follow['follow_who'] = $follow_who;
        if ($follow['who_follow'] == $follow['follow_who']) {
            //禁止关注和被关注都为同一个人的情况。
            return 0;
        }
        if ($this->where($follow)->count() > 0) {
            return 0;
        }
        $follow = $this->create($follow);

        $this->S($follow['who_follow'], $follow['follow_who'], null);
        $arr[]=modC('USER_SHOW_ORDER_FIELD0','friend','people');
        $arr[]=modC('USER_SHOW_ORDER_FIELD3','rand','people');
        $arr[]='star';
        foreach ($arr as $a){
            S($a.'_follow_cache_'. $follow['who_follow'],null);
        }unset($a);


        clean_query_user_cache($follow_who, 'fans');
        clean_query_user_cache($who_follow, 'following');
        S('atUsersJson_' . $who_follow, null);
        /**
         * @param $to_uid 接受消息的用户ID
         * @param string $content 内容
         * @param string $title 标题，默认为  您有新的消息
         * @param $url 链接地址，不提供则默认进入消息中心
         * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
         * @param int $type 消息类型，0系统，1用户，2应用
         */
        $user = query_user(array('id', 'nickname', 'space_url'), $who_follow);
        if ($invite) {
            if ($who_follow < $follow_who) {
                $content = L('_INVITED_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
            } else {
                $content = L('_YOURE_INVITING_THE_USER_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
            }
        } else {
            if ($who_follow < $follow_who) {
                $content = L('_SYSTEM_RECOMMENDED_USERS_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
            } else {
                $content = L('_NEW_USER_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
            }
        }

        D('Common/Message')->sendMessage($follow_who, L('_FANS_NUMBER_INCREASED_'), $content, 'Ucenter/Index/index', array('uid' => $who_follow), $who_follow,'Ucenter');

        return $this->add($follow);
    }
} 