<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
class WeibaAction extends BaseAction
{
    // 首页
    public function index()
    {
        $indexList = D('weiba_post')->where('is_del=0 AND is_index=1')->order('is_index_time desc')->findAll();
        $this->assign('indexList', $indexList);
        $order = '`top` desc,FIELD(recommend+digest,0,1,2) desc,last_reply_time desc';
        $map['is_del'] = 0;
        $map['is_index'] = 0;
        $list = D('weiba_post')->where($map)->order($order)->limit(20)->select();
        $this->assign('list', $list);

        // 推荐微吧
        if (!($weibalist = S('rec_weibalist'))) {
            $weibalist = D('weiba')->where('is_del=0 and status=1')->order('recommend desc,follower_count desc,thread_count')->limit(4)->select();
            S('rec_weibalist', $weibalist);
        }
        $this->assign('weibalist', $weibalist);
        $this->display();
    }

    public function forum()
    {
        if (!($weibacate = S('weiba_cate_list'))) {
            $weibacate = M('weiba_category')->order('id')->findAll();
            foreach ($weibacate as &$val) {
                $val['weibalist'] = D('weiba')->where("cid={$val['id']} AND is_del=0 and status=1")->order('recommend desc,follower_count desc,thread_count')->findAll();
            }
            S('weiba_cate_list', $weibacate);
        }
        $this->assign('weibacate', $weibacate);
        $this->display();
    }
    public function detail()
    {
        $weiba_id = intval($_GET ['weiba_id']);
        $weiba_detail = $this->_top_link($weiba_id);
        //吧主
        $map['weiba_id'] = $weiba_id;
        $map['level'] = array('in', '2,3');
        $weiba_master = D('weiba_follow')->where($map)->order('level desc,id')->field('follower_uid,level')->findAll();
        $this->assign('weiba_master', $weiba_master);

        // 帖子
        $maps ['is_del'] = 0;

        if ($_GET ['order'] == '1') {
            $order = 'top desc,post_time desc';
            $this->assign('order', 'post_time');
        } else {
            $order = 'top desc,last_reply_time desc';
            $this->assign('order', 'reply_time');
        }
        $maps ['weiba_id'] = $weiba_id;

        $post_ids = array();
        if ($_GET ['type'] != 'digest' && !($_REQUEST ['p'] > 1)) { // 列表第一页加上全局置顶的帖子
            $map2 ['top'] = 2;
            $map2 ['is_del'] = 0;
            $topPostList = D('weiba_post', 'weiba')->where($map2)->order('post_time desc')->findAll(); // 全局置顶

            $map2 ['top'] = 1;
            $map2 ['weiba_id'] = $weiba_id;
            if (!empty($topPostList)) {
                $post_ids1 = (array) getSubByKey($topPostList, 'post_id');
                $map2 ['post_id'] = array(
                        'not in',
                        $post_ids1,
                );
            }

            $innerTop = D('weiba_post', 'weiba')->where($map2)->order('post_time desc')->findAll();
            if (!empty($innerTop)) {
                $post_ids2 = (array) getSubByKey($innerTop, 'post_id');
            }
            $post_ids = array_merge((array) $post_ids1, (array) $post_ids2);
        }
        empty($post_ids) || $maps ['post_id'] = array(
                'not in',
                $post_ids,
        );
        if ($_GET['type'] == 'digest') {
            $maps['digest'] = 1;
        }
        $list = D('weiba_post', 'weiba')->where($maps)->order($order)->findpage(20, false, array(), true); //print_r($list);
        !$topPostList && $topPostList = array();
        !$innerTop && $innerTop = array();
        !$list ['data'] && $list ['data'] = array();
        $list ['data'] = array_merge($topPostList, $innerTop, $list ['data']);

        $post_uids = getSubByKey($list ['data'], 'post_uid');
        $reply_uids = getSubByKey($list ['data'], 'last_reply_uid');
        $uids = array_unique(array_filter(array_merge($post_uids, $reply_uids)));
        $this->_assignUserInfo($uids);

        $this->_assignFollowState($weiba_id);
        // dump($list);
        $this->assign('list', $list);
        $this->assign('weiba_detail', $weiba_detail);
        //dump($weiba_detail);exit;
        $this->assign('weiba_name', $weiba_detail ['weiba_name']);
        $this->assign('weiba_id', $weiba_id);

        $this->assign('type', $_GET['type'] == 'digest' ? 'digest' : 'all');
        $this->display();
    }
    public function postDetail()
    {
        $post_id = intval($_GET ['post_id']);
        $post_detail = D('weiba_post')->where('is_del=0 and post_id='.$post_id)->find();
        $weiba_detail = $this->_top_link($post_detail ['weiba_id'], true);
        if (!$post_detail || $weiba_detail ['is_del']) {
            $this->error('帖子不存在或已被删除');
        }
        if (D('weiba_favorite')->where('uid='.$this->mid.' AND post_id='.$post_id)->find()) {
            $post_detail ['favorite'] = 1;
        }
        if ($post_detail ['attach']) {
            $attachids = unserialize($post_detail ['attach']);
            $attachinfo = model('Attach')->getAttachByIds($attachids);
            foreach ($attachinfo as $ak => $av) {
                $_attach = array(
                        'attach_id' => $av ['attach_id'],
                        'attach_name' => $av ['name'],
                        'attach_url' => getImageUrl($av ['save_path'].$av ['save_name']),
                        'extension' => $av ['extension'],
                        'size' => $av ['size'],
                );
                $post_detail ['attachInfo'] [$ak] = $_attach;
            }
        }

        $post_detail ['content'] = html_entity_decode($post_detail ['content'], ENT_QUOTES, 'UTF-8');

        $this->assign('post_detail', $post_detail);
        // dump($post_detail);
        D('weiba_post')->where('post_id='.$post_id)->setInc('read_count');
        $weiba_name = $weiba_detail ['weiba_name'];
        $this->assign('weiba_id', $post_detail ['weiba_id']);
        $this->assign('weiba_name', $weiba_name);
        // 获得圈主uid
        $map ['weiba_id'] = $post_detail ['weiba_id'];
        $map ['level'] = array(
                'in',
                '2,3',
        );
        $weiba_admin = getSubByKey(D('weiba_follow')->where($map)->order('level desc')->field('follower_uid')->findAll(), 'follower_uid');
        $weiba_manage = false;
        if (CheckWeibaPermission($weiba_admin, 0, 'weiba_global_top') || CheckWeibaPermission($weiba_admin, 0, 'weiba_top') || CheckWeibaPermission($weiba_admin, 0, 'weiba_recommend') || CheckWeibaPermission($weiba_admin, 0, 'weiba_edit') || CheckWeibaPermission($weiba_admin, 0, 'weiba_del')) {
            $weiba_manage = true;
        }
        $this->assign('weiba_manage', $weiba_manage);
        $this->assign('weiba_admin', $weiba_admin);

        $this->assign('nav', 'weibadetail');
        // $this->user_group ( $post_detail ['post_uid'] );

        // 帖子点评
        unset($map);
        $map ['tid'] = $post_id;
        $postcomment = M('weiba_postcomment')->where($map)->order('id desc')->findPage(5);
        $this->assign('postcomment', $postcomment);

        // $this->_assignFollowUidState ( array (
        // $post_detail ['post_uid']
        // ) );

        $this->setTitle($post_detail ['title'].' - '.$this->site ['site_name']);
        $this->setDescription(getShort(t(bbcode($post_detail ['content'])), 200));
        $this->assign('type', $_GET['type'] == 'digg' ? 'digg' : 'time');
        $this->display();
    }
    // 面包屑
    public function _top_link($weiba_id, $detail = false)
    {
        $weiba_detail = D('weiba', 'weiba')->where('is_del=0 and status=1 and weiba_id='.$weiba_id)->find();
        if (!$weiba_detail) {
            $this->error('该版块不存在或已被删除');
        }
        $weiba_detail['logo'] = $weiba_detail['avatar_big'];
        $detail && $this->assign('weiba_detail', $weiba_detail);

        $cate = M('weiba_category')->where("id='$weiba_detail[cid]'")->find();
        $this->assign('category', $cate);
        $this->assign('cate', $cate['name']);

        return $weiba_detail;
    }
    public function _assignUserInfo($uids)
    {
        !is_array($uids) && $uids = explode(',', $uids);
        //var_dump($uids);
        $user_info = model('User')->getUserInfoByUids($uids);
        $this->assign('user_info', $user_info);
        // dump($user_info);exit;
    }

    /**
     * 发布帖子.
     */
    public function post()
    {
        $this->need_login();

        if (!CheckPermission('weiba_normal', 'weiba_post')) {
            $this->error('对不起，您没有权限进行该操作！');
        }
        $weiba_id = intval($_GET ['weiba_id']);
        $weiba = D('weiba')->where('weiba_id='.$weiba_id)->find();
        if ($weiba) {
            $this->assign('weiba_id', $weiba_id);
            $this->assign('weiba_name', $weiba ['weiba_name']);
            $this->assign('weiba', $weiba);
        } else {
            $weibacate = M('weiba_category')->order('id')->findAll();
            foreach ($weibacate as &$val) {
                $val['weibalist'] = D('weiba')->where("cid={$val['id']} AND is_del=0 and status=1")->order('recommend desc,follower_count desc,thread_count')->findAll();
            }
            $this->assign('weibacate', $weibacate);
        }

        $this->display();
    }

    /**
     * 执行发布帖子.
     */
    public function doPost()
    {
        $this->need_login();

        if (!CheckPermission('weiba_normal', 'weiba_post')) {
            $this->error('对不起，您没有权限进行该操作！', true);
        }
        $weibaid = intval($_POST ['weiba_id']);
        if (!$weibaid) {
            $this->error('请选择微吧！', true);
        }
        $weiba = D('weiba', 'weiba')->where('weiba_id='.$weibaid)->find();
        if (!CheckPermission('core_admin', 'admin_login')) {
            switch ($weiba ['who_can_post']) {
                case 1:
                    $map ['weiba_id'] = $weibaid;
                    $map ['follower_uid'] = $this->mid;
                    $res = D('weiba_follow')->where($map)->find();
                    if (!$res && !CheckPermission('core_admin', 'admin_login')) {
                        $this->error('对不起，您没有发帖权限，请关注该微吧！', true);
                    }
                    break;
                case 2:
                    $map ['weiba_id'] = $weibaid;
                    $map ['level'] = array(
                            'in',
                            '2,3',
                    );
                    $weiba_admin = D('weiba_follow')->where($map)->order('level desc')->field('follower_uid')->findAll();
                    if (!in_array($this->mid, getSubByKey($weiba_admin, 'follower_uid')) && !CheckPermission('core_admin', 'admin_login')) {
                        $this->error('对不起，您没有发帖权限，仅限管理员发帖！', true);
                    }
                    break;
                case 3:
                    $map ['weiba_id'] = $weibaid;
                    $map ['level'] = 3;
                    $weiba_admin = D('weiba_follow')->where($map)->order('level desc')->field('follower_uid')->find();
                    if ($this->mid != $weiba_admin ['follower_uid'] && !CheckPermission('core_admin', 'admin_login')) {
                        $this->error('对不起，您没有发帖权限，仅限圈主发帖！', true);
                    }
                    break;
            }
        }

        $checkContent = str_replace('&nbsp;', '', $_POST ['content']);
        $checkContent = str_replace('<br />', '', $checkContent);
        $checkContent = str_replace('<p>', '', $checkContent);
        $checkContent = str_replace('</p>', '', $checkContent);
        $checkContents = preg_replace('/<img(.*?)src=/i', 'img', $checkContent);
        $checkContents = preg_replace('/<embed(.*?)src=/i', 'img', $checkContents);
        if (strlen(t($_POST ['title'])) == 0) {
            $this->error('帖子标题不能为空', true);
        }
        if (strlen(t($checkContents)) == 0) {
            $this->error('帖子内容不能为空', true);
        }
        preg_match_all('/./us', t($_POST ['title']), $match);
        if (count($match [0]) > 30) { // 汉字和字母都为一个字
            $this->error('帖子标题不能超过30个字', true);
        }
        if ($_POST ['attach_ids']) {
            $attach = explode('|', $_POST ['attach_ids']);
            foreach ($attach as $k => $a) {
                if (!$a) {
                    unset($attach [$k]);
                }
            }
            $attach = array_map('intval', $attach);
            $data ['attach'] = serialize($attach);
        }
        $data ['weiba_id'] = $weibaid;
        $data ['title'] = t($_POST ['title']);
        $data ['content'] = h($_POST ['content']);
        $data ['post_uid'] = $this->mid;
        $data ['post_time'] = time();
        $data ['last_reply_uid'] = $this->mid;
        $data ['last_reply_time'] = $data ['post_time'];
        $data ['tag_id'] = intval($_POST ['tag_id']);
        $imgIds = explode(',', $_POST['imageIds']);
        foreach ($imgIds as $imgId) {
            $imgId = intval($imgId);
            if ($imgId > 0) {
                $imgsrc = getImageUrlByAttachId($imgId);
                if ($imgsrc) {
                    $data ['content'] .= '<p><img src="'.$imgsrc.'" /></p>';
                }
            }
        }
        $res = D('weiba_post')->add($data);
        if ($res) {
            refreshWeibaCount($data ['weiba_id']);
            // D('weiba')->where('weiba_id='.$data['weiba_id'])->setInc('thread_count');

            // 同步到分享
            $feed_id = D('weibaPost', 'weiba')->syncToFeed($res, $data ['title'], t($checkContent), $this->mid);
            D('weiba_post')->where('post_id='.$res)->setField('feed_id', $feed_id);
            // $this->assign('jumpUrl', U('weiba/Index/postDetail',array('post_id'=>$res)));
            // $this->success('发布成功');

            // 添加积分
            model('Credit')->setUserCredit($this->mid, 'publish_topic');

            $map ['post_uid'] = $this->mid;
            $map ['is_del'] = 0;
            $digest_count = intval(M('weiba_post')->where($map)->count());
            model('UserData')->setKeyValue($this->mid, 'post_count', $digest_count);

            return $this->ajaxReturn($res, '发布成功', 1);
        } else {
            $this->error('发布失败', true);
        }
    }
    public function getTags($weiba_id)
    {
        $map ['weiba_id'] = $weiba_id;

        $tag_list = M('weiba_tag')->where($map)->order('sort asc')->findAll();
        $this->assign('tag_list', $tag_list);

        return $tag_list;
    }
    public function getWeibaByAjax()
    {
        $map ['is_del'] = 0;
        $map ['cid'] = intval($_POST ['cid']);
        $list = D('Weiba', 'weiba')->where($map)->field('weiba_id,weiba_name,cid')->findAll();

        if (empty($list)) {
            echo '';
            exit();
        }

        $html = '<option value="0">请选择子版块</option>';
        foreach ($list as $vo) {
            $html .= '<option value="'.$vo ['weiba_id'].'">'.$vo ['weiba_name'];
        }

        echo $html;
    }
    public function getTagsByAjax()
    {
        $weiba_id = intval($_POST ['weibaid']);

        $list = $this->getTags($weiba_id);
        if (empty($list)) {
            echo '';
            exit();
        }

        $html = '<select name="tag_id">';
        foreach ($list as $vo) {
            $html .= '<option value="'.$vo ['tag_id'].'">'.$vo ['name'];
        }
        $html .= '</select>	';

        echo $html;
    }
    /**
     * 收藏帖子.
     */
    public function favorite()
    {
        $data ['post_id'] = intval($_POST ['post_id']);
        $data ['weiba_id'] = intval($_POST ['weiba_id']);
        $data ['post_uid'] = intval($_POST ['post_uid']);
        $data ['uid'] = $this->mid;
        $data ['favorite_time'] = time();
        if (D('weiba_favorite')->add($data)) {

            // 添加积分
            model('Credit')->setUserCredit($this->mid, 'collect_topic');
            model('Credit')->setUserCredit($data ['post_uid'], 'collected_topic');

            model('UserData')->setCountByStep($data ['uid'], 'favorite_count');
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 取消收藏帖子.
     */
    public function unfavorite()
    {
        $map ['post_id'] = intval($_POST ['post_id']);
        $map ['uid'] = $this->mid;
        if (D('weiba_favorite')->where($map)->delete()) {
            model('UserData')->setCountByStep($map ['uid'], 'favorite_count', -1);
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     *我的.
     */
    public function my()
    {
        $weiba_arr = getSubByKey(D('weiba', 'weiba')->where('is_del=0 and status=1')->field('weiba_id')->findAll(), 'weiba_id');  //未删除且通过审核的微吧
        $map['weiba_id'] = array('in', $weiba_arr);
        $map['is_del'] = 0;
        $type = in_array(t($_GET['type']), array('myPost', 'myReply', 'myWeiba', 'myFavorite', 'myFollowing')) ? t($_GET['type']) : 'index';
        switch ($type) {
            case 'myPost':
                $map['post_uid'] = $this->mid;
                $post_list = D('weiba_post', 'weiba')->where($map)->order('post_time desc')->findpage(20);
                break;
            case 'myReply':
                $myreply = D('weiba_reply', 'weiba')->where('uid='.$this->mid)->order('ctime desc')->field('post_id')->findAll();
                $map['post_id'] = array('in', array_unique(getSubByKey($myreply, 'post_id')));
                $post_list = D('weiba_post', 'weiba')->where($map)->order('last_reply_time desc')->findpage(20);
                break;
            case 'myFavorite':
                $myFavorite = D('weiba_favorite', 'weiba')->where('uid='.$this->mid)->order('favorite_time desc')->findAll();
                $map['post_id'] = array('in', getSubByKey($myFavorite, 'post_id'));
                $post_list = D('weiba_post', 'weiba')->where($map)->order('post_time desc')->findpage(20);
                break;
            case 'myWeiba':
                $sfollow = D('weiba_follow', 'weiba')->where('follower_uid='.$this->mid)->findAll();
                $sfollow = getSubByKey($sfollow, 'weiba_id');
                $map['weiba_id'] = array('in', $sfollow);
                $map['status'] = 1;
                //dump($map);
                $post_list = D('weiba', 'weiba')->where($map)->order('new_day desc, new_count desc ,recommend desc,follower_count desc,thread_count desc')->findpage(100);
                //dump($post_list);exit;
                break;
            case 'myFollowing':
                $myFollow_arr = getSubByKey(D('weiba_follow', 'weiba')->where('follower_uid='.$this->mid)->findAll(), 'weiba_id');
                foreach ($myFollow_arr as $v) {
                    if (in_array($v, $weiba_arr)) {
                        $weibas[] = $v;
                    }
                }
                $map['weiba_id'] = array('in', $weibas);
                $post_list = D('weiba_post', 'weiba')->where($map)->order('last_reply_time desc')->findpage(20);
                break;
        }
        // if($postList['nowPage']==1){  //列表第一页加上全局置顶的帖子
        // 	$topPostList = D('weiba_post')->where('top=2 and is_del=0')->order('post_time desc')->findAll();
        // 	!$topPostList && $topPostList = array();
        // 	!$postList['data'] && $postList['data'] = array();
        // 	$postList['data'] = array_merge($topPostList,$postList['data']);
        // }
        $weiba_ids = getSubByKey($post_list['data'], 'weiba_id');
        $nameArr = $this->_getWeibaName($weiba_ids);
        foreach ($post_list['data'] as $k => $v) {
            $post_list['data'][$k]['weiba'] = $nameArr[$v['weiba_id']];
            $post_list['data'][$k]['user'] = model('User')->getUserInfo($v['post_uid']);
            $post_list['data'][$k]['replyuser'] = model('User')->getUserInfo($v['last_reply_uid']);
            // $images = matchImages($v['content']);
            // $images[0] && $post_list['data'][$k]['image'] = array_slice( $images , 0 , 5 );
            $image = getEditorImages($v['content']);
            !empty($image) && $post_list['data'][$k]['image'] = array($image);
        }
        $this->assign('post_list', $post_list);
        $this->assign('type', $type);
        $this->assign('nav', 'myweiba');
//print_r($post_list);
        $this->setTitle('我的-微吧');
        $this->setKeywords('微吧');
        $this->display();
    }

    /**
     * 关注微吧.
     */
    public function doFollowWeiba()
    {
        $res = D('weiba', 'weiba')->doFollowWeiba($this->mid, intval($_REQUEST['weiba_id']));
        //清理插件缓存
        $key = '_getRelatedGroup_'.$this->mid.'_'.date('Ymd'); //达人
        S($key, null);
        $this->ajaxReturn($res, D('weiba', 'weiba')->getError(), false !== $res);
    }

    /**
     * 取消关注微吧.
     */
    public function unFollowWeiba()
    {
        $res = D('weiba', 'weiba')->unFollowWeiba($this->mid, intval($_GET['weiba_id']));
        $this->ajaxReturn($res, D('weiba', 'weiba')->getError(), false !== $res);
    }

    private function _getWeibaInfo(&$post_list)
    {
        //读取微吧详细信息
        $weiba_ids = getSubByKey($post_list['data'], 'weiba_id');
        $nameArr = $this->_getWeibaName($weiba_ids);
        foreach ($post_list['data'] as $k => $v) {
            $post_list['data'][$k]['weiba'] = $nameArr[$v['weiba_id']];
            $post_list['data'][$k]['user'] = model('User')->getUserInfo($v['post_uid']);
            $post_list['data'][$k]['replyuser'] = model('User')->getUserInfo($v['last_reply_uid']);
            // $images = matchImages($v['content']);
            // $images[0] && $post_list['data'][$k]['image'] = array_slice( $images , 0 , 5 );
            $image = getEditorImages($v['content']);
            !empty($image) && $post_list['data'][$k]['image'] = array($image);
        }
    }

    //获取微吧名称
    private function _getWeibaName($weiba_ids)
    {
        $weiba_ids = array_unique($weiba_ids);
        if (empty($weiba_ids)) {
            return false;
        }
        $map['weiba_id'] = array('in', $weiba_ids);
        $names = D('weiba')->where($map)->field('weiba_id,weiba_name')->findAll();
        foreach ($names as $n) {
            $nameArr[$n['weiba_id']] = $n['weiba_name'];
        }

        return $nameArr;
    }

    /**
     * 获取uid与微吧的关注状态
     */
    private function _assignFollowState($weiba_ids)
    {
        // 批量获取uid与微吧的关注状态
        $follow_state = D('weiba', 'weiba')->getFollowStateByWeibaids($this->mid, $weiba_ids);
        $this->assign('follow_state', $follow_state);
    }

    public function reply()
    {
        $post_id = intval($_GET['post_id']);
        $map['post_id'] = $post_id;
        $map['lock'] = 0;
        $map['is_del'] = 0;
        $post = D('weiba_post', 'weiba')->where($map)->find();
        if (!$post) {
            exit('帖子不存在！');
        }
        if (!empty($_GET['to_reply_id'])) {
            $reply_id = intval($_GET['to_reply_id']);
            $map = array();
            $map['reply_id'] = $reply_id;
            $map['is_del'] = 0;
            $reply = D('weiba_reply', 'weiba')->where($map)->find();
            if ($reply) {
                $this->assign('reply', $reply);
            }
        }
        $list_count = D('weiba_reply', 'weiba')->where(array('post_id' => $post_id))->count();
        $this->assign('list_count', $list_count);
        $this->assign('post', $post);
        $this->display();
    }
}
