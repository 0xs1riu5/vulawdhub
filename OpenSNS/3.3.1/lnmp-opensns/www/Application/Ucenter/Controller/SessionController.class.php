<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 想天
 * 创建日期: 3/12/14
 * 创建时间: 12:49 PM
 * 版权所有 想天工作室(www.ourstu.com)
 */

namespace Ucenter\Controller;

use Think\Controller;

class SessionController extends BaseController
{
    protected $mTalkModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->mTalkModel = D('Talk');
    }

    public function getSession($id)
    {
        $id = intval($id);
        //获取当前聊天
        $talk = $this->getTalk(0, $id);
        $uids = D('Talk')->getUids($talk['uids']);
        foreach ($uids as $uid) {
            if ($uid != is_login()) {
                $talk['first_user'] = query_user(array('avatar64', 'username'), $uid);
                $talk['ico'] = $talk['first_user']['avatar64'];
                break;
            }
        }
        $map['talk_id'] = $talk['id'];
        D('Common/TalkPush')->clearAll();
        $messages = D('TalkMessage')->where($map)->order('create_time desc')->limit(20)->select();
        $messages = array_reverse($messages);
        foreach ($messages as &$mes) {
            $mes['user'] = query_user(array('avatar64', 'uid', 'username'), $mes['uid']);
            $mes['ctime'] = date('m-d h:i', $mes['create_time']);
            $mes['avatar64'] = $mes['user']['avatar64'];
            $mes['content'] = parse_expression($mes['content']);
        }
        unset($mes);
        $talk['messages'] = $messages;
        $talk['self'] = query_user(array('avatar128'), is_login());
        $talk['mid'] = is_login();
        echo json_encode($talk);
    }

    /**消息页面
     * @param int $page
     * @param string $tab 当前tab
     */
    public function message($page = 1, $tab = 'unread')
    {
        //从条件里面获取Tab
        $map = $this->getMapByTab($tab, $map);

        $map['to_uid'] = is_login();

        $messages = D('Message')->where($map)->order('create_time desc')->page($page, 10)->select();
        $totalCount = D('Message')->where($map)->order('create_time desc')->count(); //用于分页

        foreach ($messages as &$v) {
            if ($v['from_uid'] != 0) {
                $v['from_user'] = query_user(array('username', 'space_url', 'avatar64', 'space_link'), $v['from_uid']);
            }
        }

        $this->assign('totalCount', $totalCount);
        $this->assign('messages', $messages);

        //设置Tab
        $this->defaultTabHash('message');
        $this->assign('tab', $tab);
        $this->display();
    }

    /**
     * 聊天列表页面
     */
    public function session()
    {
        $this->defaultTabHash('session');
        $talks = $this->mTalkModel->where('uids like' . '"%[' . is_login() . ']%"' . ' and status=1')->order('update_time desc')->select();
        foreach ($talks as $key => $v) {
            $users = array();
            $uids_array = $this->mTalkModel->getUids($v['uids']);
            foreach ($uids_array as $uid) {
                $users[] = query_user(array('avatar64', 'username', 'space_link', 'id'), $uid);
            }
            $talks[$key]['users'] = $users;
            $talks[$key]['last_message'] = D('Talk')->getLastMessage($talks[$key]['id']);
        }
        $this->assign('talks', $talks);
        $this->display();
    }

    /**对话页面
     * 创建聊天或显示现有聊天。
     * @param int $message_id 消息ID 只提供消息则从消息自动创建一个聊天
     * @param int $talk_id 聊天ID
     */
    public function talk($message_id = 0, $talk_id = 0)
    {
        //获取当前聊天
        $talk = $this->getTalk($message_id, $talk_id);
        $map['talk_id'] = $talk['id'];
        $messages = D('TalkMessage')->where($map)->order('create_time desc')->limit(20)->select();
        $messages = array_reverse($messages);
        foreach ($messages as &$mes) {
            $mes['user'] = query_user(array('avatar128', 'uid', 'username'), $mes['uid']);
            $mes['content'] = op_t($mes['content']);
        }
        unset($mes);
        $this->assign('messages', $messages);

        $this->assign('talk', $talk);
        $self = query_user(array('avatar128'), is_login());
        $this->assign('self', $self);
        $this->assign('mid', is_login());
        $this->defaultTabHash('session');
        $this->display();
    }

    /**
     * 删除现有聊天
     */
    public function doDeleteTalk($talk_id)
    {
        $this->requireLogin();

        //确认当前用户属于聊天。
        $talk = $this->mTalkModel->find($talk_id);
        $uid = get_uid();
        if (false === strpos($talk['uids'], "[$uid]")) {
            $this->error('您没有权限删除该聊天');
        }

        //如果删除前聊天中只有两个人，就将聊天标记为已删除。
        $uids = explode(',', $talk['uids']);
        if (count($uids) <= 2) {
            $this->mTalkModel->where(array('id' => $talk_id))->setField('status', -1);
            M('talk_message_push')->where(array('talk_id' => $talk_id))->delete();
            M('talk_push')->where(array('source_id' => $talk_id))->delete();
            /*D('Message')->where(array('talk_id' => $talk_id))->setField('talk_id', 0);*/
        } //如果删除前聊天中有多个人，就退出聊天。
        else {
            $uids = array_diff($uids, array("[$uid]"));
            $uids = implode(',', $uids);
            $this->mTalkModel->where(array('id' => $talk_id))->save(array('uids' => $uids));
            /*D('Message')->where(array('talk_id' => $talk_id, 'uid' => get_uid()))->setField('talk_id', 0);*/
        }

        //返回成功结果
        $this->success('删除成功', 'refresh');
    }

    /**回复的时候调用，通过该函数，会回调应用对应的postMessage函数实现对原始内容的数据添加。
     * @param $content 内容文本
     * @param $talk_id 聊天ID
     */
    public function postMessage($content, $talk_id)
    {
        //空的内容不能发送
        if (!trim($content)) {
            $this->error(L('_ERROR_CHAT_CONTENT_EMPTY_'));
        }

        D('TalkMessage')->addMessage($content, is_login(), $talk_id);
        $talk = $this->mTalkModel->find($talk_id);
        $message = D('Message')->find($talk['message_id']);
        $messageModel = $this->getMessageModel($message);
        $rs = $messageModel->postMessage($message, $talk, $content, is_login());

        D('TalkMessage')->sendMessage($content, $this->mTalkModel->getUids($talk['uids']), $talk_id);
        if (!$rs) {
            $this->error(L('_ERROR_DB_WRITE_'));
        }

        $this->success(L('_SUCCESS_SEND_'));
    }

    /**
     * @param $message
     * @return \Model
     */
    private function getMessageModel($message)
    {

        $appname = ucwords($message['appname']);
        $messageModel = D($appname . '/' . $appname . 'Message');
        return $messageModel;
    }

    /**
     * @param $message_id
     * @param $talk_id
     * @param $map
     * @return array
     */
    private function getTalk($message_id, $talk_id)
    {
        $talk = $this->mTalkModel->find($talk_id);
        $uids_array = $this->mTalkModel->getUids($talk['uids']);
        if (!count($uids_array)) {
            $this->error(L('_ERROR_POWER_EXCEED_'));
            return $talk;
        }
        return $talk;
    }

    /**
     * @param $tab
     * @param $map
     * @return mixed
     */
    private function getMapByTab($tab, $map)
    {
        switch ($tab) {
            case 'system':
                $map['type'] = 0;
                break;
            case 'user':
                $map['type'] = 1;
                break;
            case 'app':
                $map['type'] = 2;
                break;
            case 'all':
                break;
            default:
                $map['is_read'] = 0;
                break;
        }
        return $map;
    }

    /**创建聊天，
     * @auth 陈一枭
     */
    public function createTalk()
    {
        $aUids = I('post.uids', '', 'op_t');
        if ($aUids == '') {
            exit;
        }
        $memebers = explode(',', $aUids);
        $talk = $this->mTalkModel->createTalk($memebers);
        $this->success($talk);

    }

}