<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 想天
 * 创建日期: 3/13/14
 * 创建时间: 7:41 PM
 * 版权所有 想天工作室(www.ourstu.com)
 */

namespace Common\Model;

use Think\Model;

class MessageModel extends Model
{


    /**
     * sendMessage   发送消息，屏蔽自己
     * @param $to_uids 接收消息的用户们
     * @param string $title 消息标题
     * @param string $content 消息内容
     * @param string $url 消息指向的路径，U函数的第一个参数
     * @param array $url_args 消息链接的参数，U函数的第二个参数
     * @param int $from_uid 发送消息的用户
     * @param string $type 消息类型标识，对应各模块message_config.php中设置的消息类型
     * @param string $tpl 消息模板标识，对应各模块message_config.php中设置的消息模板
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendMessage($to_uids, $title = '您有新的消息', $content = '', $url = '', $url_args = array(), $from_uid = -1, $type = 'Common_system', $tpl = '')
    {
        $to_uids = is_array($to_uids) ? $to_uids : array($to_uids);
        $from_uid == -1 && $from_uid = is_login();
        $k = array_search($from_uid, $to_uids);
        if ($k !== false) {
            unset($to_uids[$k]);
        }
        if (count($to_uids) > 0) {
            $to_uids=$this->_removeOldUser($to_uids);
            if(!count($to_uids)){
                return false;
            }
            return $this->sendMessageWithoutCheckSelf($to_uids, $title, $content, $url, $url_args, $from_uid, $type, $tpl);
        } else {
            return false;
        }
    }

    /**
     * sendMessageWithoutCheckSelf  发送消息，不屏蔽自己
     * @param $to_uids 接收消息的用户们
     * @param string $title 消息标题
     * @param string $content 消息内容
     * @param string $url 消息指向的路径，U函数的第一个参数
     * @param array $url_args 消息链接的参数，U函数的第二个参数
     * @param int $from_uid 发送消息的用户
     * @param string $type 消息类型标识，对应各模块message_config.php中设置的消息类型
     * @param string $tpl 消息模板标识，对应各模块message_config.php中设置的消息模板
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendMessageWithoutCheckSelf($to_uids, $title = '您有新的消息', $content = '', $url = '', $url_args = array(), $from_uid = -1, $type = 'Common_system', $tpl = '')
    {
        $to_uids = is_array($to_uids) ? $to_uids : array($to_uids);
        $to_uids=$this->_removeOldUser($to_uids);
        if(!count($to_uids)){
            return false;
        }
        if (in_array($type, array('1', '2', '3', '0', '4', '5'))) {
            $type = 'Common_system';
        }
        $from_uid == -1 && $from_uid = is_login();
        $message_content_id = $this->addMessageContent($from_uid, $title, $content, $url, $url_args, $type);

        $this->_initUserMessageSession($to_uids, $type);

        foreach ($to_uids as $to_uid) {
            $message['to_uid'] = $to_uid;
            $message['content_id'] = $message_content_id;
            $message['from_uid'] = $from_uid;
            $message['create_time'] = time();
            $message['type'] = $type;
            $message['status'] = 1;
            $message['tpl'] = $tpl;
            $this->add($message);
            unset($message);
        }
        return true;
    }
    public function sendEmail($to_uids, $title = '您有新的消息', $content = '', $url = '', $url_args = array(), $from_uid = -1, $type = 'Common_email', $tpl = '')
    {
        $from_uid == -1 && $from_uid = is_login();
        $message_content_id = $this->addMessageContent($from_uid, $title, $content, $url, $url_args, $type);
        $to_uids = is_array($to_uids) ? $to_uids : array($to_uids);
        $result=true;
        $count = count($to_uids);
        $i = 0;
        $num = 100;//每次插入100条
        do {
            $do_to_uids = array_slice($to_uids, $i * $num, $num);
            $this->_initUserMessageSession($do_to_uids, $type);
            $dataList = array();
            foreach ($do_to_uids as $to_uid) {
                $user=query_user('mobile,email',$to_uid);
                if(!empty($aUrl)){
                    $newUrl='http://' . $_SERVER['HTTP_HOST'] .U($url);
                    $newContent=$content.'<a href='.$newUrl.'>'.$newUrl.'</a>';
                }else{
                    $newContent=$content;
                }
                $res=send_mail($user['email'],$title,$newContent);
                if($res===true){
                    $message['to_uid'] = $to_uid;
                    $message['content_id'] = $message_content_id;
                    $message['from_uid'] = $from_uid;
                    $message['create_time'] = time();
                    $message['type'] = $type;
                    $message['status'] = 1;
                    $message['tpl'] = $tpl;
                    $dataList[] = $message;
                    unset($message);
                }else{
                    $result=$res;
                }

            }
            unset($to_uid);
            $this->addAll($dataList);
            unset($dataList);
            $count -= $num;
            $i = $i + 1;
        } while ($count > 0);
        return $result;
    }
    public function sendMobileMessage($to_uids, $title = '您有新的消息', $content = '', $url = '', $url_args = array(), $from_uid = -1, $type = 'Common_mobile', $tpl = '')
    {
        $from_uid == -1 && $from_uid = is_login();
        $message_content_id = $this->addMessageContent($from_uid, $title, $content, $url, $url_args, $type);
        $to_uids = is_array($to_uids) ? $to_uids : array($to_uids);

        $count = count($to_uids);
        $i = 0;
        $num = 100;//每次插入100条
        $result=true;
        do {
            $do_to_uids = array_slice($to_uids, $i * $num, $num);
            $this->_initUserMessageSession($do_to_uids, $type);
            $dataList = array();
            foreach ($do_to_uids as $to_uid) {
                $user=query_user('mobile,email',$to_uid);
                if(!empty($aUrl)){
                    $newUrl='http://' . $_SERVER['HTTP_HOST'] .U($url);
                    $newContent='【'.$title.'】 网站地址'.$newUrl.'。'.$content;
                }else{
                    $newContent='【'.$title.'】'.$content;
                }
                $res=sendSMS($user['mobile'],$newContent);
                if($res===true){
                    $message['to_uid'] = $to_uid;
                    $message['content_id'] = $message_content_id;
                    $message['from_uid'] = $from_uid;
                    $message['create_time'] = time();
                    $message['type'] = $type;
                    $message['status'] = 1;
                    $message['tpl'] = $tpl;
                    $dataList[] = $message;
                    unset($message);
                }else{
                    $result=$res;
                }

            }
            unset($to_uid);
            $this->addAll($dataList);
            unset($dataList);
            $count -= $num;
            $i = $i + 1;
        } while ($count > 0);
        return $result;
    }
    /**
     * sendALotOfMessageWithoutCheckSelf  发送很多消息，不屏蔽自己（用于公告发送消息）
     * @param $to_uids 接收消息的用户们
     * @param string $title 消息标题
     * @param string $content 消息内容
     * @param string $url 消息指向的路径，U函数的第一个参数
     * @param array $url_args 消息链接的参数，U函数的第二个参数
     * @param int $from_uid 发送消息的用户
     * @param string $type 消息类型标识，对应各模块message_config.php中设置的消息类型
     * @param string $tpl 消息模板标识，对应各模块message_config.php中设置的消息模板
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function sendALotOfMessageWithoutCheckSelf($to_uids, $title = '您有新的消息', $content = '', $url = '', $url_args = array(), $from_uid = -1, $type = 'Common_system', $tpl = '')
    {
        $to_uids = is_array($to_uids) ? $to_uids : array($to_uids);
        $to_uids=$this->_removeOldUser($to_uids);
        if(!count($to_uids)){
            return false;
        }
        if (in_array($type, array('1', '2', '3', '0', '4', '5'))) {
            $type = 'Common_system';
        }
        $from_uid == -1 && $from_uid = is_login();
        $message_content_id = $this->addMessageContent($from_uid, $title, $content, $url, $url_args, $type);

        $count = count($to_uids);
        $i = 0;
        $num = 100;//每次插入100条
        do {
            $do_to_uids = array_slice($to_uids, $i * $num, $num);
            $this->_initUserMessageSession($do_to_uids, $type);
            $dataList = array();
            foreach ($do_to_uids as $to_uid) {
                $message['to_uid'] = $to_uid;
                $message['content_id'] = $message_content_id;
                $message['from_uid'] = $from_uid;
                $message['create_time'] = time();
                $message['type'] = $type;
                $message['status'] = 1;
                $message['tpl'] = $tpl;
                $dataList[] = $message;
                unset($message);
            }
            unset($to_uid);
            $this->addAll($dataList);
            unset($dataList);
            $count -= $num;
            $i = $i + 1;
        } while ($count > 0);
        return true;
    }

    /**
     * 去除一个月没有登录的用户
     * @param $to_uids
     * @return array
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    private function _removeOldUser($to_uids)
    {
        $map['uid']=array('in',$to_uids);
        $map['status']=1;
        $map['last_login_time']=array('gt',get_time_ago('month'));
        $uids=M('Member')->where($map)->field('uid')->select();
        $uids=array_column($uids,'uid');
        return $uids;
    }

    /**
     * 初始化用户会话类型，没有的补上
     * @param $uids
     * @param $type
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initUserMessageSession($uids, $type)
    {
        $messageTypeModel = M('MessageType');
        $map['uid'] = array('in', $uids);
        $map['type'] = $type;
        $already_uids = $messageTypeModel->where($map)->field('uid')->select();
        $already_uids = array_column($already_uids, 'uid');
        $need_uids = array_diff($uids, $already_uids);
        $dataList = array();
        foreach ($need_uids as $val) {
            S('MY_MESSAGE_SESSION_' . $val, null);
            $dataList[] = array('uid' => $val, 'type' => $type, 'status' => 1);
        }
        unset($val);
        if (count($dataList)) {
            $messageTypeModel->addAll($dataList);
        }
        return true;
    }

    /**
     * addMessageContent  添加消息内容到表
     * @param $from_uid 发送消息的用户
     * @param $title 消息的标题
     * @param $content 消息内容
     * @param $url 消息指向的路径，U函数的第一个参数
     * @param $url_args 消息链接的参数，U函数的第二个参数
     * @param string $type 消息类型，对应各模块message_config.php中设置的会话类型
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function addMessageContent($from_uid, $title, $content, $url, $url_args, $type)
    {
        $data_content['from_id'] = $from_uid;
        $data_content['title'] = $title;
        $data_content['content'] = is_array($content) ? json_encode($content) : $content;
        $data_content['url'] = $url;
        $data_content['args'] = empty($url_args) ? '' : json_encode($url_args);
        $data_content['type'] = $type;
        $data_content['create_time'] = time();
        $data_content['status'] = 1;
        $message_id = D('message_content')->add($data_content);
        return $message_id;
    }


    public function getContent($id)
    {
        $content = S('message_content_' . $id);
        if (empty($content)) {
            $content = D('message_content')->find($id);
            if ($content) {
                $content['args'] = json_decode($content['args'], true);
                $content['args_json'] = json_encode($content['args']);
                if ($content['url']) {
                    $content['web_url'] = is_bool(strpos($content['url'], 'http://')) ? U($content['url'], $content['args']) : $content['url'];
                } else {
                    $content['web_url'] = '';
                }
                if (!is_null(json_decode($content['content']))) {
                    $content['content'] = json_decode($content['content'], true);
                    foreach ($content['content'] as &$val) {
                        $val = html($val);
                    }
                    unset($val);
                    $content['tip'] = $content['title'];
                } else {
                    $content['tip'] = $content['content'];
                }
                if (in_array($content['type'], array('1', '2', '3', '0', '4', '5'))) {
                    $content['type'] = 'Common_system';
                }
                if ($content['from_id']) {
                    $content['user'] = query_user(array('nickname', 'avatar64', 'uid'), $content['from_id']);
                }
            }
            S('message_content_' . $id, $content, 60 * 60);
        }

        return $content;
    }

    /**获取全部未读消息数
     * @param $uid 用户ID
     * @return mixed
     */
    public function getHaventReadMessageCount($uid)
    {
        !$uid&&$uid=is_login();
        if(!$uid){
            return 0;
        }
        $map['to_uid']=$uid;
        $map['is_read']=0;
        $map['type']=array('notlike',array('%Common_email%','%Common_mobile%'),'AND');
        $count = $this->where($map)->count();
        return $count;
    }

    /*————————————————改版消息 ————————————————————*/

    public function readMessage($message_id)
    {
        return $this->where(array('id' => $message_id))->setField('is_read', 1);
    }

    public function setAllReaded($uid, $message_session = '')
    {
        if ($message_session != '') {
            $map['type'] = $message_session;
            $this->_transferMessage($uid,$message_session);
        }
        $map['to_uid'] = $uid;
        $map['is_read'] = 0;
        $this->where($map)->setField('is_read', 1);
        return true;
    }

    /**
     * 转移老数据
     * @param $uid
     * @param string $message_session
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    private function _transferMessage($uid,$message_session='Common_system')
    {
        $map['type']=$message_session;
        $map['to_uid']=$uid;
        $map['is_read']=1;
        $dataList=$this->where($map)->order('id desc')->limit(5, 999)->select();
        if(count($dataList)){
            $res=M('MessageOld')->addAll($dataList);
            if($res){
                $ids=array_column($dataList,'id');
                $map_delete['id']=array('in',$ids);
                $this->where($map_delete)->delete();
            }
        }

        //移动一个月未登录用户的消息，并设为已读
        $time=S('last_message_old_time');
        if($time==null||$time<get_time_ago('day',10)){
            $uids=$this->where(1)->field('to_uid')->group('to_uid')->select();
            $uids=array_column($uids,'to_uid');
            $uids=$this->_removeOldUser($uids);
            $map_uid['to_uid']=array('not in',$uids);
            do{
                $oldDataList=$this->where($map_uid)->limit(999)->select();
                if(count($oldDataList)){
                    $messageOldModel=M('MessageOld');
                    $res=$messageOldModel->addAll($oldDataList);
                    if($res){
                        S('last_message_old_time',time());
                        $messageOldModel->where(array('is_read'=>0))->setField('is_read',1);
                        $ids=array_column($oldDataList,'id');
                        $map_delete['id']=array('in',$ids);
                        $this->where($map_delete)->delete();
                    }
                }
            }while($this->where($map_uid)->count());
        }
        return true;
    }

    /**获取全部没有提示过的消息
     * @param $uid 用户ID
     * @return mixed
     */
    public function getHaventToastMessage($uid)
    {
        $messages = D('message')->where('to_uid=' . $uid . ' and  is_read=0  and last_toast=0')->order('id desc')->limit(99999)->select();
        $this->_initMessage($messages);
        return $messages;
    }

    /**设置全部未提醒过的消息为已提醒
     * @param $uid
     */
    public function setAllToasted($uid)
    {
        $now = time();
        D('message')->where('to_uid=' . $uid . ' and  is_read=0 and last_toast=0')->setField('last_toast', $now);
    }

    /*————————————————改版消息————————————————————*/

    /**
     * 取回某类型全部未读信息
     * @param $map
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function getHaventReadMeassage($map)
    {
        $map['is_read'] = 0;
        $map['status'] = 1;
        $messages = $this->where($map)->order('id desc')->limit(50)->select();
        $this->_initMessage($messages);
        return $messages;
    }

    /**
     * 获取已读消息列表
     * @param $map
     * @param int $start 开始
     * @param int $num 获取数量
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function getReadMessage($map, $start = 0, $num = 5)
    {
        $map['is_read'] = 1;
        $map['status'] = 1;
        $messages = $this->where($map)->order('id desc')->limit($start, $num)->select();
        $this->_initMessage($messages);
        return $messages;
    }

    /**
     * 加载更多消息
     * @param $session
     * @param int $start
     * @param int $num
     * @param int $uid
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getSessionMessage($session, $start = 0, $num = 5, $uid = 0)
    {
        !$uid && $uid = is_login();
        $map['to_uid'] = $uid;
        $map['type'] = $session;
        $messages = M('MessageOld')->where($map)->order('id desc')->limit($start, $num)->select();
        $this->_initMessage($messages);
        return $messages;
    }

    /**
     * 取回某类型的首次展示列表
     * @param $uid
     * @param string $type 会话类型
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getSessionMessageFirst($type = 'Common_system', $uid)
    {
        !$uid && $uid = is_login();
        $map['to_uid'] = $uid;
        $map['type'] = $type;
        $unreadMessageList = $this->getHaventReadMeassage($map);
        if (count($unreadMessageList)) {
            $has_unread_message = 1;
            if(count($unreadMessageList)==50){
                $readMessageList=array_slice($unreadMessageList,47,3);
                $unreadMessageList=array_slice($unreadMessageList,0,47);
            }else{
                $readMessageList = $this->getReadMessage($map);
            }
            if (count($readMessageList)) {
                $messageList = array_merge($unreadMessageList, array(array('line' => 1)), $readMessageList);
                $count = count($unreadMessageList) + count($readMessageList);
            } else {
                $messageList = $unreadMessageList;
                $count = count($unreadMessageList);
            }
        } else {
            $readMessageList = $this->getReadMessage($map);
            $messageList = $readMessageList;
            $count = count($readMessageList);
        }
        return array($messageList, $count, $has_unread_message);
    }

    /**
     * 获取全部会话类型
     * @return array|void
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getAllMessageSession()
    {
        $tag = 'ALL_MESSAGE_SESSION';
        $message_session = S($tag);
        if ($message_session === false) {
            $message_session = load_config(CONF_PATH . 'message_config.php');
            $message_session = $message_session['session'];
            foreach ($message_session as &$val) {
                if ($val['name'] == '') {
                    $val['name'] = 'Common';
                } else {
                    $val['name'] = 'Common_' . $val['name'];
                }
                $val['module'] = 'Common';
                $val['logo'] = __ROOT__ . '/Public/images/message_logo/' . $val['logo'];
                !isset($val['sort']) && $val['sort'] = 0;
                $val['alias'] = '系统';
            }
            unset($val);

            $model_message_session = array();
            $module_alias = array();
            $modules = D('Common/Module')->getAll(1);
            foreach ($modules as $val) {
                $path = APP_PATH . $val['name'] . '/Conf/message_config.php';
                if (file_exists($path)) {
                    $conf = load_config($path);
                    $conf = $conf['session'];
                }
                if (!in_array($val['name'], array('Core'))) {
                    //模块默认会话类型，logo使用模块logo，现在先用null代替,在下面替换为模块logo
                    if($val['name'] == 'Weibo') {
                        $addArray = array(array('name' => '', 'title' => '动态消息', 'logo' => NULL));
                    } else {
                        $addArray = array(array('name' => '', 'title' => $val['alias'] . '消息', 'logo' => NULL));
                    }
                    if (count($conf)) {
                        $conf = array_merge($conf, $addArray);
                    } else {
                        $conf = $addArray;
                    }
                }
                $model_message_session[$val['name']] = $conf;
                $conf = null;
                $module_alias[$val['name']] = $val['alias'];
            }
            unset($val);
            foreach ($model_message_session as $key => $val) {
                $has = 0;
                foreach ($val as $one_type) {
                    if ($one_type['name'] == '') {
                        if ($has == 0) {
                            $has = 1;
                            $one_type['name'] = $key;
                        } else {
                            unset($model_message_session[$key]);
                            continue;
                        }
                    } else {
                        $one_type['name'] = $key . '_' . $one_type['name'];
                    }
                    $one_type['module'] = $key;
                    !isset($one_type['sort']) && $one_type['sort'] = 0;
                    if ($one_type['logo'] == null) {//模块会话使用模块名logo作为会话logo
                        $one_type['logo'] = __ROOT__ . '/Application/' . $key . '/Static/images/message_logo/' . strtolower($key) . '.png';
                    } else {
                        $one_type['logo'] = __ROOT__ . '/Application/' . $key . '/Static/images/message_logo/' . $one_type['logo'];
                    }
                    $one_type['alias'] = $module_alias[$key];
                    $message_session[] = $one_type;
                }
            }
            unset($key, $val, $one_type);
            $message_session = array_combine(array_column($message_session, 'name'), $message_session);
            S($tag, $message_session);
        }
        return $message_session;
    }

    /**
     * 获取全部消息模板
     * @return array|void
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getAllMessageTpl()
    {
        $tag = 'ALL_MESSAGE_TPL';
        $message_tpls = S($tag);
        if ($message_tpls === false) {
            $message_tpls = load_config(CONF_PATH . 'message_config.php');
            $message_tpls = $message_tpls['tpl'];
            foreach ($message_tpls as &$val) {
                $val['name'] = 'Common_' . $val['name'];
                !$val['module'] && $val['module'] = 'Common';
                $val['alias'] = "系统";
            }
            unset($val);

            $model_message_tpls = array();
            $module_alias = array();
            $modules = D('Common/Module')->getAll(1);
            foreach ($modules as $val) {
                $path = APP_PATH . $val['name'] . '/Conf/message_config.php';
                if (file_exists($path)) {
                    $conf = load_config($path);
                    $model_message_tpls[$val['name']] = $conf['tpl'];
                }
                $module_alias[$val['name']] = $val['alias'];
            }
            unset($val);
            foreach ($model_message_tpls as $key => $val) {
                foreach ($val as $one_tpl) {
                    $one_tpl['name'] = $key . '_' . $one_tpl['name'];
                    !$one_tpl['module'] && $one_tpl['module'] = $key;
                    $one_tpl['alias'] = $module_alias[$one_tpl['module']];
                    $message_tpls[] = $one_tpl;
                }
            }
            unset($key, $val, $one_type);
            $message_tpls = array_combine(array_column($message_tpls, 'name'), $message_tpls);
            S($tag, $message_tpls);
        }
        return $message_tpls;
    }

    /**
     * 获取某人的会话类型
     * @param int $uid
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getMyMessageSession($uid = 0)
    {
        !$uid && $uid = is_login();
        if (!$uid) {
            $this->error = '请先登录！';
        }
        $tag = 'MY_MESSAGE_SESSION_' . $uid;
        $message_session = S($tag);
        if ($message_session === false) {
            $map['uid'] = $uid;
            $map['status'] = 1;
            $message_session = M('MessageType')->where($map)->select();
            if(!count($message_session)){//默认所有用户拥有系统会话
                $message_session[]=array('uid'=>$uid,'type'=>'Common_system','status'=>1);
            }
            S($tag, $message_session);
        }
        return $message_session;
    }

    /**
     * 获取用户会话类型列表，带详情和消息数统计及排序的
     * @param int $uid
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getMyMessageSessionList($uid = 0)
    {
        !$uid && $uid = is_login();
        if (!$uid) {
            $this->error = '请先登录！';
        }
        $my_types = $this->getMyMessageSession($uid);
        $type_list = $this->getAllMessageSession();
        foreach ($my_types as $key => &$val) {
            if (!$type_list[$val['type']]) {
                unset($my_types[$key]);
                continue;
            }
            $val['detail'] = $type_list[$val['type']];
            $map_last['to_uid'] = $uid;
            $map_last['type'] = $val['type'];
            $map = $map_last;
            $map['is_read'] = 0;
            $val['count'] = $this->where($map)->count();
            $lastMessage = $this->where($map_last)->order('id desc')->find();
            if ($lastMessage) {
                $val['last_message'] = $this->getContent($lastMessage['content_id']);
            }
            if ($val['count'] > 0) {
                $val['sort'] = $val['detail']['sort'] + 1000;
            } else {
                $val['sort'] = $val['detail']['sort'];
            }
        }
        unset($val);
        return list_sort_by($my_types, 'sort', 'desc');
    }

    /**
     * 根据类型标识获取类型详细信息
     * @param $type
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getInfo($type)
    {
        $allType = $this->getAllMessageSession();
        return $allType[$type];
    }

    private function _initMessage(&$messages)
    {
        foreach ($messages as &$v) {
            if (in_array($v['type'], array('1', '2', '3', '0', '4', '5'))) {
                $v['type'] = 'Common_system';
            }
            $v['ctime'] = friendlyDate($v['create_time']);
            $v['content'] = $this->getContent($v['content_id']);
            if (is_array($v['content']['content'])) {
                $v['content']['untoastr'] = 1;
            }
        }
        unset($v);
        return true;
    }
    /**
     * 检测关注话题的用户是否已读上次的消息提示
     */
    public function topicMessageRead($content,$uids)
    {
        $content_id=D('message_content')->where(array('title'=>'话题通知','content'=>'您关注的#'.$content.'#话题已更新。','url'=>'Weibo/Topic/index'))->field('id')->select();
         foreach ($content_id as $cid){
             $cids[]=$cid['id'];
         }
        $map['content_id']=array('in',$cids);
         $data=D('Message')->where($map)->select();
        if(!$data){
            $readUids=$uids;
        }else{
            foreach ($data as $vo)
            {
                if($vo['is_read']==1){
                    $readUids[]=$vo['to_uid'];
                }
            }
            $readUids=array_unique($readUids);
        }

            
        return $readUids;


    }

    /*****************以下方法v3中弃用了，保留为了兼容v2调用********************/
    /**取回全部未读,也没有提示过的信息
     * @param $uid
     * @return mixed
     */
    public function getHaventReadMeassageAndToasted($uid)
    {
        $messages = D('message')->where('to_uid=' . $uid . ' and  is_read=0  and last_toast!=0')->order('id desc')->limit(99999)->select();
        foreach ($messages as &$v) {
            $v['ctime'] = friendlyDate($v['create_time']);
            $v['content'] = $this->getContent($v['content_id']);
        }
        unset($v);
        return $messages;
    }
}