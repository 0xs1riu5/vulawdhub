<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-4
 * Time: 下午5:09
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Weibo\Widget;

use Think\Action;
use Weibo\Model\WeiboModel;

class UcenterBlockWidget extends Action
{
    public function render($uid=0,$page=1,$tab=null,$count=10)
    {
        !$uid&&$uid=is_login();

        $param = array();
        //查询条件
        $weiboModel =new WeiboModel();
        $param['field'] = 'id';
        $param['where']['status'] = 1;
        $param['where']['uid'] = $uid;
        $param['where']['is_top'] = 0;
        $param['page']=$page;
        $param['count']=$count;
        //查询
        $list = $weiboModel->getWeiboList($param);
        $this->assign('list', $list);

        // 获取置顶动态
        $top_list = $weiboModel->getWeiboList(array('where' => array('status' => 1, 'is_top' => 1,'uid'=>$uid)));
        $this->assign('top_list', $top_list);
        $this->assign('total_count', $weiboModel->getWeiboCount($param['where']));
        $this->assign('page', $page);
        //$this->assignSelf();
        $this->display(T('Weibo@Widget/ucenterblock'));
    }
}