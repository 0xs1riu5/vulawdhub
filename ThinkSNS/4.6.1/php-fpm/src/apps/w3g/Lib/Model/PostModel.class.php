<?php

class PostModel extends Model
{
    public $tableName = 'group_post';

     // 获取文件
     /**
      * getGroupList.
      */
     public function getPostList($html = 1, $map = null, $fields = null, $order = null, $limit = null, $isDel = 0)
     {
         //处理where条件
            if (!$isDel) {
                $map[] = 'is_del=0';
            } else {
                $map[] = 'is_del=1';
            }

         $map[] = 'istopic=0';
         $map = implode(' AND ', $map);
            //连贯查询.获得数据集
            $result = $this->where($map)->field($fields)->order($order)->findPage($limit);

         if ($html) {
             return $result;
         }

         return $result['data'];
     }

     // 回收站
     public function remove($id)
     {
         $id = is_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收
         $uids = D('Post')->field('uid')->where('id IN'.$id)->findAll();
         $res = D('Post')->setField('is_del', 1, 'id IN'.$id); //回复
         if ($res) {
             // 积分
             foreach ($uids as $vo) {
                 X('Credit')->setUserCredit($vo['uid'], 'group_reply_topic', -1);
             }
         }

         return $res;
     }

      // 删除
     public function del($id)
     {
         $id = in_array($id) ? '('.implode(',', $id).')' : '('.$id.')';  //判读是不是数组回收
         return D('Post')->where('id IN'.$id)->delete(); //删除回复
     }
}
