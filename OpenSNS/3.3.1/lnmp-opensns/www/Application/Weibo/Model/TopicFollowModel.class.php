<?php

namespace Weibo\Model;

use Think\Model;

class TopicFollowModel extends Model
{

    protected $tableName = 'weibo_topic_follow';

    public function followTopic($topicId,$uid,$status)
    {
        $status=$status==1?0:1;
        $data['topic_id']=$topicId;
        $data['uid']=$uid;
        $id=$this->where($data)->getField('id');
        if($id){
            $data['status']=$status;
            $res=$this->where(array('id'=>$id))->save($data);
        }else{
            $data['status']=$status;
            $data['create_time']=time();
            $res=$this->add($data);
        }
       
        return $res;

    }
    public function getFollowStatus($topicId,$uid){
        $tag='follow_topic_'.$uid.'_'.$topicId;
        $res=S($tag);
        if(empty($res)){
            $res=$this->where(array('topic_id'=>$topicId,'uid'=>$uid))->getField('status');
            $res=$res==1?1:0;
            S($tag,$res,60*60);
        }
        return $res;
    }
   public function getTopicFollow($topk_id){
       $tag='topic_follow_'.$topk_id;
       $res=S($tag);
       if(empty($res)){
           $uids=$this->where(array('topic_id'=>$topk_id,'status'=>1))->field('uid')->select();
           foreach ($uids as $uid){
               $res[]=$uid['uid'];
           }
           S($tag,$res,60*60);
       }
       return $res;
   }
   public function getMyTopic($uid)
   {
       $topkIds=$this->where(array('uid'=>$uid,'status'=>1))->field('topic_id')->select();
       foreach ($topkIds as $topk){
           $topks[]=D('Weibo/Topic')->getTopicInfo($topk['topic_id']);
       }
     return $topks;
   }
}