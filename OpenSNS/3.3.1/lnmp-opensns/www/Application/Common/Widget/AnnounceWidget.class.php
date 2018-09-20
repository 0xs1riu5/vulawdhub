<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-22
 * Time: 上午9:06
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Common\Widget;


use Think\Controller;

class AnnounceWidget extends Controller
{
    public function render($arr=array())
    {
        $this->_setAnnounceArrive();
        $is_show=cookie('announce_un_show_now');
        if(!$is_show){
            $announce=$this->_getAnnounce();
            if($announce){
                $this->assign('announce',$announce);
                $this->display(T('Application://Common@Widget/announce'));
            }
        }
        return true;
    }

    /**
     * 获取可展示的公告
     * @return null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _getAnnounce()
    {
        $list=S('Announce_list');
        if($list===false){//当前所有公告的列表
            $map['status']=1;
            $map['is_force']=1;
            $map['end_time']=array('gt',time());
            $announceModel=D('Common/Announce');
            $list=$announceModel->getList($map);
            if(!count($list)){
                $list=1;
            }
            S('Announce_list',$list);
        }
        $announce=null;
        if($list!=1){
            foreach($list as $key=>$val){
                if($val['end_time']<=time()||$val['is_force']==0){//去除过期的或非强制性公告
                    unset($list[$key]);
                }
            }
            unset($key,$val);
            if(!count($list)){
                $list=1;
                cookie('announce_cookie_ids',null);
            }else{
                $have_ids=array_column($list,'id');
                if(is_login()){
                    $arriveList=D('AnnounceArrive')->getListMap(array('uid'=>is_login(),'announce_id'=>array('in',$have_ids)));
                }else{
                    $arriveList=null;
                }
                $unShowId=cookie('announce_cookie_ids');
                if(count($arriveList)){
                    $arriveIds=array_column($arriveList,'announce_id');
                    if($unShowId){
                        $unShowId=explode(',',$unShowId);
                        $unShowId=array_unique(array_merge($unShowId,$arriveIds));
                    }else{
                        $unShowId=$arriveIds;
                    }
                }else{
                    if($unShowId){
                        $unShowId=explode(',',$unShowId);
                    }else{
                        $unShowId=array();
                    }
                }
                $unShowId=array_intersect($unShowId,$have_ids);
                foreach($list as $val){
                    if(!in_array($val['id'],$unShowId)){
                        $announce=$val;
                        break;
                    }
                }
                unset($val);
                $unShowId=implode(',',$unShowId);
                cookie('announce_cookie_ids',$unShowId);
            }
            S('Announce_list',$list);
        }
        return $announce;
    }

    /**
     * 设置公告已确认
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _setAnnounceArrive()
    {
        if(is_login()){
            $already_announce=cookie('announce_already_list');
            if($already_announce){
                $announceArriveModel=D('Common/AnnounceArrive');
                $already_announce=explode('|',$already_announce);
                $data['uid']=$map['uid']=is_login();
                foreach($already_announce as $val){
                    $val=explode(':',$val);
                    $data['announce_id']=$map['announce_id']=$val[0];
                    if(!$announceArriveModel->getData($map)){
                        $data['create_time']=intval($val[1]/1000);
                        $announceArriveModel->addData($data);
                    }
                }
                cookie('announce_already_list',null);
            }
        }
        return true;
    }
} 