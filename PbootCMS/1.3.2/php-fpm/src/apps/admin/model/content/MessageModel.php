<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月29日
 *  留言模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class MessageModel extends Model
{

    // 获取列表
    public function getList()
    {
        return parent::table('ay_message')->where("acode='" . session('acode') . "'")
            ->order('id DESC')
            ->decode(false)
            ->page()
            ->select();
    }

    // 获取详情
    public function getMessage($id)
    {
        return parent::table('ay_message')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->find();
    }

    // 删除留言
    public function delMessage($id)
    {
        return parent::table('ay_message')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->delete();
    }

    // 修改留言
    public function modMessage($id, $data)
    {
        return parent::table('ay_message')->autoTime()
            ->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->update($data);
    }

    // 获取表单字段
    public function getFormFieldByCode($fcode)
    {
        return parent::table('ay_form_field')->where("fcode='$fcode'")
            ->order('sorting ASC,id ASC')
            ->select();
    }
}