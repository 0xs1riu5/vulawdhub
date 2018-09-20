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

class MessageController extends BaseController
{
    protected $mTalkModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->mTalkModel = D('Talk');
    }

    public function index()
    {

    }

    /**消息页面
     * @param int $page
     * @param string $tab 当前tab
     */
    public function message($page = 1, $tab = 'unread')
    {
        //从条件里面获取Tab
        $map = $this->getMapByTab($tab);
        $map['to_uid'] = is_login();

        $messages = D('Message')->where($map)->order('create_time desc')->page($page, 10)->select();
        $totalCount = D('Message')->where($map)->order('create_time desc')->count(); //用于分页
//dump($messages);exit;
        foreach ($messages as &$v) {
            $v['content'] = D('Common/Message')->getContent($v['content_id']);
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
            $mes['user'] = query_user(array('avatar128', 'uid', 'username', 'nickname'), $mes['uid']);
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
    public function doDeleteTalk($talk_id = 0)
    {
        $this->requireLogin();

        //确认当前用户属于聊天。
        $talk = D('Talk')->find($talk_id);
        $uid = get_uid();
        if (false === strpos($talk['uids'], "[$uid]")) {
            $this->error(L('_ERROR_AUTHORITY_CHAT_DELETE_'));
        }

        //如果删除前聊天中只有两个人，就将聊天标记为已删除。
        $uids = explode(',', $talk['uids']);
        if (count($uids) <= 2) {
            D('Talk')->where(array('id' => $talk_id))->setField('status', -1);
            D('Message')->where(array('talk_id' => $talk_id))->setField('talk_id', 0);
        } //如果删除前聊天中有多个人，就退出聊天。
        else {
            $uids = array_diff($uids, array("[$uid]"));
            $uids = implode(',', $uids);
            D('Talk')->where(array('id' => $talk_id))->save(array('uids' => $uids));
            D('Message')->where(array('talk_id' => $talk_id, 'uid' => get_uid()))->setField('talk_id', 0);
        }

        //返回成功结果
        $this->success(L('_SUCCESS_DELETE_'), 'refresh');
    }

    /**回复的时候调用，通过该函数，会回调应用对应的postMessage函数实现对原始内容的数据添加。
     * @param $content 内容文本
     * @param $talk_id 聊天ID
     */
    public function postMessage($content, $talk_id)
    {
        $content = op_t($content);
        //空的内容不能发送
        if (!trim($content)) {
            $this->error(L('_ERROR_CHAT_CONTENT_EMPTY_'));
        }

        D('TalkMessage')->addMessage($content, is_login(), $talk_id);
        $talk = D('Talk')->find($talk_id);
        $message = D('Message')->find($talk['message_id']);

        if ($talk['appname'] != '') {
            $messageModel = $this->getMessageModel($message);

            $messageModel->postMessage($message, $talk, $content, is_login());
        }
        exit(json_encode(array('status' => 1, 'content' => parse_expression($content))));
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
        if ($message_id != 0) {
            /*如果是传递了message_id，就是创建对话*/
            $message = D('Message')->find($message_id);

            //权限检测，防止越权创建聊天
            if (($message['to_uid'] != $this->mid && $message['from_uid'] != $this->mid) || !$message) {
                $this->error(L('_ERROR_ILLEGAL_OPERATE_'));
            }

            //如果已经创建过聊天了，就不再创建
            $map['message_id'] = $message_id;
            $map['status'] = 1;
            $talk = D('Talk')->where($map)->find();
            if ($talk) {
                redirect(U('Ucenter/Message/talk', array('talk_id' => $talk['id'])));
            }

            /*创建talk*/
            $talk['uids'] = implode(',', array('[' . is_login() . ']', '[' . $message['from_uid'] . ']'));
            $talk['appname'] = $message['appname'];
            $talk['apptype'] = $message['apptype'];
            $talk['source_id'] = $message['source_id'];
            $talk['message_id'] = $message_id;

            //通过消息获取到对应应用内的消息模型
            $messageModel = $this->getMessageModel($message);
            //从对应模型内取回对话源资料
            $talk = array_merge($messageModel->getSource($message), $talk);

            //创建聊天
            $talk = D('Talk')->create($talk);
            $talk['id'] = D('Talk')->add($talk);
            /*创建talk end*/


            //关联聊天到当前消息
            $message['talk_id'] = $talk['id'];
            D('Message')->save($message);

            //插入第一条消息
            $talkMessage['uid'] = $message['from_uid'];
            $talkMessage['talk_id'] = $talk['id'];
            $talkMessage['content'] = $messageModel->getFindContent($message);
            $talkMessageModel = D('TalkMessage');
            $talkMessage = $talkMessageModel->create($talkMessage);
            $talkMessage['id'] = $talkMessageModel->add($talkMessage);


            D('Message')->sendMessage($message['from_uid'], L('_MESSAGE_CHAT_1_'), L('_MESSAGE_CHAT_2_') . $talk['title'], 'Ucenter/Message/talk', array('talk_id' => $talk['id']));

            return $talk;

        } else {
            $talk = D('Talk')->find($talk_id);
            $uids_array = $this->mTalkModel->getUids($talk['uids']);
            if (!count($uids_array)) {
                $this->error(L('_ERROR_POWER_EXCEED_'));
                return $talk;
            }
            return $talk;
        }
    }


    /**
     * @param $tab
     * @param $map
     * @return mixed
     */
    private function getMapByTab($tab)
    {
        $map = array();
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


    /**—————————————改版会话后zzl———————————————*/
    /**
     * 展示用户会话列表
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function messageSession()
    {
        $messageModel=D('Common/Message');
        $messageSessionList=$messageModel->getMyMessageSessionList();
        $this->assign('message_session_list',$messageSessionList);
        $session_tpl=modC('MESSAGE_SESSION_TPL','session3','Message');
        $this->display(T('Application://Ucenter@default/Message/session_tpl/'.$session_tpl));
    }

    public function messageDetail()
    {
        $aMessageSession=I('post.message_session','','text');
        //根据消息类型获取消息列表
        $tpl=$this->_messageList($aMessageSession);
        $this->display($tpl);
    }

    /**
     * 加载更多
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function loadMore()
    {
        $aStart=I('post.start',0,'intval');
        $aMessageSession=I('post.message_session','','text');
        $aNum=I('post.num',5,'intval');
        $messageModel=D('Common/Message');
        $messageList=$messageModel->getSessionMessage($aMessageSession,$aStart,$aNum);
        $html='';
        $messageList=$this->_fetchHtml($messageList);
        foreach($messageList as $val){
            $html.=$val['html'];
        }
        unset($val);
        $this->ajaxReturn($html);
    }

    /**
     * 处理消息相关流程
     * @param $message_session
     * @return string 消息模板地址
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _messageList($message_session)
    {
        $messageModel=D('Common/Message');
        $info=$messageModel->getInfo($message_session);
        $this->assign('message_session_info',$info);

        //获取消息列表
        list($messageList,$count,$has_unread_message)=$messageModel->getSessionMessageFirst($message_session);
        $messageList=$this->_fetchHtml($messageList);
        $this->assign('message_list',$messageList);
        $this->assign('now_count',$count);
        //设置未读消息为已读
        $messageModel->setAllReaded(is_login(),$message_session);

        if($has_unread_message&&$message_session=="Common_announce"){//对于公告消息，设置公告送达;没有新消息时，不做该步骤
            D('Common/AnnounceArrive')->setAllArrive(is_login());
        }
        if($info['block_tpl']){
            $tpl=T('Application://'.$info['module'].'@default/MessageTpl/block/'.$info['block_tpl']);
        }else{
            $tpl=T('Application://'.$info['module'].'@default/MessageTpl/block/_message_block');
        }
        return $tpl;
    }

    /**
     * 渲染消息模板
     * @param $list
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _fetchHtml($list)
    {
        foreach($list as &$val){
            if($val['line']){
                continue;
            }
            unset($tpl);
            if($val['tpl']){
                $message_tpl=get_message_tpl();
                $tpl_info=$message_tpl[$val['tpl']];
                if($tpl_info){
                    $tpl=T('Application://'.$tpl_info['module'].'@default/MessageTpl/tpl/'.$tpl_info['tpl_name']);
                }
            }
            if(!isset($tpl)){
                $tpl=T('Application://Common@default/MessageTpl/tpl/_message_li');
            }
            $this->assign('data',$val['content']);
            $val['html']=$this->fetch($tpl);
        }
        return $list;
    }

}