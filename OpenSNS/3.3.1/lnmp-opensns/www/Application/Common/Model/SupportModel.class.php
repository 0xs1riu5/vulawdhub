<?php
/**
 * Created by PhpStorm.
 * User: Yixiao Chen
 * Date: 2015/4/30 0030
 * Time: 下午 3:39
 */

namespace Common\Model;


use Think\Model;

class SupportModel extends Model
{
    protected $tableName = 'support';

    /**取得赞的数量
     * @param $appname
     * @param $table
     * @param $row
     * @return mixed
     */
    public function getSupportCount($appname, $table, $row)
    {
        return $this->where(array('appname' => $appname, 'table' => $table, 'row' => $row))->cache($this->getCacheTag($appname, $table, $row))->count();
    }

    public function getSupportedUser($app, $table, $row, $user_fields=array('nickname','space_url','avatar128'),$num=10)
    {
        $supported = $this->where(array('appname' => $app, 'table' => $table, 'row' => $row))->findPage($num);
        foreach ($supported['data'] as &$v) {
            $v['user'] = query_user($user_fields, $v['uid']);
        }
        unset($v);
        return $supported;
    }

    /**清除赞缓存
     * @param $appname
     * @param $table
     * @param $row
     */
    public function clearCache($appname, $table, $row)
    {
        S($this->getCacheTag($appname, $table, $row), null);
        if($table=='weibo' || $table=='weibo_comment'){//点赞后清除微博html缓存
            D('Weibo/WeiboCache')->cleanCache($row);
        }
    }

    private function getCacheTag($appname, $table, $row)
    {
        return 'support_count_' . $appname . '_' . $table . '_' . $row;
    }

    public function getSupported($app, $table, $row, $user_fields=array('uid','space_url'),$num=5)
    {
        $supported = $this->where(array('appname' => $app, 'table' => $table, 'row' => $row))->order('create_time desc')->limit($num)->select();
        foreach ($supported as &$v) {
            $v['user'] = query_user($user_fields, $v['uid']);
        }
        unset($v);
        return $supported;
    }
}