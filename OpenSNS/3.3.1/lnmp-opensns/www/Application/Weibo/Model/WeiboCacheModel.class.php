<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/9/1
 * Time: 17:24
 */

namespace Weibo\Model;


use Think\Model;

class WeiboCacheModel extends Model
{

    private $cacheLong=1200;//微博html缓存有效时长，弃用
    private $tag='weibo_list_detail_html_';
    /**
     * 获取微博html缓存
     * @param $weibo_id
     * @param int $uid
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getCacheHtml($weibo_id)
    {
        return S($this->tag.$weibo_id);
        //下面弃用
        $this->_deleteOld();
        !$uid && $uid = is_login();
        $map['groups'] = $this->_getAuthGroup($uid);
        $map['weibo_id'] = $weibo_id;
        $map['create_time'] = array('egt', time() - $this->cacheLong);
        $weiboCache = $this->where($map)->find();
        return $weiboCache['cache_html'];
    }

    /**
     * 设置微博html缓存
     * @param $weibo_id
     * @param $html
     * @param int $uid
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function setCacheHtml($weibo_id, $html)
    {
        S($this->tag.$weibo_id,$html);
        return true;
        //下面弃用
        !$uid && $uid = is_login();
        $data['groups'] = $this->_getAuthGroup($uid);
        $data['weibo_id'] = $weibo_id;
        $data['cache_html'] = $html;
        $data['create_time'] = time();
        return $this->add($data);
    }

    /**
     * 清除weibo html缓存
     * @param int $weibo_id 为空则清除所有，不为空则清除该weibo的缓存（全部）
     * @return bool
     */
    public function cleanCache($weibo_id = 0)
    {
        S($this->tag.$weibo_id,null);
        return true;
        //下面弃用
        if ($weibo_id) {
            $this->_deleteOld();
            $map['weibo_id'] = $weibo_id;
            $this->where($map)->delete();
        } else {
            $this->where(1)->delete();
        }
        return true;
    }

    /************************************************************下面弃用***********************************************/
    /**
     * 获取置顶微博html缓存（弃用）
     * @param $weibo_id
     * @param int $uid
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getTopCacheHtml($weibo_id, $uid = 0)
    {
        $this->_deleteOld();
        !$uid && $uid = is_login();
        $map['groups'] = $this->_getAuthGroup($uid);
        $map['groups']=$map['groups'].'_top';
        $map['weibo_id'] = $weibo_id;
        $map['create_time'] = array('egt', time() - $this->cacheLong);
        $weiboCache = $this->where($map)->find();
        return $weiboCache['cache_html'];
    }

    /**
     * 设置置顶微博html缓存（弃用）
     * @param $weibo_id
     * @param $html
     * @param int $uid
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function setTopCacheHtml($weibo_id, $html, $uid = 0)
    {
        !$uid && $uid = is_login();
        $data['groups'] = $this->_getAuthGroup($uid);
        $data['groups']=$data['groups'].'_top';
        $data['weibo_id'] = $weibo_id;
        $data['cache_html'] = $html;
        $data['create_time'] = time();
        return $this->add($data);
    }

    private function _deleteOld()
    {
        $map_old['create_time'] = array('lt', time() - $this->cacheLong);
        $this->where($map_old)->delete();
        return true;
    }

    private function _getAuthGroup($uid = 0)
    {
        if (!$uid) {
            return '';
        }
        $tag = 'UID_GROUPS_' . $uid;
        $groups = S($tag);
        if ($groups === false) {
            $map['uid'] = $uid;
            $groups = M('AuthGroupAccess')->where($map)->select();
            $groups = array_column($groups, 'group_id');
            sort($groups);
            $groups = implode(',', $groups);
            S($tag, $groups);
        }
        return $groups;
    }
}