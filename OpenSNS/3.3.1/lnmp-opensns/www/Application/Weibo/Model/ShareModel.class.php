<?php
namespace Weibo\Model;

use Think\Model;

class ShareModel extends Model
{
    public function getInfo($param)
    {
        $info = array();
        if(!empty($param['app']) && !empty($param['model']) && !empty($param['method'])){
            $info = D($param['app'].'/'.$param['model'])->$param['method']($param['id']);
        }

        return $info;
    }

}