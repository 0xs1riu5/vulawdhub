<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/11/7
 * Time: 10:00
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Ucenter\Model;


use Think\Model;

class AttestTypeModel extends Model
{
    public function getTypeList()
    {
        $map['status']=1;
        $list=$this->where($map)->select();
        return $list;
    }

    public function getData($id,$init=0)
    {
        $data=$this->find($id);
        if(!$data){
            return false;
        }
        if(!$init){
            return $data;
        }

        //初始化 start
        $data['privilege']=explode(',',$data['privilege']);//特权，1：专属认证图标；2：有限推荐；3：各类特权
        $data['conditions']=explode('|',$data['conditions']);//认证条件，avatar：1|phone:1|follow:30|fans:30|friends:2
        $conditions=array();
        foreach ($data['conditions'] as $val){
            $val=explode(':',$val);
            $conditions[$val[0]]=$val[1];
        }
        $data['conditions']=$conditions;
        unset($val);
        $data['fields']=explode('|',$data['fields']);//字段，type:1|company_name:0|name:1|id_num:1|phone:1|image_type:1|prove_image:0|image:1|other_image:1|info:2|other_image_tip:'请上传手持正面照'
        $fields=array();
        foreach ($data['fields'] as $val){
            $val=explode(':',$val);
            $fields[$val[0]]=$val[1];
        }
        $data['fields']=$fields;
        unset($val);
        //初始化 end

        return $data;
    }

    public function editData($data)
    {
        $res=$this->save($data);
        return $res;
    }
}