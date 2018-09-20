<?php
namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminSortBuilder;

/**
 * Class ScheduleController  计划任务
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class ScheduleController extends AdminController
{
    /**
     * scheduleList  计划任务列表
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function scheduleList()
    {
        $model = D('Common/Schedule');
        $list = $model->getScheduleList();
        foreach ($list as &$v) {
            list($type, $value) = $this->getTypeAndValue($v['type'], $v['type_value']);
            $v['type_text'] = $type;
            $v['type_value_text'] = $value;
            $v['next_run'] = $model->calculateNextTime($v);
            $v['last_run'] = $model->getLastUpdate($v['id']);
        }
        unset($v);
        //显示页面
        $btn_attr = $model->checkIsRunning() ? array('style' => 'color:green;font-weight:700') : array('style' => 'color:red;font-weight:700');
        $btn_attr['class'] = 'ajax-post ';
        $btn_attr[' hide-data'] = 'true';
        $btn_attr['href'] = U('Schedule/run');
        $builder = new AdminListBuilder();
        $builder->title('计划任务')->tips('Tips：执行时间较长的计划任务会影响到其他计划任务时间的计算；')
            ->button($model->checkIsRunning() ? 'Running （点击停止）' : 'Stop（点击运行）', $btn_attr)
            ->setStatusUrl(U('setScheduleStatus'));


        $btn_attr['style'] = 'color:blue;font-weight:700';
        $btn_attr['href'] = U('Schedule/reRun');
        $btn_attr['class'] = 'ajax-post re_run';
        $btn_attr['onclick'] = 'javascript:$(this).text("重启中，请不要做其他操作...")';
        $builder->button('重启计划任务', $btn_attr);


        $builder->buttonNew(U('Schedule/editSchedule')) ->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()->keyText('method', '执行方法')
            ->keyText('args', '参数')
            ->keyText('type_text', '类型')
            ->keyText('type_value_text', '设定时间')
            ->keyTime('start_time', '开始时间')
            ->keyTime('end_time', '结束时间')
            ->keyText('intro', '介绍')
            ->keyTime('last_run', '上次执行时间')
            ->keyTime('next_run', '下次执行时间')
            ->keyCreateTime()->keyStatus()->keyDoActionEdit('editSchedule?id=###')
            ->keyDoActionModalPopup('showLog?id=###', '查看日志', '日志', array('data-title' => '日志'))
            ->data($list)
            ->display();
    }

    /**
     * setScheduleStatus  禁用/启用/删除计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setScheduleStatus(){
        $ids = I('ids');
        $status = I('get.status', 0, 'intval');

        S('schedule_list',null);

        $builder = new AdminListBuilder();
        $builder->doSetStatus('Schedule', $ids, $status);
    }

    /**
     * showLog  显示日志
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function showLog()
    {
        $aId = I('get.id', 0, 'intval');
        $model = D('Common/Schedule');
        $log = $model->getLog($aId);
        if ($log) {
            $log = explode("\n", $log);
        }
        $this->assign('log', $log);
        $this->assign('id', $aId);
        $this->display();
    }

    /**
     * clearLog  清空日志
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function clearLog()
    {
        $aId = I('post.id', 0, 'intval');
        $model = D('Common/Schedule');
        $rs = $model->clearLog($aId);
        $this->success('清空成功', 'refresh');
    }

    /**
     * editSchedule  新增/编辑计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function editSchedule()
    {
        $aId = I('id', 0, 'intval');
        if (IS_POST) {
            $data['id'] = $aId;
            $aMethod = $data['method'] = I('post.method', '', 'text');
            $aArgs = $data['args'] = I('post.args', '', 'text');
            $aType = $data['type'] = I('post.type_key', 0, 'intval');
            $aTypeValue = $data['type_value'] = I('post.type_value', '', 'text');
            $aStartTime = $data['start_time'] = I('post.start_time', 0, 'intval');
            $aEndTime = $data['end_time'] = I('post.end_time', 0, 'intval');
            $aIntro = $data['intro'] = I('post.intro', '', 'text');
            $aLevel = $data['level'] = I('post.level', '', 'text');

            if (empty($aMethod)) {
                $this->error('请填写执行方法');
            }
            if (empty($aType)) {
                $this->error('请选择类型');
            }
            if (empty($aTypeValue)) {
                $this->error('请填写设置值');
            }
            if ($aType != 1) {
                if (empty($aStartTime)) {
                    $this->error('请填写开始时间');
                }
                if (empty($aEndTime)) {
                    $this->error('请填写结束时间');
                }
            }

            if (empty($aIntro)) {
                $this->error('请填写介绍');
            }

            if ($aType == 1) {
                $data['type_value'] = strtotime($data['type_value']);
            }
            $res = D('Schedule')->editSchedule($data);

            if ($res) {
                $this->success(($aId == 0 ? '添加' : '编辑') . '成功', U('scheduleList'));
            } else {
                $this->error(($aId == 0 ? '添加' : '编辑') . '失败');
            }

        } else {
            $builder = new AdminConfigBuilder();

            if ($aId != 0) {
                $tip = '编辑';
                $schedule = D('Schedule')->find($aId);
                $schedule['type_key'] = $schedule['type']; //当name为type时select有点错误。不知道为什么，用其他变量替换  駿濤
            } else {
                $tip = '新增';
                $schedule = array();
            }
            $builder->title($tip . '计划任务')
                ->keyId()
                ->keyText('method', "执行方法", "只能执行Model中的方法，如 <span style='color: red'>Weibo/Weibo->test</span> 则表示执行 D('Weibo/Weibo')->test();")
                ->keyText('args', "执行参数", "url的写法，如 <span style='color: red'>a=1&b=2</span> ")

                ->keySelect('type_key', '类型', '计划任务的类型', array(1 => '执行一次', 2 => '每隔一段时间执行', 3 => '每个时间点执行'))
                ->keyUserDefined('type_value', '设定时间', '', T('Admin@Schedule/edit'), array('schedule' => $schedule))
                ->keyTime('start_time', '开始时间')
                ->keyTime('end_time', '结束时间')
                ->keyTextArea('intro', '介绍', '该介绍将会被写入日志')
                ->keyText('lever', '优先级')
                ->data($schedule)
                ->buttonSubmit(U('Schedule/editSchedule'))->buttonBack()->display();
        }
    }

    /**
     * getTypeAndValue   获取计划任务类型和值
     * @param $type
     * @param $value
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function getTypeAndValue($type, $value)
    {
        switch ($type) {
            case 1:
                $type = '执行一次';
                $value = date('Y-m-d h:i', $value);
                break;
            case 2:
                $type = '每隔一段时间执行';
                break;
            case 3:
                $type = '每个时间点执行';
                break;
        }

        return array($type, $value);
    }

    /**
     * run  运行/停止计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function run()
    {
        $model = D('Common/Schedule');
        if ($model->checkIsRunning()) {
            $model->setStop();
        } else {
            $this->_run();
        }
        $this->success('successfully');
    }

    /**
     * reRun  重启计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function reRun()
    {
        $model = D('Common/Schedule');
        $model->setStop();
        $this->_checkLock();

        $this->_run();
        $this->success('successfully');
    }

    /**
     * _checkLock  判断lock文件，当文件不存在的时候返回
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function _checkLock(){
        $model = D('Common/Schedule');
        if($model->checkLockFileExist()){
            sleep(1);
           return $this->_checkLock();
        }
        return true;
    }

    /**
     * _run  运行计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function _run()
    {
        $time = time();
        $url = U('Core/Public/runSchedule', array('time' => $time, 'token' => md5($time . C('DATA_AUTH_KEY'))), true, true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);  //设置过期时间为1秒，防止进程阻塞
        curl_setopt($ch, CURLOPT_USERAGENT, '');
        curl_setopt($ch, CURLOPT_REFERER, 'b');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
    }
}
