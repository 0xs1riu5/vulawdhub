<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年03月23日
 * 自定义标签模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class LabelModel extends Model
{

    // 获取自定义标签列表
    public function getList()
    {
        return parent::table('ay_label')->select();
    }

    // 检查自定义标签
    public function checkLabel($where)
    {
        return parent::table('ay_label')->field('id')
            ->where($where)
            ->find();
    }

    // 获取自定义标签详情
    public function getLabel($id)
    {
        return parent::table('ay_label')->where("id=$id")->find();
    }

    // 添加自定义标签
    public function addLabel(array $data)
    {
        return parent::table('ay_label')->autoTime()->insert($data);
    }

    // 删除自定义标签
    public function delLabel($id)
    {
        return parent::table('ay_label')->where("id='$id'")->delete();
    }

    // 修改自定义标签
    public function modLabel($id, $data)
    {
        return parent::table('ay_label')->where("id=$id")
            ->autoTime()
            ->update($data);
    }

    // 修改自定义标签值
    public function modValue($name, $value)
    {
        return parent::table('ay_label')->where("name='$name'")
            ->autoTime()
            ->update("value='$value'");
    }

    // 获取配置参数
    public function getValue()
    {
        return parent::table('ay_label')->column('value', 'name');
    }
}

