<?php
/**
 * 话题模型
 * 没有话题的情况下添加话题
 * 有话题的时候在输入#的时候弹出
 * @author quick
 *
 */
namespace Weibo\Model;

use Think\Model;
use Think\Page;

class TopicModel extends Model
{

    protected $tableName = 'weibo_topic';

    public function addTopic(&$content)
    {
        //检测话题的存在性
        $topic = get_topic($content);
        $weiboTopicLink=array();
        if (isset($topic) && !is_null($topic)) {
            $link=array('create_time'=>time(),'status'=>1);
            foreach ($topic as $e) {
                $topic_id=0;
                $e=trim($e);
                $tik = $this->where(array('name' => $e,'status'=>1))->find();
                //没有这个话题的时候创建这个话题
                if (!$tik&&$e!='') {
                    $topic_id=$this->add(array('name' => $e));
                }else{
                    $topic_id=$tik['id'];
                }
                $content=str_replace('#'.$e.'#','[topic:'.$topic_id.']',$content);
                //增加话题动态链接
                $link['topic_id']=$topic_id;
                $weiboTopicLink[]=$link;
            }
        }
        //话题动态链接后面会写入数据库
        return $weiboTopicLink;
    }

    /**
     * 获取热门话题
     * @param int $hour
     * @param int $num
     * @param int $page
     * @return array
     */
    public function getHot($hour = 1, $num = 10, $page = 1)
    {
        $map['create_time'] = array('gt', time() - $hour * 60 * 60);

        $map['status'] = 1;
        $weiboTopicLinkModel = M('WeiboTopicLink');
        $list=$weiboTopicLinkModel->where($map)->field("count(weibo_id) as weibos,topic_id")->group('topic_id')->order('weibos desc')->page($page,$num)->select();
        $allList=$weiboTopicLinkModel->where($map)->field("topic_id")->group('topic_id')->limit(999)->select();
        $totalCount=count($allList);
        foreach ($list as $key=>&$v) {
            $topic = $this->find($v['topic_id']);
            $topic['weibos']=$v['weibos'];
            $topic['user'] = query_user(array('space_link'), $topic['uadmin']);
            $topic['top_num']=($page-1)*$num+$key+1;
            $v=$topic;
        }
        unset($v);

        return array($list,$totalCount);
    }

    /**
     * 根据数组中的某个键值大小进行排序，仅支持二维数组
     *
     * @param array $array 排序数组
     * @param string $key 键值
     * @param bool $asc 默认正序
     * @return array 排序后数组
     */
    function arraySortByKey(array $array, $key, $asc = true)
    {
        $result = array();
        // 整理出准备排序的数组
        foreach ($array as $k => &$v) {
            $values[$k] = isset($v[$key]) ? $v[$key] : '';
        }
        unset($v);
        // 对需要排序键值进行排序
        $asc ? asort($values) : arsort($values);
        // 重新排列原有数组
        foreach ($values as $k => $v) {
            $result[$k] = $array[$k];
        }

        return $result;
    }

    /**
     * 获取话题列表
     * @param $map 查询条件
     * @return mixed
     */
    public function getTopicByMap($map)
    {
        $list=$this->where($map)->select();
        return $list;
    }

    /**
     * 获取动态右侧热门话题排行榜
     * @return mixed
     */
    public function getHotTopicList()
    {
        $tag='HOT_TOPIC_WEIBO_RIGHT';
        $list=S($tag);
        if($list===false){
            $list=$this->where(array('status'=>1))->order('weibo_num desc')->limit(10)->select();
            S($tag,$list,60);
        }
        return $list;
    }

    /**删除动态后对话题进行相关操作
     * @param $weibo_id
     * @return mixed
     */
    public function afterDelWeibo($weibo_id)
    {
        $weiboTopicLink=M('weibo_topic_link');
        $map=array('weibo_id'=>$weibo_id);
        $weiboTopicLink->where($map)->setField('status',-1);
        $topic_ids=$weiboTopicLink->where($map)->field('topic_id')->select();
        $topic_ids=array_column($topic_ids,'topic_id');
        $res=$this->where(array('id'=>array('in',$topic_ids)))->setDec('weibo_num');
        return $res;
    }
    
    public function getTopicInfo($topk_id)
    {
        $tag='topic_info_'.$topk_id;
        $res=S($tag);
        if(empty($res)){
            $res=$this->where(array('id'=>$topk_id))->find();
            S($tag,$res,60*10);
        }
      
        return $res;
    }
}