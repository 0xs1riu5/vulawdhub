<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/8/26
 * Time: 15:48
 */

namespace Weibo\Model;

use Think\Model;

class WeiboTopicLinkModel extends Model
{

    /**
     * 批量添加动态话题链接
     * @param $list
     * @return bool|string
     */
    public function addDatas($list)
    {
        $res=$this->addAll($list);
        $topic_ids=array_column($list,'topic_id');
        M('WeiboTopic')->where(array('id'=>array('in',$topic_ids),'status'=>1))->setInc('weibo_num');
        return $res;
    }

    /**
     * 根据map获取分页动态话题链接
     * @param $map
     * @param $page
     * @param $r
     * @param string $order
     * @return array
     */
    public function getListPageByMap($map,$page,$r,$order='create_time desc')
    {
        $totalCount=$this->where($map)->count();
        if($totalCount){
            $list=$this->where($map)->page($page,$r)->order($order)->select();
        }
        return array($list,$totalCount);
    }

    /**
     * 获取话题置顶列表
     * @param $map
     * @param string $order
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getTopList($map,$order='create_time desc')
    {
        $list=$this->where($map)->order($order)->select();
        return $list;
    }

    /**
     * 设置动态话题链接标记动态置顶
     * @param $weibo_id
     * @param int $top
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function setWeiboTop($weibo_id,$top=0)
    {
        $this->where(array('weibo_id'=>$weibo_id))->setField('is_top',$top);
        return true;
    }

}