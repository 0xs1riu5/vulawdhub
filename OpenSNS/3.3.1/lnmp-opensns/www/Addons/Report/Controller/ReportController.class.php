<?php

namespace Addons\Report\Controller;

use Home\Controller\AddonsController;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminTreeListBuilder;

require_once(ONETHINK_ADDON_PATH . 'Report/Common/function.php');


class ReportController extends AddonsController
{
    /**
     * 获取管理员配置参数
     */
    public function eject()
    {
        parse_str(I('get.param'), $param);
        $param['data'] = json_encode($param['data']);
        $this->assign('param', $param);
        $config = $this->_getAddonsReportConfig();
        $this->assign("reason", $config);
        $this->display(T('Addons://Report@Report/eject'));

    }

    public function _getAddonsReportConfig()
    {
        $config = S('REPORT_ADDON_CONFIG');
        if (!$config) {
            $config = M('Addons')->where(array('name' => 'Report'))->find();
            $config = json_decode($config['config'], ture);
            $config = explode("\r\n", $config['meta']);
            S('REPORT_ADDON_CONFIG', $config, 600);
        }
        return $config;
    }

    /**
     * 对举报的信息进行数据库写入操作
     */
    public function addReport()
    {
        parse_str(I('get.param'), $param);      //含有url,type,data,
        $preason = I('post.reason', '', 'op_t');
        $pcontent = I('post.content', '', 'op_t');

        $data['uid'] = is_login();
        $data['url'] = $param['url']?$param['url']:'';
        $data['reason'] = $preason;        //  举报原因
        $data['content'] = $pcontent;      // 举报描述
        $data['type'] = $param['type']?$param['type']:'';
        $data['data'] = json_encode($param['data'])?json_encode($param['data']):'';



        $result = D('Addons://Report/Report')->addData($data);
        if ($result) {
            D('Message')->sendMessageWithoutCheckSelf('1', '有新的举报。', '有一封举报，请到后台查看。');
            $this->success('举报成功', 0);
        } else {
            $this->error('举报失败');
        }
    }


}