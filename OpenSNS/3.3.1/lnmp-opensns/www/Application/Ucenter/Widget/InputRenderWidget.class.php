<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-9
 * Time: 上午8:59
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Widget;

use Think\Action;

/**input类型输入渲染
 * Class InputWidget
 * @package Usercenter\Widget
 * @郑钟良
 */
class InputRenderWidget extends Action
{

    public function inputRender($data = array(), $type)
    {
        //dump($data);exit;
        $this->assign('type', $type);
        $this->assign('field_id', $data['id']);
        $this->assign('required', $data['required']);
        $this->assign('input_tips', $data['input_tips']);
        if (!isset($data['field_content']) && $data['required'] && $data['form_default_value'] == '') {
            $this->assign('canSubmit', 0);
        } elseif (isset($data['field_content']['field_data']) && $data['field_content']['field_data'] == '' && $data['required']) {
            $this->assign('canSubmit', 0);
        } else {
            $this->assign('canSubmit', 1);
        }
        switch ($data['form_type']) {
            case 'input':
                $this->assign('field_name', $data['field_name']);
                $this->assign('child_form_type', $data['child_form_type']);
                $validation = $this->_getValidation($data['validation']);
                $this->assign('validation', $validation);
                if (!$data['field_content']) {
                    $this->assign('field_data', $data['form_default_value']);
                } else {
                    $this->assign('field_data', $data['field_content']['field_data']);
                }
                break;
            case 'radio':
                $this->assign('field_name', $data['field_name']);
                $checked = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : L('_SELECT_NOT_');
                if ($type == "personal") {
                    if ($data['form_default_value'] != '' && $data['form_default_value'] != null) {
                        $canCheck = explode('|', $data['form_default_value']);
                        $items = array();
                        foreach ($canCheck as $key => $val) {
                            $items[$key]['value'] = $val;
                            $items[$key]['checked'] = ($checked == $val) ? 'checked' : '';
                        }
                        if (!isset($data['field_content']['field_data'])) {
                            $items[0]['checked'] = 'checked';
                        }
                    }
                }

                $this->assign('items', $items);
                $this->assign('checked', $checked);
                break;
            
            /*
             * 改造用户扩展资料下拉菜单、多选默认值提交数据为键值、原有字符串格式不受影响
             * 改造后当checkbox和select类型时，默认值可按照“1:男|2:女”这样的数组格式以及“字段名|表名”，
             * 用户提交资料只提交数组键值和数据表ID字段值
             * @MingYang <xint5288@126.com>
             */
            case 'checkbox':
                $this->assign('field_name', $data['field_name']);
                if ($type == "personal") {
                    if ($data['form_default_value'] != '' && $data['form_default_value'] != null) {
                        $canCheck = explode('|', $data['form_default_value']);
                        $items = null;
                        /*
                         * 添加用户扩展资料可添加关联字段功能（多选支持）
                         * 给原有多选扩展字段做一个join判断
                         * @MingYang <xint5288@126.com>
                         */
                        //join判断,对关联扩展资料信息处理
                        if($data['child_form_type'] == "join"){
                            $joindata = explode('|',$data['form_default_value']);
                            $checked = explode('|', $data['field_content']['field_data']);
                            foreach (get_userdata_join('',$joindata[0],$joindata[1]) as $key =>$val){
                                $items[$key]['value'] = $val[$joindata[1]];
                                $items[$key]['join'] = 1;
                                $items[$key]['id'] = $val['id'];
                                if (in_array($val['id'], $checked)) {
                                    $items[$key]['selected'] = 1;
                                } else {
                                    $items[$key]['selected'] = 0;
                                }
                            }
                            $this->assign('items', $items);
                        } else {
                                /*
                                 * 添加用户扩展资料可添加默认值添加默认值数组选项，用户直接提交键值而不是字符串、原有字符串提交功能不受影响（多选支持）
                                 * @MingYang <xint5288@126.com>
                                 */
                                $vlaa = explode('|', $data['form_default_value']);
                                $checked = explode('|', $data['field_content']['field_data']);
                                foreach ($vlaa as $kye=>$vlaas){
                                    $needle =':';
                                    $tmparray = explode($needle,$vlaas);
                                    if(count($tmparray)>1){
                                        $vlab[] = explode(':', $vlaas); 
                                        foreach ($vlab as $key=>$vlass){
                                            $items[$key]['id'] = $vlass[0];
                                            $items[$key]['value'] = $vlass[1];
                                            $items[$key]['join'] = 1;
                                            if (in_array($vlass[0], $checked)) {
                                                $items[$key]['selected'] = 1;
                                            } else {
                                                $items[$key]['selected'] = 0;
                                            }
                                        }
                                    } else {
                                    //执行原扩展信息处理
                                    $checked = explode('|', $data['field_content']['field_data']);
                                        foreach ($canCheck as $key => $val) {
                                            if ($val != '') {
                                                $items[$key]['value'] = $val;
                                                if (in_array($val, $checked)) {
                                                    $items[$key]['selected'] = 1;
                                                } else {
                                                    $items[$key]['selected'] = 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            $this->assign('items', $items);
                        }
                    }

                } else {
                    $checked = explode('|', $data['field_content']['field_data']);
                    $this->assign('checked', $checked);
                }
                break;
            case 'select':
                $this->assign('field_name', $data['field_name']);
                $selected = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : L('_SELECT_NOT_');
                if ($type == "personal") {
                    /*
                     * 添加用户扩展资料可添加关联字段功能（多选支持）
                     * 给原有多选扩展字段做一个join判断
                     * @MingYang <xint5288@126.com>
                     */
                    //join判断,对关联扩展资料信息处理
                    if($data['child_form_type'] == "join"){
                        $joindata = explode('|',$data['form_default_value']);
                        foreach (get_userdata_join('',$joindata[0],$joindata[1]) as $key =>$val){
                            $items[$key]['value'] = $val[$joindata[1]];
                            $items[$key]['join'] = 1;
                            $items[$key]['id'] = $val['id'];
                            $items[$key]['selected'] = ($selected == $val['id']) ? 'selected' : '';
                        }
                    } else {  
                        if ($data['form_default_value'] != '' && $data['form_default_value'] != null) {
                            /*
                             * 添加用户扩展资料可添加默认值添加默认值数组选项，用户直接提交键值而不是字符串、原有字符串提交功能不受影响（下拉支持）
                             * @MingYang <xint5288@126.com>
                             */
                            $canSelected = explode('|', $data['form_default_value']);
                            $checked = explode('|', $data['field_content']['field_data']);
                            foreach ($canSelected as $kye=>$vlaas){
                                $needle =':';
                                $tmparray = explode($needle,$vlaas);
                                if(count($tmparray)>1){
                                    $vlab[] = explode(':', $vlaas);
                                    foreach ($vlab as $key=>$vlass){
                                        $items[$key]['id'] = $vlass[0];
                                        $items[$key]['value'] = $vlass[1];
                                        $items[$key]['join'] = 1;
                                        if (in_array($vlass[0], $checked)) {
                                            $items[$key]['selected'] = 'selected';
                                        } else {
                                            $items[$key]['selected'] = '';
                                        }
                                    }
                                } else {
                                    //执行原扩展信息处理
                                    $items = array();
                                    foreach ($canSelected as $key => $val) {
                                        $items[$key]['value'] = $val;
                                        $items[$key]['selected'] = ($selected == $val) ? 'selected' : '';
                                    }
                                    if (!isset($data['field_content']['field_data'])) {
                                        $items[0]['selected'] = 'selected';
                                    }
                                }
                            
                            }
                        } else {
                            $canSelected[0]['value'] = L('_NONE_');
                            $canSelected[0]['selected'] = 'selected';
                        }
                      }
                }
                

                $this->assign('items', $items);
                $this->assign('selected', $selected);
            break;
            case 'time':
                $this->assign('field_name', $data['field_name']);

                if (!$data['field_content']) {
                    $this->assign('field_data', null);
                } else {
                    $data['field_content']['field_data'] = date("Y-m-d", $data['field_content']['field_data']);
                    $this->assign('field_data', $data['field_content']['field_data']);
                }
                break;
            case 'textarea':
                $this->assign('field_name', $data['field_name']);
                $validation = $this->_getValidation($data['validation']);
                $this->assign('validation', $validation);
                if (!$data['field_content']) {
                    $this->assign('field_data', $data['form_default_value']);
                } else {
                    $this->assign('field_data', $data['field_content']['field_data']);
                }
                break;
        }

        $this->display('Widget/InputRender/' . $data['form_type']);


    }

    function _getValidation($validation)
    {
        $data['min'] = $data['max'] = 0;
        if ($validation != '') {
            $items = explode('&', $validation);
            foreach ($items as $val) {
                $item = explode('=', $val);
                if ($item[0] == 'min' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['min'] = $item[1];
                }
                if ($item[0] == 'max' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['max'] = $item[1];
                }
            }
        }
        return $data;
    }

}