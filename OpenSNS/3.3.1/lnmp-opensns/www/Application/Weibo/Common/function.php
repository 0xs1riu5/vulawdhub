<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-26
 * Time: 上午10:43
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


/**
 * send_weibo  发布动态
 * @param $content
 * @param $type
 * @param string $feed_data
 * @param string $from
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_weibo($content, $type, $feed_data = '', $from = '')
{
    $topicModel=D('Weibo/Topic');
    $topicFollowModel=D('Weibo/TopicFollow');
    $uid = is_login();
    $weiboTopicLink = $topicModel->addTopic($content);
    $weibo_id = D('Weibo')->addWeibo($uid, $content, $type, $feed_data, $from);
    if (!$weibo_id) {
        return false;
    }
    if (count($weiboTopicLink)) {
        foreach ($weiboTopicLink as &$val) {
            $val['weibo_id'] = $weibo_id;
        }
        unset($val);
        D('Weibo/WeiboTopicLink')->addDatas($weiboTopicLink);

        //关注话题的用户接收到话题更新通知
        $k=0;
        foreach ($weiboTopicLink as $topk){
            $topks[$k]['topk']=$topicModel->getTopicInfo($topk['topic_id']);
            $topks[$k]['uid']=$topicFollowModel->getTopicFollow($topk['topic_id']);
            $k++;
        }
        unset($k);

        foreach ($topks as $vo)
        { 
            if(!empty($vo['uid'])){
                //排除自己
                if(in_array($uid,$vo['uid'])){
                    $key=array_search($uid,$vo['uid']);
                    unset($vo['uid'][$key]);
                }
                // 未读过该话题的用户不再提醒
                $readUids=D('Message')->topicMessageRead($vo['topk']['name'],$vo['uid']);
                D('Message')->sendALotOfMessageWithoutCheckSelf($readUids,'话题通知','您关注的#'.$vo['topk']['name'].'#话题已更新。','Weibo/Topic/index',array('topk'=>$vo['topk']['id']),1,'Weibo');
            }
        }

    }

    action_log('add_weibo', 'weibo', $weibo_id, $uid);
    $uids = get_at_uids($content);
    if ($type == 'repost') {
        $message_at_content = array(
            'keyword1' => parse_content_for_message($content),
            'keyword2' => '发布动态时@了你：',
            'keyword3' => "转发动态"
        );
    } else {
        $message_at_content = array(
            'keyword1' => '发布动态时@了你：',
            'keyword2' => '',
            'keyword3' => parse_content_for_message($content)
        );
    }
    D('Pushing')->sendMsg('all', array('uid'=>$uid,'weibo_id'=>$weibo_id),'send_weibo');
    send_at_message($uids, $weibo_id, $message_at_content);
    clean_query_user_cache(is_login(), array('weibocount'));
    return $weibo_id;

}

/**
 * send_comment  发布评论
 * @param $weibo_id
 * @param $content
 * @param int $comment_id
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment($weibo_id, $content, $comment_id = 0)
{
    $uid = is_login();
    $result = D('WeiboComment')->addComment($uid, $weibo_id, $content, $comment_id);
    if (!$result) {
        return false;
    }
    //清除html缓存
    clean_weibo_html_cache($weibo_id);
    //行为日志
    action_log('add_weibo_comment', 'weibo_comment', $result, $uid);
    //通知动态作者
    $weibo = D('Weibo')->getWeiboDetail($weibo_id);
    $message_content = array(
        'keyword1' => parse_content_for_message($content),
        'keyword2' => '评论我的动态：',
        'keyword3' => $weibo['type'] == 'repost' ? "转发动态" : parse_content_for_message($weibo['content'])
    );
    send_comment_message($weibo['uid'], $weibo_id, $message_content);
    //通知回复的人
    if ($comment_id) {
        $comment = D('WeiboComment')->getComment($comment_id);
        if ($comment['uid'] != $weibo['uid']) {
            send_comment_message($comment['uid'], $weibo_id, $message_content);
        }
    }

    $uids = get_at_uids($content);
    $uids = array_subtract($uids, array($weibo['uid'], $comment['uid']));
    $message_at_content = array(
        'keyword1' => parse_content_for_message($content),
        'keyword2' => '评论动态时@了你：',
        'keyword3' => $weibo['type'] == 'repost' ? "转发动态" : parse_content_for_message($weibo['content'])
    );
    send_at_message($uids, $weibo_id, $message_at_content);
    return $result;
}

/**
 * send_comment_message 发送评论消息
 * @param $uid
 * @param $weibo_id
 * @param $message
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment_message($uid, $weibo_id, $message)
{
    $title = L('_COMMENT_MESSAGE_');
    $from_uid = is_login();
    send_message($uid, $title, $message, 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $from_uid, 'Weibo', 'Common_comment');
}


/**
 * send_at_message  发送@消息
 * @param $uids
 * @param $weibo_id
 * @param $content
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_at_message($uids, $weibo_id, $content)
{
    $my_username = get_nickname();
    $title = $my_username . '@了您';
    $fromUid = get_uid();
    send_message($uids, $title, $content, 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $fromUid, 'Weibo', 'Common_comment');
}

function parse_topic($content,$no_link=0)
{
    //找出话题
    $topic = get_topic_back($content);
    if (isset($topic) && !is_null($topic)) {
        //将[topic:ID]替换成##链接
        $topics = D('Weibo/Topic')->getTopicByMap(array('id' => array('in', $topic)));
        foreach ($topics as $e) {
            if ($e['status'] == -1||$no_link) {
                $content = str_replace("[topic:" . $e['id'] . "]", "#" . $e['name'] . "# ", $content);
            } else {
                $space_url = U('Weibo/Topic/index', array('topk' => $e['id']));
                if (modC('HIGH_LIGHT_TOPIC', 1, 'Weibo')) {
                    $content = str_replace("[topic:" . $e['id'] . "]", " <a class='label label-badge label-info'  href=\"$space_url\" target=\"_blank\">#" . $e['name'] . "# </a> ", $content);
                } else {
                    $content = str_replace("[topic:" . $e['id'] . "]", " <a href=\"$space_url\" target=\"_blank\">" . $e['name'] . " </a> ", $content);
                }
            }
        }
    }

    //返回替换的文本
    return $content;
}

function get_topic_back($content)
{
    //正则表达式匹配
    $topic_pattern = "/\[topic:([0-9]+)\]/";
    preg_match_all($topic_pattern, $content, $users);

    //返回话题列表
    return array_unique($users[1]);
}

function get_topic($content)
{
    //正则表达式匹配
    $topic_pattern = "/#([^\\#|.]+)#/";
    preg_match_all($topic_pattern, $content, $users);

    //返回话题列表
    return array_unique($users[1]);
}


function use_topic()
{
    $topic = modC('USE_TOPIC');

    if (empty($topic)) {
        return;
    }
    $topics = explode(',', $topic);
    $html = '';
    foreach ($topics as $k => $v) {
        $v = '#' . $v . '#';
        $html .= '<a href="javascript:" class="recom-topic" data-role="chose_topic">' . $v . '</a> ';
    }
    unset($k, $v);

    return $html;

}


function parse_content_for_message($content)
{
    $content = shorten_white_space($content);
    $content = op_t($content, false);
    $content = parse_expression($content);
    //at转化
    $list = get_replace_list($content, 'at');
    foreach ($list as $val) {
        $user = query_user(array('nickname','space_url'), $val);
        $content = str_replace('[at:' . $val . ']', '<span ucard="'.$val.'">@'.$user['real_nickname'].'</span>', $content);
    }
    unset($val);
    //at转化 end

    //topic转化
    $content = parse_topic($content,1);
    //topic转化 end

    return $content;
}

/**
 * 清除weibo html缓存
 * @param int $weibo_id 为空则清除所有，不为空则清除该weibo的缓存（全部）
 * @return bool
 */
function clean_weibo_html_cache($weibo_id = 0)
{
    D('Weibo/WeiboCache')->cleanCache($weibo_id);
    return true;
}

/**
 * 替换html
 * @param $html
 * @param $weibo_id
 * @return mixed
 */
function replace_weibo_html($html, $weibo_id = 0)
{
    if ($weibo_id) {//动态详情部分才执行
        //替换follow
        $list = get_replace_list($html, 'follow');
        foreach ($list as $val) {
            $map['follow_who'] = $val;
            $map['who_follow'] = is_login();
            if ($map['follow_who'] != $map['who_follow'] && is_login()) {
                $res = M('follow')->where($map)->find();
                if (!$res) {
                    $show_follow = 1;
                }
            }
            if ($show_follow) {
                $follow_html = '<button type="button" class="btn btn-primary" data-after="btn btn-default"  data-before="btn btn-primary"  data-role="follow" data-follow-who="' . $val . '" style="width: 65px">
                                <i class="icon-plus"></i>
                                {:L("_FOLLOWERS_")}
                            </button>';
                $html = str_replace('[follow:' . $val . ']', $follow_html, $html);
            } else {
                $html = str_replace('[follow:' . $val . ']', '', $html);
            }
        }
        unset($val);
        //替换follow end
        //替换点赞按钮样式start
        if (is_login()) {
            $map_support['appname'] = 'Weibo';
            $map_support['table'] = 'weibo';
            $map_support['row'] = $weibo_id;
            $map_support['uid'] = is_login();
            $supported = M('Support')->where($map_support)->count();
            if ($supported) {
                $html = str_replace('<i class="weibo_like icon-heart-empty">', '<i class="weibo_like icon-heart">', $html);
            }else{
                $html = str_replace('<i class="weibo_like icon-heart">', '<i class="weibo_like icon-heart-empty">', $html);
            }
        }
        //替换点赞按钮样式end
    }
    //替换nickname
    $list = get_replace_list($html, 'nickname');
    foreach ($list as $val) {
        $nickname = query_user(array('nickname'), $val);
        if (is_login()) {
            if ($nickname['nickname'] == null) {
                $nickname['nickname'] = '';
            }
            $html = str_replace('[nickname:' . $val . ']', $nickname['nickname'], $html);
        } else {
            if ($nickname['real_nickname'] == null) {
                $nickname['real_nickname'] = '';
            }
            $html = str_replace('[nickname:' . $val . ']', $nickname['real_nickname'], $html);
        }
    }
    unset($val);
    //替换nickname end
    //替换时间 start
    $list = get_replace_list($html, 'time');
    foreach ($list as $val) {
        $html = str_replace('[time:' . $val . ']', friendlyDate($val), $html);
    }
    unset($val);
    //替换时间 end
    return $html;
}

function get_replace_list($html, $type)
{
    //正则表达式匹配
    $pattern = "/\[" . $type . ":([0-9]+)\]/";
    preg_match_all($pattern, $html, $list);

    //返回话题列表
    return array_unique($list[1]);
}