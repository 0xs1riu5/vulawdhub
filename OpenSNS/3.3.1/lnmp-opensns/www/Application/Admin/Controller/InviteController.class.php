<?php
/**
 * 邀请注册
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-23
 * Time: 下午2:52
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

class InviteController extends AdminController
{
    protected $inviteModel;
    protected $inviteTypeModel;
    protected $inviteBuyLogModel;
    protected $inviteLogModel;
    protected $inviteUserInfoModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->inviteModel=D('Ucenter/Invite');
        $this->inviteTypeModel=D('Ucenter/InviteType');
        $this->inviteBuyLogModel=D('Ucenter/InviteBuyLog');
        $this->inviteLogModel=D('Ucenter/InviteLog');
        $this->inviteUserInfoModel=D('Ucenter/InviteUserInfo');
    }

    /**
     * 邀请注册基本配置
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function config()
    {
        $builder = new AdminConfigBuilder;
        $data = $builder->handleConfig();
        !isset($data['REGISTER_TYPE'])&&$data['REGISTER_TYPE']='normal';

        $register_options=array(
            'normal'=>L('_ORDINARY_REGISTRATION_'),
            'invite'=>L('_INVITED_TO_REGISTER_')
        );
        $builder->title(L('_INVITE_REGISTERED_INFORMATION_CONFIGURATION_'))
            ->keyCheckBox('REGISTER_TYPE', L('_REGISTERED_TYPE_'), L('_CHECK_TO_OPEN_'),$register_options)
            ->data($data)
            ->buttonSubmit()
            ->buttonBack()
            ->display();
    }

    /**
     * 邀请码类型列表
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function index()
    {
        $data_list=$this->inviteTypeModel->getList();
        $builder=new AdminListBuilder();
        $builder->title(L('_INVITE_CODE_TYPE_LIST_'))
            ->buttonNew(U('Invite/edit'))
            ->button(L('_DELETE_'),array('class' => 'btn ajax-post confirm', 'url' => U('Invite/setStatus', array('status' => -1)), 'target-form' => 'ids', 'confirm-info' => L('_DELETE_CONFIRM_')))
            ->keyId()->keyTitle()->keyText('length',L('_INVITE_CODE_LENGTH_'))->keyText('time_show',L('_LONG_'))
            ->keyText('cycle_num',L('_PERIOD_CAN_BUY_A_FEW_'))->keyText('cycle_time_show',L('_PERIOD_IS_LONG_'))
            ->keyText('roles_show',L('_BINDING_IDENTITY_'))->keyText('auth_groups_show',L('_ALLOWS_USERS_TO_BUY_'))
            ->keyText('pay',L('_EACH_AMOUNT_'))->keyText('income',L('_AFTER_EVERY_SUCCESS_'))
            ->keyBool('is_follow',L('_SUCCESS_IS_CONCERNED_WITH_EACH_OTHER_'))->keyCreateTime()->keyUpdateTime()
            ->keyDoActionEdit('Invite/edit?id=###')
            ->data($data_list)
            ->display();
    }

    /**
     * 编辑邀请码类型
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function edit()
    {
        $aId=I('id',0,'intval');
        $is_edit=$aId?1:0;
        $title=$is_edit?L('_EDIT_'):L('_NEW_');
        if(IS_POST){
            $data['title']=I('post.title','','op_t');
            $data['length']=I('post.length',0,'intval');
            $data['time_num']=I('post.time_num',0,'intval');
            $data['time_unit']=I('post.time_unit','second','op_t');
            $data['cycle_num']=I('post.cycle_num',0,'intval');
            $data['cycle_time_num']=I('post.cycle_time_num',0,'intval');
            $data['cycle_time_unit']=I('post.cycle_time_unit','second','op_t');
            $data['roles']=I('post.roles',array());
            $data['auth_groups']=I('post.auth_groups',array());
            $data['pay_score_type']=I('post.pay_score_type',1,'intval');
            $data['pay_score']=I('post.pay_score',0,'intval');
            $data['income_score_type']=I('post.income_score_type',1,'intval');
            $data['income_score']=I('post.income_score',0,'intval');
            $data['is_follow']=I('post.is_follow',0,'intval');
            if($is_edit){
                $data['id']=$aId;
                $result=$this->inviteTypeModel->saveData($data);
            }else{
                $result=$this->inviteTypeModel->addData($data);
            }
            if($result){
                $this->success($title.L('_INVITATION_CODE_TYPE_SUCCESS_'),U('Invite/index'));
            }else{
                $this->error($title.L('_INVITATION_CODE_TYPE_FAILED_').$this->inviteTypeModel->getError());
            }
        }else{
            if($is_edit){
                $map['id']=$aId;
                $data=$this->inviteTypeModel->getData($map);

                $data['time']=explode(' ',$data['time']);
                $data['time_num']=$data['time'][0];
                $data['time_unit']=$data['time'][1];

                $data['cycle_time']=explode(' ',$data['cycle_time']);
                $data['cycle_time_num']=$data['cycle_time'][0];
                $data['cycle_time_unit']=$data['cycle_time'][1];
            }

            $data['length']=$data['length']?$data['length']:11;

            $score_option=$this->_getMemberScoreType();
            $role_option=$this->_getRoleOption();
            $auth_group_option=$this->_getAuthGroupOption();
            $is_follow_option=array(
                0=>L('_NO_'),
                1=>L('_YES_')
            );

            $builder=new AdminConfigBuilder();

            $builder->title($title.L('_INVITATION_CODE_TYPE_'));
            $builder->keyId()->keyTitle()->keyText('length',L('_INVITE_CODE_LENGTH_'))
                ->keyMultiInput('time_num|time_unit',L('_LONG_'),L('_TIME_UNIT_'),array(array('type'=>'text','style'=>'width:295px;margin-right:5px'),array('type'=>'select','opt'=>get_time_unit(),'style'=>'width:100px')))
                ->keyInteger('cycle_num',L('_PERIOD_CAN_BUY_A_FEW_'))
                ->keyMultiInput('cycle_time_num|cycle_time_unit',L('_PERIOD_IS_LONG_'),L('_TIME_UNIT_'),array(array('type'=>'text','style'=>'width:295px;margin-right:5px'),array('type'=>'select','opt'=>get_time_unit(),'style'=>'width:100px')))
                ->keyChosen('roles',L('_BINDING_IDENTITY_'),'',$role_option)
                ->keyChosen('auth_groups',L('_ALLOWS_USERS_TO_BUY_'),'',$auth_group_option)
                ->keyMultiInput('pay_score_type|pay_score',L('_EVERY_INVITATION_AMOUNT_'),L('_SCORE_NUMBER_'),array(array('type'=>'select','opt'=>$score_option,'style'=>'width:100px;margin-right:5px'),array('type'=>'text','style'=>'width:295px')))
                ->keyMultiInput('income_score_type|income_score',L('_EACH_INVITATION_WAS_SUCCESSFUL_'),L('_SCORE_NUMBER_'),array(array('type'=>'select','opt'=>$score_option,'style'=>'width:100px;margin-right:5px'),array('type'=>'text','style'=>'width:295px')))
                ->keyRadio('is_follow',L('_SUCCESS_IS_CONCERNED_WITH_EACH_OTHER_'),'',$is_follow_option)
                ->buttonSubmit()->buttonBack()
                ->data($data)
                ->display();
        }
    }

    /**
     * 真删除邀请码类型
     * @param mixed|string $ids
     * @param $status
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setStatus($ids,$status=-1)
    {
        $ids=is_array($ids)?$ids:explode(',',$ids);
        //删除邀请码类型，真删除
        if($status==-1){
            $this->inviteTypeModel->deleteIds($ids);
            $this->success(L('_OPERATION_SUCCESS_'));
        }else{
            $this->error(L('_UNKNOWN_OPERATION_'));
        }

    }

    /**
     * 邀请码列表页
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function invite($page=1,$r=20)
    {
        $aBuyer=I('buyer',0,'intval');
        if($aBuyer==1){
            $map['uid']=array('gt',0);
        }else{
            $map['uid']=array('lt',0);
        }
        $aStatus=I('status',1,'intval');
        $status=$aStatus;
        if($aStatus==3){
            $status=1;
            $map['end_time']=array('lt',time());
        }else if($aStatus==1){
            $map['end_time']=array('egt',time());
        }
        $map['status']=$status;

        $aType=I('type',0,'intval');
        if($aType!=0){
            $map['invite_type']=$aType;
        }

        list($list,$totalCount)=$this->inviteModel->getList($map,$page,$r);
        $typeOptions=$this->_getTypeList();
        foreach($typeOptions as &$val){
            $val['value']=$val['title'];
        }
        unset($val);
        $typeOptions=array_merge(array(array('id'=>0,'value'=>L('_ALL_'))),$typeOptions);
        if($aStatus==1){
            $this->assign('invite_list',$list);
            $this->assign('buyer',$aBuyer);
            $this->assign('type_list',$typeOptions);
            $this->assign('now_type',$aType);
            //生成翻页HTML代码
            C('VAR_PAGE', 'page');
            $pager = new \Think\PageBack($totalCount, $r, $_REQUEST);
            $pager->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %HEADER%');
            $paginationHtml = $pager->show();
            $this->assign('pagination', $paginationHtml);
            $this->display();
        }else{
            $builder=new AdminListBuilder();
            $builder->title(L('_INVITE_CODE_LIST_PAGE_'))
                ->setSelectPostUrl(U('Invite/invite'))
                /*->buttonDelete(U('Invite/delete'))*/
                ->buttonModalPopup(U('Invite/createCode'),array(),L('_GENERATE_AN_INVITATION_CODE_'),array('data-title'=>L('_GENERATE_AN_INVITATION_CODE_')))
                ->buttonDelete(U('Invite/deleteTrue'),L('_DELETE_INV_CODE_WEAK_'))
                ->select('邀请码类型：','type','select','','','',$typeOptions)
                ->select('','status','select','','','',array(array('id'=>'1','value'=>L('_REGISTERED_')),array('id'=>'3','value'=>L('_EXPIRED_')),array('id'=>'2','value'=>L('_HAS_BEEN_RETURNED_')),array('id'=>'0','value'=>L('_RUN_OUT_')),array('id'=>'-1','value'=>L('_ADMIN_DELETE_'))))
                ->select('','buyer','select','','','',array(array('id'=>'-1','value'=>L('_ADMINISTRATOR_GENERATION_')),array('id'=>'1','value'=>L('_USER_PURCHASE_'))))
                ->keyId()
                ->keyText('code',L('_INVITATION_CODE_'))
                ->keyText('code_url',L('_INVITE_CODE_LINK_'))
                ->keyText('invite',L('_INVITATION_CODE_TYPE_'))
                ->keyText('buyer',L('_BUYERS_'))
                ->keyText('can_num',L('_CAN_BE_REGISTERED_A_FEW_'))
                ->keyText('already_num',L('_ALREADY_REGISTERED_A_FEW_'))
                ->keyTime('end_time',L('_PERIOD_OF_VALIDITY_'))
                ->keyCreateTime()
                ->data($list)
                ->pagination($totalCount,$r)
                ->display();
        }

    }

    /**
     * 生成邀请码
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function createCode()
    {
        if(IS_POST){
            $data['invite_type']=I('post.invite',0,'intval');
            $aCodeNum=I('post.code_num',0,'intval');
            $aCanNum=$data['can_num']=I('post.can_num',0,'intval');
            if($aCanNum<=0||$aCodeNum<=0){
                $result['status']=0;
                $result['info']=L('_GENERATE_A_NUMBER_AND_CAN_BE_REGISTERED_A_NUMBER_CAN_NOT_BE_LESS_THAN_1_');
            }else{
                $result=$this->inviteModel->createCodeAdmin($data,$aCodeNum);
            }
            $this->ajaxReturn($result);
        }else{
            $type_list=$this->_getTypeList();
            $this->assign('type_list',$type_list);
            $this->display('create');
        }
    }

    /**
     * 伪删除邀请码
     * @param string $ids
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function delete($ids)
    {
        $ids=is_array($ids)?$ids:explode(',',$ids);
        $result=$this->inviteModel->where(array('id'=>array('in',$ids)))->setField('status','-1');
        if($result){
            $this->success(L('_OPERATION_SUCCESS_'));
        }else{
            $this->error(L('_OPERATION_FAILED_').$this->inviteModel->getError());
        }
    }

    /**
     * 删除无用的邀请码
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function deleteTrue()
    {
        $map['status']=array('neq',1);
        $map['end_time']=array('lt',time());
        $map['_logic']='OR';
        $result=$this->inviteModel->where($map)->delete();
        if($result){
            $this->success(L('_OPERATION_SUCCESS_'));
        }else{
            $this->error(L('_OPERATION_FAILED_').$this->inviteModel->getError());
        }
    }

    /**
     * 用户兑换名额记录
     * @param int $page
     * @param int $r
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function buyLog($page=1,$r=20)
    {
        $aInviteType=I('invite_type',0,'intval');
        $aOrder=I('order',0,'intval');
        if($aInviteType){
            $map['invite_type']=$aInviteType;
        }
        if($aOrder==0){
            $order='create_time desc';
        }elseif($aOrder==1){
            $order='create_time asc';
        }elseif($aOrder==2){
            $order='uid asc,invite_type asc,create_time desc';
        }
        list($list,$totalCount)=$this->inviteBuyLogModel->getList($map,$page,$order,$r);
        $orderOptions=array(
            array('id'=>0,'value'=>L('_LATEST_CREATION_')),
            array('id'=>1,'value'=>L('_FIRST_CREATED_')),
            array('id'=>2,'value'=>L('_USER_'))
        );
        $typeOptions=$this->_getTypeList();
        foreach($typeOptions as &$val){
            $val['value']=$val['title'];
        }
        unset($val);
        $typeOptions=array_merge(array(array('id'=>0,'value'=>L('_ALL_'))),$typeOptions);

        $builder=new AdminListBuilder();
        $builder->title(L('_USER_EXCHANGE_QUOTA_RECORD_'))
            ->setSelectPostUrl(U('Invite/buyLog'))
            ->select(L('_INVITATION_CODE_TYPE_').L('_COLON_'),'invite_type','select','','','',$typeOptions)
            ->select(L('_SORT_TYPE_').L('_COLON_'),'order','select','','','',$orderOptions)
            ->keyId()
            ->keyText('user',L('_BUYERS_'))
            ->keyText('invite_type_title',L('_INVITATION_CODE_TYPE_'))
            ->keyText('num',L('_EXCHANGE_COUNT_'))
            ->keyText('content',L('_INFORMATION_'))
            ->keyCreateTime()
            ->pagination($totalCount,$r)
            ->data($list)
            ->display();
    }

    /**
     * 用户邀请信息列表
     * @param int $page
     * @param int $r
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function userInfo($page=1,$r=20)
    {
        $aInviteType=I('invite_type',0,'intval');
        if($aInviteType){
            $map['invite_type']=$aInviteType;
        }
        list($list,,$totalCount)=$this->inviteUserInfoModel->getList($map,$page,$r);

        $typeOptions=$this->_getTypeList();
        foreach($typeOptions as &$val){
            $val['value']=$val['title'];
        }
        unset($val);
        $typeOptions=array_merge(array(array('id'=>0,'value'=>L('_ALL_'))),$typeOptions);

        $builder=new AdminListBuilder();
        $builder->title(L('_USER_INFORMATION_'))
            ->setSelectPostUrl(U('Invite/userInfo'))
            ->select(L('_INVITATION_CODE_TYPE_').L('_COLON_'),'invite_type','select','','','',$typeOptions)
            ->keyId()
            ->keyText('user',L('_USER_'))
            ->keyText('invite_type_title',L('_INVITATION_CODE_TYPE_'))
            ->keyText('num',L('_AVAILABLE_'))
            ->keyText('already_num',L('_ALREADY_INVITED_'))
            ->keyText('success_num',L('_SUCCESSFUL_INVITATION_'))
            ->keyDoActionEdit('Invite/editUserInfo?id=###')
            ->pagination($totalCount,$r)
            ->data($list)
            ->display();
    }

    /**
     * 编辑用户邀请信息
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function editUserInfo()
    {
        $aId=I('id',0,'intval');
        if($aId<=0){
            $this->error(L('_PARAMETER_ERROR_'));
        }
        if(IS_POST){
            $data['num']=I('num',0,'intval');
            $data['already_num']=I('already_num',0,'intval');
            $data['success_num']=I('success_num',0,'intval');
            if($data['num']<0||$data['already_num']<0||$data['success_num']<0){
                $this->error(L('_PLEASE_FILL_IN_THE_CORRECT_DATA_'));
            }
            $result=$this->inviteUserInfoModel->saveData($data,$aId);
            if($result){
                $this->success(L('_EDITOR_SUCCESS_'),U('Admin/Invite/userInfo'));
            }else{
                $this->error(L('_EDIT_FAILED_'));
            }
        }else{
            $map['id']=$aId;
            $data=$this->inviteUserInfoModel->getInfo($map);

            $builder=new AdminConfigBuilder();
            $builder->title(L('_EDIT_USER_INVITATION_INFORMATION_'))
                ->keyId()
                ->keyReadOnly('uid',L('_USER_ID_'))
                ->keyReadOnly('invite_type',L('_INVITATION_CODE_TYPE_ID_'))
                ->keyInteger('num',L('_AVAILABLE_'))
                ->keyInteger('already_num',L('_INVITED_PLACES_'))
                ->keyInteger('success_num',L('_SUCCESSFUL_INVITATION_'))
                ->data($data)
                ->buttonSubmit()->buttonBack()
                ->display();
        }
    }

    /**
     * 邀请日志
     * @param int $page
     * @param int $r
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function inviteLog($page=1,$r=20)
    {
        list($list,,$totalCount)=$this->inviteLogModel->getList($page,$r);
        $builder=new AdminListBuilder();
        $builder->title(L('_INVITE_REGISTRATION_RECORDS_'))
            ->keyId()
            ->keyText('user','注册者')
            ->keyText('inviter',L('_INVITED_'))
            ->keyText('invite_type_title','邀请码类型')
            ->keyText('content',L('_INFORMATION_'))
            ->keyCreateTime('create_time',L('_REGISTRATION_TIME_'))
            ->pagination($totalCount,$r)
            ->data($list)
            ->display();
    }

    /**
     * 导出cvs
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function cvs()
    {
        $aIds=I('ids',array());

        if(count($aIds)){
            $map['id']=array('in',$aIds);
        }else{
            $map['status']=array('in',array(1,0,-1));
            $dataListBack=$this->inviteModel->getListAll(array('status'=>2));
        }
        $dataList=$this->inviteModel->getListAll($map,'status desc,end_time desc');
        if(!count($dataList)&&!count($dataListBack)){
            $this->error(L('_NO_DATA_'));
        }
        if(count($dataListBack)){
            if(count($dataList)){
                $dataList=array_merge($dataList,$dataListBack);
            }else{
                $dataList=$dataListBack;
            }
        }
        $data=L('_DATA_MANY_')."\n";
        foreach ($dataList as $val) {
            if($val['status']==-1){
                $val['status']=L('_ADMIN_DELETE_');
            }elseif($val['status']==0){
                $val['status']=L('_RUN_OUT_');
            }elseif($val['status']==1){
                if($val['end_time']<=time()){
                    $val['status']=L('_EXPIRED_');
                }else{
                    $val['status']=L('_REGISTERED_');
                }
            }elseif($val['status']==2){
                $val['status']=L('_HAS_BEEN_RETURNED_');
            }
            $val['end_time']=time_format($val['end_time']);
            $val['create_time']=time_format($val['create_time']);
            $data.=$val['id'].",[".$val['invite_type']."]".$val['invite'].",".$val['code'].",".$val['code_url'].",[".$val['uid']."]".$val['buyer'].",".$val['can_num'].",".$val['already_num'].",".$val['end_time'].",".$val['status'].",".$val['create_time']."\n";
        }
        $data=iconv('utf-8','gb2312',$data);
        $filename = date('Ymd').'.csv'; //设置文件名
        $this->export_csv($filename,$data); //导出
    }

    private function export_csv($filename,$data) {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        echo $data;
    }

    //私有函数 start

    /**
     * 获取身份列表
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _getRoleOption()
    {
        $role_option=D('Role')->where(array('status'=>1))->order('sort asc')->field('id,title')->select();
        return $role_option;
    }

    /**
     * 获取权限权限组列表
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _getAuthGroupOption()
    {
        $role_option=D('AuthGroup')->where(array('status'=>1))->field('id,title')->select();
        return $role_option;
    }

    /**
     * 获取积分类型列表
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _getMemberScoreType()
    {
        $score_option=D('UcenterScoreType')->where(array('status'=>1))->field('id,title')->select();
        $score_option=array_combine(array_column($score_option,'id'),array_column($score_option,'title'));
        return $score_option;
    }

    private function _getTypeList(){
        $map['status']=1;
        $type_list=$this->inviteTypeModel->getSimpleList($map);
        return $type_list;
    }

    //私有函数 end
} 