<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/9/30
 * Time: 13:57
 */
namespace Core\Controller;

use Think\Controller;

/**
 * 自动导出积分日志和行为日志
 * Class AutoExportController
 * @package Core\Controller
 * @author 王杰 wj@ourstu.com
 */
class AutoExportController extends Controller
{
    public function autoExportLog()
    {
        $actionCount = M('ActionLog')->count('id');
        $scoreCount = M('ScoreLog')->count('id');
        //todo 写成配置项
        if ($actionCount > 1000) {
            $this->_csv('ActionLog');
        }
        if ($scoreCount > 1000) {
            $this->_csv('ScoreLog');
        }
    }
    /**
     * 导出csv
     * @author 路飞<lf@ourstu.com>
     */
    private function _csv($type)
    {
        load('Admin/function');
        $model = M($type);
        $path = realpath("./Data/Log") . DIRECTORY_SEPARATOR;
        is_writeable($path) || $this->error('备份目录不存在或不可写，请检查后重试！');

        $aIds = I('ids', array());

        if(count($aIds)) {
            $map['id'] = array('in', $aIds);
        } else {
            $map['create_time'] = array('gt',0);
        }
        $list = $model->where($map)->order('create_time asc')->select();
        if ($type == 'ActionLog') {
            $data = L('_DATA_MORE_')."\n" ;
            foreach ($list as $val) {
                $val['create_time'] = time_format($val['create_time']);
                $data.=$val['id'].",".get_action($val['action_id'], 'title').",".get_nickname($val['user_id']).",".long2ip($val['action_ip']).",".$val['remark'].",".$val['create_time']."\n";
            }
        } else {
            $data = L('_EXPORT_SCORE_LOG_')."\n";
            $scoreTypes=D('Ucenter/Score')->getTypeListByIndex();
            foreach ($list as $val) {
                $val['create_time'] = time_format($val['create_time']);
                $data.=$val['id'].",".get_nickname($val['uid']).",".$scoreTypes[$val['type']]['title'].",".($val['action'] == 'inc'? '增加': '减少').",".$val['value'].",".$val['finally_value'].",".$val['remark'].",".$val['create_time']."\n";
            }
        }
        $data = iconv('utf-8', 'gb2312', $data);
        $filename = $type.date('YmdHis').'.csv'; //设置文件名

        $myfile = fopen($path . $filename, "w") or die("Unable to open file!");
        if(fwrite($myfile, $data)){
            $model->where($map)->delete();
        }
        fclose($myfile);
    }
}