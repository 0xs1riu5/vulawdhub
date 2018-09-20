<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/10/25
 * Time: 16:31
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Ucenter\Model;


use Think\Model;

class UcenterShortModel extends Model
{
    /**
     * 获取用户短域名信息
     * @param $uid
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getShortUrl($uid)
    {
        $uid=$uid?$uid:is_login();
        $map['uid']=$uid;
        $map['status']=1;
        $short_url=$this->where($map)->find();
        return $short_url;
    }

    /**
     * 设置短域名
     * @param $data
     * @return bool|mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function setShortUrl($data)
    {
        if(!$this->checkShort($data['short'])){
            return false;
        }
        if($uid=$this->findShort($data['short'])){
            if($uid==is_login()){
                $this->error="短域名没有修改！";
            }else{
                $this->error="该域名已存在！";
            }

            return false;
        }

        $data['uid']=is_login();
        $data['status']=1;
        $data['update_time']=time();
        if($data['id']){
            $res=$this->save($data);
        }else{
            $data['create_time']=time();
            $res=$this->add($data);
        }
        return $res;
    }

    /**
     * 根据短域名查询用户
     * @param string $short
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function findShort($short='')
    {
        if(!$this->checkShort($short)){
            return false;
        }
        $map['short']=$short;
        $map['status']=1;
        $user=$this->where($map)->find();
        return $user['uid'];
    }

    /**
     * 检测短域名合法性
     * @param string $short
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    private function checkShort($short='')
    {
        if(strlen($short)<4||strlen($short)>20){
            $this->error="个性域名长度不合法！";
            return false;
        }
        if(!preg_match('/\w+/',$short)){
            $this->error="个性域名包含非法字符！";
            return false;
        }
        return true;
    }
}