<?php
namespace Ucenter\Model;

use Think\Model;

/**
 * Class ScoreModel   用户积分模型
 * @package Ucenter\Model
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class ScoreModel extends Model
{

    private $typeModel = null;

    protected function _initialize()
    {
        parent::_initialize();
        $this->typeModel = M('ucenter_score_type');
    }

    /**
     * getTypeList  获取类型列表
     * @param string $map
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getTypeList($map = '')
    {
        $list = $this->typeModel->where($map)->order('id asc')->select();

        return $list;
    }

    public function getTypeListByIndex($map = ''){
        $list = $this->typeModel->where($map)->order('id asc')->select();
        foreach($list as $v)
        {
            $array[$v['id']]=$v;
        }
        return $array;
    }
    /**
     * getType  获取单个类型
     * @param string $map
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getType($map = '')
    {
        $type = $this->typeModel->where($map)->find();
        return $type;
    }

    /**
     * addType 增加积分类型
     * @param $data
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function addType($data)
    {
        $db_prefix = C('DB_PREFIX');
        $res = $this->typeModel->add($data);
        if($data['type_key']==4){
            $query = "ALTER TABLE  `{$db_prefix}member` ADD  `score" . $res . "` DECIMAL(10,".$data['type_value'].") COMMENT  '" . $data['title'] . "'";
            D()->execute($query);
        }else{
            $query = "ALTER TABLE  `{$db_prefix}member` ADD  `score" . $res . "` DOUBLE NOT NULL COMMENT  '" . $data['title'] . "'";
            D()->execute($query);
        }
        return $res;
    }

    /**
     * delType  删除分类
     * @param $ids
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function delType($ids)
    {
        $db_prefix = C('DB_PREFIX');
        $res = $this->typeModel->where(array('id' => array(array('in', $ids), array('gt', 4), 'and')))->delete();
        foreach ($ids as $v) {
            if ($v > 4) {
                $query = "alter table `{$db_prefix}member` drop column score" . $v;
                D()->execute($query);
            }
        }
        return $res;
    }

    /**
     * editType  修改积分类型
     * @param $data
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function editType($data)
    {
        $db_prefix = C('DB_PREFIX');
        $res = $this->typeModel->save($data);
        if($data['type_key']==4){
            $query = "alter table `{$db_prefix}member` modify column `score" . $data['id'] . "` DECIMAL(10,".$data['type_value'].") comment '" . $data['title'] . "';";
            D()->execute($query);
        }else{
            $query = "alter table `{$db_prefix}member` modify column `score" . $data['id'] . "` FLOAT comment '" . $data['title'] . "';";
            D()->execute($query);
        }
        return $res;
    }


    /**
     * getUserScore  获取用户的积分
     * @param int $uid
     * @param int $type
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getUserScore($uid, $type)
    {
        $model = D('Member');
        $score = $model->where(array('uid' => $uid))->getField('score' . $type);
        return $score;
    }

    /**
     * setUserScore  设置用户的积分
     * @param $uids
     * @param $score
     * @param $type
     * @param string $action
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setUserScore($uids, $score, $type, $action = 'inc',$action_model ='',$record_id=0,$remark='')
    {
        $uids = is_array($uids) ? $uids : explode(',',$uids);
        $model = D('Member');
        switch ($action) {
            case 'inc':
                $score = abs($score);
                $res = $model->where(array('uid' => array('in', $uids)))->setInc('score' . $type, $score);
                break;
            case 'dec':
                $score = abs($score);
                $res = $model->where(array('uid' => array('in', $uids)))->setDec('score' . $type, $score);
                break;
            case 'to':
                $res = $model->where(array('uid' => array('in', $uids)))->setField('score' . $type, $score);
                break;
            default:
                $res = false;
                break;
        }

        if(!($action != 'to' && $score == 0)){
            $this->addScoreLog($uids,$type,$action,$score,$action_model,$record_id,$remark);
        }

        foreach ($uids as $val) {
           $this->cleanUserCache($val,$type);
        }
        unset($val);
        return $res;
    }


    public function addScoreLog($uid, $type, $action='inc',$value=0, $model='',$record_id=0,$remark='')
    {
        $uid = is_array($uid) ? $uid : explode(',',$uid);
        foreach($uid as $v){
            $score =  D('Member')->where(array('uid'=>$v))->getField('score'.$type);
            $data['uid'] = $v;
            $data['ip'] = ip2long(get_client_ip());
            $data['type'] = $type;
            $data['action'] = $action;
            $data['value'] = $value;
            $data['model'] = $model;
            $data['record_id'] = $record_id;
            $data['finally_value'] = $score;
            $data['remark'] = $remark;
            $data['create_time'] = time();
            D('score_log')->add($data);
        }
        return true;
    }

    public function cleanUserCache($uid,$type){
        $uid = is_array($uid) ? $uid : explode(',',$uid);
        $type = is_array($type)?$type:explode(',',$type);
        foreach($uid as $val){
            foreach($type as $v){
                clean_query_user_cache($val, 'score' . $v);
            }
            clean_query_user_cache($val, 'title');
        }
    }

    public function getAllScore($uid)
    {
        $typeList = $this->getTypeList(array('status'=>1));
        $return = array();
        foreach($typeList as $key => &$v){
            $v['value'] = $this->getUserScore($uid,$v['id']);
            $return[$v['id']] = $v;

        }
        unset($v);
        return $return;
    }

}