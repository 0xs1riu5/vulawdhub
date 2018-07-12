<?php

class GroupTagModel extends Model
{
    public $tableName = 'group_tag';

    // 设置群组tag
    public function setGroupTag($tagname, $gid)
    {
        $tagname = str_replace(' ', ',', $tagname);
        $tagname = str_replace('，', ',', $tagname);
        $tagInfo = $this->__addTags($tagname, 0);
        if ($tagInfo) {
            foreach ($tagInfo as $k => $v) {
                $groupTagInfo = $this->where("gid=$gid AND tag_id=".$v['tag_id'])->find();
                if (!$groupTagInfo) {
                    $data['gid'] = $gid;
                    $data['tag_id'] = $v['tag_id'];
                    if ($v['group_tag_id'] = $this->add($data)) {
                        $tagdata[] = $v;
                        $tagids[] = $v['tag_id'];
                    }
                } else {
                    $tagids[] = $v['tag_id'];
                }
            }
            if ($tagids) {
                $delete_map['gid'] = $gid;
                $delete_map['tag_id'] = array('not in', $tagids);
                $this->where($delete_map)->delete();
                $return['code'] = '1';
                //$return['data'] =  $tagdata ;
            } else {
                $return['code'] = '0';
            }
        } else {
            $return['code'] = '-1';
        }

        return $return['code'];
        //return json_encode($return);
    }

    //添加全局tag
    private function __addTags($tagname, $nowcount)
    {
        if (!$tagname) {
            return false;
        }
        $tagname = str_replace(' ', ',', $tagname);
        $tagname = str_replace('，', ',', $tagname);
        $tagname = explode(',', $tagname);
        foreach ($tagname as $k => $v) {
            $v = preg_replace('/\s/i', '', $v);
            if (mb_strlen($v, 'UTF-8') > '10' || $v == '') {
                continue;
            }
            $result[] = $this->__addOneTag($v);
            $addcount = $addcount + 1;
            if ($addcount + $nowcount >= 5) {
                break;
            }
        }

        return $result;
    }

    private function __addOneTag($tagname)
    {
        $map['name'] = t($tagname);
        if ($info = D('tag')->where($map)->find()) {
            return $info;
        } else {
            $map['tag_id'] = D('tag')->add($map);

            return $map;
        }
    }

    // 获取指定群组Tag列表
    public function getGroupTagList($gid)
    {
        $base_cache_id = 'group_tag_';

        if (($res = model('Cache')->get($base_cache_id.$gid)) === false) {
            $this->setGroupTagObjectCache(array($gid));
            $res = model('Cache')->get($base_cache_id.$gid);
        }

        return $res;
    }

    public function setGroupTagObjectCache(array $gids)
    {
        if (!is_numeric($gids[0])) {
            return false;
        }

        $base_cache_id = 'group_tag_';
        $gids = implode(',', $gids);
        $res = $this->field('a.*,b.name')
                     ->table("{$this->tablePrefix}{$this->tableName} AS a LEFT JOIN {$this->tablePrefix}tag AS b ON b.tag_id=a.tag_id")
                     ->where("a.gid IN ( {$gids} )")
                     ->order('a.group_tag_id ASC')
                     ->findAll();

        // 格式化为: array($gid => $tags_array)
        // 注: 每个群组最多含有5个标签
        $group_tags = array();
        foreach ($res as $v) {
            if (count($group_tags[$v['gid']]) >= 5) {
                continue;
            } else {
                $group_tags[$v['gid']][] = $v;
            }
        }

        foreach ($group_tags as $k => $v) {
            model('Cache')->set($base_cache_id.$k, $v);
        }

        return $res;
    }

    // 热门群组标签
    public function getHotTags($recommend = null, $limit = 8)
    {
        if ('recommend' == $recommend) {
            $hot_tags = model('Xdata')->get('group:hotTags');
            $hot_tags = array_filter(array_unique(explode('|', $hot_tags)));

            return $hot_tags;
        } else {
            // 1小时锁缓存
            if (!($cache = S('Cache_Hot_Tags'))) {
                S('Cache_Hot_Tags_t', time()); //缓存未设置 先设置缓存设定时间
            } else {
                if (!($cacheSetTime = S('Cache_Hot_Tags_t')) || $cacheSetTime + 3600 <= time()) {
                    S('Cache_Hot_Tags_t', time()); //缓存未设置 先设置缓存设定时间
                } else {
                    return $cache;
                }
            }
            // 缓存锁结束
            $cache = $this->field('a.tag_id,b.tag_name,count(a.tag_id) AS `count`')
                         ->table("{$this->tablePrefix}{$this->tableName} AS a LEFT JOIN {$this->tablePrefix}tag AS b ON b.tag_id=a.tag_id")
                         ->group('a.tag_id')
                         ->order('`count` DESC')
                         ->limit($limit)
                         ->findAll();
            S('Cache_Hot_Tags', $cache);

            return $cache;
        }
    }
/*
    //查找指定签标的群组
    function doSearchTag($k) {
        global $ts;
        $keyinfo = M('tag')->where("tag_name='{$k}'")->find();
        if ($keyinfo && $k ) {
            $list = $this->where("tag_id=".$keyinfo['tag_id'])->field('gid')->findPage();
            $gids = getSubByKey($list['data'], 'gid');

            //缓存用户的资料, 粉丝数, 关注数, Tag列表
            $group_model 	  = D('Group', 'home');
            $group_count_model = model('GroupCount');
            $group_model->setGroupObjectCache($gids);
            $group_count_model->setGroupFollowerCount($gids);
            $group_count_model->setGroupFollowingCount($gids);
            $this->setGroupTagObjectCache($gids);

            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['group']        = $group_model->getGroupByIdentifier($v['gid']);
                $list['data'][$k]['taglist'] 	 = $this->getGroupTagList($v['gid']);
                $list['data'][$k]['following']   = $group_count_model->getGroupFollowingCount($v['gid']);
                $list['data'][$k]['follower']    = $group_count_model->getGroupFollowerCount($v['gid']);
                $list['data'][$k]['followState'] = getFollowState( $ts['group']['gid'] , $v['gid'] );
            }
        }else {
            $list['count'] = 0;
        }

        return $list;
    }

    //获取感兴趣的Tag列表
    function getFavTageList($gid){
        $db_prefix = C('DB_PREFIX');
        $sql = "SELECT a.* FROM {$db_prefix}tag a
            LEFT JOIN {$db_prefix}user_tag b  ON a.tag_id = b.tag_id
            WHERE b.uid != {$uid} AND a.tag_id >= ((SELECT MAX(tag_id) FROM {$db_prefix}tag)-(SELECT MIN(tag_id) FROM {$db_prefix}tag)) * RAND() + (SELECT MIN(tag_id) FROM {$db_prefix}tag)
            LIMIT 10";
        return $this->query($sql);
    }
*/
    //添加Tag by Id
    /*function addGroupTagById($tagid,$gid){
        $tagInfo = M('tag')->where('tag_id='.$tagid)->find();
        if($tagInfo){
            $groupTagInfo = $this->where("gid=$gid AND tag_id=".$tagInfo['tag_id'])->find();
            if(!$groupTagInfo){
                $data['gid'] = $gid;
                $data['tag_id'] = $tagInfo['tag_id'];
                $data['group_tag_id'] = $this->add($data);
                $data['tag_name'] = $tagInfo['tag_name'];
                $return['code'] = '1';
                $return['data'] = $data;
            }else{
                $return['code'] =  '0' ;
            }
        }else{
            $return['code'] = '0';
        }

        return json_encode( $return );
    }*/
}
