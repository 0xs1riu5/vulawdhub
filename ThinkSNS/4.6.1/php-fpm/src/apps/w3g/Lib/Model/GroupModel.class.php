<?php
/**
 * 群组Dao类.
 *
 * @author Stream
 */
class GroupModel extends Model
{
    //表名
    public $tableName = 'group';
    //表结构
    protected $fields = array(1 => 'id',
    2 => 'uid',
    3 => 'name',
    4 => 'intro',
    5 => 'logo',
    6 => 'announce',
    7 => 'cid0',
    8 => 'cid1',
    9 => 'membercount',
    10 => 'threadcount',
    11 => 'type',
    12 => 'need_invite',
    13 => 'need_verify',
    14 => 'actor_level',
    15 => 'brower_level',
    16 => 'openWeibo',
    17 => 'openBlog',
    18 => 'openUploadFile',
    19 => 'whoUploadFile',
    20 => 'whoDownloadFile',
    21 => 'openAlbum',
    22 => 'whoCreateAlbum',
    23 => 'whoUploadPic',
    24 => 'anno',
    25 => 'ipshow',
    26 => 'invitepriv',
    27 => 'createalbumpriv',
    28 => 'uploadpicpriv',
    29 => 'ctime',
    30 => 'mtime',
    31 => 'status',
    32 => 'isrecom',
    33 => 'is_del',
    34 => 'hot',
    35 => 'sort',

    );

    public function _initialize()
    {
        parent::_initialize();
    }

    public function getapi()
    {
        return $this->api;
    }

    //获取我的群组  包括我管理的和加入的群组
    public function getAllMyGroup($mid, $html = 0, $open = array(), $limit = false)
    {
        static $_group_list = array();

        if (!$html && isset($_group_list[0][$mid])) {
            return $_group_list[0][$mid];
        }

        if (!empty($open)) {
            foreach ($open as $key => $value) {
                $open[$key] = $key.' = '.intval($value);
            }
            $openSql = ' AND '.implode(' AND ', $open);
        }
        $groupList = $this->table(C('DB_PREFIX').'group_member as member left join '.C('DB_PREFIX').'group as g on g.id = member.gid')
                          ->field('g.id,g.name,g.openWeibo,g.type,g.membercount,g.logo,g.cid0,g.ctime,g.status')
                          ->where('member.uid = '.$mid.' and member.level>0 and g.is_del = 0 '.$openSql)
                          ->order('member.level ASC,member.ctime DESC');
        if ($limit) {
            $groupList = $groupList->limit($limit);
        }
        if ($html) {
            $data = $groupList->findPage();
        } else {
            $data = $groupList->findAll();
            $_group_list[0][$mid] = $data;
        }

        return $data;
    }

    //我管理的群组
    public function mymanagegroup($mid, $html = 0)
    {
        $gidarr = D('Member')->field('gid')->where('(level=1 OR level=2) AND uid='.$mid)->findAll();

        if ($gidarr) {
            $in = 'id IN '.render_in($gidarr, 'gid').' AND is_del=0';
            $groupList = D('Group')->field('id,name,type,membercount,logo,cid0,ctime,status')->where($in)->findPage();
            if (!$html) {
                return $groupList['data'];
            }

            return  $groupList;
        }

        return false;
    }

    //我加入的群组
    public function myjoingroup($mid, $html = 0)
    {
        $gidarr = D('Member')->field('gid')->where('level > 1 AND status=1 AND uid='.$mid)->findAll();

        if ($gidarr) {
            $in = 'id IN '.render_in($gidarr, 'gid').' AND is_del=0';
            $groupList = D('Group')->field('id,name,type,membercount,logo,cid0,ctime,status')->where($in)->findPage();
            if (!$html) {
                return $groupList['data'];
            }

            return  $groupList;
        }

        return false;
    }

    //好友加入的群
    public function friendjoingroup($mid)
    {
        import('ORG.Util.Page');

        $cond = '';
        $group = array();
        $friendlist = getfriendlist($mid);  //放入缓存当中

        if (!empty($friendlist) && is_array($friendlist)) {
            $in = 'uid IN '.render_in($friendlist, 'fuid');

            $count = D('Member')->field('count(distinct(gid)) AS count')->where($in)->find();  //显示分页总数
            if ($count['count'] == 0) {
                return '';
            }

            $p = new Page($count['count'], 10);
            $friendgroup = D('Member')->field('gid')->where($in)->group('gid')->limit($p->firstRow.','.$p->listRows)->findAll();  //获取数据

            foreach ($friendgroup as $k => $v) {
                $group[$v['gid']] = D('Member')->where($in.' AND gid='.$v['gid'])->findAll();  //循环显示朋友
                $group[$v['gid']]['c'] = D('Member')->where($in.' AND gid='.$v['gid'])->count();
            }

            return array($group, $p->show());
        }

        return false;
    }

    //某人加入某群组
    public function joinGroup($mid, $gid, $level, $incMemberCount = false, $reason = '')
    {
        if (D('Member')->where("uid=$mid AND gid=$gid")->find()) {
            exit('你已经加入过');
        }

        $member['uid'] = $mid;
        $member['gid'] = $gid;
        $member['name'] = getUserName($mid);
        $member['level'] = $level;
        $member['ctime'] = time();
        $member['mtime'] = time();
        $member['reason'] = $reason;
        $ret = D('Member')->add($member);

        // 不需要审批直接添加，审批就不用添加了。
        if ($incMemberCount) {
            // 成员统计
            D('Group')->setInc('membercount', 'id='.$gid);
            // 积分操作
            X('Credit')->setUserCredit($mid, 'join_group');
        }

        return $ret;
    }

    // 个人感兴趣的群组
    public function interestingGroup($uid, $pagesize = 4)
    {
        // 个人兴趣
        $user_tag = D('UserTag', 'home')->getUserTagList($uid);
        foreach ((array) $user_tag as $v) {
            $_tag_id[] = $v['tag_id'];
            $_tag_in_name .= " OR g.name LIKE '%{$v['tag_name']}%' ";
            $_tag_in_intro .= " OR g.intro LIKE '%{$v['tag_name']}%' ";
        }
        // 管理和已经加入的群组
        $my_group = D('Member')->field('gid')->where('(level >= 1 AND status=1)  AND uid='.$uid)->findAll();
        foreach ((array) $my_group as $v) {
            $_my_group_id[] = $v['gid'];
        }

        $map = 'g.status=1 AND g.is_del=0';
        $_tag_id && $map .= ' AND (t.tag_id IN ('.implode(',', $_tag_id).')';
        $_tag_id && $map .= $_tag_in_name;
        $_tag_id && $map .= $_tag_in_intro.')';
        $_my_group_id && $map .= ' AND g.id NOT IN ('.implode(',', $_my_group_id).')';

        $group_count = $this->field('count(DISTINCT(g.id)) AS count')
                                   ->table("{$this->tablePrefix}group AS g LEFT JOIN {$this->tablePrefix}group_tag AS t ON g.id=t.gid")
                                   ->where($map)
                                   ->find();
        $group_list = $this->field('DISTINCT(g.id),g.name,g.logo,g.membercount,g.ctime')
                                  ->table("{$this->tablePrefix}group AS g LEFT JOIN {$this->tablePrefix}group_tag AS t ON g.id=t.gid")
                                  ->where($map)
                                  ->findPage($pagesize, $group_count['count']);
        // 标签相关的群组不够四个
        if ($group_list['count'] < 4) {
            $not_in_gids = array_merge($_my_group_id, getSubByKey($group_list['data'], 'id'));
            $hot_list = $this->getHotList(true);
            foreach ((array) $hot_list as $v) {
                if (!in_array($v['id'], $not_in_gids)) {
                    $v['reason'] = '热门群组';
                    $group_list['data'][] = $v;
                    $not_in_gids[] = $v['id'];
                    ++$group_count['count'];
                }
                if ($group_count['count'] >= 4) {
                    break;
                }
            }

            if ($group_count['count'] < 4) {
                $gid_map = ' AND id NOT IN ('.implode(',', $not_in_gids).') ';
                $_count = $this->where('status=1 AND is_del=0 '.$gid_map)->count();
                $rand_list = $this->field('id,name,logo,membercount,ctime')
                                  ->where('status=1 AND is_del=0 '.$gid_map)
                                  ->limit((rand(0, $_count - (4 - $group_count['count']))).','.(4 - $group_count['count']))
                                  ->findAll();
                foreach ($rand_list as $v) {
                    $v['reason'] = '随机推荐';
                    $group_list['data'][] = $v;
                }
            }
        }

        return $group_list;
    }

    /**
     * 群组热门排行.
     *
     * @param unknown_type $reset 是否重设缓存
     *
     * @return unknown
     */
    public function getHotList($reset = false)
    {
        // 1分钟锁缓存
        if (!($cache = S('Cache_Group_Hot_list')) || $reset) {
            S('Cache_Group_Hot_list_t', time()); //缓存未设置 先设置缓存设定时间
        } else {
            if (!($cacheSetTime = S('Cache_Group_Hot_list_t')) || $cacheSetTime + 60 <= time()) {
                S('Cache_Group_Hot_list_t', time()); //缓存未设置 先设置缓存设定时间
            } else {
                return $cache;
            }
        }
        // 缓存锁结束

        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $yesterday = $today - 24 * 3600;
        $gids['by_new_weibo'] = D('GroupFeed')->field('gid')
                                   ->where("publish_time>{$yesterday} AND publish_time<{$today} AND is_del=0")
                                   ->group('gid')
                                   ->order('count(gid) DESC')
                                   ->limit(20)
                                   ->findAll();

        $a_week_ago = $today - 7 * 24 * 3600;
        $gids['by_new_member'] = D('Member', 'group')->field('gid')
                                    ->where("ctime>{$a_week_ago} AND ctime<{$today} AND level>1")
                                    ->group('gid')
                                    ->order('count(gid) DESC')
                                    ->limit(20)
                                    ->findAll();

        $gids['by_member_count'] = $this->field('id AS gid')
                                ->where('brower_level=-1 AND status=1 AND is_del=0')
                                ->order('membercount DESC')
                                ->limit(20)
                                ->findAll();

        foreach ($gids as $v) {
            foreach ((array) $v as $_v) {
                $_gids[] = $_v['gid'];
            }
        }

        // 新分享数权值系数、统计
        $gid_map = $_gids ? ' AND gid IN ('.implode(',', $_gids).') ' : '';
        $factor['new_weibo_count'] = 2;
        $count['new_weibo_count'] = D('GroupFeed')->field('gid,count(gid) as new_weibo_count')
                                      ->where("publish_time>{$yesterday} AND publish_time<{$today} AND is_del=0 ".$gid_map)
                                      ->group('gid')
                                      ->findAll();
        // 新成员数权值系数、统计
        $factor['new_member_count'] = 3;
        $count['new_member_count'] = D('Member', 'group')->field('gid,count(gid) as new_member_count')
                                       ->where("ctime>{$a_week_ago} AND ctime<{$today} AND level>1 ".$gid_map)
                                       ->group('gid')
                                       ->findAll();

        // 成员数权值系数、统计
        $gid_map = $_gids ? ' AND id IN ('.implode(',', $_gids).') ' : '';
        $factor['membercount'] = 3;
        $count['membercount'] = $hot_list = $this->field('id,id AS gid,name,logo,membercount,ctime')
                         ->where('status=1 AND is_del=0 '.$gid_map)
                         ->findAll();

        // 计算权值
        foreach ($count as $k => $v) {
            foreach ((array) $v as $_v) {
                $weight[$_v['gid']] += $_v[$k] * $factor[$k];
            }
        }

        // 根据权值倒序排列
        $group_num = count($hot_list);
        for ($i = 0; $i < $group_num; ++$i) {
            $hot_list[$i]['weight'] = $weight[$hot_list[$i]['gid']];
            for ($j = $i; $j > 0; --$j) {
                if ($hot_list[$j]['weight'] > $hot_list[$j - 1]['weight']) {
                    $_temp = $hot_list[$j];
                    $hot_list[$j] = $hot_list[$j - 1];
                    $hot_list[$j - 1] = $_temp;
                } else {
                    break;
                }
            }
        }

        // 返回前十热门
        $data = array_slice($hot_list, 0, 10);
        S('Cache_Group_Hot_list', $data);

        return $data;
    }

    //最新话题
    public function getnewtopic($uid)
    {
        $gidarr = D('Member')->field('gid')->where('uid='.$uid)->findAll();
        if ($gidarr) {
            $in = 'gid IN '.render_in($gidarr, 'gid');

            return D('Topic')->where('is_del=0 AND '.$in)->order('replytime DESC')->findPage();
        }

        return false;
    }

    //获取群组动态
    public function getGroupFeed($gid, $appid, $pageLimit = 6)
    {
        $gid = intval($gid);
        //$map = "type!= 'create_group' AND appid={$appid} AND fid={$gid} ";
        //$map['type'] = array('neq','create_group');
        //$map['type'] = array();

        //return $this->api->Feed_getApp($appid,'',$pageLimit,array('group_create'),'','',$gid);
    }

    //获取我所在群组的动态
    public function getMyJoinGroup($uid, $appid)
    {
        $feedList = array();
        $joinGroup = D('Member')->field('gid')->where('uid='.$uid.' AND level != 0 ')->findPage();

        if ($joinGroup['data']) {
            foreach ($joinGroup['data'] as $k => $v) {
                $feedList[$k]['gid'] = $v['gid'];
                $feedList[$k]['feed'] = $this->getGroupFeed($v['gid'], $appid, 6);
            }
            $joinGroup['data'] = $feedList;
        }

        return $joinGroup;
    }

        /**
         * getGroupList.

         */
        public function getGroupList($html = 1, $map = array(), $fields = null, $order = null, $limit = null, $isDel = 0)
        {
            //处理where条件
            if (!$isDel) {
                $map[] = 'is_del=0';
            } else {
                $map[] = 'is_del=1';
            }
            $map = implode(' AND ', $map);

            $function_find = $html ? 'findPage' : 'findAll';
            //连贯查询.获得数据集
            $result = $this->where($map)->field($fields)->order($order)->$function_find($limit);

            return $result;
        }

      //回收站 群组，话题，文件，相册，话题回复
      public function remove($id)
      {
          $id = is_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收

          $uids = D('Group', 'group')->field('uid')->where('id IN '.$id)->findAll(); // 创建者ID
          $res = D('Group', 'group')->setField('is_del', 1, 'id IN '.$id);  // 回收群组
          if ($res) {
              // 删除成员
            D('Member', 'group')->where('gid IN '.$id)->delete();       //删除成员
            // 删除成员
            D('GroupTag', 'group')->where('gid IN '.$id)->delete();       //删除标签
            // 回收分享
              D('GroupWeibo', 'group')->setField('isdel', 1, 'gid IN'.$id);   //回收分享
              D('WeiboAtme', 'group')->where('gid IN '.$id)->delete();    //回收分享@TA 的
              D('WeiboComment', 'group')->setField('isdel', 1, 'gid IN'.$id);  //回收分享评论
              //D('WeiboFavorite')->where('gid IN ' . $id)->delete(); //回收分享评论
              D('WeiboTopic', 'group')->where('gid IN '.$id)->delete(); //回收分享帖子
            // 回收帖子和文件
              D('Topic', 'group')->setField('is_del', 1, 'gid IN'.$id); //回收话题
              D('Post', 'group')->setField('is_del', 1, 'gid IN'.$id);  //回收话题回复
              D('Dir', 'group')->setField('is_del', 1, 'gid IN'.$id);   //文件回收
              $dirList = D('Dir', 'group')->field('attachId')->where('gid IN'.$id)->findAll();

              if ($dirList) {
                  $attachIds = array();
                  foreach ($dirList as $k => $v) {
                      $attachIds[] = $v['attachId'];
                  }

                  model('Attach')->deleteAttach($attachIds, true);
                  unset($attachIds);
                  unset($dirList);
              }

              D('Album', 'group')->setField('is_del', 1, 'gid IN'.$id);   // 相册回收

              D('Photo', 'group')->setField('is_del', 1, 'gid IN'.$id);   // 图片回收
              $photoList = D('Photo', 'group')->field('attachId')->where('gid IN'.$id)->findAll();
              if ($photoList) {
                  $attachIds = array();
                  foreach ($photoList as $k => $v) {
                      $attachIds[] = $v['attachId'];
                  }
                  model('Attach')->deleteAttach($attachIds, true);
                  unset($attachIds);
                  unset($photoList);
              }
            // 积分操作
            foreach ($uids as $vo) {
                X('Credit')->setUserCredit($vo['uid'], 'delete_group');
            }
              S('Cache_MyGroup_'.$this->mid, null);
          }

          return $res;
      }

      //删除文件
      /*public function del($id) {
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
      	D('Group')->where('id IN'.$id)->delete();  //删除群组

      	D('Topic')->where('gid IN'.$id)->delete(); //回收话题
      	D('Post')->where('gid IN'.$id)->delete();  //回收话题回复
      	D('Dir')->where('gid IN'.$id)->delete();  //文件回收  删除文件unlink
      	D('Album')->where('gid IN'.$id)->delete();
      	D('Photo')->where('gid IN'.$id)->delete();   //图片回收
      }*/

    //获取某群组的群聊消息
    public function getGroupWeibo($gid)
    {
        return M('group_weibo')->where('gid ='.$gid)->count();
    }
}
