<?php

class GroupSettingModel extends Model
{
    public $tableName = 'group_setting';

    /*
    function getGroupSetting(){
        $data = $this->findAll();
        $setting = array();
        foreach($data as $k=>$v){
            $setting[$v['name']] = $v['value'];
        }
        return $setting;
    }


    function add($setting) {
        if(!is_array($setting)) return false;
        foreach ($setting as $k=>$v) {

            $this->where("name='$k'")->setField("value",$v);

        }
        return true;
    }*/

    //搜索数据
    public function searchData($type, $uid, $title, $content, $field, $asc, $limit = null, $isDel = 0)
    {
        $condition = array();

        if ($username) {
            $arr_uid = M('user')->where("name like '%$name%'")->field('id')->findAll();

            if (!empty($arr_uid) && $type != 'album' && $type != 'photo') {
                $condition[] = 'uid IN '.render_in($arr_uid, 'id');
            } else {
                $condition[] = 'userId IN '.render_in($arr_uid, 'id');
            }
        }

        if ($type == 'group') {
            if ($uid) {
                $condition[] = "uid = $uid ";
            }
            if ($title) {
                $condition[] = "name like '%".$title."%'";
            }
            $condition[] = 'status=1';

            $data = D('Group')->getGroupList(1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return    $data;
        } elseif ($type == 'weibo') {
            if ($uid) {
                $condition[] = "uid = {$uid} ";
            }
            if ($title) {
                $condition[] = "name like '%{$title}%'";
            }

            $data = D('Group')->getGroupList(1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return    $data;
        } elseif ($type == 'topic') {
            if ($uid) {
                $condition[] = "uid = $uid ";
            }
            if ($title) {
                $condition[] = "title like '%".$title."%'";
            }

            $data = D('Topic')->getTopicList(1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return    $data;
        } elseif ($type == 'album') {
            if ($title) {
                $condition[] = "name like '%".$title."%'";
            }
            if ($uid) {
                $condition[] = "userId=$uid";
            }
            $data = D('Album')->getAlbumList($html = 1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return $data;
        } elseif ($type == 'file') {
            if ($uid) {
                $condition[] = "uid = $uid ";
            }
            if ($title) {
                $condition[] = "name like '%".$title."%'";
            }

            $data = D('Dir')->getFileList(1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return    $data;
        } elseif ($type == 'post') {
            if ($uid) {
                $condition[] = "uid = $uid ";
            }
            if ($content) {
                $condition[] = "note %'".$content."'%";
            }
            $data = D('Post')->getPostList(1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return    $data;
        } elseif ($type == 'photo') {
            if ($uid) {
                $condition[] = "userId=$uid";
            }
            if ($title) {
                $condition[] = "name like '%".$title."%'";
            }
            $data = D('photo')->getPhotoList($html = 1, $condition, $fields = null, "$field $asc", $limit, $isDel);

            return $data;
        }
    }
}
