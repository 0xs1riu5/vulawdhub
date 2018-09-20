<?php

namespace Addons\Support;

use Common\Controller\Addon;

/**
 * 签到插件
 * @author 嘉兴想天信息科技有限公司
 */
class SupportAddon extends Addon
{

    public $info = array(
        'name' => 'Support',
        'title' => '赞',
        'description' => '赞的功能',
        'status' => 1,
        'author' => '嘉兴想天信息科技有限公司',
        'version' => '0.1'
    );


    public function install()
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "
CREATE TABLE IF NOT EXISTS `{$db_prefix}support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appname` varchar(20) NOT NULL COMMENT '应用名',
  `row` int(11) NOT NULL COMMENT '应用标识',
  `uid` int(11) NOT NULL COMMENT '用户',
  `create_time` int(11) NOT NULL COMMENT '发布时间',
  `table` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支持的表'  ;
        ";
        $rs = D('')->execute($sql);
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的checkin钩子方法
    public function support($param)
    {

       $param['jump']=isset($param['jump'])?$param['jump']:'';
        $this->assign($param);

        $map_support['appname'] = $param['app'];
        $map_support['table'] = $param['table'];
        $map_support['row'] = $param['row'];

        $count = $this->getSupportCountCache($map_support);

        $map_supported = array_merge($map_support, array('uid' => is_login()));
        $supported = D('Support')->where($map_supported)->count();


        $this->assign('count', $count);
        $this->assign('supported', $supported);
        $this->display('support');

    }

    /**
     * @param $map_support
     * @return mixed
     * @auth 陈一枭
     */
    private function getSupportCountCache($map_support)
    {
        $cache_key = "support_count_" . implode('_', $map_support);
        $count = S($cache_key);
        if (empty($count)) {
            $count = D('Support')->where($map_support)->count();
            S($cache_key, $count);
            return $count;
        }
        return $count;
    }


}








