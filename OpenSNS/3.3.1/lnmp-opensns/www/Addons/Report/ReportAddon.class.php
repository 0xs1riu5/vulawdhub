<?php

namespace Addons\Report;
use Admin\Controller\Addones;
use Common\Controller\Addon;

require_once(ONETHINK_ADDON_PATH . 'Report/Common/function.php');
class ReportAddon extends Addon
{
    public $info = array(
        'name' => 'Report',
        'title' => '举报后台',
        'description' => '可举报不法数据',
        'status' => 1,
        'author' => '想天科技xuminwei',
        'version' => '0.1',
        'has_adminlist'=>'1',
    );
    public $admin_list = array(
        '' => '',
    );

    public function install()
    {
        $prefix = C("DB_PREFIX");
        $model = D();
        $model->execute("DELETE FROM `{$prefix}hooks`  WHERE `name` =\"report\";");
        $model->execute("INSERT INTO `{$prefix}hooks` ( `name`, `description`, `type`, `update_time`, `addons`) VALUES
(\"report\", \"举报钩子\", 1, 1429511732, \"Report\");");


        $model->execute("DROP TABLE IF EXISTS `{$prefix}report`");
        $model->execute("
CREATE TABLE IF NOT EXISTS `{$prefix}report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL,
  `uid` int(11) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `data` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `updata_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `handle_status` tinyint(4) NOT NULL,
  `handle_result` text NOT NULL,
  `handle_uid` int(11) NOT NULL,
  `handle_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

        return true;
    }

    public function uninstall()
    {
        $prefix = C("DB_PREFIX");
        D()->execute("DROP TABLE IF EXISTS `{$prefix}report`");
        return true;
    }


//实现钩子
    public function report($param)
    {
        $this->assign('param', $param);
        $this->display(T('Addons://Report@Report/report'));     //  页面上的report
    }


}