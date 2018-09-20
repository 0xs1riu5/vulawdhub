<?php
namespace Addons\Report\Model;

use Think\Model;

class ReportModel extends Model
{
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 0, self::MODEL_BOTH),
    );

    public function addData($data = array())
    {

        $data = $this->create($data);
        $abc= $this->add($data);
        return $abc;
    }


}