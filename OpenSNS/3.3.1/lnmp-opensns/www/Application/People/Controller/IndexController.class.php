<?php


namespace People\Controller;

use Think\Controller;


class IndexController extends Controller
{
    public function _initialize(){

        $field3=modC('USER_SHOW_ORDER_FIELD3','rand','people');
        $title3=modC('USER_SHOW_TITLE3','随机推荐关注','people');
        $field0=modC('USER_SHOW_ORDER_FIELD0','friend','people');
        $title0=modC('USER_SHOW_TITLE0','好友也关注','people');
        $data3=show_side($field3,3);
        $data0=show_side($field0,0);

        $this->assign('title3',$title3);
        $this->assign('right3',$data3);
        $this->assign('title0',$title0);
        $this->assign('right0',$data0);

        $star=star_follow();
        $bigstar=array_shift($star);

        $this->assign('bigstar',$bigstar);
        $this->assign('star',$star);
    }

    public function index()
    {
        $map = $this->setMap();
        $map['status'] = 1;
        $map['last_login_time'] = array('neq', 0);
        $peoples = S('People_peoples_' . I('page', 0, 'intval') . '_' . serialize($map));
        if (empty($peoples)) {
            $peoples = D('Member')->where($map)->field('uid', 'reg_time', 'last_login_time')->order('last_login_time desc')->findPage(12);
            $userConfigModel = D('Ucenter/UserConfig');
            $titleModel = D('Ucenter/Title');
            foreach ($peoples['data'] as &$v) {
                $v = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $v['uid']);
                $v['province'] = get_area_name($v['pos_province']);
                $v['city'] = get_area_name($v['pos_city']);
                $v['district'] = get_area_name($v['pos_district']);

                $v['level'] = $titleModel->getCurrentTitleInfo($v['uid']);
                //获取用户封面id
                $where = getUserConfigMap('user_cover', '', $v['uid']);
                $where['role_id'] = 0;
                $model = $userConfigModel;
                $cover = $model->findData($where);
                $v['cover_id'] = $cover['value'];
                $v['cover_path'] = getThumbImageById($cover['value'], 273, 80);

            }
            unset($v);
            S('People_peoples_' . I('page', 0, 'intval') . '_' . serialize($map), $peoples, 3600);
        }
        $k=0;
        foreach ($peoples['data'] as $v){
            $arr[$k]['uid']=$v['uid'];
            $arr[$k]['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
            $k++;
        }unset($k);unset($v);
        $this->assign('isfollow',$arr);
        $this->assign('tab', 'index');
        $this->assign('lists', $peoples);
        $this->display();
    }

    /***********************area***************************************/
    public function area()
    {
        $map = array();
        $arearank = I('get.arearank', 0,'intval');
        $arealv = I('get.arealv',0,'intval');
        $areaname = I('get.areaname',0,'intval');
        if ($arearank == null || $arearank == 0) {
            $map['pos_province'] =array('neq','');
        } else {
            switch ($arealv) {
                case 1:
                    $map['pos_province'] = $arearank;
                    break;
                case 2:
                    $map['pos_city'] = $arearank;
                    break;
                case 3:
                    $map['pos_district'] = $arearank;
                    break;
                default:
                    $map['pos_province'] != null;
            }
        }


        $map['status'] = 1;
        $map['last_login_time'] = array('neq', 0);
        $peoples = S('People_peoples_' . I('page', 0, 'intval') . '_' . serialize($map));
        if (empty($peoples)) {
            $peoples = D('Member')->where($map)->field('uid', 'reg_time', 'last_login_time')->order('last_login_time desc')->findPage(12);

            $userConfigModel = D('Ucenter/UserConfig');
            $titleModel = D('Ucenter/Title');


            foreach ($peoples['data'] as &$v) {
                $v = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $v['uid']);
                $v['province'] = get_area_name($v['pos_province']);
                $v['city'] = get_area_name($v['pos_city']);
                $v['district'] = get_area_name($v['pos_district']);

                $v['level'] = $titleModel->getCurrentTitleInfo($v['uid']);
                //获取用户封面id
                $where = getUserConfigMap('user_cover', '', $v['uid']);
                $where['role_id'] = 0;
                $model = $userConfigModel;
                $cover = $model->findData($where);
                $v['cover_id'] = $cover['value'];
                $v['cover_path'] = getThumbImageById($cover['value'], 273, 80);
            }
            unset($v);
            S('People_peoples_' .I('page',0,'intval').'_' . serialize($map), $peoples, 3600);
        }
        //地区信息
        $district = M('district');
        $areanumber = M('member');
        $areadata = $district->where(array('upid'=>$arearank))->select();
        //地区人数
        foreach ($areadata as &$v1) {
            switch ($v1['level']) {
                case 1:
                    $res = $areanumber->where(array('pos_province' => $v1['id']))->count();
                    $v1['number'] = $res;
                    break;
                case 2:
                    $res = $areanumber->where(array('pos_city' => $v1['id']))->count();
                    $v1['number'] = $res;
                    break;
                case 3:
                    $res = $areanumber->where(array('pos_district' => $v1['id']))->count();
                    $v1['number'] = $res;
                    break;
                default:
                    $res = 0;
            }
        }
        unset($v1);

        if ($areadata == null) {
            $areadata = $district->where(array('id'=>$arearank))->field('upid', true)->select();
        }

        $this->assign('tag_arealist', $areadata);
        if ($areaname == null) {
            $this->assign('areaname', "");
            $this->assign('goback', "");
        } else {
            $this->assign('areaname', $areaname . ':');
            $this->assign('goback', "返回");
        }

        $k=0;
        foreach ($peoples['data'] as $v){
            $arr[$k]['uid']=$v['uid'];
            $arr[$k]['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
            $k++;
        }unset($k);unset($v);
        $this->assign('isfollow',$arr);
        $this->assign('tab', 'area');
        $this->assign('lists', $peoples);
        $this->display();


    }

    /***********************area***************************************/
    private function setMap()
    {
        $aTag = I('tag', 0, 'intval');
        $aRole = I('role', 0, 'intval');
        $role_list = modC('SHOW_ROLE_TAB', '', 'People');
        if ($role_list != '') {
            $role_list = json_decode($role_list, true);
            $role_list = $role_list[1]['items'];
            if (count($role_list)) {
                foreach ($role_list as &$val) {
                    $val['id'] = $val['data-id'];
                }
                unset($val);
                $this->assign('role_list', $role_list);
            } else {
                $aRole = 0;
            }
        } else {
            $aRole = 0;
        }
        $map = array();
        if ($aTag && $aRole) {//同时选择标签和身份
            !isset($_GET['tag']) && $_GET['tag'] = $_POST['tag'];
            $map_uids['tags'] = array('like', '%[' . $aTag . ']%');
            $tag_links = D('Ucenter/UserTagLink')->getListByMap($map_uids);
            $tag_uids = array_column($tag_links, 'uid');
            $this->assign('tag_id', $aTag);

            !isset($_GET['role']) && $_GET['role'] = $_POST['role'];
            $map_role['role_id'] = $aRole;
            $map_role['status'] = 1;
            $role_links = M('UserRole')->where($map_role)->limit(999)->field('uid')->select();
            $role_uids = array_column($role_links, 'uid');
            $this->assign('role_id', $aRole);
            if ($tag_uids && $role_uids) {
                $uids = array_intersect($tag_uids, $role_uids);
            } else {
                $uids = array();
            }
            $map['uid'] = array('in', $uids);
        } else if ($aTag) {//选择标签，没选择身份
            !isset($_GET['tag']) && $_GET['tag'] = $_POST['tag'];
            $map_uids['tags'] = array('like', '%[' . $aTag . ']%');
            $tag_links = D('Ucenter/UserTagLink')->getListByMap($map_uids);
            $tag_uids = array_column($tag_links, 'uid');
            $map['uid'] = array('in', $tag_uids);
            $this->assign('tag_id', $aTag);
        } else if ($aRole) {//选择身份，没选择标签
            !isset($_GET['role']) && $_GET['role'] = $_POST['role'];
            $map_role['role_id'] = $aRole;
            $map_role['status'] = 1;
            $role_links = M('UserRole')->where($map_role)->limit(999)->field('uid')->select();
            $role_uids = array_column($role_links, 'uid');
            $map['uid'] = array('in', $role_uids);
            $this->assign('role_id', $aRole);
        }
        $userTagModel = D('Ucenter/UserTag');
        if ($aRole) {
            $map_tags = getRoleConfigMap('user_tag', $aRole);
            $can_config = M('RoleConfig')->where($map_tags)->field('value')->find();
            if ($can_config['value'] != '') {
                $tag_list = $userTagModel->getTreeListByIds($can_config['value']);
            } else {
                $tag_list = null;
            }
        } else {
            $tag_list = $userTagModel->getTreeList();
        }
        $this->assign('tag_list', $tag_list);
        $nickname = I('keywords', '', 'op_t');
        if ($nickname != '') {
            !isset($_GET['keywords']) && $_GET['keywords'] = $_POST['keywords'];
            $map['nickname'] = array('like', '%' . $nickname . '%');
            $this->assign('nickname', $nickname);
        }
        return $map;
    }
    /**
     * 关/取注
     * 范佳炜 fjw@ourstu.com
     */
    public function follow(){
        $who_follow=I('post.who_follow',0,'intval');
        $follow_who=I('post.follow_who',0,'intval');
        S('Core_follow_'.$who_follow.'_'.$follow_who,null);
        $arr[]=modC('USER_SHOW_ORDER_FIELD0','friend','people');
        $arr[]=modC('USER_SHOW_ORDER_FIELD3','rand','people');
        $arr[]='star';
        foreach ($arr as $a){
            S($a.'_follow_cache_'.$who_follow,null);
        }unset($a);
        $res= D('Common/Follow')->isFollow($who_follow,$follow_who);
        if($res){
            $unf=D('Common/Follow')->unfollow($follow_who);
            if($unf===false){
                $data['info']='取关失败~';
                $data['status']=0;
            }else{
                $data['info']='取关成功~';
                $data['status']=1;
                D('Common/Member')->where(array('uid' => $follow_who))->setDec('fans', 1);
            }
            $this->ajaxReturn($data);
        }else{
            $addf=D('Common/Follow')->addFollow($who_follow,$follow_who);

            if($addf===false){
                $data['info']='关注失败~';
                $data['status']=0;
            }else{
                $data['info']='关注成功~';
                $data['status']=1;
                D('Common/Member')->where(array('uid' => $follow_who))->setInc('fans', 1);
            }
            $this->ajaxReturn($data);
        }


    }

}