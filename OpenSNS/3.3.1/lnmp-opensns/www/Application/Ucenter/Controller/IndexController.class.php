<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 下午1:54
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Controller;


use Think\Controller;

class IndexController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $uid = isset($_GET['uid']) ? op_t($_GET['uid']) : is_login();
        //调用API获取基本信息
        $this->userInfo($uid);
        $this->_fans_and_following($uid);

        $this->_tab_menu();

        $isWuKong = M('Addons')->where(array('name' => 'WuKong'))->find();
        $this->assign('has_wukong', $isWuKong['status'] == 1 ? 1 : 0);

    }

    public function index( $page = 1)
    {
        $aShortUrl=I('user_short_url','','text');
        if($aShortUrl!=''){
            $aUid=D('Ucenter/UcenterShort')->findShort($aShortUrl);
            if(!$aUid){
                $aUid=I('uid',0,'intval');
            }
        }else{
            $aUid=I('uid',0,'intval');
        }
        $show_tab = get_kanban_config('UCENTER_KANBAN', 'enable', '', 'USERCONFIG');
        $menu = $this->_tab_menu();
        foreach ($show_tab as $v1) {
            foreach ($menu as $v2) {
                if (array_search($v1, $v2)) {
                    $arr3[$v1] = $v2;
                }
            }
        }
        unset($v1);
        unset($v2);
        $appArr = $arr3;
        $current_action = current($appArr);
        $url_link = array(
            'info' => 'Ucenter/Index/information',
            'rank_title' => 'Ucenter/Index/rank',
            'follow' => 'Ucenter/Index/following',
        );
        if (!$current_action) {
            $this->redirect('Ucenter/Index/information', array('uid' => $aUid));
        }
        if (in_array($current_action['data-id'], array('info', 'rank_title', 'follow'))) {
            $aUid = ($aUid > 0) ? $aUid : is_login();
            $this->redirect($url_link[$current_action['data-id']], array('uid' => $aUid));
        }
        $type = key($appArr);
        if (!isset ($appArr [$type])) {
            $this->error(L('_ERROR_PARAM_') . L('_EXCLAMATION_') . L('_EXCLAMATION_'));
        }

        $this->assign('type', $type);
        $this->assign('module', $appArr[$type]['data-id']);
        $this->assign('page', $page);

        //四处一词 seo
        $str = '{$user_info.nickname|text}';
        $str_app = '{$appArr.' . $type . '.title|text}';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_') . $str_app);
        $this->setDescription($str . L('_DE_PERSON_') . $str_app . L('_PAGE_'));
        //四处一词 seo end
        $this->display();
    }


    private function userInfo($uid = null)
    {
        $user_info = query_user(array('avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link', 'signature'), $uid);
        //获取用户封面id
        $map = getUserConfigMap('user_cover', '', $uid);
        $map['role_id'] = 0;
        $model = D('Ucenter/UserConfig');
        $cover = $model->findData($map);
        $user_info['cover_id'] = $cover['value'];
        $user_info['cover_path'] = getThumbImageById($cover['value'], 1140, 230);
        $user_info['tags'] = D('Ucenter/UserTagLink')->getUserTag($uid);
        $this->assign('user_info', $user_info);
        return $user_info;
    }

    public function information($uid = null)
    {
        //调用API获取基本信息
        //TODO tox 获取省市区数据
        $user = query_user(array('nickname', 'signature', 'email', 'mobile', 'rank_link', 'sex', 'pos_province', 'pos_city', 'pos_district', 'pos_community'), $uid);
        if ($user['pos_province'] != 0) {
            $user['pos_province'] = D('district')->where(array('id' => $user['pos_province']))->getField('name');
            $user['pos_city'] = D('district')->where(array('id' => $user['pos_city']))->getField('name');
            $user['pos_district'] = D('district')->where(array('id' => $user['pos_district']))->getField('name');
            $user['pos_community'] = D('district')->where(array('id' => $user['pos_community']))->getField('name');
        }
        //显示页面
        $this->assign('user', $user);
        $this->getExpandInfo($uid);
        //四处一词 seo
        $str = '{$user_info.nickname|text}';
        $this->setTitle($str . L('_INFO_TITLE_'));
        $this->setKeywords($str . L('_INFO_KEYWORDS_'));
        $this->setDescription($str . L('_INFO_DESC_'));
        //四处一词 seo end

        $this->display();
    }

    /**获取用户扩展信息
     * @param null $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getExpandInfo($uid = null, $profile_group_id = null)
    {
        $profile_group_list = $this->_profile_group_list($uid);
        foreach ($profile_group_list as &$val) {
            $val['info_list'] = $this->_info_list($val['id'], $uid);
        }
        $this->assign('profile_group_list', $profile_group_list);
    }

    /**扩展信息分组列表获取
     * @param null $uid
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _profile_group_list($uid = null)
    {

        $profile_group_list = array();
        $fields_list = $this->getRoleFieldIds($uid);
        if ($fields_list) {
            $fields_group_ids = D('FieldSetting')->where(array('id' => array('in', $fields_list), 'status' => '1'))->field('profile_group_id')->select();
            if ($fields_group_ids) {
                $fields_group_ids = array_unique(array_column($fields_group_ids, 'profile_group_id'));
                $map['id'] = array('in', $fields_group_ids);

                if (isset($uid) && $uid != is_login()) {
                    $map['visiable'] = 1;
                }
                $map['status'] = 1;
                $profile_group_list = D('field_group')->where($map)->order('sort asc')->select();
            }
        }
        return $profile_group_list;
    }

    private function getRoleFieldIds($uid = null)
    {
        $roleid = M('member')->where('uid=' . $uid)->field('show_role')->select();
        $role_id = $roleid[0]['show_role'];
        $fields_list = S('Role_Expend_Info_' . $role_id);
        if (!$fields_list) {
            $map_role_config = getRoleConfigMap('expend_field', $role_id);
            $fields_list = D('RoleConfig')->where($map_role_config)->getField('value');
            if ($fields_list) {
                $fields_list = explode(',', $fields_list);
                S('Role_Expend_Info_' . $role_id, $fields_list, 600);
            }
        }
        return $fields_list;
    }

    /**分组下的字段信息及相应内容
     * @param null $id
     * @param null $uid
     * @return null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _info_list($id = null, $uid = null)
    {
        $fields_list = $this->getRoleFieldIds($uid);
        $info_list = null;

        if (isset($uid) && $uid != is_login()) {
            //查看别人的扩展信息
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'visiable' => '1', 'id' => array('in', $fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = $uid;
        } else if (is_login()) {
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'id' => array('in', $fields_list)))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = is_login();

        } else {
            $this->error(L('_ERROR_PLEASE_LOGIN_') . L('_EXCLAMATION_'));
        }
        foreach ($field_setting_list as &$val) {
            $map['field_id'] = $val['id'];
            $field = D('field')->where($map)->find();
            $val['field_content'] = $field;
            unset($map['field_id']);
            $info_list[$val['id']] = $this->_get_field_data($val);
            //当用户扩展资料为数组方式的处理@MingYangliu
            $vlaa = explode('|', $val['form_default_value']);
            $needle = ':';//判断是否包含a这个字符
            $tmparray = explode($needle, $vlaa[0]);
            if (count($tmparray) > 1) {
                foreach ($vlaa as $kye => $vlaas) {
                    if (count($tmparray) > 1) {
                        $vlab[] = explode(':', $vlaas);
                        foreach ($vlab as $key => $vlass) {
                            $items[$vlass[0]] = $vlass[1];
                        }
                    }
                    continue;
                }
                $info_list[$val['id']]['field_data'] = $items[$info_list[$val['id']]['field_data']];
            }
            //当扩展资料为join时，读取数据并进行处理再显示到前端@MingYang
            if ($val['child_form_type'] == "join") {
                $j = explode('|', $val['form_default_value']);
                $a = explode(' ', $info_list[$val['id']]['field_data']);
                $info_list[$val['id']]['field_data'] = get_userdata_join($a, $j[0], $j[1]);
            }
        }
        return $info_list;
    }

    public function _get_field_data($data = null)
    {
        $result = null;
        $result['field_name'] = $data['field_name'];
        $result['field_data'] = L('');
        switch ($data['form_type']) {
            case 'input':
            case 'radio':
            case 'textarea':
            case 'select':
                $result['field_data'] = isset($data['field_content']['field_data']) ? $data['field_content']['field_data'] : "还未设置";
                break;
            case 'checkbox':
                $result['field_data'] = isset($data['field_content']['field_data']) ? implode(' ', explode('|', $data['field_content']['field_data'])) : "还未设置";
                break;
            case 'time':
                $result['field_data'] = isset($data['field_content']['field_data']) ? date("Y-m-d", $data['field_content']['field_data']) : "还未设置";
                break;
        }
        $result['field_data'] = op_t($result['field_data']);
        return $result;
    }

    public function appList($uid = null, $page = 1, $tab = null)
    {
        $show_tab = get_kanban_config('UCENTER_KANBAN', 'enable', '', 'USERCONFIG');
        $menu = $this->_tab_menu();
        foreach ($show_tab as $v1) {
            foreach ($menu as $v2) {
                if (array_search($v1, $v2)) {
                    $arr3[$v1] = $v2;
                }
            }
        }
        unset($v1);
        unset($v2);
        $appArr = $arr3;

        if (!$appArr) {
            $this->redirect('Ucenter/Index/information', array('uid' => $uid));
        }

        $type = op_t($_GET['type']);
        if (!isset ($appArr [$type])) {
            $this->error(L('_ERROR_PARAM_') . L('_EXCLAMATION_') . L('_EXCLAMATION_'));
        }
        $this->assign('type', $type);
        $this->assign('module', $appArr[$type]['data-id']);
        $this->assign('page', $page);
        $this->assign('tab', $tab);

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $str_app = '{$appArr.' . $type . '.title|op_t}';
        $this->setTitle($str . L('_DE_PERSON_') . $str_app . L('_PAGE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_') . $str_app);
        $this->setDescription($str . L('_DE_PERSON_') . $str_app . L('_PAGE_'));
        //四处一词 seo end

        $this->display('index');
    }

    /**
     * 个人主页标签导航
     * @return void
     */
    public function _tab_menu()
    {
        $modules = D('Common/Module')->getAll();
        $apps = array();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/UcenterBlockWidget.class.php')) {
                    $apps[] = array('data-id' => $m['name'], 'title' => $m['alias'], 'sort' => $m['sort'], 'key' => strtolower($m['name']));
                }
            }
        }

        $show_tab = get_kanban_config('UCENTER_KANBAN', 'enable', '', 'USERCONFIG');
        $apps[] = array('data-id' => 'info', 'sort' => '0', 'title' => '资料', 'key' => 'info');
        $apps[] = array('data-id' => 'rank_title', 'sort' => '0', 'title' => L('_RANK_TITLE_'), 'key' => 'rank_title');
        $apps[] = array('data-id' => 'follow', 'sort' => '0', 'title' => L('_FOLLOWERS_NO_SPACE_') . '/粉丝', 'key' => 'follow');
        $apps[] = array('data-id' => 'topic_list', 'sort' => '0', 'title' => '关注的话题', 'key' => 'topic_list');

        $apps = $this->sortApps($apps);
        $apps = array_combine(array_column($apps, 'key'), $apps);
        foreach ($show_tab as $v1) {
            foreach ($apps as $v2) {
                if (array_search($v1, $v2)) {
                    $arr3[$v1] = $v2;
                }
            }
        }
        unset($v1);
        unset($v2);
        $this->assign('appArr', $arr3);
        return $apps;
    }


    public function _fans_and_following($uid = null)
    {
        $uid = isset($uid) ? $uid : is_login();
        //我的粉丝展示
        $map['follow_who'] = $uid;
        $fans_default = D('Follow')->where($map)->field('who_follow')->order('create_time desc')->limit(8)->select();
        $fans_totalCount = D('Follow')->where($map)->count();
        foreach ($fans_default as &$user) {
            $user['user'] = query_user(array('avatar64', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title', 'signature'), $user['who_follow']);
        }
        unset($user);
        $this->assign('fans_totalCount', $fans_totalCount);
        $this->assign('fans_default', $fans_default);

        //我关注的展示
        $map_follow['who_follow'] = $uid;
        $follow_default = D('Follow')->where($map_follow)->field('follow_who')->order('create_time desc')->limit(8)->select();
        $follow_totalCount = D('Follow')->where($map_follow)->count();
        foreach ($follow_default as &$user) {
            $user['user'] = query_user(array('avatar64', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title', 'signature'), $user['follow_who']);
        }
        unset($user);
        $this->assign('follow_totalCount', $follow_totalCount);
        $this->assign('follow_default', $follow_default);
    }

    public function fans($uid = null, $page = 1)
    {
        $uid = isset($uid) ? $uid : is_login();

        $this->assign('tab', 'fans');
        $fans = D('Follow')->getFans($uid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title', 'signature'), $totalCount);
        $this->assign('fans', $fans);
        $this->assign('totalCount', $totalCount);

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_FANS_TITLE_'));
        $this->setKeywords($str . L('_FANS_KEYWORDS_'));
        $this->setDescription($str . L('_FANS_TITLE_'));
        //四处一词 seo end

        $this->display();
    }

    public function following($uid = null, $page = 1)
    {
        $uid = isset($uid) ? $uid : is_login();

        $following = D('Follow')->getFollowing($uid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title', 'signature'), $totalCount);
        // dump($following);exit;
        $this->assign('following', $following);
        $this->assign('totalCount', $totalCount);
        $this->assign('tab', 'following');

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_FOLLOWING_TITLE_'));
        $this->setKeywords($str . L('_FOLLOWING_KEYWORDS_'));
        $this->setDescription($str . L('_FOLLOWING_DESC_'));
        //四处一词 seo end

        $this->display();
    }

    public function topicList($uid = null, $page = 1)
    {
        $uid = isset($uid) ? $uid : is_login();
        $topk = D('Weibo/TopicFollow')->page($page, 10)->order('create_time desc')->getMyTopic($uid);
        $totalCount = D('Weibo/TopicFollow')->where(array('uid' => $uid, 'status' => 1))->count();
        $this->assign('totalCount', $totalCount);

        $this->assign('topk', $topk);
        $this->assign('tab', 'topiclist');


        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . '关注的话题页');
        $this->setKeywords($str . '，微博话题');
        $this->setDescription($str . '关注的话题页');
        //四处一词 seo end

        $this->display();
    }

    public function ranking()
    {
        $uid = is_login();
        $aPage = 1;
        $limit = 100;
        $memberModel = D('Member');


        $tag = 'rank_list_ranking';
        $rankListTotal = S($tag);
        if ($rankListTotal === false) {
            $user_fans_list = $memberModel->where(array('status' => 1))->field('uid,fans,nickname')->order('fans desc,uid asc')->limit($limit)->select();
            foreach ($user_fans_list as $key => &$val) {
                $val['ranking'] = ($aPage - 1) * $limit + $key + 1;
                if ($val['ranking'] <= 3) {
                    $val['ranking'] = '<span class="ico-num' . $val['ranking'] . '">' . '</span>';
                } else {
                    $val['ranking'] = '<span class="num">' . $val['ranking'] . '</span>';
                }
            }
            foreach ($user_fans_list as &$u) {
                $temp_user = query_user(array('avatar32'), $u['uid']);
                $u['avatar32'] = $temp_user['avatar32'];
            }
            unset($u);


            $user_list = $memberModel->where(array('status' => 1))->field('uid,con_check,nickname')->order('con_check desc,uid asc')->limit($limit)->select();
            foreach ($user_list as $key => &$val) {
                $val['ranking'] = ($aPage - 1) * $limit + $key + 1;
                if ($val['ranking'] <= 3) {
                    $val['ranking'] = '<span class="ico-num' . $val['ranking'] . '">' . '</span>';
                } else {
                    $val['ranking'] = '<span class="num">' . $val['ranking'] . '</span>';
                }
            }

            foreach ($user_list as &$u) {
                $temp_user = query_user(array('avatar32'), $u['uid']);
                $u['avatar32'] = $temp_user['avatar32'];
            }
            unset($u);


            $user_total_list = $memberModel->where(array('status' => 1))->field('uid,total_check,nickname')->order('total_check desc,uid asc')->limit($limit)->select();
            foreach ($user_total_list as $key => &$val) {
                $val['ranking'] = ($aPage - 1) * $limit + $key + 1;
                if ($val['ranking'] <= 3) {
                    $val['ranking'] = '<span class="ico-num' . $val['ranking'] . '">' . '</span>';
                } else {
                    $val['ranking'] = '<span class="num">' . $val['ranking'] . '</span>';
                }
            }

            foreach ($user_total_list as &$u) {
                $temp_user = query_user(array('avatar32'), $u['uid']);
                $u['avatar32'] = $temp_user['avatar32'];
            }
            unset($u);


            $user_score_list = $memberModel->where(array('status' => 1))->field('uid,score1,nickname')->order('score1 desc,uid asc')->limit($limit)->select();
            foreach ($user_score_list as $key => &$val) {
                $val['ranking'] = ($aPage - 1) * $limit + $key + 1;
                if ($val['ranking'] <= 3) {
                    $val['ranking'] = '<span class="ico-num' . $val['ranking'] . '">' . '</span>';
                } else {
                    $val['ranking'] = '<span class="num">' . $val['ranking'] . '</span>';
                }
            }

            foreach ($user_score_list as &$u) {
                $temp_user = query_user(array('avatar32'), $u['uid']);
                $u['avatar32'] = $temp_user['avatar32'];
                $u['score1'] = round($u['score1']);
            }
            unset($u);


            $rankListTotal = array(
                'user_list' => $user_list,
                'user_total_list' => $user_total_list,
                'user_score_list' => $user_score_list,
                'user_fans_list' => $user_fans_list
            );
            S($tag, $rankListTotal, 36000);
        }

        //排行榜个人排名
        $userScore = $memberModel->where(array('uid' => $uid))->field('fans, con_check, total_check, score1')->find();

        $mapFans = array(
            'fans' => array('gt', $userScore['fans']),
            'status' => 1,
        );
        $fansRank = $memberModel->where($mapFans)->count();
        $fansRank += 1;

        $mapConCheck = array(
            'con_check' => array('gt', $userScore['con_check']),
            'status' => 1,
        );
        $conCheckRank = $memberModel->where($mapConCheck)->count();
        $conCheckRank += 1;

        $mapTotalCheck = array(
            'total_check' => array('gt', $userScore['total_check']),
            'status' => 1,
        );
        $totalCheckRank = $memberModel->where($mapTotalCheck)->count();
        $totalCheckRank += 1;
        
        $mapScore = array(
            'score1' => array('gt', $userScore['score1']),
            'status' => 1,
        );
        $scoreRank = $memberModel->where($mapScore)->count();
        $scoreRank += 1;

        $rankList = array(
            'fans_rank' => $fansRank,
            'con_check_rank' => $conCheckRank,
            'total_check_rank' => $totalCheckRank,
            'score_rank' => $scoreRank,
        );

        $rankSwitch = modC('RANK_LIST', 'fans,con_check,total_check,score', 'USERCONFIG');
        $rankSwitch = explode(',', $rankSwitch);

        $this->assign('rankSwitch', $rankSwitch);
        $this->assign($rankListTotal);
        $this->assign($rankList);
        $this->display();
    }

    public function rank($uid = null)
    {
        $uid = isset($uid) ? $uid : is_login();

        $rankList = D('rank_user')->where(array('uid' => $uid, 'status' => 1))->field('rank_id,reason,create_time')->select();
        foreach ($rankList as &$val) {
            $rank = D('rank')->where('id=' . $val['rank_id'])->find();
            $val['title'] = $rank['title'];
            $val['logo_url'] = get_pic_src(M('picture')->where('id=' . $rank['logo'])->field('path')->getField('path'));
            $val['label_content'] = $rank['label_content'];
            $val['label_bg'] = $rank['label_bg'];
            $val['label_color'] = $rank['label_color'];
        }
        unset($val);
        $this->assign('rankList', $rankList);
        $this->assign('tab', 'rank');

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_RANK__TITLE_'));
        $this->setKeywords($str . L('_RANK__KEYWORDS_'));
        $this->setDescription($str . L('_RANK__DESC_'));
        //四处一词 seo end

        $this->display('rank');
    }

    public function rankVerifyFailure()
    {
        $uid = isset($uid) ? $uid : is_login();

        $rankList = D('rank_user')->where(array('uid' => $uid, 'status' => -1))->field('id,rank_id,reason,create_time')->select();
        foreach ($rankList as &$val) {
            $rank = D('rank')->where('id=' . $val['rank_id'])->find();
            $val['title'] = $rank['title'];
            $val['logo_url'] = get_pic_src(M('picture')->where('id=' . $rank['logo'])->field('path')->getField('path'));
            $val['label_content'] = $rank['label_content'];
            $val['label_bg'] = $rank['label_bg'];
            $val['label_color'] = $rank['label_color'];
        }
        unset($val);
        $this->assign('rankList', $rankList);
        $this->assign('tab', 'rankVerifyFailure');

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_RANK_TITLE_'));
        $this->setKeywords($str . L('_RANK__KEYWORDS_'));
        $this->setDescription($str . L('_RANK_TITLE_'));
        //四处一词 seo end

        $this->display('rank');
    }

    public function rankVerifyWait()
    {
        $uid = isset($uid) ? $uid : is_login();

        $rankList = D('rank_user')->where(array('uid' => $uid, 'status' => 0))->field('rank_id,reason,create_time')->select();
        foreach ($rankList as &$val) {
            $rank = D('rank')->where('id=' . $val['rank_id'])->find();
            $val['title'] = $rank['title'];
            $val['logo_url'] = get_pic_src(M('picture')->where('id=' . $rank['logo'])->field('path')->getField('path'));
            $val['label_content'] = $rank['label_content'];
            $val['label_bg'] = $rank['label_bg'];
            $val['label_color'] = $rank['label_color'];
        }
        unset($val);
        $this->assign('rankList', $rankList);
        $this->assign('tab', 'rankVerifyWait');

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_RANK_TITLE_'));
        $this->setKeywords($str . L('_RANK__KEYWORDS_'));
        $this->setDescription($str . L('_RANK_TITLE_'));
        //四处一词 seo end

        $this->display('rank');
    }

    public function rankVerifyCancel($rank_id = null)
    {
        $rank_id = intval($rank_id);
        if (is_login() && $rank_id) {
            $map['rank_id'] = $rank_id;
            $map['uid'] = is_login();
            $map['status'] = 0;
            $result = D('rank_user')->where($map)->delete();
            if ($result) {
                D('Message')->sendMessageWithoutCheckSelf(is_login(), L('_MESSAGE_RANK_CANCEL_1_'), L('_MESSAGE_RANK_CANCEL_2_'), 'Ucenter/Message/message', array('tab' => 'system'));
                $this->success(L('_SUCCESS_CANCEL_'), U('Ucenter/Index/rankVerifyWait'));
            } else {
                $this->error(L('_FAIL_CANCEL_'));
            }
        }
    }

    public function rankVerify($rank_user_id = null)
    {
        $uid = isset($uid) ? $uid : is_login();

        $rank_user_id = intval($rank_user_id);
        $map_already['uid'] = $uid;
        //重新申请头衔
        if ($rank_user_id) {
            $model = D('rank_user')->where(array('id' => $rank_user_id));
            $old_rank_user = $model->field('id,rank_id,reason')->find();
            if (!$old_rank_user) {
                $this->error(L('_ERROR_RANK_RE_SELECT_'));
            }
            $this->assign('old_rank_user', $old_rank_user);
            $map_already['id'] = array('neq', $rank_user_id);
            D('Message')->sendMessageWithoutCheckSelf(is_login(), L(''), L(''), 'Ucenter/Message/message', array('tab' => 'system'));
        }
        $alreadyRank = D('rank_user')->where($map_already)->field('rank_id')->select();
        $alreadyRank = array_column($alreadyRank, 'rank_id');
        if ($alreadyRank) {
            $map['id'] = array('not in', $alreadyRank);
        }
        $map['types'] = 1;
        $rankList = D('rank')->where($map)->select();
        foreach ($rankList as &$rank) {
            $rank['logo_url'] = get_pic_src(M('picture')->where('id=' . $rank['logo'])->field('path')->getField('path'));
        }
        unset($rank);
        $this->assign('rankList', $rankList);
        $this->assign('tab', 'rankVerify');

        //四处一词 seo
        $str = '{$user_info.nickname|op_t}';
        $this->setTitle($str . L('_RANK_APPLY_TITLE_'));
        $this->setKeywords($str . L('_RANK_APPLY_KEYWORDS_'));
        $this->setDescription($str . L('_RANK_APPLY_TITLE_'));
        //四处一词 seo end

        $this->display('rank_verify');
    }

    public function verify($rank_id = null, $reason = null, $rank_user_id = 0)
    {
        $rank_id = intval($rank_id);
        $reason = op_t($reason);
        $rank_user_id = intval($rank_user_id);
        if (!$rank_id) {
            $this->error(L('_ERROR_RANK_SELECT_'));
        }
        if ($reason == null || $reason == '') {
            $this->error(L('_ERROR_RANK_REASON_'));
        }
        $data['rank_id'] = $rank_id;
        $data['reason'] = $reason;
        $data['uid'] = is_login();
        $data['is_show'] = 1;
        $data['create_time'] = time();
        $data['status'] = 0;
        if ($rank_user_id) {
            $model = D('rank_user')->where(array('id' => $rank_user_id));
            if (!$model->select()) {
                $this->error(L('_ERROR_RANK_RE_SELECT_'));
            }
            $result = D('rank_user')->where(array('id' => $rank_user_id))->save($data);
        } else {
            $result = D('rank_user')->add($data);
        }
        if ($result) {
            D('Message')->sendMessageWithoutCheckSelf(is_login(), L('_MESSAGE_RANK_APPLY_1_'), L('_MESSAGE_RANK_APPLY_2_'), 'Ucenter/Message/message', array('tab' => 'system'));
            $this->success(L('_SUCCESS_RANK_APPLY_'), U('Ucenter/Index/rankVerify'));
        } else {
            $this->error(L('_FAIL_RANK_APPLY_'));
        }
    }

    /**
     * @param $apps
     * @param $vals
     * @return mixed
     * @auth 陈一枭
     */
    private function sortApps($apps)
    {
        return $this->multi_array_sort($apps, 'sort', SORT_DESC);
    }

    function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
    {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

}