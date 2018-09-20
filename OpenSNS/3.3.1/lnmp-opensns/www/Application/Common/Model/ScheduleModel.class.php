<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-26
 * Time: 下午4:29
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */

namespace Common\Model;

use Think\Model;

class ScheduleModel extends Model
{
    protected $tableName = 'schedule';
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );
    public $lockFile = './Data/Schedule/lock.txt';
    public $interval = 10;
    protected $schedule_path = './Data/Schedule/';

    /**
     * editSchedule 编辑或增加计划任务
     * @param $data
     * @return bool|mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function editSchedule($data)
    {
        $data = $this->create($data);
        if (!$data) return false;
        if ($data['id']) {
            $result = $this->save($data);
        } else {
            $result = $this->add($data);
            $data['id'] = $result;
        }
        if (!$result) {
            return false;
        }
        S('schedule_list', null);
        return $data['id'];
    }


    /**
     * getScheduleList  获取全部可运行的计划任务列表
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getScheduleList()
    {
        $tag = 'schedule_list';
        $list = S($tag);
        if (empty($list)) {
            $map['status'] = array('egt', 0);
            $list = $this->where($map)->select();
            S($tag, $list);
        }
        return $list;
    }


    /**
     * checkIsRunning  判断计划任务是否在运行
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function checkIsRunning()
    {
        $lock_file = $this->lockFile;
        if ($this->checkLockFileExist() && $this->readFile($lock_file) == 'running' && (filemtime($lock_file) + $this->interval + 10 > $_SERVER['REQUEST_TIME'])) {
            return true;
        }
        $this->setStop('stop_abnormal');
        return false;
    }

    /**
     * setStop  设置状态为停止
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setStop($text = 'stop')
    {
        $lock_file = $this->lockFile;
        if ($this->checkLockFileExist() && $this->readFile($lock_file) != 'stop') {
            $this->writeFile($lock_file, $text);
        }
        return true;
    }

    /**
     * run 执行计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function run()
    {
        ignore_user_abort(true); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
        set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        $lock_txt = $this->lockFile;
        if ($this->checkIsRunning()) { //防止重复运行，判断是否在运行，是则退出
            exit();
        } else {
            touch($lock_txt); //重新生成锁文件，更新文件访问和修改时间
            $this->writeFile($lock_txt, 'running'); //重复写入一个文件，标志已经运行计划任务
        }
        $this->setConfig(1);
        do {
            $this->runScheduleList(); //执行计划任务列表
            touch($lock_txt); //更新运行时间
            ob_flush();
            flush();
            sleep($this->interval); //程序暂停
        } while ($this->readFile($lock_txt) == 'running');

        @unlink($lock_txt); //删除标记文件
    }

    /**
     * runScheduleList  按列表执行计划任务
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function runScheduleList()
    {
        $list = $this->getScheduleList();
        $now_time = time();
        foreach ($list as $v) {
            if ($v['start_time'] > $now_time) { //早于设定的时间
                continue;
            }
            if ($v['end_time'] < $now_time) { //过期
                continue;
            }
            $next_time = $this->calculateNextTime($v);
            if (!empty($next_time) && $next_time <= $now_time) {
                $this->runSchedule($v);
            } else {
                continue;
            }

        }
        return true;
    }

    /**
     * calculateNextTime   计算下次执行的时间
     * @param $schedule
     * @return int
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function calculateNextTime($schedule)
    {
        $last_time = $this->getLastUpdate($schedule['id']);
        $time = 0;
        if ($schedule['status'] == 1) {
            switch ($schedule['type']) {
                case 1: //ONCE 一次，以分为单位
                    $time = $schedule['type_value'] > $last_time ? $schedule['type_value'] : 0;
                    break;
                case 2: //每隔多久，以分为单位

                    $array = explode('*', $schedule['type_value']);
                    $num = 1;
                    foreach ($array as $v) {
                        $num = $num * $v;
                    }
                    $time = $last_time + $num * 60;

                    if ($time < time()) {
                        $time = time();// + $num * 60;
                    }
                    if ($time < $schedule['start_time']) {
                        $time = $schedule['start_time'];
                    }
                    if ($time > $schedule['end_time']) {
                        $time = 0;
                    }
                    break;
                case 3 : // 每个时间点
                    $value = explode('=', $schedule['type_value']);
                    $type = '_get' . ucfirst($value[0]);
                    $that = &$this;
                    $time = $this->$type($value[1], $last_time, function ($time) use ($schedule, $that, $type, $value) {
                        if ($time < $schedule['start_time']) {
                            $time = $that->$type($value[1], $schedule['start_time']);
                        }
                        if ($time > $schedule['end_time']) {
                            $time = 0;
                        }
                        return $time;
                    });
                    break;
            }

        }
        return $time;
    }

    /**
     * _getHourly  hourly 获取下次执行的时间
     * @param $min
     * @param $last_time
     * @param $callback
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function _getHourly($min, $last_time, $callback = '')
    {
        if ($min >= 60 || $min < 0) {
            $min = 0;
        }

        $h = date("i", $last_time) < $min ? 0 : 1;
        $time = mktime(date("H", $last_time) + $h, $min, 0, date("m", $last_time), date("d", $last_time), date("Y", $last_time));
        return $callback == '' ? $time : $callback($time);
    }

    /**
     * _getDaily  daily 获取下次执行的时间
     * @param $t
     * @param $last_time
     * @param $callback
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function _getDaily($t, $last_time, $callback = '')
    {
        $t = explode(':', $t);
        $d = $this->checkIsGone($last_time, $t);
        $time = mktime($t[0], $t[1], 0, date("m", $last_time), date("d", $last_time) + $d, date("Y", $last_time));
        return $callback == '' ? $time : $callback($time);
    }

    /**
     * checkIsGone  判断时间是否已经过去
     * @param $time
     * @param $t
     * @return int
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function checkIsGone($time, $t)
    {
        $d = 0;
        if (date("H", $time) == $t[0]) {
            if (date("i", $time) >= $t[1]) {
                $d = 1;
            }
        }
        if (date("H", $time) > $t[0]) {
            $d = 1;
        }
        return $d;
    }

    /**
     * _getWeekly    weekly 获取下次执行的时间
     * @param $args
     * @param $last_time
     * @param $callback
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function _getWeekly($args, $last_time, $callback = '')
    {

        $args = explode('|', $args);
        $week = $args[0];
        empty($args[1]) && $args[1] = "00:00";
        $w = date("N", $last_time);
        $d = 1;
        if ($w == $week) {
            $t = $args[1];
            $t = explode(':', $t);
            $d = $this->checkIsGone($last_time, $t);
        }
        $day = '';
        switch ($week) {
            case 1:
                $day = 'Monday';
                break;
            case 2:
                $day = 'Tuesday';
                break;
            case 3:
                $day = 'Wednesday';
                break;
            case 4:
                $day = 'Thursday';
                break;
            case 5:
                $day = 'Friday';
                break;
            case 6:
                $day = 'Saturday';
                break;
            case 7:
                $day = 'Sunday';
                break;
        }

        $time = strtotime(date('Y-m-d ' . $args[1] . ':00', $d > 0 ? strtotime("next " . $day, $last_time) : $last_time));
        return $callback == '' ? $time : $callback($time);
    }

    /**
     * _getMonthly   monthly 获取下次执行的时间
     * @param $args
     * @param $last_time
     * @param $callback
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function _getMonthly($args, $last_time, $callback = '')
    {

        $args = explode('|', $args);
        $day = $args[0];
        empty($args[1]) && $args[1] = "00:00";
        $t = $args[1];
        $t = explode(':', $t);
        $count = date("t");
        $day > $count && $day = $count;
        $m = date("d", $last_time) < $day ? 0 : 1;
        if (date("d", $last_time) == $day) {
            $m = $this->checkIsGone($last_time, $t);
        }
        $time = mktime($t[0], $t[1], 0, date("m", $last_time) + $m, $day, date("Y", $last_time));
        return $callback == '' ? $time : $callback($time);
    }


    /**
     * runSchedule  执行单个计划任务
     * @param $schedule
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function runSchedule($schedule)
    {
        if ($schedule['status'] == 1) {
            $method = explode('->', $schedule['method']);
            parse_str($schedule['args'], $args);  //分解参数
            try {
                $return = D($method[0])->$method[1]($args, $schedule); //执行model中的方法
            } catch (\Exception $exception) {
                $return = false;
            }
            if ($return) {
                $log = '任务已运行，描述：' . $schedule['intro'];
            } else {
                $log = '任务运行失败，描述：' . $schedule['intro'];
            }
            $this->writeLog($schedule['id'], $log);
        }
        return true;
    }

    /**
     * writeLog  写日志
     * @param $id
     * @param $content
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function writeLog($id, $content)
    {
        $file = $this->schedule_path . 'schedule_' . $id . '.txt';
        $data = $this->readFile($file);
        touch($file); //更新文件访问和修改时间
        $this->writeFile($file, $data . '[' . date('Y-m-d H:i:s') . ']' . $content . "\n");
    }

    /**
     * getLog  获取日志
     * @param $id
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getLog($id)
    {
        $file = $this->schedule_path . 'schedule_' . $id . '.txt';
        return $this->readFile($file);
    }

    /**
     * clearLog  清空日志
     * @param $id
     * @return int|void
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function clearLog($id)
    {
        $file = $this->schedule_path . 'schedule_' . $id . '.txt';
        return $this->writeFile($file, '');
    }

    /**
     * getLastUpdate  获取上次执行的时间
     * @param $id
     * @return int
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function getLastUpdate($id)
    {
        $file = $this->schedule_path . 'schedule_' . $id . '.txt';
        if (!file_exists($file)) {
            touch($file);
        }
        return filemtime($file);
    }

    /**
     * writeFile  写文件，代理方法
     * @param $file
     * @param $data
     * @return int|void
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function writeFile($file, $data)
    {
        return @file_put_contents($file, $data);
    }

    /**
     * readFile  读文件，代理方法
     * @param $fils
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function readFile($fils)
    {
        return @file_get_contents($fils);
    }

    /**
     * checkLockFileExist  判断lock文件是否存在
     * @return bool
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function checkLockFileExist()
    {
        $lock_file = $this->lockFile;
        if (file_exists($lock_file)) {
            return true;
        }
        return false;
    }

    /**
     * dealAbnormalStop  处理异常停止
     * @author :  xjw129xjt（駿濤） xjt@ourstu.com
     */
    public function dealAbnormalStop()
    {
        $lock_file = $this->lockFile;
        if ($this->checkLockFileExist() && $this->readFile($lock_file) == 'stop_abnormal') {
            //异常停止则启动计划任务
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
            return;
        } else {
            //其他情况不做操作
            return;
        }
    }


    /**
     * setIsLogin 设置session，模拟登录,使得is_login()能获取到uid
     * @param $uid
     * @param int $role_id
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setIsLogin($uid, $role_id = 1)
    {
        $this->clearIsLogin();
        $map['uid'] = $uid;
        $map['role_id'] = $role_id;
        $audit = M('UserRole')->where($map)->getField('status');
        $auth = array(
            'uid' => $uid,
            'username' => get_username($uid),
            'last_login_time' => time(),
            'role_id' => $role_id,
            'audit' => $audit,
        );
        try {
            session('user_auth', $auth);
            session('user_auth_sign', data_auth_sign($auth));
        } catch (\Exception $exception) {
        }
    }

    /**
     * clearIsLogin  清空session
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function clearIsLogin()
    {
        try {
            session('user_auth', null);
            session('user_auth_sign', null);
        } catch (\Exception $exception) {
        }


    }

    /**************************************以下为测试代码****************************************************************************/
    public function sendWeibo($args, $schedule)
    {
        $uid = $args['uid'];
        if ($uid) {
            $uid = explode(',', $uid);
            foreach ($uid as $v) {

                $this->setIsLogin($v); //设置session,
                $data['uid'] = $v;
                $data['content'] = $schedule['intro'];
                $data['create_time'] = time();
                $data['status'] = 1;
                $data['type'] = 'feed';
                $data['data'] = '';
                $data['comment_count'] = 0;
                $data['is_top'] = 0;
                $data['repost_count'] = 0;
                $data['from'] = '';
                $weibo_id = M('Weibo')->add($data);
                action_log('add_weibo', 'weibo', $weibo_id, $v);
                clean_query_user_cache($v, array('weibocount'));
            }
        }
        return true;
    }

    public function test($args, $schedule)
    {
        return true;
    }

}
