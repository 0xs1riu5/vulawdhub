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

class PublicController extends Controller
{
    /**获取个人资料，用以支持小名片
     * @auth 陈一枭
     */
    private function getProfile($uid)
    {
        $uid = intval($_REQUEST['uid']);
        $userProfile = query_user(array('uid', 'nickname', 'avatar64','avatar_html64', 'space_url', 'following', 'fans', 'weibocount', 'signature', 'rank_link'), $uid);
        $follow['follow_who'] = $userProfile['uid'];
        $follow['who_follow'] = is_login();
        $userProfile['followed'] = D('Follow')->where($follow)->count();
        $userProfile['following_url'] = U('Ucenter/Index/following', array('uid' => $uid));
        $userProfile['fans_url'] = U('Ucenter/Index/fans', array('uid' => $uid));
        $userProfile['weibo_url'] = U('Ucenter/Index/appList', array('uid' => $uid, 'type' => "weibo"));
        $html = '';
        if (count($userProfile['rank_link'])) {
            foreach ($userProfile['rank_link'] as $val) {
                if ($val['is_show']) {
                    if (empty($val['label_content'])) {
                        $html = $html . '<img class="img-responsive" src="' . $val['logo_url'] . '" title="' . $val['title'] . '" alt="' . $val['title'] . '" style="width: 18px;height: 18px;vertical-align: middle;margin-left: 3px;display: inline;"/>';
                    } else {
                        $html = $html . '<span class="label label-badge rank-label" title="' . $val['title'] . '" style="background:' . $val['label_bg'] . ' !important;color:' . $val['label_color'] . ' !important;vertical-align: middle;margin-left: 3px;">' . $val['label_content'] . '</span>';
                    }
                }
            }
            unset($val);
        }
        $userProfile['rank_link'] = $html;
        //获取用户封面path
        $map = getUserConfigMap('user_cover', '', $uid);
        $map['role_id'] = 0;
        $model = D('Ucenter/UserConfig');
        $cover = $model->findData($map);
        if ($cover) {
            $userProfile['cover_path'] = getThumbImageById($cover['value'], 344, 100);
        } else {
            $userProfile['cover_path'] = __ROOT__ . '/Public/images/qtip_bg.png';
        }
        //个人标签
        $userProfile['tags'] = '';
        $userTagLinkModel = D('Ucenter/UserTagLink');
        $myTags = $userTagLinkModel->getUserTag($uid);
        if (count($myTags)) {
            $userProfile['tags'] = L('_PERSONAL_TAB_') . L('_COLON_') . '<span>';
            $first = 1;
            foreach ($myTags as $val) {
                if ($first) {
                    $userProfile['tags'] .= '<a style="color: #848484;"  href="' . U('people/index/index', array('tag' => $val['id'])) . '">' . $val['title'] . '</a>';
                    $first = 0;
                } else {
                    $userProfile['tags'] .= '、<a style="color: #848484;"  href="' . U('people/index/index', array('tag' => $val['id'])) . '">' . $val['title'] . '</a>';
                }
            }
            $userProfile['tags'] .= '</span>';
        }
        return $userProfile;
    }


    public function card()
    {
        $aUID = I('get.uid', 0, 'intval');
        $user = $this->getProfile($aUID);
        $follow = D('Common/Follow')->isFollow(is_login(), $aUID);
        $this->assign('follow', $follow);

        $this->assign('uid', $aUID);
        $this->assign('user', $user);

        echo "<script> sessionStorage['user_info_'+" . $aUID . "] = JSON.stringify(" . json_encode($user) . ")</script>";

        $not_self = get_uid() != $aUID;
        $this->assign('not_self', $not_self);

        $isWuKong = M('Addons')->where(array('name' => 'WuKong'))->find();
        $this->assign('has_wukong', $isWuKong['status'] == 1 ? 1 : 0);

        $this->display();
    }

    public function setAlias()
    {
        $aUid = I('post.uid', 0, 'intval');
        $aAlias = trim(I('post.alias', '', 'text'));
        if ($aAlias == '') {
            $this->error(L('_ERROR_REMARK_CANNOT_EMPTY_') . L('_PERIOD_'));
        }
        if (is_login()) {
            $followModel = D('Common/Follow');
            $follow['who_follow'] = get_uid();
            $follow['follow_who'] = $aUid;
            $follow = $followModel->where($follow)->find();
            if (!$follow) {
                $this->error(L('_ERROR_REMARK_CANNOT_'));
            }
            $follow['alias'] = $aAlias;
            $result = $followModel->save($follow);
            if ($result === false) {
                $this->error(L('_ERROR_DB_WRITE_FAIL_') . L('_PERIOD_'));
            } else {
                S('nickname_' . get_uid() . '_' . $aUid, null);
                $this->success(L('_SUCCESS_SETTINGS_') . L('_PERIOD_'));
            }

        } else {
            $this->error(L('_FOLLOW_AFTER_LOGIN_') . L('_PERIOD_'));
        }

    }

    /**检测消息
     * 返回新聊天状态和系统的消息
     * @auth 陈一枭
     */
    public function getInformation()
    {

        $message = D('Common/Message');
        //取到所有没有提示过的信息
        $haventToastMessages = $message->getHaventToastMessage(is_login());

        $message->setAllToasted(is_login()); //消息中心推送

        $haventReadMessagesCount=$message->getHaventReadMessageCount(is_login());

        $new_talks = D('TalkPush')->getAllPush(); //聊天推送
        D('TalkPush')->where(array('uid' => get_uid(), 'status' => 0))->setField('status', 1); //读取到推送之后，自动删除此推送来防止反复推送。

        $new_talk_messages = D('TalkMessagePush')->getAllPush(); //聊天消息推送
        D('TalkMessagePush')->where(array('uid' => get_uid(), 'status' => 0))->setField('status', 1); //读取到推送之后，自动删除此推送来防止反复推送。

        foreach ($new_talk_messages as &$message) {
            $message = D('TalkMessage')->find($message['source_id']);
            $message['user'] = query_user(array('avatar64', 'uid', 'username'), $message['uid']);
            $message['ctime'] = date('m-d h:i', $message['create_time']);
            $message['avatar64'] = $message['user']['avatar64'];
        }
        exit(json_encode(array('messages' => $haventToastMessages,'message_count'=>$haventReadMessagesCount, 'new_talk_messages' => $new_talk_messages, 'new_talks' => $new_talks)));
    }

    /**设置全部的系统消息为已读
     * @auth 陈一枭
     */
    public function setAllMessageReaded()
    {
        D('Message')->setAllReaded(is_login());
    }

    /**设置某条系统消息为已读
     * @param $message_id
     * @auth 陈一枭
     */
    public function readMessage($message_id)
    {
        exit(json_encode(array('status' => D('Common/Message')->readMessage($message_id))));

    }

    /**
     * 用户修改封面
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function changeCover()
    {
        if (!is_login()) {
            $this->error(L('_ERROR_NEED_LOGIN_') . L('_EXCLAMATION_'));
        }
        if (IS_POST) {
            $aCoverId = I('post.cover_id', 0, 'intval');
            $result['status'] = 0;
            if ($aCoverId <= 0) {
                $result['info'] = L('_ERROR_ILLEGAL_OPERATE_') . L('_EXCLAMATION_');
                $this->ajaxReturn($result);
            }

            $data = getUserConfigMap('user_cover');
            $data['role_id'] = 0;
            $model = D('Ucenter/UserConfig');
            $already_data = $model->findData($data);
            if (!$already_data) {
                $data['value'] = $aCoverId;
                $res = $model->addData($data);
            } else {
                if ($already_data['value'] == $aCoverId) {
                    $result['info'] = L('_ALTER_NOT_') . L('_EXCLAMATION_');
                    $this->ajaxReturn($result);
                } else {
                    $res = $model->saveValue($data, $aCoverId);
                }
            }
            if ($res) {
                $result['status'] = 1;
                $result['path_1140'] = getThumbImageById($aCoverId, 1140, 230);
                $result['path_273'] = getThumbImageById($aCoverId, 273, 70);
            } else {
                $result['info'] = L('_FAIL_OPERATE_') . L('_EXCLAMATION_');
            }
            $this->ajaxReturn($result);
        } else {
            //获取用户封面id
            $map = getUserConfigMap('user_cover');
            $map['role_id'] = 0;
            $model = D('Ucenter/UserConfig');
            $cover = $model->findData($map);
            $my_cover['cover_id'] = $cover['value'];
            $my_cover['cover_path'] = getThumbImageById($cover['value'], 348, 70);
            $this->assign('my_cover', $my_cover);
            $this->display('_change_cover');
        }
    }






}