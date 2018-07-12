<?php
/**
 * ProtocolModel
 * 提供给Ts核心调用的协议类.
 */
class GroupProtocolModel extends Model
{
    // 假删除用户数据
    public function deleteUserAppData($uidArr)
    {
    }

    // 恢复假删除的用户数据
    public function rebackUserAppData($uidArr)
    {
    }

    // 彻底删除用户数据
    public function trueDeleteUserAppData($uidArr)
    {
    }

    // 获取评论内容
    public function getSourceInfo($row_id, $_forApi)
    {
    }

    // 在个人空间里查看该应用的内容列表
    public function profileContent($uid)
    {
        $list = D('Group', 'group')->getAllMyGroup($uid, 1, null, 20);
        $listIds = getSubByKey($list['data'], 'id');
        $map['gid'] = array('IN', $listIds);
        $map['uid'] = $uid;
        $userCount = D('GroupUserCount')->where($map)->findAll();
        foreach ($userCount as $value) {
            if ($value['atme'] || $value['comment'] || $value['topic']) {
                $groupHash[$value['gid']]['atme'] = $value['atme'];
                $groupHash[$value['gid']]['comment'] = $value['comment'];
                $groupHash[$value['gid']]['topic'] = $value['topic'];
            }
        }
        foreach ($list['data'] as &$val) {
            $val['unread_usercount'] = $groupHash[$val['id']];
            $val['group_type'] = group_getCategoryName($val['cid0']);
        }

        $tpl = APPS_PATH.'/group/Tpl/default/Index/profileContent.html';

        return fetch($tpl, $list);
    }
}
