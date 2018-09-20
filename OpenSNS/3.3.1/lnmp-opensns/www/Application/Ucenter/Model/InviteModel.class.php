<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-24
 * Time: 下午5:04
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class InviteModel extends Model
{
    protected $_auto = array(
        array('already_num', '0', self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_BOTH),
    );

    /**
     * 管理员后台生成邀请码
     * @param array $data
     * @param int $num
     * @return bool|string
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function createCodeAdmin($data = array(), $num = 1)
    {
        $map['status'] = 1;
        $map['id'] = $data['invite_type'];
        $invite_type = D('Ucenter/InviteType')->getSimpleList($map, 'length,time');
        $data['end_time'] = unitTime_to_time($invite_type[0]['time'], '+');
        $data['uid'] = -is_login(); //管理员后台生成，以负数uid标记

        $dataList = array();
        do {
            $dataList[] = $this->createOneCode($data, $invite_type[0]['length']);
        } while (count($dataList) < $num);
        $res = $this->addAll($dataList);
        if ($res) {
            $result['status'] = 1;
            $result['url'] = U('Admin/Invite/invite', array('status' => 1, 'buyer' => -1));
        } else {
            $result['status'] = 0;
            $result['info'] = L('_FAILED_TO_GENERATE_AN_INVITATION_CODE_WITH_EXCLAMATION_') . $this->getError();
        }
        return $result;
    }

    /**
     * 用户前台生成邀请码
     * @param array $data
     * @param int $num
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function createCodeUser($data = array(), $num = 1)
    {
        $map['status'] = 1;
        $map['id'] = $data['invite_type'];
        $invite_type = D('Ucenter/InviteType')->getSimpleList($map, 'length,time');
        $data['end_time'] = unitTime_to_time($invite_type[0]['time'], '+');
        $data['uid'] = is_login(); //用户前台生成，以正数uid标记

        $dataList = array();
        do {
            $dataList[] = $this->createOneCode($data, $invite_type[0]['length']);
        } while (count($dataList) < $num);
        $res = $this->addAll($dataList);
        if ($res) {
            $result['status'] = 1;
            $result['url'] = U('Ucenter/Invite/invite');
        } else {
            $result['status'] = 0;
            $result['info'] = L('_FAILED_TO_GENERATE_AN_INVITATION_CODE_WITH_EXCLAMATION_') . $this->getError();
        }
        return $result;
    }

    /**
     * 获取简易结构的邀请码列表
     * @param array $ids
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getSimpleListByIds($ids = array())
    {
        $map['id'] = array('in', $ids);
        $dataList = $this->where($map)->field('code')->select();
        foreach ($dataList as &$val) {
            $val['code_url'] = U('Ucenter/Member/register', array('code' => $val['code']), true, true);
        }
        unset($val);
        return $dataList;
    }

    /**
     * 获取分页邀请码列表
     * @param array $map
     * @param int $page
     * @param int $r
     * @param string $order
     * @return array|null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getList($map = array(), $page = 1, $r = 20, $order = 'id desc')
    {
        $totalCount = $this->where($map)->count();
        if ($totalCount) {
            $dataList = $this->where($map)->page($page, $r)->order($order)->select();
            return array($this->_initSelectData($dataList), $totalCount);
        }
        return array(null, 0);
    }

    /**
     * 获取邀请码列表
     * @param array $map
     * @param string $order
     * @return array|null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getListAll($map = array(), $order = 'id desc')
    {
        $dataList = $this->where($map)->order($order)->select();
        return $this->_initSelectData($dataList);
    }

    /**
     * 退还邀请码
     * @param int $id
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function backCode($id = 0)
    {
        $result = $this->where(array('id' => $id))->setField('status', 2);
        if ($result) {
            $invite = $this->where(array('id' => $id))->find();
            $num = $invite['can_num'] - $invite['already_num'];
            if ($num > 0) {
                $map['invite_type'] = $invite['invite_type'];
                $map['uid'] = $invite['uid'];
                D('InviteUserInfo')->where($map)->setDec('already_num', $num);
                D('InviteUserInfo')->where($map)->setInc('num', $num);
            }
        }
        return $result;
    }

    /**
     * 根据邀请码获取邀请码信息
     * @param string $code
     * @return mixed|null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getByCode($code = '')
    {
        $map['code'] = $code;
        $map['status'] = 1;
        $data = $this->where($map)->find();
        if ($data) {
            $data['user'] = query_user(array('uid', 'nickname'), abs($data['uid']));
            return $data;
        }
        return null;
    }

    /**
     * 初始化查询信息
     * @param array $dataList
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initSelectData($dataList = array())
    {
        $invite_type_id = array_column($dataList, 'invite_type');
        $map['id'] = array('in', $invite_type_id);
        $invite_types = D('Ucenter/InviteType')->getSimpleList($map);
        $invite_types = array_combine(array_column($invite_types, 'id'), $invite_types);
        foreach ($dataList as &$val) {
            $val['invite'] = $invite_types[$val['invite_type']]['title'];
            $val['code_url'] = U('Ucenter/Member/register', array('code' => $val['code']), true, true);
            if ($val['uid'] > 0) {
                $val['buyer'] = get_nickname( $val['uid']);
            } else {
                $val['buyer'] = get_nickname( -$val['uid']) . L('_BACKGROUND_GENERATION_');
            }
        }
        unset($val);
        return $dataList;
    }


    /**
     * 创建邀请码
     * @param array $data
     * @param $length
     * @return array|mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function createOneCode($data = array(), $length)
    {
        $length = $length ? $length : 11;
        do {
            //生成随机数
            $map['code'] = create_rand($length);
        } while ($this->where($map)->count());
        $data['code'] = $map['code'];
        if($data['end_time']===false){
            $data['end_time']=2100000000;
        }
        $data = $this->create($data);
        return $data;
    }

    /**
     * 自动生成邀请码
     * @param array $data
     * @param int $num
     * @return mixed
     * @author 路飞<lf@ourstu.com>
     */
    public function createUserCode($data = array(), $num = 1)
    {
        $map['status'] = 1;
        $map['id'] = $data['invite_type'];
        $invite_type = D('Ucenter/InviteType')->getSimpleList($map, 'length,time');
        $data['end_time'] = unitTime_to_time($invite_type[0]['time'], '+');
        $data['uid'] = session('temp_login_uid'); //以正数uid标记

        $dataList = array();
        do {
            $dataList[] = $this->createOneCode($data, $invite_type[0]['length']);
        } while (count($dataList) < $num);
        $res = $this->addAll($dataList);
        if ($res) {
            $result['status'] = 1;
            $result['url'] = U('Ucenter/Invite/invite');
        } else {
            $result['status'] = 0;
            $result['info'] = L('_FAILED_TO_GENERATE_AN_INVITATION_CODE_WITH_EXCLAMATION_') . $this->getError();
        }
        return $result;
    }
} 