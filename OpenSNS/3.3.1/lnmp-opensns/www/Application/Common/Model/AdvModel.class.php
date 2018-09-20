<?php

namespace Common\Model;

use Think\Model;

class AdvModel extends Model
{
    protected $tableName = 'adv';

    /*  展示数据  */
    public function getAdvList($name, $path)
    {

        $list = S('adv_list_' . $name . $path);
        if ($list === false) {
            $now_theme = modC('NOW_THEME', 'default', 'Theme');

            $advPos = D('Common/AdvPos')->getInfo($name, $path); //找到当前调用的广告位
            if ($advPos['theme'] != 'all' && !in_array($now_theme, explode(',', $advPos['theme']))) {
                return null;
            }

            $advMap['pos_id'] = $advPos['id'];
            $advMap['status'] = 1;
            $advMap['start_time'] = array('lt', time());
            $advMap['end_time'] = array('gt', time());
            $data = $this->where($advMap)->order('sort asc')->select();


            foreach ($data as &$v) {
                $d = json_decode($v['data'], true);
                if (!empty($d)) {
                    $v = array_merge($d, $v);

                }
            }
            unset($v);
            S('adv_list_' . $name . $path, $list);
        }

        return $data;
    }

    /*——————————————————分隔线————————————————*/


}