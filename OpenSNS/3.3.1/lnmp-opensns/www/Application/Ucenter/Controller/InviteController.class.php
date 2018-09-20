<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-25
 * Time: 上午10:19
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Controller;


use Think\Controller;

class InviteController extends BaseController
{
    protected $mInviteModel;
    protected $mInviteTypeModel;
    protected $mInviteBuyLogModel;
    protected $mInviteUserInfoModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->mInviteModel=D('Ucenter/Invite');
        $this->mInviteTypeModel=D('Ucenter/InviteType');
        $this->mInviteBuyLogModel=D('Ucenter/InviteBuyLog');
        $this->mInviteUserInfoModel=D('Ucenter/InviteUserInfo');
    }

    /**
     * 邀请码类型列表页
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function index()
    {
        //获取邀请码类型列表
        $typeList=$this->mInviteTypeModel->getUserTypeList();
        $this->assign('invite_type_list',$typeList);
        $this->defaultTabHash('invite');
        $this->assign('type','index');
        $this->display();
    }

    /**
     * 邀请码列表页
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function invite()
    {
        $typeList=$this->mInviteTypeModel->getUserTypeSimpleList();
        foreach($typeList as $key=>&$val){
            $val['codes']=$this->_getUserCode($val['id']);
            if(!$val['codes']){
                unset($typeList[$key]);
            }
        }
        unset($val);
        $this->assign('type_list',$typeList);
        $this->defaultTabHash('invite');
        $this->assign('type','invite');
        $this->display();
    }

    /**
     * 兑换邀请名额
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function exchange()
    {
        if(IS_POST){
            $aTypeId=I('post.invite_id',0,'intval');
            $aNum=I('post.exchange_num',0,'intval');
            $this->_checkCanBuy($aTypeId,$aNum);
            $inviteType=$this->mInviteTypeModel->where(array('id'=>$aTypeId))->find();
            D('Ucenter/Score')->setUserScore(array(is_login()),$aNum*$inviteType['pay_score'],$inviteType['pay_score_type'],'dec','',0,L('_INV_QUOTA_2_'));//扣积分

            $result=$this->mInviteBuyLogModel->buy($aTypeId,$aNum);
            if($result){
                $this->mInviteUserInfoModel->addNum($aTypeId,$aNum);
                $data['status']=1;
            }else{
                $data['status']=0;
                $data['info']=L('_INFO_EXCHANGE_FAIL_');
            }
            $this->ajaxReturn($data);
        }else{
            $aId=I('id',0,'intval');
            $can_buy_num=$this->_getCanBuyNum($aId);
            $this->assign('can_buy_num',$can_buy_num);
            $this->assign('id',$aId);
            $this->display();
        }
    }

    /**
     * 生成邀请码
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function createCode()
    {
        if(IS_POST){
            $aTypeId=I('post.invite_type',0,'intval');
            $aCodeNum=I('post.code_num',0,'intval');
            $aCanNum=I('post.can_num',0,'intval');

            //判断合法性
            $result['status']=0;
            if($aTypeId<=0){
                $result['info']=L('_ERROR_PARAM_').L('_EXCLAMATION_');
                $this->ajaxReturn($result);
            }
            $userInfo=$this->mInviteUserInfoModel->getInfo(array('uid'=>is_login(),'invite_type'=>$aTypeId));
            if($aCodeNum<=0||$aCanNum<=0||$aCodeNum*$aCanNum>$userInfo['num']){
                $result['info']=L('_INFO_RIGHT_INFO_INOUT_').L('_EXCLAMATION_');
                $this->ajaxReturn($result);
            }
            //判断合法性 end

            $this->mInviteUserInfoModel->decNum($aTypeId,$aCanNum*$aCodeNum);//修改用户信息
            $data['can_num']=$aCanNum;
            $data['invite_type']=$aTypeId;
            $result=$this->mInviteModel->createCodeUser($data,$aCodeNum);
            $this->ajaxReturn($result);
        }else{
            $aId=I('id',0,'intval');
            $inviteType=$this->mInviteTypeModel->where(array('id'=>$aId))->find();
            $userInfo=$this->mInviteUserInfoModel->getInfo(array('uid'=>is_login(),'invite_type'=>$aId));
            if($userInfo){
                $inviteType['can_num']=$userInfo['num'];
                $inviteType['already_num']=$userInfo['already_num'];
                $inviteType['success_num']=$userInfo['success_num'];
            }
            $this->assign('invite_type',$inviteType);
            $this->assign('id',$aId);
            $this->display('create');
        }
    }

    /**
     * 退还邀请码
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function backCode()
    {
        $aId=I('post.id',0,'intval');
        $result=$this->mInviteModel->backCode($aId);
        if($result){
            $data['status']=1;
        }else{
            $data['info']=L('_FAIL_RETURN_').L('_EXCLAMATION_');
            $data['status']=0;
        }
        $this->ajaxReturn($data);
    }

    /**
     * 获取用户邀请码
     * @param int $type_id 邀请码类型
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _getUserCode($type_id=0)
    {
        $map['uid']=is_login();
        $map['end_time']=array('gt',time());
        $map['invite_type']=$type_id;
        $map['status']=1;
        $inviteList=$this->mInviteModel->where($map)->select();
        foreach($inviteList as &$val){
            $val['num']=$val['can_num']-$val['already_num'];
            $val['code_url']=U('Ucenter/Member/register',array('code'=>$val['code']),true,true);
        }
        return $inviteList;
    }


    /**
     * 判断是否可兑换
     * @param int $inviteType
     * @param int $num
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _checkCanBuy($inviteType=0,$num=0)
    {
        $result['status']=0;
        if($num<=0){
            $result['info']=L('_INFO_RIGHT_NUMBER_').L('_EXCLAMATION_');
            $this->ajaxReturn($result);
        }
        if($inviteType==0){
            $result['info']=L('_ERROR_PARAM_').L('_EXCLAMATION_');
            $this->ajaxReturn($result);
        }
        if($num>($this->_getCanBuyNum($inviteType))){
            $result['info']=L('_INFO_EXCEED_COUNT_').L('_EXCLAMATION_');
            $this->ajaxReturn($result);
        }
        //验证是否有权限兑换
        $inviteType=$this->mInviteTypeModel->where(array('id'=>$inviteType))->find();
        if($inviteType['auth_groups']!=''){
            $inviteType['auth_groups']=str_replace('[','',$inviteType['auth_groups']);
            $inviteType['auth_groups']=str_replace(']','',$inviteType['auth_groups']);
            $inviteType['auth_groups']=explode(',',$inviteType['auth_groups']);
            $map['group_id']=array('in',$inviteType['auth_groups']);
            $map['uid']=is_login();
            if(!D('AuthGroupAccess')->where($map)->count()){
                $result['info']=L('_INFO_AUTHORITY_LACK_').L('_EXCLAMATION_');
                $this->ajaxReturn($result);
            }
        }

        return true;
    }

    /**
     * 获取可兑换最大值
     * @param int $inviteType
     * @return int
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _getCanBuyNum($inviteType=0)
    {
        $inviteType=$this->mInviteTypeModel->where(array('id'=>$inviteType))->find();
        $this->assign('long',unitTime_to_showUnitTime($inviteType['cycle_time']));
        $this->assign('num_buy',$inviteType['cycle_num']);
        //以周期算，获取最多购买
        $map['uid']=is_login();
        $map['invite_type']=$inviteType['id'];
        $map['create_time']=array('gt',unitTime_to_time($inviteType['cycle_time'],'-'));
        $buyList=$this->mInviteBuyLogModel->where($map)->select();
        $can_buy_num=0;
        foreach($buyList as $val){
            $can_buy_num+=$val['num'];
        }
        $can_buy_num=$inviteType['cycle_num']-$can_buy_num;
        //以周期算，获取最多购买 end
        $max_num_score=query_user('score'.$inviteType['pay_score_type']);
        if($inviteType['pay_score']!=0){
            $max_num_score=intval($max_num_score['score'.$inviteType['pay_score_type']]/$inviteType['pay_score']);//以积分算，获取最多购买
           $can_buy_num=$max_num_score>$can_buy_num?$can_buy_num:$max_num_score;

        }
        if($can_buy_num<0){
            $can_buy_num=0;
        }
        return $can_buy_num;

    }
} 