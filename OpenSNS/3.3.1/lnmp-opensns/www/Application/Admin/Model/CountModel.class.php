<?php
/**
 * 数据统计模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-7-22
 * Time: 上午11:23
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Model;


use Think\Model;

class CountModel extends Model
{

    protected $lostModel,$remainModel,$consumptionModel,$activeModel;
    public function _initialize()
    {
        parent::_initialize();
        $this->lostModel=M('CountLost');
        $this->remainModel=M('CountRemain');
        $this->consumptionModel=M('CountConsumption');
        $this->activeModel=M('CountActive');
    }

    /**
     * 每日执行统计
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function dayCount()
    {
        $map['date']=strtotime(time_format(time(),'Y-m-d 00:00')." - 1 day");
        if(!$this->lostModel->where($map)->find()){
            $this->lostCount();
            $this->remainCount();
            $this->activeCount();
            $this->consumptionCount();
        }
        return true;
    }

    /**
     * 每日执行流失率统计
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function lostCount()
    {
        $memberModel=M('Member');
        $map['status']=1;
        $totalUser=$memberModel->where($map)->count()*1;

        $date=time_format(time(),'Y-m-d 00:00');
        $lost_long=modC('LOST_LONG',30,'Count');
        $select_date=strtotime($date." - ".$lost_long." day");
        $map['last_login_time']=array('lt',$select_date);
        $lostUser=$memberModel->where($map)->count()*1;

        $lostRate=$lostUser/$totalUser;

        $map_yesterday['date']=strtotime($date." - 2 day");
        $yesterdayInfo=$this->lostModel->where($map_yesterday)->find();
        if($yesterdayInfo){
            $data['new_lost']=$lostUser-$yesterdayInfo['lost_num'];
        }
        $data['date']=strtotime($date." - 1 day");
        $data['user_num']=$totalUser;
        $data['lost_num']=$lostUser;
        $data['rate']=$lostRate;
        $data['create_time']=time();
        $this->lostModel->add($data);
        return true;
    }

    /**
     * 每日执行留存率统计
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function remainCount()
    {
        $date=date('Y-m-d 00:00',time());
        $date=time_format(strtotime($date." - 2 day"),'Y-m-d 00:00');
        $this->_doRemainCount($date,1);
        return true;
    }

    /**
     * @param null $date 统计日期
     * @param int $day 统计几日留存率（1~8）
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _doRemainCount($date,$day=1)
    {
        //统计start
        $strDayTime=strtotime($date);
        $endDayTime=strtotime($date." + 1 day")-1;
        $strCountTime=strtotime($date." + ".$day." day");
        $endCountTime=strtotime($date." + ".($day+1)." day")-1;

        $data=null;
        $remain=$this->remainModel->where(array('date'=>$strDayTime))->find();
        if($remain){
            $data=$remain;
        }

        $memberModel=M('Member');
        $map_reg['reg_time']=array('between',array($strDayTime,$endDayTime));
        $map_reg['status']=1;
        $regUids=$memberModel->where($map_reg)->field('uid')->select();
        $regUids=array_column($regUids,'uid');
        if(!$remain){
            $data['reg_num']=count($regUids);
            $data['date']=$strDayTime;
        }

        if(count($regUids)){
            $tag='LOHIN_ACTION_ID';
            $login_action_id=S($tag);
            if($login_action_id===false){
                $login_action_id=M('Action')->where(array('name'=>'user_login','status'=>1))->getField('id');
                S($tag,$login_action_id);
            }

            $map_login['action_id']=$login_action_id;//用户登录行为id
            $map_login['user_id']=array('in',$regUids);
            $map_login['create_time']=array('between',array($strCountTime,$endCountTime));
            $loginUids=M('ActionLog')->where($map_login)->field('user_id')->select();
            $loginUids=array_column($loginUids,'user_id');
            $loginCount=count(array_unique($loginUids));
        }else{
            $loginCount=0;
        }
        $data['day'.$day.'_num']=$loginCount;
        S('DAY_'.$day,$data);
        if($remain){
            $this->remainModel->save($data);
        }else{

            $this->remainModel->add($data);
        }
        //统计end

        if($day==8){
            return true;
        }
        //下面执行前一天的统计
        $date=time_format(strtotime($date." - 1 day"),'Y-m-d 00:00');
        $day=$day+1;
        $this->_doRemainCount($date,$day);
        return true;
    }

    /**
     * 每日执行活跃用户统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function activeCount()
    {
        $activeAction = C('COUNT_ACTIVE_ACTION',null,3);
        $total_user=M('Member')->where(array('status'=>1))->count();

        $time=strtotime(time_format(time(),'Y-m-d'));
        $day_data=$this->_dayActiveCount($activeAction,$time);
        $day_data['total_num']=$total_user;
        $this->activeModel->add($day_data);

        if(date('w',$time)==='0'){
            $week_data=$this->_weekActiveCount($activeAction,$time);
            $week_data['total_num']=$total_user;
            $this->activeModel->add($week_data);
        }

        if($time===strtotime(time_format($time,'Y-m-01'))){
            $month_data=$this->_monthActiveCount($activeAction,$time);
            $month_data['total_num']=$total_user;
            $this->activeModel->add($month_data);
        }
        return true;
    }

    /**
     * 每日活跃度统计
     * @param $action
     * @param $today
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _dayActiveCount($action,$today)
    {
        $startTime=$today-24*60*60;
        $map['action_id']=$action;
        $map['create_time']=array('between',array($startTime,$today-1));
        $users=M('ActionLog')->where($map)->field('user_id')->select();
        $users=array_column($users,'user_id');
        $users=array_unique($users);
        $data['num']=count($users)*1;
        $data['type']='day';
        $data['date']=$startTime;
        return $data;
    }

    /**
     * 每周活跃度统计
     * @param $action
     * @param $today
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _weekActiveCount($action,$today)
    {
        $startTime=$today-7*24*60*60;
        $map['action_id']=$action;
        $map['create_time']=array('between',array($startTime,$today-1));
        $users=M('ActionLog')->where($map)->field('user_id')->select();
        $users=array_column($users,'user_id');
        $users=array_unique($users);
        $data['num']=count($users)*1;
        $data['type']='week';
        $data['date']=$startTime+7;//周统计date偏移7，实现date唯一
        return $data;
    }

    /**
     * 每月活跃度统计
     * @param $action
     * @param $today
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _monthActiveCount($action,$today)
    {
        $startTime=strtotime(time_format($today,'Y-m-d 00:00').' - 1 month');
        $map['action_id']=$action;
        $map['create_time']=array('between',array($startTime,$today-1));
        $users=M('ActionLog')->where($map)->field('user_id')->select();
        $users=array_column($users,'user_id');
        $users=array_unique($users);
        $data['num']=count($users)*1;
        $data['type']='month';
        $data['date']=$startTime+30;//月统计date偏移30，实现date唯一
        return $data;
    }

    /**
     * 每日执行消费用户统计
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function consumptionCount()
    {
        $time=time_format(time(),'Y-m-d 00:00');
        $startTime=strtotime($time.' - 1 day');
        $endTime=strtotime($time)-1;
        $consumption=$this->consumptionModel->where(array('date'=>$startTime))->find();
        if($consumption){
            return true;//已经统计过昨日消费量
        }
        $data=$this->_doConsumptionCount($startTime,$endTime);
        $this->consumptionModel->add($data);
        return true;
    }

    /**
     * 统计消费数据
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _doConsumptionCount($startTime,$endTime)
    {
        $data['date']=$startTime;
        $pingxxOrderModel=M('PingxxCharge');//ping++模块消费用户统计
        $rechargeOrderModel=M('RechargeRecordAlipay');//Recharge模块消费用户统计
        $orderLinkModel=M('order_link');//订单关联记录表

        $map_pingxx['time_paid']=array('between',array($startTime,$endTime));
        $map_pingxx['paid']=1;
        $pingxxList=$pingxxOrderModel->where($map_pingxx)->select();

        $map_recharge['notify_time']=array('between',array($startTime,$endTime));
        $map_recharge['trade_status']=array('in',array('TRADE_SUCCESS','TRADE_FINISHED'));
        $rechargeList=$rechargeOrderModel->where($map_recharge)->select();

        $data['total_fee']=0;
        $order_ids=array();
        foreach($pingxxList as $val){
            $order_ids[]=$val['order_no'];
            $data['total_fee']+=$val['amount'];
        }

        foreach($rechargeList as $val){
            $order_ids[]=$val['out_trade_no'];
            $data['total_fee']+=$val['total_fee'];
        }

        $data['user_num']=0;
        if(count($order_ids)){
            $uids=$orderLinkModel->where(array('order_id'=>array('in',$order_ids)))->field('uid')->select();
            $uids=array_unique(array_column($uids,'uid'));
            $data['user_num']=count($uids);
        }
        $data['total_fee']="".sprintf("%.2f",$data['total_fee']);
        return $data;
    }


    /*-----------------------统计数据展示start------------------------**/
    /**
     * 获取分页流失率统计
     * @param $map
     * @param int $page
     * @param int $r
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getLostListPage($map,$page=1,$r=20)
    {
        $totalCount=$this->lostModel->where($map)->count();
        $list=$this->lostModel->where($map)->page($page,$r)->order('id desc')->select();
        return array($list,$totalCount);
    }

    /**
     * 留存率数据查询
     * @param $strTime 开始日期（时间戳）
     * @param $endTime 结束日期（时间戳）
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getRemainList($strTime,$endTime)
    {
        $map['date']=array('between',array($strTime,$endTime));
        $list=$this->remainModel->where($map)->select();
        $list=$this->_initRemainList($list);
        return $list;
    }

    /**
     * 格式化留存率数据
     * @param $list
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initRemainList($list)
    {
        $date=date('Y-m-d 00:00',time());
        $special=array(
            strtotime($date.' - 2 day')=>1,
            strtotime($date.' - 3 day')=>2,
            strtotime($date.' - 4 day')=>3,
            strtotime($date.' - 5 day')=>4,
            strtotime($date.' - 6 day')=>5,
            strtotime($date.' - 7 day')=>6,
            strtotime($date.' - 8 day')=>7,
            strtotime($date.' - 9 day')=>8
        );
        $max=0;
        foreach($list as &$val){
            $total=0;
            if($val['date']>strtotime($date.' - 2 day')){
                continue;
            }else if($val['date']<strtotime($date.' - 9 day')){
                $val['day']=array($val['day1_num'],$val['day2_num'],$val['day3_num'],$val['day4_num'],$val['day5_num'],$val['day6_num'],$val['day7_num'],$val['day8_num']);
            }else{
                $num=$special[$val['date']];
                for($i=1;$i<=$num;$i++){
                    $val['day'][]=$val['day'.$i.'_num'];
                }
            }

            $val['date_str']=time_format($val['date'],'y-m-d');
            foreach($val['day'] as &$day){
                if($day!=0){
                    $day=array('num'=>$day,'value'=>$day/$val['reg_num']);
                    $total+=$day['value']+0.0499;
                }else{
                    $day=array('num'=>0,'value'=>0);
                    $total+=0.0499;
                }
            }
            if($total>$max){
                $max=$total;
            }
            unset($day);
        }
        unset($val);

        $minWidth=sprintf("%.2f",substr(sprintf("%.3f", 0.05/$max*100), 0, -2));
        foreach($list as &$val){
            foreach($val['day'] as &$day){
                if($day['num']==0){
                    $day['value']='0%';
                    $day['width']=$minWidth.'%';
                }else{
                    $width=($day['value']/$max)*100+$minWidth;
                    $width=sprintf("%.2f",substr(sprintf("%.3f", $width), 0, -2)).'%';
                    $day['value']=round($day['value']*100,2).'%';
                    $day['width']=$width;
                }
            }
            unset($day);
        }
        unset($val);
        if(count($list)>15){
            $list=list_sort_by($list,'date','desc');
        }else{
            $list=list_sort_by($list,'date','asc');
        }
        return $list;
    }

    /**
     * 充值用户数据统计
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getConsumptionList($startTime,$endTime)
    {
        $map['date']=array('between',array($startTime,$endTime));
        $list=$this->consumptionModel->where($map)->order('date asc')->select();
        if(strtotime(date('Y-m-d'))<=$endTime){//今日实时统计
            $startTodayTime=$endTime;
            $endTodayTime=time();
            $list['today']=$this->_doConsumptionCount($startTodayTime,$endTodayTime);
        }
        $list=$this->_initConsumptionList($list,$startTime,$endTime);
        return $list;
    }

    /**
     * 格式化充值用户数据
     * @param $list
     * @param $startTime
     * @param $endTime
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initConsumptionList($list,$startTime,$endTime)
    {
        $lostDay=array();

        $hasDate=array_column($list,'date');
        $date=$startTime;
        do{
            if(!in_array($date,$hasDate)){
                $lostDay[]=array('date'=>$date,'user_num'=>0,'total_fee'=>'0.00');
            }
            $date=strtotime(time_format($date,'Y-m-d').' + 1 day');
        }while($date<=$endTime);

        if(count($lostDay)&&count($list)){
            $list=array_merge($lostDay,$list);
        }else if(count($lostDay)){
            $list=$lostDay;
        }
        $list=list_sort_by($list,'date');

        foreach($list as $val){
            $labels[]=time_format($val['date'],'Y-m-d');
            $user_num[]=$val['user_num'];
            $total_fee[]=$val['total_fee'];
        }
        unset($val);
        $resultList=array(
            'labels'=>$labels,
            'datas'=>array(
                'user_num'=>$user_num,
                'total_fee'=>$total_fee
            )
        );
        return $resultList;
    }

    /**
     * 获取活跃度列表
     * @param $startTime 开始时间
     * @param $endTime 结束时间
     * @param string $type
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getActiveList($startTime,$endTime,$type='day')
    {
        switch($type){
            case 'week':
                $startTime=strtotime(date('Y-m-d',$startTime).' - '.date('w',$startTime).' day');
                break;
            case 'month':
                $startTime=strtotime(date('Y-m-01',$startTime));
                break;
            default:;
        }
        $map['type']=$type;
        $map['date']=array('between',$startTime.','.$endTime);
        $list=$this->activeModel->where($map)->select();
        $list=$this->_initActiveList($list,$startTime,$endTime,$type);
        return $list;
    }

    /**
     * 格式化活跃度数据
     * @param $list
     * @param $startTime
     * @param $endTime
     * @param $type 类型
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initActiveList($list,$startTime,$endTime,$type)
    {
        switch($type){
            case 'day':
                $away=0;
                $range=' + 1 day';
                $format='Y-m-d';
                if(strtotime(date('Y-m-d'))<=$endTime){//今日实时统计
                    $next=strtotime(time_format(time(),'Y-m-d').$range);
                }
                break;
            case 'week':
                $away=7;//周统计date偏移7，实现date唯一
                $range=' + 7 day';
                $format='W(Y-m-d)';
                if(strtotime(date('Y-m-d').' - '.date('w').' day')<=$endTime){//本周实时统计
                    $next=strtotime(date('Y-m-d').' - '.date('w').' day + 7 day');
                }
                break;
            case 'month':
                $away=30;//月统计date偏移30，实现date唯一
                $range=' + 1 month';
                $format='Y-m';
                if(strtotime(date('Y-m-01'))<=$endTime){//本月实时统计
                    $next=strtotime(time_format(time(),'Y-m-01').$range);
                }
                break;
            default:;
        }
        if($next){
            $activeAction = C('COUNT_ACTIVE_ACTION',null,3);
            $function='_'.$type.'ActiveCount';
            $list['now']=$this->$function($activeAction,$next);
        }
        $lost=array();

        $hasDate=array_column($list,'date');
        $date=$startTime+$away;
        do{
            if(!in_array($date,$hasDate)){
                $lost[]=array('type'=>$type,'date'=>$date,'num'=>0,'total'=>'0');
            }
            $date=strtotime(time_format($date,'Y-m-d').$range)+$away;
        }while($date<=$endTime);
        if(count($lost)&&count($list)){
            $list=array_merge($lost,$list);
        }else if(count($lost)){
            $list=$lost;
        }
        $list=list_sort_by($list,'date');

        foreach($list as $val){
            $labels[]=date($format,$val['date']);
            $num[]=$val['num'];
        }
        unset($val);
        $resultList=array(
            'labels'=>$labels,
            'datas'=>array(
                'num'=>$num
            )
        );
        return $resultList;
    }
} 