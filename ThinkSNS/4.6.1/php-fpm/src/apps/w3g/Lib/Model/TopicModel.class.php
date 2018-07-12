<?php

class TopicModel extends Model
{
    public $tableName = 'group_topic';

    //获取帖子
    public function getThread($tid, $field = '*')
    {
        $thread = $this->where('id='.$tid.' AND is_del=0')->field($field)->find();

        if ($thread) {
            $thread['content'] = D('Post')->getField('content', 'istopic=1 AND tid='.$tid);
            $thread['pid'] = D('Post')->getField('id', 'istopic=1 AND tid='.$tid);
        }

        return $thread;
    }

    //获取帖子
    public function getThreadDetail($gid, $tid, $field = '*')
    {
        $thread = $this->where('id='.$tid.' AND is_del=0')->field($field)->find();

        if ($thread) {
            $thread['content'] = D('Post')->getField('content', 'istopic=1 AND tid='.$tid);
            $thread['pid'] = D('Post')->getField('id', 'istopic=1 AND tid='.$tid);
        }

        return $thread;
    }

    //获取帖子列表
    /**
     * getTopicList.
     */
    public function getTopicList($html = 1, $map = null, $fields = null, $order = null, $limit = null, $isDel = 0)
    {
        //处理where条件
            if (!$isDel) {
                $map[] = 'is_del=0';
            } else {
                $map[] = 'is_del=1';
            }

        $map = implode(' AND ', $map);
            //连贯查询.获得数据集
            $result = $this->where($map)->field($fields)->order($order)->findPage($limit);
        if ($html) {
            return $result;
        }

        return $result['data'];
    }

     //搜索
    public function getSearch($keywords, $gid)
    {
        import('ORG.Util.Page');

        $sqlCount = 'SELECT count(*) as count FROM '.C('DB_PREFIX').'group_topic AS t Left Join '.C('DB_PREFIX').'group_post as p'.
                 " ON t.id=p.tid WHERE t.is_del=0 AND t.gid=$gid AND p.istopic = 1 AND (t.title like '%$keywords%' OR p.content like '%$keywords%')";
         //echo $sqlCount;
         $count = $this->query($sqlCount);  //显示分页总数

         $p = new Page($count[0]['count'], 10);

        $sql = 'SELECT * FROM '.C('DB_PREFIX').'group_topic AS t Left Join '.C('DB_PREFIX').'group_post as p'.
                 " ON t.id=p.tid WHERE t.is_del=0 AND t.gid=$gid AND p.gid = $gid AND p.istopic = 1 AND (t.title like '%$keywords%' OR p.content like '%$keywords%') LIMIT ".$p->firstRow.','.$p->listRows;
        $tList = $this->query($sql);

        return array('html' => $p->show(), 'count' => intval($count[0]['count']), 'data' => $tList);
    }

    //回收站
    public function remove($id)
    {
        $id = is_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收
         $uids = D('Topic')->field('uid')->where('id IN'.$id)->findAll();
        $res = D('Topic')->setField('is_del', 1, 'id IN'.$id); //回收话题
         if ($res) {
             D('Post')->setField('is_del', 1, 'tid IN'.$id); //回复
             // 积分
             foreach ($uids as $vo) {
                 X('Credit')->setUserCredit($vo['uid'], 'group_delete_topic');
             }
         }

        return $res;
    }

    // 删除
    public function del($id)
    {
        $id = in_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收
         D('Topic')->where('id IN'.$id)->delete(); //删除话题
         D('Post')->where('tid IN'.$id)->delete(); //删除回复
    }

    public function recover($id)
    {
        $id = in_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收
         D('Topic')->setField('is_del', 0, 'id IN'.$id); //回收话题
         D('Post')->setField('is_del', 0, 'tid IN'.$id); //回复
    }

     // 帖子分类列表
    public function categoryList($gid)
    {
        return M('group_topic_category')->where('gid='.intval($gid))->order('id ASC')->findAll();
    }

     // 群组热贴
    public function getHotThread()
    {
        // 1分钟锁缓存
        if (!($cache = S('Cache_Hot_Thread'))) {
            S('Cache_Hot_Thread_t', time()); //缓存未设置 先设置缓存设定时间
        } else {
            if (!($cacheSetTime = S('Cache_Hot_Thread_t')) || $cacheSetTime + 60 <= time()) {
                S('Cache_Hot_Thread_t', time()); //缓存未设置 先设置缓存设定时间
            } else {
                return $cache;
            }
        }
        // 缓存锁结束
        $cache = $this->field('topic.id,topic.gid,topic.title,topic.dist,post.content')
                           ->table(C('DB_PREFIX').'group_topic as topic
                                    left join '.C('DB_PREFIX').'group_post as post
                                    on topic.id = post.tid
                                    left join '.C('DB_PREFIX').'group as `group`
                                    on topic.gid=`group`.id')
                           ->where('`group`.brower_level=-1 AND post.istopic = 1 AND topic.is_del=0 and post.is_del=0 AND topic.replytime>'.(time() - 30 * 24 * 3600))
                           ->order('topic.viewcount+topic.replycount DESC,topic.id DESC')
                           ->limit(10)->findAll();
        S('Cache_Hot_Thread', $cache);

        return $cache;
    }
}
