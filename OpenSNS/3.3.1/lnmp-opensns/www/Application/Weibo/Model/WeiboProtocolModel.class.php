<?php
/**
 *
 */

namespace Weibo\Model;

use Think\Model;
use Weibo\Api\WeiboApi;

/**
 * Class WeiboProtocolModel
 * @package Weibo\Model
 * @郑钟良
 */
class WeiboProtocolModel extends Model
{
    private $weiboApi;

    public function _initialize()
    {
        $this->weiboApi =D('Weibo');
    }
    // 在个人空间里查看该应用的内容列表
    public function profileContent($uid=null,$page=1,$count=10) {
        if ($uid != 0) {
            $result = $this->weiboApi->listAllWeibo($page, $count, array('uid' => $uid));
        } else {
            $result = $this->weiboApi->listAllWeibo($page, $count, array('uid' => is_login()));
        }
        $view=new \Think\View();
        $view->assign($result);
        $content='';
        $content=$view->fetch(T('Application://Weibo@Index/profile_content'),$content);
        return $content;
    }
    //返回列表项总数，分页用
    public function getTotalCount($uid=null){
        if ($uid != 0) {
            $totalCount=$this->weiboApi->listAllWeiboCount( array('uid' => $uid));
        } else {
            $totalCount=$this->weiboApi->listAllWeiboCount( array('uid' =>is_login()));
        }
        return $totalCount['total_count'];
    }
    //返回中文名称
    public function getModelInfo(){
        return array('title' => L('_MICRO_BLOG_'), 'sort' => 100);
    }
}