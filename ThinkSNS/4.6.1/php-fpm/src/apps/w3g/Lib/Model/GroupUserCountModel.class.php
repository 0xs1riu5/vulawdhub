<?php
/**
 * 用户统计模型.
 *
 * @author nonant
 */
class GroupUserCountModel extends Model
{
    protected $tableName = 'group_user_count';

    /**
     * 添加统计数据.
     *
     * @param int|array $uid    用户ID
     * @param string    $type   统计的项目
     * @param int       $IncNum 变化值  默认1
     */
    public function addCount($uid, $type, $gid = 0, $IncNum = 1)
    {
        global $ts;
        if ($uid == $ts['user']['uid']) {
            return false;
        }
        if (is_array($uid)) {
            foreach ($uid as $k => $v) {
                $this->addCount($v, $type, $gid, $IncNum);
            }
        } else {
            if (!$uid) {
                return false;
            }
            $map['uid'] = $uid;
            $map['gid'] = $gid;
            if ($this->where($map)->find()) {
                $this->where($map)->setInc($type);
            } else {
                $data['uid'] = $uid;
                $data['gid'] = $gid;
                $data[$type] = 1;
                $this->add($data);
            }
        }
    }

    /**
     * 归0.
     *
     * @param int|array $uid  用户ID
     * @param string    $type 统计的项目
     */
    public function setZero($uid, $type)
    {
        $map['uid'] = $uid;

        return $this->where($map)->setField($type, 0);
    }

    /**
     * 归0.
     *
     * @param int|array $uid  用户ID
     * @param string    $type 统计的项目
     */
    public function setGroupZero($uid, $gid, $type)
    {
        $map['uid'] = $uid;
        $map['gid'] = $gid;

        return $this->where($map)->setField($type, 0);
    }

    /**
     * 获取统计值
     *
     * @param int|array $uid  用户ID
     * @param string    $type 统计的项目，为空将返回所有统计项目结果
     *
     * @return mixed
     */
    public function getUnreadCount($uid = 0, $type = '')
    {
        $map['uid'] = $uid;
        $res = $this->where($map)->findAll();
        $count['uid'] = $uid;
        foreach ($res as $r) {
            $count['comment'] += $r['comment'];
            $count['atme'] += $r['atme'];
            $count['bbs'] += $r['bbs'];
        }

        return empty($type) ? $count : $count[$type];
    }

    /**
     * 获取某群内统计值
     *
     * @param int|array $uid  用户ID
     * @param string    $type 统计的项目，为空将返回所有统计项目结果
     *
     * @return mixed
     */
    public function getGroupUnreadCount($uid = 0, $gid = 0, $type = '')
    {
        $map['uid'] = $uid;
        $map['gid'] = $gid;
        $res = $this->field($type)->where($map)->find();

        return empty($type) ? $res : $res[$type];
    }
}
