<?php

class MemberModel extends Model
{
    public $tableName = 'group_member';

    public function getNewMemberList($gid, $limit = 3)
    {
        $gid = intval($gid);
        $new_member_list = $this->field('id,uid,level,ctime')->where("gid={$gid} AND level>1")->order('ctime DESC')->limit($limit)->findAll();
        foreach ($new_member_list as &$v) {
            $v['userinfo'] = model('User')->getUserInfo($v['uid']);
        }

        return $new_member_list;
    }

    public function memberCount($gid)
    {
        return $this->where('gid='.$gid)->count();
    }
}
