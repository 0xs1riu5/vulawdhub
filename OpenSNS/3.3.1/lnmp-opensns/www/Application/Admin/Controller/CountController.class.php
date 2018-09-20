<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-7-22
 * Time: 上午9:46
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;

class CountController extends AdminController{

    protected $countModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->assign('now_table',ACTION_NAME);
        $this->countModel=D('Count');
    }

    /**
     * 网站统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function index()
    {
        if(IS_POST){
            $count_day=I('post.count_day', C('COUNT_DAY'),'intval',7);
            if(M('Config')->where(array('name'=>'COUNT_DAY'))->setField('value',$count_day)===false){
                $this->error("设置失败！");
            }else{
                S('DB_CONFIG_DATA',null);
                $this->success("设置成功！",'refresh');
            }

        }else{
            $this->meta_title = L('_INDEX_MANAGE_');
            $today = date('Y-m-d', time());
            $today = strtotime($today);
            $count_day = C('COUNT_DAY',null,7);
            $count['count_day']=$count_day;
            for ($i = $count_day; $i--; $i >= 0) {
                $day = $today - $i * 86400;
                $day_after = $today - ($i - 1) * 86400;
                $week_map=array('Mon'=>L('_MON_'),'Tue'=>L('_TUES_'),'Wed'=>L('_WEDNES_'),'Thu'=>L('_THURS_'),'Fri'=>L('_FRI_'),'Sat'=>'<strong>'.L('_SATUR_').'</strong>','Sun'=>'<strong>'.L('_SUN_').'</strong>');
                $week[] = date('m月d日 ', $day). $week_map[date('D',$day)];
                $user = UCenterMember()->where('status=1 and reg_time >=' . $day . ' and reg_time < ' . $day_after)->count() * 1;
                $registeredMemeberCount[] = $user;
                if ($i == 0) {
                    $count['today_user'] = $user;
                }
            }
            $week = json_encode($week);
            $this->assign('week', $week);
            $count['total_user'] = $userCount = UCenterMember()->where(array('status' => 1))->count();
            $count['today_action_log'] = M('ActionLog')->where('status=1 and create_time>=' . $today)->count();
            $count['last_day']['days'] = $week;
            $count['last_day']['data'] = json_encode($registeredMemeberCount);
            // dump($count);exit;
            if(C('SESSION_TYPE')=='db') {
                $count['now_inline'] = M('Session')->where(array('session_expire'=>array('gt',time())))->count() * 1;
            }

            $this->assign('count', $count);
            $this->meta_title = '网站统计';
            $this->display();
        }
    }

    /**
     * 流失率统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function lost($page=1,$r=10)
    {
        if(IS_POST){
            $aLostLong=I('post.lost_long',30,'intval');
            if($aLostLong>=1){
                if(M('Config')->where(array('name'=>'LOST_LONG'))->setField('value',$aLostLong)===false){
                    $this->error("设置失败！");
                }else{
                    S('DB_CONFIG_DATA',null);
                    $this->success("设置成功！");
                }
            }
        }else{
            $day=C('LOST_LONG',null,30);
            $this->assign('lost_long',$day);
            list($lostList,$totalCount)=$this->countModel->getLostListPage($map=1,$page,$r);
            foreach($lostList as &$val){
                $val['date']=time_format($val['date'],'Y-m-d');
                $val['rate']=($val['rate']*100)."%";
            }
            unset($val);
            $this->assign('lostList',$lostList);

            //生成翻页HTML代码
            C('VAR_PAGE', 'page');
            $pager = new \Think\PageBack($totalCount,$r, $_REQUEST);
            $pager->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %HEADER%');
            $paginationHtml = $pager->show();

            $this->assign('pagination', $paginationHtml);
            $this->meta_title = '流失率统计';
            $this->display();
        }
    }

    /**
     * 留存率统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function remain()
    {
        if(IS_POST){
            $aStartTime=I('post.startDate','','text');
            $aEndTime=I('post.endDate','','text');
            if($aStartTime==''||$aEndTime==''){
                $this->error('请选择时间段!');
            }
            $startTime=strtotime($aStartTime);
            $endTime=strtotime($aEndTime);
            $remainList=$this->countModel->getRemainList($startTime,$endTime);
            $this->assign('remainList',$remainList);
            $html=$this->fetch(T('Application://Admin@Count/_remain_data'));
            $this->show($html);
        }else{
            $today=date('Y-m-d 00:00',time());
            $startTime=strtotime($today." - 9 day");
            $endTime=strtotime($today." - 2 day");
            $remainList=$this->countModel->getRemainList($startTime,$endTime);
            $options=array('startDate'=>time_format(strtotime($today." - 9 day"),"Y-m-d"),'endDate'=>time_format(strtotime($today." - 2 day"),"Y-m-d"));
            $this->assign('options',$options);
            $this->assign('remainList',$remainList);
            $this->meta_title = '留存率统计';
            $this->display();
        }
    }

    /**
     * 消费用户统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function consumption()
    {
        if(IS_POST){
            $aStartTime=I('post.startDate','','text');
            $aEndTime=I('post.endDate','','text');
            if($aStartTime==''||$aEndTime==''){
                $this->error('请选择时间段!');
            }
            $startTime=strtotime($aStartTime);
            $endTime=strtotime($aEndTime);
            $consumptionList=$this->countModel->getConsumptionList($startTime,$endTime);
            $consumptionList['status']=1;
            $this->ajaxReturn($consumptionList);
        }else{
            $today=date('Y-m-d 00:00',time());
            $startTime=strtotime($today." - 10 day");
            $endTime=strtotime($today);

            $consumptionList=$this->countModel->getConsumptionList($startTime,$endTime);
            $options=array('startDate'=>time_format(strtotime($today." - 10 day"),"Y-m-d"),'endDate'=>time_format(strtotime($today),"Y-m-d"));
            $this->assign('options',$options);
            $this->assign('consumptionList',json_encode($consumptionList));
            $this->meta_title = '消费用户统计';
            $this->display();
        }
    }

    /**
     * 活跃用户统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function active()
    {
        if(IS_POST){
            $aType=I('post.type','day','text');
            $aStartTime=I('post.startDate','','text');
            $aEndTime=I('post.endDate','','text');
            if($aStartTime==''||$aEndTime==''){
                $this->error('请选择时间段!');
            }
            $startTime=strtotime($aStartTime);
            $endTime=strtotime($aEndTime);
            if(!in_array($aType,array('week','month','day'))){
                $aType='day';
            }
            $activeList=$this->countModel->getActiveList($startTime,$endTime,$aType);
            $activeList['status']=1;
            $this->ajaxReturn($activeList);
        }else{
            $aType=I('get.type','day','text');
            switch($aType){
                case 'week':
                    $startTime=strtotime(date('Y-m-d').' - '.date('w').' day - 91 day');
                    break;
                case 'month':
                    $startTime=strtotime(date('Y-m-01').' - 9 month');
                    break;
                case 'day':
                default:
                    $aType='day';
                    $startTime=strtotime(date('Y-m-d').' - 9 day');
            }
            $this->assign('type',$aType);
            $options=array('startDate'=>time_format($startTime,"Y-m-d"),'endDate'=>time_format(time(),"Y-m-d"));
            $this->assign('options',$options);
            $activeList=$this->countModel->getActiveList($startTime,time(),$aType);
            $this->assign('activeList',json_encode($activeList));
            $this->meta_title = '活跃用户统计';
            $this->display();
        }
    }

    /**
     * 设置活跃度绑定的行为
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setActiveAction()
    {
        if(IS_POST){
            $aActiveAction=I('post.active_action',3,'intval');
            if(M('Config')->where(array('name'=>'COUNT_ACTIVE_ACTION'))->setField('value',$aActiveAction)===false){
                $this->error("设置失败！");
            }else{
                S('DB_CONFIG_DATA',null);
                $this->success("设置成功！");
            }
        }else{
            $map['status']=1;
            $actionList=D('Action')->getAction($map);
            $this->assign('action_list',$actionList);
            $nowAction=C('COUNT_ACTIVE_ACTION',null,3);
            $this->assign('now_active_action',$nowAction);
            $this->meta_title = '设置活跃度绑定的行为';
            $this->display('set_active_action');
        }
    }

    /**
     * 在线用户列表
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function nowUserList($page=1,$r=20)
    {
        if(C('SESSION_TYPE')!='db'){
            $this->error('当前只支持session存入数据库的情况下进行在线用户列表统计！');
        }
        $sessionModel=M('Session');
        $map['session_expire']=array('gt',time());
        $totalCount=$sessionModel->where($map)->count()*1;
        $map['session_data']=array('neq','');
        $loginCount=$sessionModel->where($map)->count()*1;
        $userList=$sessionModel->where($map)->page($page,$r)->field('session_id,session_expire')->select();
        $memberModel=M('Member');
        foreach ($userList as &$val){
            $user=$memberModel->where(array('session_id'=>$val['session_id']))->find();
            if(!$user){
                $val['uid']=0;
                $val['nickname']='不是在网站端登录，没有对应上session_id';
                $val['last_login_time']=$user['last_login_time'];
                $val['id']=$val['session_id'];
                continue;
            }
            $val['uid']=$user['uid'];
            $val['nickname']=$user['nickname'];
            $val['last_login_time']=$user['last_login_time'];
            $val['id']=$val['session_id'];
        }
        unset($key,$val);

        $data['userList']=$userList;
        $data['loginCount']=$loginCount;
        $data['totalCount']=$totalCount;
        $this->assign($data);

        //生成翻页HTML代码
        C('VAR_PAGE', 'page');
        $pager = new \Think\PageBack($loginCount,$r, $_REQUEST);
        $pager->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %HEADER%');
        $paginationHtml = $pager->show();

        $this->assign('pagination', $paginationHtml);
        $this->meta_title = '流失率统计';
        $this->assign('now_table','now_user');
        $this->display('user');
    }

    /**
     * 下线在线用户
     * @param $ids
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function downUser($ids=0,$all=0)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        $sessionModel=M('Session');
        $memberModel=M('Member');
        $userTokenModel=M('UserToken');
        if($all){
            $map['session_data']=array('neq','');
            $sessionModel->where($map)->setField('session_data','');
            $userTokenModel->where(1)->delete();
        }else{
            $map['session_id']=array('in',$ids);
            $sessionModel->where($map)->setField('session_data','');
            $uids=$memberModel->where($map)->field('uid')->select();
            $uids=array_column($uids,'uid');
            $userTokenModel->where(array('uid'=>array('in',$uids)))->delete();
        }
        $this->success('操作成功！');
    }
} 