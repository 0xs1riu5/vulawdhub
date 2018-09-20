<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-14
 * Time: AM10:59
 */

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminSortBuilder;

class SEOController extends AdminController
{
    public function index($page = 1, $r = 20)
    {
        //读取规则列表
        $aApp=I('get.app','','text');
        $map = array('status' => array('EGT', 0));
        if($aApp!=''){
            $map['app']=$aApp;
        }
        $model = M('SeoRule');
        $ruleList = $model->where($map)->page($page, $r)->order('sort asc')->select();
        $totalCount = $model->where($map)->count();

        $module = D('Common/Module')->getAll();
        $app = array();
        foreach ($module as $m) {
            if ($m['is_setup'])
                $app[] =array('id'=>$m['name'],'value'=>$m['alias']) ;
        }

        //显示页面
        $builder = new AdminListBuilder();
        $builder->setSelectPostUrl(U('index'));
        $builder->title(L('_SEO_RULE_CONFIGURATION_'))
            ->setStatusUrl(U('setRuleStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->buttonNew(U('editRule'))->buttonSort(U('sortRule'))
            ->keyId()->keyTitle()->keyText('app', L('_MODULE_PLAIN_'))->keyText('controller', L('_CONTROLLER_'))->keyText('action', L('_METHOD_'))
            ->keyText('seo_title', L('_SEO_TITLE_'))->keyText('seo_keywords', L('_SEO_KEYWORD_'))->keyText('seo_description', L('_SEO_DESCRIPTION_'))
            ->select(L('_MODULE_BELONGED_').L('_COLON_'), 'app', 'select', '', '', '', array_merge(array(array('id' => '', 'value' => L('_ALL_'))), $app))
            ->keyStatus()->keyDoActionEdit('editRule?id=###')
            ->data($ruleList)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function ruleTrash($page = 1, $r = 20)
    {
        //读取规则列表
        $map = array('status' => -1);
        $model = M('SeoRule');
        $ruleList = $model->where($map)->page($page, $r)->order('sort asc')->select();
        $totalCount = $model->where($map)->count();


        //显示页面
        $builder = new AdminListBuilder();
        $builder->title(L('_SEO_RULE_RECYCLING_STATION_'))
            ->setStatusUrl(U('setRuleStatus'))->setDeleteTrueUrl(U('doClear'))->buttonRestore()->buttonDeleteTrue()
            ->keyId()->keyTitle()->keyText('app', L('_MODULE_PLAIN_'))->keyText('controller', L('_CONTROLLER_'))->keyText('action', L('_METHOD_'))
            ->keyText('seo_title', L('_SEO_TITLE_'))->keyText('seo_keywords', L('_SEO_KEYWORD_'))->keyText('seo_description', L('_SEO_DESCRIPTION_'))
            ->data($ruleList)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setRuleStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('SeoRule', $ids, $status);
    }

    public function doClear($ids)
    {
        $builder = new AdminListBuilder();
        $builder->doDeleteTrue('SeoRule', $ids);
    }

    public function sortRule()
    {
        //读取规则列表
        $list = M('SeoRule')->where(array('status' => array('EGT', 0)))->order('sort asc')->select();

        //显示页面
        $builder = new AdminSortBuilder();
        $builder->title(L('_SORT_SEO_RULE_'))
            ->data($list)
            ->buttonSubmit(U('doSortRule'))
            ->buttonBack()
            ->display();
    }

    public function doSortRule($ids)
    {
        $builder = new AdminSortBuilder();
        $builder->doSort('SeoRule', $ids);
    }

    public function editRule($id = null)
    {
        //判断是否为编辑模式
        $isEdit = $id ? true : false;

        //读取规则内容
        if ($isEdit) {
            $rule = M('SeoRule')->where(array('id' => $id))->find();
        } else {
            $rule = array('status' => 1);
        }

        //
        $rule['action2'] = $rule['action'];

        //显示页面
        $builder = new AdminConfigBuilder();


        $modules = D('Module')->getAll();


        $app = array('' => L('_MODULE_ALL_'));
        foreach ($modules as $m) {
            if ($m['is_setup']) {
                $app[$m['name']] = $m['alias'];
            }
        }

        $rule['summary']=nl2br($rule['summary']);
        $builder->title($isEdit ? L('_EDIT_RULES_') : L('_ADD_RULE_'))
            ->keyId()->keyText('title', L('_NAME_'), L('_RULE_NAME,_CONVENIENT_MEMORY_'))->keySelect('app', L('_MODULE_NAME_'), L('_NOT_FILLED_IN_ALL_MODULES_'), $app)->keyText('controller', L('_CONTROLLER_'), L('_DO_NOT_FILL_IN_ALL_CONTROLLERS_'))
            ->keyText('action2', L('_METHOD_'), L('_DO_NOT_FILL_OUT_ALL_THE_METHODS_'))->keyText('seo_title', L('_SEO_TITLE_'), L('_DO_NOT_FILL_IN_THE_USE_OF_THE_NEXT_RULE,_SUPPORT_VARIABLE_'))
            ->keyText('seo_keywords', L('_SEO_KEYWORD_'), L('_DO_NOT_FILL_IN_THE_USE_OF_THE_NEXT_RULE,_SUPPORT_VARIABLE_'))->keyTextArea('seo_description', L('_SEO_DESCRIPTION_'), L('_DO_NOT_FILL_IN_THE_USE_OF_THE_NEXT_RULE,_SUPPORT_VARIABLE_'))
            ->keyReadOnly('summary',L('_VARIABLE_DESCRIPTION_'),L('_VARIABLE_DESCRIPTION_VICE_'))
            ->keyStatus()
            ->data($rule)
            ->buttonSubmit(U('doEditRule'))->buttonBack()
            ->display();
    }

    public function doEditRule($id = null, $title, $app, $controller, $action2, $seo_title, $seo_keywords, $seo_description, $status)
    {
        //判断是否为编辑模式
        $isEdit = $id ? true : false;


        //写入数据库
        $data = array('title' => $title, 'app' => $app, 'controller' => $controller, 'action' => $action2, 'seo_title' => $seo_title, 'seo_keywords' => $seo_keywords, 'seo_description' => $seo_description, 'status' => $status);
        $model = M('SeoRule');
        if ($isEdit) {
            $result = $model->where(array('id' => $id))->save($data);
        } else {
            $result = $model->add($data);
        }

        clean_all_cache();
        //如果失败的话，显示失败消息
        if (!$result) {
            $this->error($isEdit ? L('_EDIT_FAILED_') : L('_CREATE_FAILURE_'));
        }

        //显示成功信息，并返回规则列表
        $this->success($isEdit ? L('_EDIT_SUCCESS_') : L('_CREATE_SUCCESS_'), U('index'));
    }
}