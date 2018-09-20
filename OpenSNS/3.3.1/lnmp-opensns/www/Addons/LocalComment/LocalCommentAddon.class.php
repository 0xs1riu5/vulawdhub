<?php

namespace Addons\LocalComment;

use Common\Controller\Addon;

/**
 * 本地评论插件
 * @author caipeichao
 */
class LocalCommentAddon extends Addon
{

    public $info = array(
        'name' => 'LocalComment',
        'title' => '本地评论',
        'description' => '本地评论插件，不依赖社会化评论平台',
        'status' => 1,
        'author' => 'caipeichao',
        'version' => '0.1'
    );

    public function install()
    {
        $prefix = C("DB_PREFIX");
        D()->execute("DROP TABLE IF EXISTS `{$prefix}local_comment`");
        D()->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `{$prefix}local_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `app` text NOT NULL,
  `mod` text NOT NULL,
  `row_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `area` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
SQL
        );
        return true;
    }

    public function uninstall()
    {
        $prefix = C("DB_PREFIX");
        D()->execute("DROP TABLE IF EXISTS `{$prefix}local_comment`");
        return true;
    }

    //实现的documentDetailAfter钩子方法
    /**
     * @param $path string 例如 Travel/detail/12
     * @param $uid int 评论给谁？
     * @author caipeichao
     */
    public function localComment($param)
    {
        $path = $param['path'];
        //获取参数
        $aPath = explode('/', $path);
        $app = $aPath[0];
        $mod = $aPath[1];
        $row_id = $aPath[2];
        $count =  modC($mod.'_LOCAL_COMMENT_COUNT',10,$app);;
        //调用接口获取评论列表
        $list = $this->getCommentList($app, $mod, $row_id, 1, $count);
        $total_count = $this->getCommentCount($app, $mod, $row_id);
        //增加用户信息
        foreach ($list as &$e) {
            $e['user'] = query_user(array('uid', 'avatar64', 'nickname', 'space_url'), $e['uid']);
        }
        unset($e);
        $pageCount = ceil($total_count / $count);
       $pageHtml = getPageHtml('local_comment_page',$pageCount,array('app'=>$app,'mod'=>$mod, 'row_id'=>$row_id),1);
        //显示页面

        $can_guest = modC($mod.'_LOCAL_COMMENT_CAN_GUEST',1,$app);
        $this->assign('can_guest', $can_guest);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('list', $list);
        $this->assign('total_count', $total_count);
        $this->assign('count', $count);
        $this->assign('app', $app);
        $this->assign('mod', $mod);
        $this->assign('row_id', $row_id);

        $param['extra'] = http_build_query($param['extra']);
        $this->assign($param);
        $this->assign('myInfo',query_user(array('avatar64','nickname','uid','space_url'),is_login()));
        $this->display('comment');
    }





    private function getCommentModel()
    {
        return D('Addons://LocalComment/LocalComment');
    }


    public function getCommentHtml($id){
        $model = $this->getCommentModel();
        $comment = $model->getComment($id);
        $this->assign('comment',$comment);
        $html = $this->fetch('_comment');
        return $html;
    }


    public function getCommentList($app, $mod, $row_id, $page, $count)
    {
        $model = $this->getCommentModel();
        $map = array('app' => $app, 'mod' => $mod, 'row_id' => $row_id, 'status' => 1);
        $param['where'] = $map;
        $param['page'] = $page;
        $param['count'] = $count;

        $sort = modC($mod.'_LOCAL_COMMENT_ORDER',0,$app) == 0 ? 'desc':'asc';

        $param['order'] = 'create_time '.$sort;

        $param['field'] = 'id';
        $list = $model->getList($param);
        foreach ($list as &$v) {
            $v = $model->getComment($v);
        }
        unset($v);
        return $list;
    }



    public function getCommentCount($app, $mod, $row_id)
    {
        $model = $this->getCommentModel();
        $map = array('app' => $app, 'mod' => $mod, 'row_id' => $row_id, 'status' => 1);
        $result = $model->where($map)->count();
        return $result;
    }





    //实现的AdminIndex钩子方法
    public function AdminIndex($param)
    {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display'])
            $this->display('widget');
    }
}