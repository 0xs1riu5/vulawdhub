<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/9/13
 * Time: 16:00
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Admin\Model;


use Think\Model;

class SensitiveModel extends Model
{
    public function getListPage($page, $r)
    {
        $map['status'] = array('in', '0,1');
        $totalCount = $this->where($map)->count();
        if ($totalCount) {
            $list = $this->where($map)->order('create_time desc')->page($page, $r)->select();
        }
        return array($list, $totalCount);
    }

    public function editData()
    {
        $data = $this->create();
        if ($data) {
            if (isset($data['id'])) {
                $res = $this->save($data);
            } else {
                $res = $this->add($data);
            }
        }
        return $res;
    }
}