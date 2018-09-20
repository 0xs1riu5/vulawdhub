<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class IndexController extends AdminController
{

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index()
    {
        if (UID) {
            $this->meta_title = L('_INDEX_MANAGE_');

            $aRefresh = I('get.refresh', 0, 'intval');
            if($aRefresh == 1) {
                //重置member表的fans
                $memberModel = D('Member');
                $user_fans_list = D('Member')->field('uid')->select();
                foreach ($user_fans_list as $key => &$val) {
                    $map['follow_who'] = $val['uid'];
                    $val['fans'] = D('Follow')->where($map)->field('who_follow')->order('create_time desc')->count();
                    $memberModel->where(array('uid' => $val['uid']))->field('fans')->save($val);
                }
            }

            $tileModel = D('tile');
            $list = $tileModel->where(array('status' => 1))->order('sort asc')->select();
            foreach($list as &$key) {
                $key['url'] = U($key['url']);
                $key['url_vo'] = U($key['url_vo']);
            }
            $this->assign('list', $list);

            $this->display();
        } else {
            $this->redirect('Public/login');
        }
    }

    public function stats()
    {
        if (IS_POST) {
            switch (I('post.method','','text')) {
                case 'saveUserCount': {
                    $this->saveUserCount();
                    break;
                }
                case 'savePortletSort': {
                    $this->savePortletSort();
                    break;
                }
            }
        } else {
            $this->meta_title = '系统统计';
            $this->assign('count', $this->getUserCount());
            $this->assign('portlets',userC('PORTLET_SORT',''));

            $this->getOtherCount();
            $this->display();
        }
    }

    private function getOtherCount(){
        $countModel=D('Count');
        list($lostList,$totalCount)=$countModel->getLostListPage($map=1,1,5);
        foreach($lostList as &$val){
            $val['date']=time_format($val['date'],'Y-m-d');
            $val['rate']=($val['rate']*100)."%";
        }
        unset($val);
        $this->assign('lostList',$lostList);

        $today=date('Y-m-d 00:00',time());
        $startTime=strtotime($today." - 10 day");
        $endTime=strtotime($today);
        $consumptionList=$countModel->getConsumptionList($startTime,$endTime);
        $this->assign('consumptionList',json_encode($consumptionList));

        $startTime=strtotime(date('Y-m-d').' - 9 day');
        $activeList=$countModel->getActiveList($startTime,time(),'day');
        $this->assign('activeList',json_encode($activeList));

        $startTime=strtotime(date('Y-m-d').' - '.date('w').' day - 49 day');
        $weekActiveList=$countModel->getActiveList($startTime,time(),'week');
        $this->assign('weekActiveList',json_encode($weekActiveList));

        $startTime=strtotime(date('Y-m-01').' - 9 month');
        $monthActiveList=$countModel->getActiveList($startTime,time(),'month');
        $this->assign('monthActiveList',json_encode($monthActiveList));

        $startTime=strtotime($today." - 9 day");
        $endTime=strtotime($today." - 2 day");
        $remainList=$countModel->getRemainList($startTime,$endTime);
        $this->assign('remainList',$remainList);
        return true;
    }

    private function getUserCount(){
        $today = date('Y-m-d', time());
        $today = strtotime($today);
        $count_day = C('COUNT_DAY', null, 7);
        $count['count_day'] = $count_day;
        for ($i = $count_day; $i--; $i >= 0) {
            $day = $today - $i * 86400;
            $day_after = $today - ($i - 1) * 86400;
            $week_map = array('Mon' => L('_MON_'), 'Tue' => L('_TUES_'), 'Wed' => L('_WEDNES_'), 'Thu' => L('_THURS_'), 'Fri' => L('_FRI_'), 'Sat' => '<strong>' . L('_SATUR_') . '</strong>', 'Sun' => '<strong>' . L('_SUN_') . '</strong>');
            $week[] = date('m月d日 ', $day) . $week_map[date('D', $day)];
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
        $count['now_inline']=M('Session')->where(1)->count()*1;
        return $count;
    }

    /**
     * 保存用户统计设置
     */
    private function saveUserCount()
    {
        $count_day = I('post.count_day', C('COUNT_DAY'), 'intval', 7);
        if (M('Config')->where(array('name' => 'COUNT_DAY'))->setField('value', $count_day) === false) {
            $this->error(L('_ERROR_SETTING_') . L('_PERIOD_'));
        } else {
            S('DB_CONFIG_DATA', null);
            $this->success(L('_SUCCESS_SETTING_'), 'refresh');
        }
    }

    /**
     * 保存Portlet排序
     */
    private function savePortletSort()
    {
        set_user_config('PORTLET_SORT',I('post.data'));
    }

    /**
     * 添加常用操作
     * @author 路飞<lf@ourstu.com>
     */
    public function addTo()
    {
        $tileId = I('post.id', '', 'intval');

        $rs = M('tile')->where(array('aid' => $tileId, 'status' => 1))->find();
        if($rs) {
            $this->ajaxReturn(array('status' => 0, 'info' => '请勿重复添加！'));
        } else {
            $nav = D('Menu')->getPath($tileId);
            $max = D('Tile')->max('sort');

            $data['aid'] = $tileId;
            $data['icon'] = 'direction';
            $data['sort'] = $max+1;
            $data['status'] = 1;
            $data['title'] = $nav[1]['title'];
            $data['title_vo'] = $nav[0]['title'];
            $data['url'] = $nav[1]['url'];
            $data['url_vo'] = $nav[0]['url'];
            $data['tile_bg'] = '#1ba1e2';

            $res = M('tile')->add($data);
            if($res) {
                $this->ajaxReturn(array('status' => 1, 'info' => '添加成功'));
            } else {
                $this->ajaxReturn(array('status' => 0, 'info' => '添加失败'));
            }
        }
    }

    /**
     * 删除常用操作
     * @author 路飞<lf@ourstu.com>
     */
    public function delTile()
    {
        $tileId = I('post.id', '', 'intval');

        $res = M('tile')->where(array('id' => $tileId))->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'info' => '删除成功', 'tile_id' => $tileId));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '删除失败'));
        }
    }

    /**
     * 修改常用操作
     * @author 路飞<lf@ourstu.com>
     */
    public function setTile()
    {
        $tileId = I('post.id', '', 'intval');
        $tileIcon = I('post.icon', '', 'text');
        $tileBg = I('post.tile_bg', '', 'text');

        $data['icon'] = substr($tileIcon, 5);
        $data['tile_bg'] = $tileBg;
        $res = M('tile')->where(array('id' => $tileId))->save($data);

        if($res){
            $this->ajaxReturn(array('status' => 1, 'info' => '保存成功', 'tile_id' => $tileId, 'tile_icon' => $data['icon'], 'tile_bg' => $tileBg));
        }else{
            $this->ajaxReturn(array('status' => 0, 'info' => '保存失败'));
        }
    }

    /**
     * 常用操作排序
     * @author 路飞<lf@ourstu.com>
     */
    public function sortTile()
    {
        $ids = I('post.ids', '', 'text');

        $i = 1;
        foreach($ids as $val) {
            if($val) {
                $val = substr($val,5);
                D('Tile')->where(array('id' => $val))->setField(array('sort' => $i));
                $i++;
            }
        }
    }

}
