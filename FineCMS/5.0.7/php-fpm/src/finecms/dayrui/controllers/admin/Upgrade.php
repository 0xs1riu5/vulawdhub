<?php

/**
 * FineCMS 公益软件
 *
 * @策划人 李睿
 * @开发组自愿者  邢鹏程 刘毅 陈锦辉 孙华军
 */

class Upgrade extends M_Controller {

    /**
     * 程序管理
     */
    public function index() {

        $this->template->assign(array(
            'menu' => $this->get_menu_v3(array(
                fc_lang('程序升级') => array('admin/upgrade/index', 'refresh'),
            )),
        ));
        $this->template->display('upgrande.html');
    }

    // 版本列表
    public function vlist() {

        $data = dr_catcher_data('http://v5.finecms.net/index.php?s=api&c=finecms&m=index&my='.DR_VERSION);

        if (!$data) {
            exit('<font color="red">无法获取到版本信息</font>');
        }

        exit($data);
    }


}