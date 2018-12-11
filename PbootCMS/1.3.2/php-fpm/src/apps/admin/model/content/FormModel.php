<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年5月28日
 *  自定义表单模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class FormModel extends Model
{

    // 获取自定义表单列表
    public function getList()
    {
        return parent::table('ay_form')->page()->select();
    }

    // 查找自定义表单
    public function findForm($field, $keyword)
    {
        return parent::table('ay_form')->like($field, $keyword)
            ->page()
            ->select();
    }

    // 获取最后一个code
    public function getLastCode()
    {
        return parent::table('ay_form')->order('id DESC')->value('fcode');
    }

    // 获取自定义表单详情
    public function getForm($id)
    {
        return parent::table('ay_form')->where("id=$id")->find();
    }

    // 获取自定义表单详情
    public function getFormByCode($fcode)
    {
        return parent::table('ay_form')->where("fcode='$fcode'")->find();
    }

    // 获取自定义表单表
    public function getFormTable($id)
    {
        return parent::table('ay_form')->where("id=$id")->value('table_name');
    }

    // 获取自定义表单表
    public function getFormCode($id)
    {
        return parent::table('ay_form')->where("id=$id")->value('fcode');
    }

    // 获取自定义表单表
    public function getFormTableByCode($fcode)
    {
        return parent::table('ay_form')->where("fcode='$fcode'")->value('table_name');
    }

    // 添加自定义表单
    public function addForm(array $data)
    {
        return parent::table('ay_form')->autoTime()->insert($data);
    }

    // 删除自定义表单
    public function delForm($id)
    {
        return parent::table('ay_form')->where("id=$id")->delete();
    }

    // 修改自定义表单
    public function modForm($id, $data)
    {
        return parent::table('ay_form')->where("id=$id")
            ->autoTime()
            ->update($data);
    }

    // 获取表单字段
    public function getFormFieldByCode($fcode)
    {
        return parent::table('ay_form_field')->where("fcode='$fcode'")
            ->order('sorting ASC,id ASC')
            ->select();
    }

    // 获取字段详情
    public function getFormField($id)
    {
        return parent::table('ay_form_field')->where("id=$id")->find();
    }

    // 检查表单字段
    public function checkFormField($fcode, $name)
    {
        return parent::table('ay_form_field')->where("fcode='$fcode' AND name='$name'")->find();
    }

    // 获取表单字段名称
    public function getFormFieldName($id)
    {
        return parent::table('ay_form_field')->where("id=$id")->value('name');
    }

    // 新增表单字段
    public function addFormField(array $data)
    {
        return parent::table('ay_form_field')->autoTime()->insert($data);
    }

    // 删除表单字段
    public function delFormField($id)
    {
        return parent::table('ay_form_field')->where("id=$id")->delete();
    }

    // 删除表单字段
    public function delFormFieldByCode($fcode)
    {
        return parent::table('ay_form_field')->where("fcode='$fcode'")->delete();
    }

    // 修改表单字段
    public function modFormField($id, $data)
    {
        return parent::table('ay_form_field')->where("id=$id")
            ->autoTime()
            ->update($data);
    }

    // 判断字段是否存在
    public function isExistField($table, $field)
    {
        $fields = parent::tableFields($table);
        if (in_array($field, $fields)) {
            return true;
        } else {
            return false;
        }
    }

    // 获取表单数据
    public function getFormData($table)
    {
        return parent::table($table)->page()
            ->order('id DESC')
            ->select();
    }

    // 删除自定义表单数据
    public function delFormData($table, $id)
    {
        return parent::table($table)->where("id=$id")->delete();
    }
}