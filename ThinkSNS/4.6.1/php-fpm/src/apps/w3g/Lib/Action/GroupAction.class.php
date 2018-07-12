<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
class GroupAction extends BaseAction
{
    public $gid;
    public $groupinfo;
    public $config;
    public $group;
    public $ismember;
    public $isadmin;
    public $topic;
    public $post;
    public $member;
    public function _initialize()
    {
        parent::_initialize();
        $this->member = D('Member');
        // 基本配置
        $this->config = model('Xdata')->lget('group');
        $this->assign('config', $this->config);

        $this->topic = D('Topic', 'group');
        $this->post = D('post');

        $this->gid = intval($_REQUEST ['gid']);
        $this->assign('gid', $this->gid);

        if ($this->gid) {
            $groupinfo = D('group')->where('id='.$this->gid.' AND is_del=0')->find();
            if (!$groupinfo) {
                $jumpUrl = U('w3g/Group/index');
                $this->error('该微吧不存在，或者被删除', 3, $jumpUrl);
            }
            // 关闭群分享时，自动跳转到群帖子页面；如果群帖子也没开启，自动跳转到群成员页面
            if ($groupinfo ['openWeibo'] == 0 && $groupinfo ['openBlog'] == 1 && ACTION_NAME == 'detail') {
                redirect(U('w3g/Group/topic', array(
                        'gid' => $this->gid,
                )));
                // 如果群帖子也没开启，自动跳转到群成员页面
            } elseif ($groupinfo ['openWeibo'] == 0 && $groupinfo ['openBlog'] == 0) {
                $jumpUrl = U('w3g/Group/index');
                $this->error('该微吧已关闭', 3, $jumpUrl);
            }

            $groupinfo ['cname0'] = D('Category', 'group')->getField('title', array(
                    'id' => $groupinfo ['cid0'],
            ));
            $groupinfo ['cname1'] = D('Category', 'group')->getField('title', array(
                    'id' => $groupinfo ['cid1'],
            ));
            $groupinfo ['type_name'] = $groupinfo ['brower_level'] == -1 ? '公开' : '私密';
            $groupinfo ['tags'] = D('GroupTag', 'group')->getGroupTagList($this->gid);
            $groupinfo ['openUploadFile'] = (model('Xdata')->get('group:uploadFile')) ? $groupinfo ['openUploadFile'] : 0;
            $groupinfo ['path'] = D('Category', 'group')->getPathWithCateId($groupinfo ['cid1']);
            if (!$groupinfo ['path']) {
                $groupinfo ['path'] = D('Category', 'group')->getPathWithCateId($groupinfo ['cid0']);
            }
            $groupinfo ['path'] = implode(' - ', $groupinfo ['path']);
            $groupinfo ['logourl'] = logo_path_to_url($groupinfo ['logo'], 750, 370);

            // dump ( $groupinfo );
            $this->groupinfo = $groupinfo;
            $this->assign('groupinfo', $groupinfo);

            if ($this->mid) {
                // 判读当前用户的成员状态
                $member_info = M('group_member')->where("uid={$this->mid} AND gid={$this->gid}")->find();
                if ($member_info) {
                    if ($member_info ['level'] > 0) {
                        $this->ismember = 1;
                        $this->assign('ismember', $this->ismember);
                        if ($member_info ['level'] == 1 || $member_info ['level'] == 2) {
                            $this->isadmin = 1;
                            $this->assign('isadmin', $this->isadmin);
                        }
                        // 记录访问时间
                        M('group_member')->where('gid='.$this->gid." AND uid={$this->mid}")->setField('mtime', time());
                    } else {
                        $need_join_audit;
                        $this->assign('need_join_audit', 1);
                    }
                }
            }

            // 浏览权限
            if (!$this->ismember) {
                // 邀请加入
                if (M('group_invite_verify')->where("gid={$this->gid} AND uid={$this->mid} AND is_used=0")->find()) {
                    $this->is_invited = 1;
                    $this->assign('is_invited', $this->is_invited);
                }
                if ($groupinfo ['brower_level'] == 1) {
                    if (ACTION_NAME == 'PostFeed') {
                        $return = array(
                                'status' => 0,
                                'data' => '抱歉，您不是该圈成员',
                        );
                        exit(json_encode($return));
                    }
                    if (MODULE_NAME != 'Group' || (ACTION_NAME != 'index' && ACTION_NAME != 'joinGroup')) {
                        if (ACTION_NAME != 'loadmore') {
                            $this->redirect('w3g/Group/index', array(
                                    'gid' => $this->gid,
                            ));
                        } else {
                            exit();
                        }
                    }
                }
            }
        }
        $this->assign('groupinfo', $this->groupinfo);
        $this->assign('ismember', $this->ismember);
    }
    // 首页
    public function index()
    {
        //$var ['group_list_recommend'] = D ( 'group', 'group' )->where ( 'is_del=0 and recommend=1' )->order ( 'sort asc' )->limit ( 4 )->findAll ();
        $var ['hot_group_list'] = D('group')->where('is_del=0 and hot=1')->order('sort asc')->limit(4)->findAll();
        $this->assign($var);
        // dump ( $var );

        // 微吧分类
        $list = D('Category', 'group')->where('pid=0')->order('pid')->findAll();

        $group_list = D('group')->where('is_del=0')->order('ctime desc')->findAll();
        foreach ($group_list as $vo) {
            if (count($group_arr [$vo ['cid0']]) < 4) {
                $group_arr [$vo ['cid0']] [$vo ['id']] = $vo;
            }
        }
        foreach ($list as &$v) {
            $v ['group_list'] = $group_arr [$v ['id']];
        }

        $this->assign('catelist', $list);
        // dump ( $list );

        $this->display();
    }
    public function lists()
    {
        // 微吧分类
        $cate_list = D('Category', 'group')->where('pid=0')->order('pid')->findAll();
        $this->assign('catelist', $cate_list);

        $cate = intval($_GET ['cate']);

        $map ['is_del'] = 0;
        if ($cate == -1) {
            $cate_title = '推荐微吧';
            $map ['recommend'] = 1;
            $list = D('group')->where($map)->order('sort asc')->findPage(10);
        } elseif ($cate == -2) {
            $cate_title = '热门微吧';
            $map ['hot'] = 1;
            $list = D('group')->where($map)->order('sort asc')->findPage(10);
        } else {
            foreach ($cate_list as $c) {
                if ($c ['id'] == $cate) {
                    $cate_title = $c['title'];
                    break;
                }
            }
            $map ['cid0'] = $cate;
            $list = D('group')->where($map)->order('sort asc')->findPage(10);
        }
        $this->assign('list', $list);
        $this->assign('cate_title', $cate_title);

        $this->display();
    }
    public function my()
    {
        $this->need_login();

        // $group_list = D ( 'Group', 'group' )->getAllMyGroup ( $this->uid, 1 );
        $group_manage = D('group')->mymanagegroup($this->uid, 1);
        $this->_getGroupInfo($group_manage);
        $group_join = D('group')->myjoingroup($this->uid, 1);
        $this->_getGroupInfo($group_join);
        $db_prefix = C('DB_PREFIX');
        $group_list = D('group')->field('g.id,g.name,g.type,g.membercount,g.logo,g.cid0,g.ctime,g.status')
                        ->table("{$db_prefix}group AS g LEFT JOIN {$db_prefix}user_follow AS f ON f.uid={$this->uid} AND g.uid=f.fid")
                        ->where('g.status=1 AND g.is_del=0 AND f.fid<>\'\'')
                        ->findPage();
        $this->_getGroupInfo($group_list);
        //dump ( $group_list );exit;
        $this->assign('group_manage', $group_manage);
        $this->assign('group_join', $group_join);
        $this->assign('group_list', $group_list);
        $this->display();
    }
    public function detail()
    {
        $d ['feed_type'] = t($_REQUEST ['feed_type']) ? t($_REQUEST ['feed_type']) : '';
        $d ['feed_key'] = t($_REQUEST ['feed_key']) ? t($_REQUEST ['feed_key']) : '';
        switch ($d['feed_type']) {
            case '':
            case 'all':
                $d['feed_type_name'] = '全部';
                break;
            case 'post':
                $d['feed_type_name'] = '原创';
            break;
            case 'repost':
                $d['feed_type_name'] = '转发';
            break;
            case 'postimage':
                $d['feed_type_name'] = '图片';
            break;
            case 'postvideo':
                $d['feed_type_name'] = '视频';
            break;
            default:
                $d['feed_type_name'] = '全部';
                break;
        }

        $this->assign($d);
        // echo $this->ismember;
        // dump($this->groupinfo);exit;

        // dump($groupinfo);
        // exit;
        $this->display();
    }
    public function topic()
    {
        // 判断功能是否开启
        if (!$this->groupinfo ['openBlog']) {
            $jumpUrl = U('w3g/Group/index', array(
                    'gid' => $this->gid,
            ));
            $this->error('帖子功能已关闭', 3, $jumpUrl);
        }

        $topiclist = $this->topic->order('top DESC,replytime DESC')->where('is_del=0 AND gid='.$this->gid)->findPage();
        // dump ( $topiclist );
        $this->assign('topiclist', $topiclist);

        $this->display();
    }
    public function topicDetail()
    {
        $tid = intval($_GET ['tid']) > 0 ? $_GET ['tid'] : 0;

        if ($tid == 0) {
            $this->error('参数错误');
        }
        $limit = 20;

        $this->topic->setInc('viewcount', 'id='.$tid);
        $thread = $this->topic->getThread($tid); // 获取主题
                                                    // 判读帖子存不存在
        if (!$thread) {
            $jumpUrl = U('w3g/Group/detail', array(
                    'gid' => $this->gid,
            ));
            $this->error('帖子不存在');
        }
        // 帖子的分类
        $thread ['ctitle'] = M('group_topic_category')->getField('title', "id={$thread['cid']} AND gid={$this->gid}");
        $thread ['ctitle'] = $thread ['ctitle'] ? "[{$thread['ctitle']}]" : '';

        // 附件信息
        if ($thread ['attach']) {
            $_attach_map ['id'] = array(
                    'IN',
                    unserialize($thread ['attach']),
            );
            $thread ['attach'] = D('Dir', 'group')->field('id,name,note,is_del')->where($_attach_map)->findAll();
        }
        if (!empty($thread ['image_ids'])) {
            $thread ['attachIds'] = explode(',', $thread ['image_ids']);

            $attachInfo = model('Attach')->getAttachByIds($thread ['attachIds']);
            foreach ($attachInfo as $var) {
                $src = getImageUrl($var ['save_path'].$var ['save_name'], 250, 250, true);
                if ($src) {
                    $thread ['content'] .= '<br/><p><img src="'.$src.'" /></p>';
                }
            }
        }
        $postlist = $this->post->where('is_del = 0 AND istopic=0 AND tid='.$tid)->findPage($limit);
        foreach ($postlist ['data'] as &$vo) {
            if (!empty($vo ['image_ids'])) {
                $vo ['attachIds'] = explode(',', $vo ['image_ids']);

                $attachInfo = model('Attach')->getAttachByIds($vo ['attachIds']);
                foreach ($attachInfo as $var) {
                    $src = getImageUrl($var ['save_path'].$var ['save_name'], 250, 250, true);
                    if ($src) {
                        $vo ['content'] .= '<br/><p><img src="'.$src.'" /></p>';
                    }
                }
            }
        }
        // 起始楼层计算
        $p = $_GET [C('VAR_PAGE')] ? intval($_GET [C('VAR_PAGE')]) : 1;
        $this->assign('start_floor', intval((1 == $p) ? (($p - 1) * $limit + 1) : (($p - 1) * $limit)));

        $this->assign('topic', $thread);
        // dump($thread);
        $this->assign('tid', $tid);
        $this->assign('postlist', $postlist);

        $this->assign('isCollect', D('Collect', 'group')->isCollect($tid, $this->mid)); // 判断是否收藏

        $this->setTitle("{$thread['title']} - 帖子 - {$this->groupinfo['name']}");
        $this->display();
    }
    // 话题回复
    public function topic_reply()
    {
        // 权限判读
        $tid = is_numeric($_POST ['tid']) ? intval($_POST ['tid']) : 0;

        if ($tid > 0) {
            $topic = D('Topic', 'group')->field('id,uid,title,`lock`')->where("gid={$this->gid} AND id={$tid} AND is_del=0")->find(); // 获取话题内容
            if (!$topic) {
                $this->error('帖子不存在或已被删除');
            } elseif ($topic ['lock'] == 1) {
                $url = U('group/Topic/topic', array(
                        'gid' => $this->gid,
                        'tid' => $tid,
                ));
                $this->error('帖子已被锁定，不可回复', 3, $url);
            }
            $this->__checkContent($_POST ['content'], 5, 10000);
            $post ['gid'] = $this->gid;
            $post ['uid'] = $this->mid;
            $post ['tid'] = $tid;
            $post ['content'] = h($_POST ['content']);
            $post ['istopic'] = 0;
            $post ['ctime'] = time();
            $post ['ip'] = get_client_ip();
            $post ['attach'] = implode(',', array_filter(explode('|', $_POST ['image_ids'])));

            if (isset($_POST ['quote'])) { // 如果引用帖子
                $post ['quote'] = isset($_POST ['qid']) ? intval($_POST ['qid']) : 0; // 引用帖子id
                $post_info = $this->post->field('uid,istopic,content')->where("id={$post['quote']}")->find();
                if ($post_info ['uid'] != $this->mid) {
                    // 发送通知
                    $notify_dao = model('Notify');
                    $notify_data = array(
                            'post' => $post_info ['istopic'] ? "的帖子“{$topic['title']}”并回复您" : "在帖子“{$topic['title']}”中的回复",
                            'quote' => strip_tags(getShort(html_entity_decode($post_info ['content']), 30, '...')),
                            'content' => strip_tags(getShort(html_entity_decode($post ['content']), 60, '...')),
                            'gid' => $this->gid,
                            'tid' => $topic ['id'],
                    );
                    $notify_dao->send($post_info ['uid'], 'group_topic_quote', $notify_data, $this->mid);
                    D('GroupUserCount', 'group')->addCount($post_info ['uid'], 'bbs', $this->gid);
                }
            }

            $result = $this->post->add($post); // 添加回复
            if ($result) {
                if ($topic ['uid'] != $this->mid && $post_info ['uid'] != $topic ['uid']) {
                    // 发送通知
                    $notify_dao = model('Notify');
                    $notify_data = array(
                            'title' => $topic ['title'],
                            'content' => strip_tags(getShort(html_entity_decode($post ['content']), 60, '...')),
                            'gid' => $this->gid,
                            'tid' => $topic ['id'],
                    );
                    $notify_dao->send($topic ['uid'], 'group_topic_reply', $notify_data, $this->mid);
                    D('GroupUserCount', 'group')->addCount($post_info ['uid'], 'bbs', $this->gid);
                }

                $this->topic->setField('replytime', time(), 'id='.$tid);
                $this->topic->setInc('replycount', 'id='.$tid);
                // 积分
                X('Credit')->setUserCredit($this->mid, 'group_reply_topic');
            }
            $this->redirect('w3g/Group/topicDetail', array(
                    'gid' => $this->gid,
                    'tid' => $tid,
            ));
        } else {
            $this->error('帖子参数错误');
        }
    }
    public function reply_reply()
    {
        $tid = intval($_GET ['tid']);
        $thread = $this->topic->getThread($tid);
        $this->assign('topic', $thread);

        $map ['id'] = intval($_GET ['qid']);
        $quote = $this->post->where($map)->find();
        $this->assign('quote', $quote);

        $this->assign('qid', $map ['id']);
        $this->assign('tid', $tid);

        $this->display();
    }
    private function __checkContent($content, $mix = 5, $max = 5000)
    {
        $content_length = get_str_length($content, true);

        if (0 == $content_length) {
            $this->ajaxReturn(false, '内容不能为空', false);
        } elseif ($content_length < $mix) {
            $this->ajaxReturn(false, '内容不能少于'.$mix.'个字', false);
        } elseif ($content_length > $max) {
            $this->ajaxReturn(false, '内容不能超过'.$max.'个字', false);
        }
    }
    public function addTopic()
    {
        $this->need_login();
        if (IS_POST) {
            $title = getShort($_POST ['title'], 30);
            if (empty($title)) {
                $this->ajaxReturn(false, '标题不能为空', false);
            }

            $this->__checkContent($_POST ['content'], 10, 5000);

            $topic ['gid'] = $this->gid;
            $topic ['uid'] = $this->mid;
            $topic ['name'] = getUserName($this->mid);
            $topic ['title'] = h(t($title));
            $topic ['cid'] = intval($_POST ['cid']);
            $topic ['addtime'] = time();
            $topic ['replytime'] = time();
            $topic ['image_ids'] = implode(',', array_filter(explode('|', $_POST ['image_ids'])));
            $tid = D('Topic', 'group')->add($topic);
            if ($tid) {
                $post ['gid'] = $this->gid;
                $post ['uid'] = $this->mid;
                $post ['tid'] = $tid;
                $post ['content'] = h($_POST ['content']);
                $post ['istopic'] = 1;
                $post ['ctime'] = time();
                $post ['ip'] = get_client_ip();
                $post ['image_ids'] = $topic ['image_ids'];
                $post_id = $this->post->add($post);

                D('GroupFeed', 'group')->syncToFeed('我发布了一个微吧帖子“'.t($_POST ['title']).'”,详情请点击'.U('group/Topic/topic', array(
                        'tid' => $tid,
                        'gid' => $this->gid,
                )), $this->mid, 0, 0, $this->gid);

                $res ['tid'] = $tid;
                $res ['gid'] = $this->gid;

                return $this->ajaxReturn($res, '发布成功', 1);
            } else {
                $this->ajaxReturn(false, '发帖失败', false);
            }
        } else {
            $category_list = $this->topic->categoryList($this->gid);
            $this->assign('category_list', $category_list);

            $this->display();
        }
    }
    // 群的创建
    public function add()
    {
        $this->need_login();

        $this->group = D('Group', 'group');

        if (0 == $this->config ['createGroup']) {
            // 系统后台配置关闭创建
            $this->error('微吧创建已关闭');
        } elseif ($this->config ['createMaxGroup'] <= $this->group->where('is_del=0 AND uid='.$this->mid)->count()) {
            // 系统后台配置要求，如果超过，则不可以创建
            $this->error('你不可以再创建了，超过系统规定数目');
        }

        if (IS_POST) {
            // 检查验证码
            if (md5(strtoupper($_POST ['verify'])) != $_SESSION ['verify']) {
                $this->error('验证码错误');
            }

            $group ['uid'] = $this->mid;
            $group ['name'] = h(t($_POST ['name']));
            $group ['intro'] = h(t($_POST ['intro']));
            $group ['cid0'] = intval($_POST ['cid0']);
            // intval($_POST['cid1']) > 0 && $group['cid1'] = intval($_POST['cid1']);
            $cid1 = D('Category', 'group')->_digCateNew($_POST);
            intval($cid1) > 0 && $group ['cid1'] = intval($cid1);

            if (!$group ['name']) {
                $this->error('微吧名称不能为空');
            } elseif (get_str_length($_POST ['name']) > 30) {
                $this->error('微吧名称不能超过30个字');
            }

            if (D('Group', 'group')->where(array(
                    'name' => $group ['name'],
            ))->find()) {
                $this->error('这个微吧名称已被占用');
            }

            if (get_str_length($_POST ['intro']) > 200) {
                $this->error('微吧简介请不要超过200个字');
            }
            // if (!preg_replace("/[,\s]*/i", '' ,$_POST['tags']) || count(array_filter(explode(',', $_POST['tags']))) > 5) {
            // $this->error('标签不能为空或者不要超过五个');
            // }

            $group ['type'] = $_POST ['type'] == 'open' ? 'open' : 'close';

            $group ['need_invite'] = intval($this->config [$group ['type'].'_invite']); // 是否需要邀请
            $group ['brower_level'] = $_POST ['type'] == 'open' ? '-1' : '1'; // 浏览权限

            $group ['openWeibo'] = intval($this->config ['openWeibo']);
            $group ['openUploadFile'] = intval($this->config ['openUploadFile']);
            $group ['openBlog'] = intval($this->config ['openBlog']);
            $group ['whoUploadFile'] = intval($this->config ['whoUploadFile']);
            $group ['whoDownloadFile'] = intval($this->config ['whoDownloadFile']);
            $group ['openAlbum'] = intval($this->config ['openAlbum']);
            $group ['whoCreateAlbum'] = intval($this->config ['whoCreateAlbum']);
            $group ['whoUploadPic'] = intval($this->config ['whoUploadPic']);
            $group ['anno'] = intval($_POST ['anno']);
            $group ['ctime'] = time();

            if (1 == $this->config ['createAudit']) {
                $group ['status'] = 0;
            }

            // 微吧LOGO
            $group ['logo'] = 'default.gif';
            if (!empty($_POST ['image_ids'])) {
                $_POST ['image_ids'] = implode(',', array_filter(explode('|', $_POST ['image_ids'])));
                $attachInfo = model('Attach')->getAttachById($_POST ['image_ids']);
                $group ['logo'] = $attachInfo ['save_path'].$attachInfo ['save_name'];
            }

            $gid = $this->group->add($group);

            if ($gid) {
                // 把自己添加到成员里面
                $res = $this->group->joingroup($this->mid, $gid, 1, $incMemberCount = true);

                // 积分操作
                X('Credit')->setUserCredit($this->mid, 'add_group');

                // 添加微吧标签
                D('GroupTag', 'group')->setGroupTag($_POST ['tags'], $gid);

                S('Cache_MyGroup_'.$this->mid, null);
                model('UserData')->setKeyValue($this->mid, 'group_count', D('group_member')->where('level>0 and uid='.$this->mid)->count());
                if (1 == $this->config ['createAudit']) {
                    $this->success('创建成功，请等待审核', 3, U('w3g/Group/my'));
                } else {
                    $jumpUrl = U('w3g/Group/detail', array(
                            'gid' => $gid,
                    ));
                    $this->success('创建成功', 3, $jumpUrl);
                }
            } else {
                $this->error('创建失败');
            }
        } else {
            $this->_getSearchKey();

            $attachConf = model('Xdata')->get('admin_Config:attachimage');
            $this->assign($attachConf);

            $this->assign('reTags', D('GroupTag', 'group')->getHotTags('recommend'));
            $this->setTitle('创建微吧');
            $this->display();
        }
    }
    protected function _getSearchKey($key_name = 'k', $prefix = 'group_search')
    {
        $key = '';
        // 为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
        if (isset($_REQUEST [$key_name]) && !empty($_REQUEST [$key_name])) {
            if ($_GET [$key_name]) {
                $key = html_entity_decode(urldecode($_GET [$key_name]), ENT_QUOTES);
            } elseif ($_POST [$key_name]) {
                $key = $_POST [$key_name];
            }
            // 关键字不能超过30个字符
            if (mb_strlen($key, 'UTF8') > 30) {
                $key = mb_substr($key, 0, 30, 'UTF8');
            }
            $_SESSION [$prefix.'_'.$key_name] = serialize($key);
        } elseif (is_numeric($_GET [C('VAR_PAGE')])) {
            $key = unserialize($_SESSION [$prefix.'_'.$key_name]);
        } else {
            unset($_SESSION [$prefix.'_'.$key_name]);
        }
        $this->assign('search_key', h(t($key)));

        return trim($key);
    }

    // 加入该群
    public function joinGroup()
    {
        $joinCount = D('Member')->where("uid={$this->mid} AND level>1")->count();
        $member_info = D('Member')->field('level')->where("gid={$this->gid} AND uid={$this->mid}")->find();

        $msg = '';
        if ($this->groupinfo ['need_invite'] == 2) {
            $msg = '需要邀请才能加入';
        } elseif ($this->config ['joinMaxGroup'] && $joinCount >= $this->config ['joinMaxGroup']) {
            $msg = '你加入的群太多了！！不可以再加入了！！';
        } elseif (is_numeric($member_info ['level'])) {
            if ($member_info ['level'] > 0) {
                $msg = '你已经加入过！！！';
            } elseif ($member_info ['level'] == 0) {
                $msg = '请等待审核！！！';
            }
        }

        if (!empty($msg)) {
            $this->ajaxReturn(0, $msg, 0);
            exit();
        }

        $level = 0;
        $incMemberCount = false;
        if ($this->groupinfo ['need_invite'] == 0) {
            // 直接加入
            $level = 3;
            $incMemberCount = ture;
            $msg = '加入成功';
        } elseif ($this->groupinfo ['need_invite'] == 1) {
            // 需要审批，发送私信到管理员
            $level = 0;
            $incMemberCount = false;
            // 添加通知
            $toUserIds = D('Member')->field('uid')->where('gid='.$this->gid.' AND (level=1 or level=2)')->findAll();
            foreach ($toUserIds as $k => $v) {
                $toUserIds [$k] = $v ['uid'];
            }

            $message_data ['title'] = "申请加入微吧 {$this->groupinfo['name']}";
            $message_data ['content'] = "你好，请求你批准加入“{$this->groupinfo['name']}” 微吧，点此"."<a href='".U('group/Manage/membermanage', array(
                    'gid' => $this->gid,
                    'type' => 'apply',
            ))."' target='_blank'>".U('group/Manage/membermanage', array(
                    'gid' => $this->gid,
                    'type' => 'apply',
            )).'</a>进行操作。';
            $message_data ['to'] = $toUserIds;
            $res = model('Message')->postMessage($message_data, $this->mid);
            $msg = '请等待审核！！！';
        }

        $result = D('Group', 'group')->joinGroup($this->mid, $this->gid, $level, $incMemberCount); // 加入
        S('Cache_MyGroup_'.$this->mid, null);

        $this->ajaxReturn(1, $msg, 1);
        exit();
    }

    // 退出该群对话框
    public function quitGroupDialog()
    {
        $this->assign('gid', $this->gid);
        $this->display();
    }

    // 退出该群
    public function quitGroup()
    {
        if (iscreater($this->mid, $this->gid) || !$this->ismember) {
            echo '-1';
            exit();
        } // $this->error('你没有权限'); //群组不可以退出
        $res = M('group_member')->where("uid={$this->mid} AND gid={$this->gid}")->delete(); // 用户退出
        if ($res) {
            $map ['uid'] = $this->mid;
            $map ['gid'] = $this->gid;
            D('GroupUserCount', 'group')->where($map)->delete();
            D('Group', 'group')->setDec('membercount', 'id='.$this->gid); // 用户数量减少1
            model('UserData')->setKeyValue($this->mid, 'group_count', D('group_member')->where('level>0 and uid='.$this->mid)->count());
            // 积分操作
            X('Credit')->setUserCredit($this->mid, 'quit_group');
            S('Cache_MyGroup_'.$this->mid, null);
            echo '1';
            exit();
        } else {
            echo 0;
            exit();
        }
    }

    // 删除
    public function del()
    {
        $id = isset($_POST ['tid']) && !empty($_POST ['tid']) ? t($_POST ['tid']) : '';
        if ($id == '') {
            exit(json_encode(array(
                    'flag' => '0',
                    'msg' => 'tid错误',
            )));
        }

        if ($_POST ['type'] == 'thread') {
            if (strpos($id, ',') && $this->isadmin) {
                $map ['id'] = array(
                        'IN',
                        $id,
                );
                $map ['gid'] = $this->gid;
                $topicInfo = $this->topic->field('id,uid,title')->where($map)->findAll();
            } elseif (is_numeric($id)) {
                $map ['id'] = $id;
                $map ['gid'] = $this->gid;
                $topicInfo = $this->topic->field('id,uid,title')->where($map)->find();
                if (!$this->isadmin && $topicInfo ['uid'] != $this->mid) {
                    exit(json_encode(array(
                            'flag' => '0',
                            'msg' => '你没有权限',
                    )));
                }
            } else {
                exit(json_encode(array(
                        'flag' => '0',
                        'msg' => '你没有权限',
                )));
            }
            $res = $this->topic->remove($id);

            if ($res === false) {
                exit(json_encode(array(
                        'flag' => '0',
                        'msg' => '删除失败',
                )));
            } else {
                exit(json_encode(array(
                        'flag' => '1',
                        'msg' => '删除成功',
                )));
            }
        } elseif ($_POST ['type'] == 'post') {
            $post_info = $this->post->field('uid,tid')->where('id='.$id)->find(); // 获取要删除的帖子id
            if (!$this->isadmin && $post_info ['uid'] != $this->mid) {
                $this->error('你没有权限');
            }
            $this->post->remove($id); // 删除回复

            // 帖子回复数目减少1个
            $this->topic->setDec('replycount', 'id='.$post_info ['tid']);
            exit(json_encode(array(
                    'flag' => '1',
                    'msg' => '删除成功',
            )));
        }
    }

    public function groupInfo()
    {
        //关闭群分享时，自动跳转到群帖子页面；如果群帖子也没开启，自动跳转到群成员页面
        //if($this->groupinfo['openWeibo']==0 && $this->groupinfo['openBlog']==1){
            //redirect(U('group/Topic/index', array('gid' => $this->gid)));
        //如果群帖子也没开启，自动跳转到群成员页面
       // }elseif($this->groupinfo['openWeibo']==0 && $this->groupinfo['openBlog']==0){
        //    redirect(U('group/Member/index', array('gid' => $this->gid)));
        //}
        //dump($this->groupinfo);exit;
        $d['feed_type'] = t($_REQUEST['feed_type']) ? t($_REQUEST['feed_type']) : '';
        $d['feed_key'] = t($_REQUEST['feed_key']) ? t($_REQUEST['feed_key']) : '';
        $this->assign($d);
        $this->assign('group', $this->groupinfo);
        $this->setTitle($this->groupinfo['name'].' - '.$this->groupinfo['intro']);
        $this->display();
    }

    //群成员列表
    public function member()
    {
        if ($_GET['order'] == 'new') {
            $order = 'ctime DESC';
            $this->assign('order', $_GET['order']);
        } elseif ($_GET['order'] == 'visit') {
            $order = 'mtime DESC';
            $this->assign('order', $_GET['order']);
        } else {
            $order = 'level ASC';
            $this->assign('order', 'all');
        }

        //$search_key = $this->_getSearchKey();
        //if ($search_key) {

        //} else {
            $memberInfo = $this->member->order($order)->where('gid='.$this->gid.' AND status=1 AND level>0')->findPage(20);
        //}
        foreach ($memberInfo['data'] as &$member) {
            $feedid = D('GroupFeed')->where("uid={$member['uid']} AND gid={$member['gid']} AND is_del=0")->order('publish_time DESC')->getField('feed_id');
            $feedid && $member['feed'] = D('GroupFeed')->getFeedInfo($feedid);
        }
        $uids = getSubByKey($memberInfo['data'], 'uid');
        // 批量获取与当前登录用户之间的关注状态
        $follow_state = model('Follow')->getFollowStateByFids($this->mid, $uids);
        $this->assign('follow_state', $follow_state);
        //dump($follow_state);exit;

        $this->assign('memberInfo', $memberInfo);
        $this->display();
    }

    public function notice()
    {
        // 		//获取未读@Me的条数
// 		$this->assign('unread_atme_count',D('GroupUserCount')->where('uid='.$this->mid." and `key`='unread_atme'")->getField('value'));
        // 拼装查询条件
        $map['uid'] = $this->mid;
        $map['gid'] = $this->gid;
        $this->assign('gid', $this->gid);
        // !empty($_GET['t']) && $map['table'] = t($_GET['t']);
        if (!empty($_GET['t'])) {
            $table = t($_GET['t']);
            switch ($table) {
                case 'feed':
                    $map['app'] = 'Public';
                    break;
            }
        }
        // 设置应用名称与表名称
        $app_name = isset($_GET['app_name']) ? t($_GET['app_name']) : 'group';
        // 获取@Me分享列表
        $at_list = D('GroupAtme')->setAppName($app_name)->getAtmeList($map);
        // dump($at_list);exit;
        // 添加Widget参数数据
        foreach ($at_list['data'] as &$val) {
            if ($val['source_table'] == 'comment') {
                $val['widget_sid'] = $val['sourceInfo']['source_id'];
                $val['widget_style'] = $val['sourceInfo']['source_table'];
                $val['widget_sapp'] = $val['sourceInfo']['app'];
                $val['widget_suid'] = $val['sourceInfo']['uid'];
                $val['widget_share_sid'] = $val['sourceInfo']['source_id'];
            } elseif ($val['is_repost'] == 1) {
                $val['widget_sid'] = $val['source_id'];
                $val['widget_stype'] = $val['source_table'];
                $val['widget_sapp'] = $val['app'];
                $val['widget_suid'] = $val['uid'];
                $val['widget_share_sid'] = $val['app_row_id'];
                $val['widget_curid'] = $val['source_id'];
                $val['widget_curtable'] = $val['source_table'];
            } else {
                $val['widget_sid'] = $val['source_id'];
                $val['widget_stype'] = $val['source_table'];
                $val['widget_sapp'] = $val['app'];
                $val['widget_suid'] = $val['uid'];
                $val['widget_share_sid'] = $val['source_id'];
            }
            // 获取转发与评论数目
            if ($val['source_table'] != 'comment') {
                $feedInfo = D('GroupFeed')->get($val['widget_sid']);
                $val['repost_count'] = $feedInfo['repost_count'];
                $val['comment_count'] = $feedInfo['comment_count'];
            }
            //解析数据成网页端显示格式(@xxx  加链接)
            $val['source_content'] = parse_html($val['source_content']);
        }
        // 获取分享设置
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $this->assign($weiboSet);
        // 用户@Me未读数目重置
        D('GroupUserCount')->setGroupZero($this->mid, $this->gid, 'atme', 0);
        $this->setTitle(L('PUBLIC_MENTION_INDEX'));
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->setKeywords('@提到'.$userInfo['uname'].'的消息');
        $this->assign('hashtab', 'atme');
        $this->assign($at_list);
        $this->assign('swich_title', '@提到我的');
        $this->assign('is_atme', true);

        $this->display();
    }

    //收到或发出
    public function comment()
    {
        // 安全过滤
        $type = t($_GET['type']);
        $type == 'send' && $this->assign('swich_title', '我发出的评论');
        $type == 'receive' && $this->assign('swich_title', '收到的评论');
        if ($type == 'send') {
            $keyword = '发出';
            $map['uid'] = $this->uid;
            $this->assign('hashtab', 'send');
        } else {
            // 分享配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $this->assign('weibo_premission', $weiboSet['weibo_premission']);
            $keyword = '收到';
            //获取未读评论的条数
            $this->assign('unread_comment_count', model('UserData')->where('uid='.$this->mid." and `key`='unread_comment'")->getField('value'));
            // 收到的
            $map['_string'] = " (to_uid = '{$this->uid}' OR app_uid = '{$this->uid}') AND uid !=".$this->uid;
            $this->assign('hashtab', 'receive');
            D('GroupUserCount')->setGroupZero($this->mid, $this->gid, 'comment', 0);
        }
        // 类型描述术语 TODO:放到统一表里面
        $d['tabHash'] = array(
                'feed' => L('PUBLIC_WEIBO'),            // 分享
        );

        $d['tab'] = model('Comment')->getTab($map);
        $this->assign($d);

        // 安全过滤
        $t = t($_GET['t']);
        !empty($t) && $map['table'] = $t;
        $list = D('GroupComment')->setAppName(t($_GET['app_name']))->getCommentList($map, 'comment_id DESC', null, true);
        foreach ($list['data'] as $k => $v) {
            if ($v['sourceInfo']['app'] == 'weiba') {
                $list['data'][$k]['sourceInfo']['source_body'] = str_replace($v['sourceInfo']['row_id'], $v['comment_id'], $v['sourceInfo']['source_body']);
            }
        }
        $this->assign('list', $list);
        $this->assign('gid', $this->gid);
        $this->assign('type', $type);
        //dump($list);exit;
        $this->setTitle($keyword.'的评论');                    // 我的评论
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->setKeywords($userInfo['uname'].$keyword.'的评论');

        $this->assign('is_comment', true);

        $this->display('notice');
    }

    //解析group详细数据
    private function _getGroupInfo(&$group_list)
    {
        $gids = getSubByKey($group_list['data'], 'id');
        $map['gid'] = array('in', $gids);
        $map['uid'] = $this->mid;
        $usercounts = D('GroupUserCount')->where($map)->findAll();
        $gcount = array();
        foreach ($usercounts as $v) {
            if ($v['atme'] || $v['comment'] || $v['topic']) {
                $gcount[ $v['gid'] ]['atme'] = $v['atme'];
                $gcount[ $v['gid'] ]['comment'] = $v['comment'];
                $gcount[ $v['gid'] ]['topic'] = $v['topic'];
            }
        }
        foreach ($group_list['data'] as &$g) {
            $g['unread_usercount'] = $gcount[$g['id']];
        }
    }
}
