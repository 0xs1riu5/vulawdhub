<?php

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

/**
 * Class ActionLimitController  后台行为限制控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class ActionLimitController extends AdminController
{


    public function limitList()
    {
        $action_name = I('get.action','','op_t') ;
        !empty($action_name) && $map['action_list'] = array(array('like', '%[' . $action_name . ']%'),'','or');
        //读取规则列表
        $map['status'] = array('EGT', 0);
        $model = M('action_limit');
        $List = $model->where($map)->order('id asc')->select();
        $timeUnit = $this->getTimeUnit();
        foreach($List as &$val){
            $val['time'] =$val['time_number']. $timeUnit[$val['time_unit']];
            $val['action_list'] = get_action_name($val['action_list']);
            empty( $val['action_list']) &&  $val['action_list'] = L('_ALL_ACTS_');

            $val['punish'] = get_punish_name($val['punish']);


        }
        unset($val);
        //显示页面
        $builder = new AdminListBuilder();
        $builder->title(L('_ACTION_LIST_'))
            ->buttonNew(U('editLimit'))
            ->setStatusUrl(U('setLimitStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()
            ->keyTitle()
            ->keyText('name', L('_NAME_'))
            ->keyText('frequency', L('_FREQUENCY_'))
            ->keyText('time', L('_TIME_UNIT_'))
            ->keyText('punish', L('_PUNISHMENT_'))
            ->keyBool('if_message', L('_SEND_REMINDER_'))
            ->keyText('message_content', L('_MESSAGE_PROMPT_CONTENT_'))
            ->keyText('action_list', L('_ACT_'))
            ->keyStatus()
            ->keyDoActionEdit('editLimit?id=###')
            ->data($List)
            ->display();
    }

    public function editLimit()
    {
        $aId = I('id', 0, 'intval');
        $model = D('ActionLimit');
        if (IS_POST) {

            $data['title'] = I('post.title', '', 'op_t');
            $data['name'] = I('post.name', '', 'op_t');
            $data['frequency'] = I('post.frequency', 1, 'intval');
            $data['time_number'] = I('post.time_number', 1, 'intval');
            $data['time_unit'] = I('post.time_unit', '', 'op_t');
            $data['punish'] = I('post.punish', '', 'op_t');
            $data['if_message'] = I('post.if_message', '', 'op_t');
            $data['message_content'] = I('post.message_content', '', 'op_t');
            $data['action_list'] = I('post.action_list', '', 'op_t');
            $data['status'] = I('post.status', 1, 'intval');
            $data['module'] = I('post.module', '', 'op_t');

            $data['punish'] = implode(',', $data['punish']);

            foreach($data['action_list'] as &$v){
                $v = '['.$v.']';
            }
            unset($v);
            $data['action_list'] = implode(',', $data['action_list']);
            if ($aId != 0) {
                $data['id'] = $aId;
                $res = $model->editActionLimit($data);
            } else {
                $res = $model->addActionLimit($data);
            }
            if($res){
                $this->success(($aId == 0 ? L('_ADD_') : L('_EDIT_')) . L('_SUCCESS_'), $aId == 0 ? U('', array('id' => $res)) : '');
            }else{
                $this->error($aId == 0 ? L('_THE_OPERATION_FAILED_') : L('_THE_OPERATION_FAILED_VICE_'));
            }
        } else {
            $builder = new AdminConfigBuilder();

            $modules = D('Module')->getAll();
            $module['all'] = L('_TOTAL_STATION_');
            foreach($modules as $k=>$v){
                $module[$v['name']] = $v['alias'];
            }

            if ($aId != 0) {
                $limit = $model->getActionLimit(array('id' => $aId));
                $limit['punish'] = explode(',', $limit['punish']);
                $limit['action_list'] = str_replace('[','',$limit['action_list']);
                $limit['action_list'] = str_replace(']','',$limit['action_list']);
                $limit['action_list'] = explode(',', $limit['action_list']);

            } else {
                $limit = array('status' => 1,'time_number'=>1);
            }
            $opt_punish = $this->getPunish();
            $opt = D('Action')->getActionOpt();
            $builder->title(($aId == 0 ? L('_NEW_') : L('_EDIT_')) . L('_ACT_RESTRICTION_'))->keyId()
                ->keyTitle()
                ->keyText('name', L('_NAME_'))
                ->keySelect('module', L('_MODULE_'),'',$module)
                ->keyText('frequency', L('_FREQUENCY_'))
                // ->keySelect('time_unit', L('_TIME_UNIT_'), '', $this->getTimeUnit())
                ->keyMultiInput('time_number|time_unit',L('_TIME_UNIT_'),L('_TIME_UNIT_'),array(array('type'=>'text','style'=>'width:295px;margin-right:5px'),array('type'=>'select','opt'=>$this->getTimeUnit(),'style'=>'width:100px')))

                ->keyChosen('punish', L('_PUNISHMENT_'), L('_MULTI_SELECT_'), $opt_punish)
                ->keyBool('if_message', L('_SEND_REMINDER_'))
                ->keyTextArea('message_content', L('_MESSAGE_PROMPT_CONTENT_'))
                ->keyChosen('action_list', L('_ACT_'), L('_MULTI_SELECT_DEFAULT_'), $opt)
                ->keyStatus()
                ->data($limit)
                ->buttonSubmit(U('editLimit'))->buttonBack()->display();
        }
    }


    public function setLimitStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('action_limit', $ids, $status);
    }

    private function getTimeUnit()
    {
        return get_time_unit();
    }


    private function getPunish()
    {
        $obj = new \ActionLimit();
        return $obj->punish;

    }


}
