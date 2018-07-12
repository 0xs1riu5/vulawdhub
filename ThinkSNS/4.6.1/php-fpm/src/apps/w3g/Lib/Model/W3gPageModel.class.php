<?php
/**
 * 分享模型 - 数据对象模型.
 *
 * @author jason <yangjs17@yeah.net>
 *
 * @version TS3.0
 */
class W3gPageModel
{
    /**
     * 获取登录用户所关注人的最新分享.
     *
     * @param string $type     分享类型,原创post,转发repost,图片postimage,附件postfile,视频postvideo
     * @param int    $mid      用户ID
     * @param int    $since_id 分享ID，从此分享ID开始，默认为0
     * @param int    $max_id   最大分享ID，默认为0
     * @param int    $limit    结果集数目，默认为20
     * @param int    $page     分页数，默认为1
     *
     * @return array 登录用户所关注人的最新分享
     */
    public function friends_timeline_page($type, $mid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1)
    {
        $limit = intval($limit);
        $page = intval($page);
        $where = ' a.is_del = 0 ';
        $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}user_follow AS b ON a.uid=b.fid AND b.uid = {$mid}";
        // 加上自己的信息，若不需要此数据，请屏蔽下面语句
        $where = "(a.uid = '{$mid}' OR b.uid = '{$mid}') AND ($where)";
        $feed_ids = D()->where($where)->table($table)->field('a.feed_id')->limit("{$start},{$end}")->order('a.feed_id DESC')->getAsFieldArray('feed_id');
        // 获取页面分页信息
        //$sql = "SELECT COUNT(a.feed_id) AS `count` FROM ts_feed AS a LEFT JOIN ts_user_follow AS b ON a.uid=b.fid AND b.uid = '{$this->mid}' WHERE (a.uid = '{$this->mid}' OR b.uid = '{$this->mid}') AND ( a.is_del = 0 ) ORDER BY a.feed_id LIMIT 1;";
        $sql = "SELECT COUNT(a.feed_id) AS 'count' FROM ts_feed AS a LEFT JOIN ts_user_follow AS b ON a.uid=b.fid AND b.uid = '{$this->mid}' WHERE (a.uid = '{$this->mid}' OR b.uid = '{$this->mid}') AND ( a.is_del = 0 ) ORDER BY a.feed_id LIMIT 1;";
        $count = D()->query($sql);

        return $count;
    }

    public function getMyWeibo($type, $mid)
    {
        $count = D()->table(C('DB_PREFIX').'feed AS a LEFT JOIN '.C('DB_PREFIX').'user_follow AS b ON a.uid=b.fid AND b.uid = '.$mid)->field('a.feed_id')->where($map)->count();
    }

    //获得分享列表总条数
    public function getAllWeiboCount()
    {
        $data['is_del'] = 0;
        $count = M('Feed')->where($data)->count();

        return $count;
    }

    //获得自己关注人的分享列表总条数
    public function getWeiboCount($type, $mid)
    {
        $map['a.is_del'] = 0;
        // 动态类型
        $weiboType = array('post', 'repost', 'postimage', 'postfile', 'postvideo');
        in_array($type, $weiboType) && $map['a.type'] = $type;
        // 加上自己的信息，若不需要此数据，请屏蔽下面语句
        $map['_string'] = 'a.uid = '.$mid.' OR b.uid = '.$mid;
        // 获取分享总数
        $count = D()->table(C('DB_PREFIX').'feed AS a LEFT JOIN '.C('DB_PREFIX').'user_follow AS b ON a.uid=b.fid AND b.uid = '.$mid)->field('a.feed_id')->where($map)->count();

        return $count;
    }
    //获得自己关注人的分享列表最新一条id
    public function getWeiboLatestId($type, $mid)
    {
        $map['a.is_del'] = 0;
        // 动态类型
        $weiboType = array('post', 'repost', 'postimage', 'postfile', 'postvideo');
        in_array($type, $weiboType) && $map['a.type'] = $type;
        // 加上自己的信息，若不需要此数据，请屏蔽下面语句
        $map['_string'] = 'a.uid = '.$mid.' OR b.uid = '.$mid;
        // 获取分享总数
        $id = D()->table(C('DB_PREFIX').'feed AS a LEFT JOIN '.C('DB_PREFIX').'user_follow AS b ON a.uid=b.fid AND b.uid = '.$mid)
            ->field('a.feed_id')
            ->limit('0,1')
            ->order('a.feed_id DESC')
            ->where($map)
            ->select();

        return $id;
    }

    //获得自己的分享列表总条数
    public function getMyWeiboCount($type, $mid)
    {
        $map['is_del'] = 0;
        $map['uid'] = $mid;
        $map['is_del'] = 0;
        // 动态类型
        $weiboType = array('post', 'repost', 'postimage', 'postfile', 'postvideo');
        in_array($type, $weiboType) && $map['a.type'] = $type;
        // 加上自己的信息，若不需要此数据，请屏蔽下面语句
        // $map['_string'] = 'a.uid = '.$mid.' OR b.uid = '.$mid;
        // 获取分享总数
        $count = M('Feed')->where($map)->count();

        return $count;
    }

    //获得自己的粉丝列表总条数
    public function getMyFansCount($mid)
    {
        $data['fid'] = $mid;
        // 获取粉丝总数
        $count = M('UserFollow')->where($data)->count();

        return $count;
    }

    //获得自己的关注列表总条数
    public function getMyFollCount($mid)
    {
        $data['uid'] = $mid;
        // 获取粉丝总数
        $count = M('UserFollow')->where($data)->count();

        return $count;
    }

    //获得自己的被@的总条数
    public function getAtmeCount($mid)
    {
        $data['uid'] = $mid;
        // 获取粉丝总数
        $count = M('Atme')->where($data)->count();

        return $count;
    }

    //获得自己的评论的总条数
    public function getComCount($mid)
    {
        $data['app_uid'] = $mid;
        $data['to_uid'] = $mid;
        $data['_logic'] = 'or';
        // 获取粉丝总数
        $count = M('Comment')->where($data)->count();

        return $count;
    }
}
