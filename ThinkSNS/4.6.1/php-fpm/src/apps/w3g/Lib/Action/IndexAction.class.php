<?php

class IndexAction extends BaseAction
{
    private $_config; // 注册配置信息字段
    private $_register_model; // 注册模型字段
    private $_user_model; // 用户模型字段
    private $_invite; // 是否是邀请注册
    private $_invite_code; // 邀请码

    // 个人首页
    public function rec($uid = 0)
    {
        // POST 为加载更多，否则为分页加载，必须配合使用
        $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        if ($isPost) {
            $post ['since_id'] = intval($_POST['new_id']);
            $post ['max_id'] = intval($_POST['load_id']);
            $post ['page'] = 1;
            $post ['count'] = isset($_POST['new_id']) ? 999 : 20;
        } else {
            $post ['since_id'] = 0;
            $post ['max_id'] = 0;
            $post ['page'] = 1;
            $post ['count'] = 20;
        }

        $weibolist = model('Feed')->getOriginalWeiboFor3G($post);
        $weibolist = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $weibolist);

        $listCount = count($weibolist);
        $maxWeiboID = $weibolist['0']['weibo_id'];
        $minWeiboID = $weibolist[$listCount - 1]['weibo_id'];

        if ($isPost) {
            $data['status'] = 1;
            $data['data'] = $this->fetch('_feedlist');
            $data['maxId'] = $maxWeiboID;
            $data['minId'] = $minWeiboID;
            $data['count'] = $listCount;
            echo json_encode($data);
            exit;
        } else {
            $this->assign('maxWeioboID', $maxWeiboID);
            $this->assign('minWeioboID', $minWeiboID);
            $this->assign('count', $listCount);
            $this->assign('headtitle', '朋友圈-推荐');
            $this->assign('isLogged', model('Passport')->isLogged());
            $this->display('index');
        }
    }

    public function index()
    {
        // POST 为加载更多，否则为分页加载，必须配合使用
        $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        if ($isPost) {
            $post ['since_id'] = intval($_POST['new_id']);
            $post ['max_id'] = intval($_POST['load_id']);
            $post ['page'] = 1;
            $post ['count'] = isset($_POST['new_id']) ? 999 : 20;
        } else {
            $post ['since_id'] = 0;
            $post ['max_id'] = 0;
            $post ['page'] = 1;
            $post ['count'] = 20;
        }

        /* # 取得指定fids */
        $feedOfTop = model('Feed')->getFeedTop(true);
        $this->assign('feedOfTop', $feedOfTop);
        unset($feedOfTop);

        $weibolist = model('Feed')->getAllWeibo($post);
        $weibolist = $this->__formatByContent($weibolist);

        $this->assign('weibolist', $weibolist);

        // 分页模块
        //$map['is_del'] = array('neq',1);
        //$count = M('feed')->where($map)->count();
        //$this->assign ( 'count', $count );

        $listCount = count($weibolist);
        $maxWeiboID = $weibolist['0']['weibo_id'];
        $minWeiboID = $weibolist[$listCount - 1]['weibo_id'];

        if ($isPost) {
            $data['status'] = 1;
            $data['data'] = $this->fetch('_feedlist');
            $data['maxId'] = $maxWeiboID;
            $data['minId'] = $minWeiboID;
            $data['count'] = $listCount;
            echo json_encode($data);
            exit;
        } else {
            $this->assign('maxWeioboID', $maxWeiboID);
            $this->assign('minWeioboID', $minWeiboID);
            $this->assign('count', $listCount);
            $this->assign('headtitle', '朋友圈');
            $this->assign('isLogged', model('Passport')->isLogged());
            $this->display('all');
        }
    }

    // weibo_list
    public function weibo_list($uid = 0)
    {
        $data ['user_id'] = $uid <= 0 ? $this->mid : $uid;
        $data ['page'] = $this->_page;
        $data ['count'] = 20;
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile ['avatar_small']);
        // 分享列表friends_timeline
        // $weibolist = api('WeiboStatuses')->data($data)->friends_timeline();
        // dump($weibolist);exit;
        // dump($weibolist);exit;
        $post ['since_id'] = intval($_REQUEST ['since_id']);
        $post ['max_id'] = intval($_REQUEST ['max_id']);
        $post ['page'] = $data ['page'];
        $post ['count'] = 20;
        // dump($post);
        $weibolist = model('Feed')->getOriginalWeibo($post);
        // dump($weibolist);exit;
        // echo D()->getLastSql();exit;
        $weibolist = $this->__formatByContent($weibolist);
        // dump($weibolist);exit;
        $this->assign('weibolist', $weibolist);
        // dump($weibolist['0']);
        // 分页模块
        $count = D('W3gPage', 'w3g')->getWeiboCount($data ['type'], $data ['user_id']);
        $count = round($count / $post ['count']);
        $this->assign('count', $count);
        /*
         * $maxWeiboID = $weibolist['0']['weibo_id'];
         * $this->assign('maxWeioboID',$maxWeiboID);
         * $this->assign('xin','xin');
         */
        $this->assign('headtitle', '朋友圈');
        foreach ($this->tVar ['weibolist'] as $key => $value) { // optimize data
            if ((($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '0') || ($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($this->tVar ['weibolist'] [$key] ['transpond_data'] ['feed_content'])) {
                switch ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['type']) {
                    case 'postimage':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postimage';
                        break;

                    case 'postfile':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postfile';
                        foreach ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] as $k => $v) {
                            if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                            } elseif ($v ['size'] < 1024) {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                            } else {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                            }
                        }
                        break;

                    case 'postvideo':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postvideo';
                        break;
                }
            } elseif (($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '1') && $this->tVar ['weibolist'] [$key] ['type'] === 'repost') {
                $this->tVar ['weibolist'] [$key] ['type'] = 'repost-removed';
            } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'postfile') {
                foreach ($this->tVar ['weibolist'] [$key] ['attach'] as $k => $v) {
                    if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                    } elseif ($v ['size'] < 1024) {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] .= 'B';
                    } else {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                    }
                }
            } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'weiba_repost') {
                if ($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['is_del'] === '1') {
                    $this->tVar ['weibolist'] [$key] ['type'] = 'weiba_repost-removed';
                }
            }
        }
        if ($_GET ['json'] === 'true') {
            echo json_encode($this->tVar ['weibolist']);
        } else {
            $this->display('weibo_list');
        }
    }

    // 好友分享
    public function fri_weibo()
    {
        // POST 为加载更多，否则为分页加载，必须配合使用
        $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        if ($isPost) {
            $post ['since_id'] = intval($_POST['new_id']);
            $post ['max_id'] = intval($_POST['load_id']);
            $post ['page'] = 1;
            $post ['count'] = isset($_POST['new_id']) ? 999 : 20;
        } else {
            $post ['since_id'] = 0;
            $post ['max_id'] = 0;
            $post ['page'] = 1;
            $post ['count'] = 20;
        }

        $weibolist = model('Feed')->friends_timeline(
            'all', $this->mid, $post['since_id'],
            $post['max_id'], $post['count'], $post ['page']
        );
        $weibolist = $this->__formatByContent($weibolist);

        $this->assign('weibolist', $weibolist);

        // 分页模块
        //$map['is_del'] = array('neq',1);
        //$count = M('feed')->where($map)->count();
        //$this->assign ( 'count', $count );

        $listCount = count($weibolist);
        $maxWeiboID = $weibolist['0']['weibo_id'];
        $minWeiboID = $weibolist[$listCount - 1]['weibo_id'];

        if ($isPost) {
            $data['status'] = 1;
            $data['data'] = $this->fetch('_feedlist');
            $data['maxId'] = $maxWeiboID;
            $data['minId'] = $minWeiboID;
            $data['count'] = $listCount;
            echo json_encode($data);
            exit;
        } else {
            $this->assign('maxWeioboID', $maxWeiboID);
            $this->assign('minWeioboID', $minWeiboID);
            $this->assign('count', $listCount);
            $this->assign('headtitle', '朋友圈-关注');
            $this->assign('isLogged', model('Passport')->isLogged());
            $this->display('friends_list');
        }
    }

    // 转发分享
    public function retweet()
    {
        if (($_GET ['weibo_id'])) {
            $data ['id'] = intval($_GET ['weibo_id']);
        } elseif (($_GET ['id'])) {
            $data ['id'] = intval($_GET ['id']);
        }
        $detail = api('WeiboStatuses')->data($data)->show();
        $map ['source_id'] = $data ['id'];
        $map ['uid'] = $this->mid;
        $detail ['iscoll'] ['colled'] = model('Collection')->where($map)->count() ? 1 : 0;
        $detail ['favorited'] = $detail ['iscoll'] ['colled'];
        // $detail['is_favorite'] = api('Favorites')->data($data)->isFavorite() ? 1 : 0;
        // $detail['content'] = wapFormatContent($detail['content'], false, urlencode($this->_self_url));
        // $detail = $this->__formatByContent($detail);
        // optimize data

        if ((($detail ['type'] === 'repost' && $detail ['api_source'] ['is_del'] === '0') || ($detail ['type'] === 'repost' && $detail ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($detail ['transpond_data'] ['feed_content'])) {
            switch ($detail ['transpond_data'] ['type']) {
                case 'postimage':
                    $detail ['type'] = 'repost-postimage';
                    break;

                case 'postfile':
                    $detail ['type'] = 'repost-postfile';
                    foreach ($detail ['transpond_data'] ['attach'] as $k => $v) {
                        if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                        } elseif ($v ['size'] < 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                        } else {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                        }
                    }
                    break;

                case 'postvideo':
                    $detail ['type'] = 'repost-postvideo';
                    break;
            }
        } elseif (($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['api_source'] ['is_del'] === '1') && $detail ['type'] === 'repost') {
            $detail ['type'] = 'repost-removed';
        } elseif ($detail ['type'] === 'postfile') {
            foreach ($detail ['attach'] as $k => $v) {
                if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                } elseif ($v ['size'] < 1024) {
                    $detail ['attach'] [$k] ['size'] .= 'B';
                } else {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                }
            }
        } elseif ($detail ['type'] === 'weiba_repost') {
            if ($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['is_del'] === '1') {
                $detail ['type'] = 'weiba_repost-removed';
            }
        }
        $detail ['from'] = getFromClient($detail ['from'], $detail ['app']);

        // 转发分享标志
        $detail ['repost'] = $detail ['api_source'] ['feed_id'] ? $detail ['api_source'] ['feed_id'] : intval($detail ['app_row_id']);
        // 如果是转发，看是否有评论当前用户的权限
        $privacy1 = $this->privacy($detail ['uid']);
        $detail ['cancomment_current'] = 0;
        if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
            $detail ['cancomment_current'] = 1;
        }
        // 判断是否有评论作者或原文作者权限
        $origin_uid = $detail ['api_source'] ['uid'] ? $detail ['api_source'] ['uid'] : 0;
        $detail ['retweet_name'] = $detail ['api_source'] ['uname'];
        $detail ['cancomment'] = 0;
        // 如果是转发，判断是否有评论给原作者的权限
        if ($origin_uid) {
            $privacy = $this->privacy($origin_uid);
            if ($privacy === true || $privacy ['comment_weibo'] == 0) {
                $detail ['cancomment'] = 1;
            }
        }
        // 原创分享且可被评论
        if (!$origin_uid && $detail ['cancomment_current'] == 1) {
            $detail ['retweet_name'] = $detail ['uname'];
            $detail ['cancomment'] = 1;
        }

        $detail ['comment_touid'] = $detail ['repost'] ? $origin_uid : $detail ['uid'];
        // dump($detail);exit;
        $this->assign('feed', $detail);
        $_title = '转发分享';
        $this->assign('_title', $_title);
        $this->setTitle($_title);
        $this->setKeywords($_title);
        $this->setDescription($_title);
        $this->display();
    }
    // reply page
    public function reply()
    {
        $_title = '评论';
        $this->assign('_title', $_title);
        $this->setTitle($_title);
        $this->setKeywords($_title);
        $this->setDescription($_title);

        if (($_GET ['weibo_id'])) {
            $data ['id'] = intval($_GET ['weibo_id']);
        } elseif (($_GET ['id'])) {
            $data ['id'] = intval($_GET ['id']);
        }
        if (!$data ['id']) {
            redirect($_SERVER ['HTTP_REFERER'], 3, '参数错误');
        }
        $detail = api('WeiboStatuses')->data($data)->show();
        // 转发分享标志
        // $detail['repost'] = $detail['app_row_id'];
        // 如果是转发，看是否有评论当前用户的权限
        $privacy1 = $this->privacy($detail ['uid']);
        $detail ['cancomment_current'] = 0;
        if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
            $detail ['cancomment_current'] = 1;
        }
        if ($detail ['cancomment_current'] == 0) {
            redirect($_SERVER ['HTTP_REFERER'], 3, '没有评论权限');
        }
        // 判断是否有评论作者或原文作者权限
        $origin_uid = $detail ['api_source'] ['uid'] ? $detail ['api_source'] ['uid'] : 0;
        $detail ['cancomment'] = 0;
        // 如果是转发，判断是否有评论给原作者的权限
        if ($origin_uid && $origin_uid != $this->mid) {
            $privacy = $this->privacy($origin_uid);
            if ($privacy ['comment_weibo'] == 0) {
                $detail ['cancomment'] = 1;
            }
        }
        $detail ['defaultHtml'] = $_GET ['to_uname'] ? '回复@'.t($_GET ['to_uname']).'：' : '';
        // dump($detail);exit;
        // 当前分享id
        $detail ['row_id'] = $data ['id'];
        // 原分享ID
        $detail ['app_row_id'] = $detail ['app_row_id'] ? $detail ['app_row_id'] : 0;
        $detail ['app_row_id'] = $detail ['api_source'] ['feed_id'] ? $detail ['api_source'] ['feed_id'] : $detail ['app_row_id'];
        // 原分享数据表
        $detail ['app_row_table'] = $detail ['table'] = $detail ['app_row_table'] ? $detail ['app_row_table'] : 'feed';
        // 原分享UID
        $detail ['app_uid'] = $origin_uid;
        $detail ['to_comment_id'] = $_GET ['to_comment_id'] ? intval($_GET ['to_comment_id']) : 0;
        $detail ['to_uid'] = $_GET ['to_uid'] ? intval($_GET ['to_uid']) : 0;

        $this->assign('detail', $detail);
        // dump($detail);exit;
        $this->display('reply');
    }

    // 下拉刷新
    /*
     * public function resetScrollDownRefresh($uid = 0) {
     * $data['user_id'] = $uid <= 0 ? $this->mid : $uid;
     * $data['page'] = $this->_page;
     * $data['count'] = 11;
     * $data['since_id']=intval($_GET['since_id']);
     * // 用户资料
     * $profile = api('User')->data($data)->show();
     * $this->assign('profile', $profile['avatar_small']);
     * // 分享列表friends_timeline
     * $weibolist = api('WeiboStatuses')->data($data)->friends_timeline();
     * $weibolist = $this->__formatByContent($weibolist);
     * $this->assign('weibolist', $weibolist);
     * //分页模块
     * $count = D('W3gPage', 'w3g')->getWeiboCount($data['type'], $data['user_id']);
     * $this->assign('count',$count);
     * $this->assign('headtitle', '下拉刷新');
     * $this->display('resetScrollDownRefresh');
     * }
     */
    // 分享广场
    public function resetScrollDownRefreshSquare()
    {
        $data ['page'] = $this->_page;
        $data ['count'] = 11;
        $data ['since_id'] = intval($_GET ['since_id']);
        $weibolist = api('WeiboStatuses')->data($data)->public_timeline();
        // $weibolist = $this->__formatByFavorite($weibolist);
        $weibolist = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $weibolist);
        $this->assign('headtitle', '下拉刷新for广场');
        // 分页模块
        $count = D('W3gPage', 'w3g')->getAllWeiboCount($data ['type'], $data ['user_id']);
        $this->assign('count', $count);
        $this->display('resetScrollDownRefreshSquare');
    }

    // 分享广场
    public function publicsquare()
    {
        $data ['page'] = $this->_page;
        $data ['count'] = 10;
        $weibolist = api('WeiboStatuses')->data($data)->public_timeline();
        // $weibolist = $this->__formatByFavorite($weibolist);
        $weibolist = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $weibolist);
        $this->assign('headtitle', '分享广场');
        // 分页模块
        $count = D('W3gPage', 'w3g')->getAllWeiboCount($data ['type'], $data ['user_id']);
        $this->assign('count', $count);
        $this->display('publicsquare');
    }

    /**
     * 隐私设置.
     */
    public function privacy($uid)
    {
        if ($this->mid != $uid) {
            $privacy = model('UserPrivacy')->getPrivacy($this->mid, $uid);

            return $privacy;
        } else {
            return true;
        }
    }
    // XX的分享
    public function weibo()
    {
        // 分享列表
        // $data['user_id'] = $_GET['uid'] <= 0 ? $this->mid : $_GET['uid'];
        $data ['user_id'] = isset($_GET ['uid']) ? intval($_GET ['uid']) : $this->mid;
        // 判断隐私设置
        $userPrivacy = $this->privacy($data ['user_id']);
        $isAllowed = 0;
        $isMessage = 1;
        ($userPrivacy ['space'] == 1) && $isMessage = 0;
        $this->assign('sendmsg', $isMessage);

        if ($userPrivacy === true || $userPrivacy ['space'] == 0) {
            $isAllowed = 1;
            $data ['page'] = $this->_page;
            $data ['count'] = 10;

            // 用户资料
            $profile = api('User')->data($data)->show();
            $following = $profile ['follow_state'] ['following'];
            $follower = $profile ['follow_state'] ['follower'];
            if ($following) {
                $profile ['follow_state'] ['value'] = '已关注';
            } else {
                $profile ['follow_state'] ['value'] = '未关注';
            }
            if ($following && $follower) {
                $profile ['follow_state'] ['value'] = '互相关注';
            }
            $this->assign('profile', $profile);
            // 分享列表
            $weibolist = api('WeiboStatuses')->data($data)->user_timeline();
            $weibolist = $this->__formatByContent($weibolist);
            $this->assign('weibolist', $weibolist);

            foreach ($this->tVar ['weibolist'] as $key => $value) { // optimize data
                if ((($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '0') || ($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($this->tVar ['weibolist'] [$key] ['transpond_data'] ['feed_content'])) {
                    switch ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['type']) {
                        case 'postimage':
                            $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postimage';
                            break;

                        case 'postfile':
                            $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postfile';
                            foreach ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] as $k => $v) {
                                if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                                    $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                                } elseif ($v ['size'] < 1024) {
                                    $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                                } else {
                                    $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                                }
                            }
                            break;
                    }
                } elseif (($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '1') && $this->tVar ['weibolist'] [$key] ['type'] === 'repost') {
                    $this->tVar ['weibolist'] [$key] ['type'] = 'repost-removed';
                } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'postfile') {
                    foreach ($this->tVar ['weibolist'] [$key] ['attach'] as $k => $v) {
                        if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                            $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                        } elseif ($v ['size'] < 1024) {
                            $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] .= 'B';
                        } else {
                            $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                        }
                    }
                } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'weiba_repost') {
                    if ($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['is_del'] === '1') {
                        $this->tVar ['weibolist'] [$key] ['type'] = 'weiba_repost-removed';
                    }
                }
            }
            // 分页模块
            $count = D('W3gPage', 'w3g')->getMyWeiboCount($data ['type'], $data ['user_id']);
            $this->assign('count', $count);
        }
        $this->assign('isAllowed', $isAllowed);
        // dump($weibolist['0']);
        $this->assign('hideUsername', '1');
        if ($this->mid == $this->uid) {
            $this->assign('headtitle', '我的主页');
        } else {
            $this->assign('headtitle', 'TA的主页');
        }
        $this->display();
    }

    // @提到我的
    public function atMe()
    {
        $data ['page'] = $this->_page;
        $data ['count'] = 20;

        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile);
        // @XX的分享列表
        $weibolist = api('WeiboStatuses')->data($data)->mentions();
        $weibolist = $this->__formatByContent($weibolist);
        // 提示数字归0
        model('UserCount')->resetUserCount($this->mid, 'unread_atme');
        // 分页模块
        $count = D('W3gPage', 'w3g')->getAtmeCount($this->mid);
        $this->assign('count', $count);
        // digg
        $feedIds = getSubByKey($weibolist, 'feed_id');
        $feedIds = array_filter($feedIds);
        $sdiggArr = model('FeedDigg')->checkIsDigg($feedIds, $this->mid);
        $sdiggArr = array_keys($sdiggArr);
        /*
         * foreach ($weibolist as &$value) {
         * if (!empty($value['feed_id']) && in_array($value['feed_id'], $sdiggArr)) {
         * $value['is_digg'] = 1;
         * } else {
         * $value['is_digg'] = 0;
         * }
         * }
         */
        foreach ($this->$weibolist as $key => $value) { // optimize data
            if ((($this->$weibolist [$key] ['type'] === 'repost' && $weibolist [$key] ['api_source'] ['is_del'] === '0') || ($this->$weibolist [$key] ['type'] === 'repost' && $this->$weibolist [$key] ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($weibolist [$key] ['transpond_data'] ['feed_content'])) {
                switch ($weibolist [$key] ['transpond_data'] ['type']) {
                    case 'postimage':
                        $this->$weibolist [$key] ['type'] = 'repost-postimage';
                        break;

                    case 'postfile':
                        $weibolist [$key] ['type'] = 'repost-postfile';
                        foreach ($weibolist [$key] ['transpond_data'] ['attach'] as $k => $v) {
                            if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                                $weibolist [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                            } elseif ($v ['size'] < 1024) {
                                $weibolist [$key] ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                            } else {
                                $weibolist [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                            }
                        }
                        break;

                    case 'postvideo':
                        $weibolist [$key] ['type'] = 'repost-postvideo';
                        break;
                }
            } elseif (($this->$weibolist [$key] ['api_source'] ['is_del'] === '1' || $weibolist [$key] ['transpond_data'] ['api_source'] ['is_del'] === '1') && $weibolist [$key] ['type'] === 'repost') {
                $weibolist [$key] ['type'] = 'repost-removed';
            } elseif ($weibolist [$key] ['type'] === 'postfile') {
                foreach ($weibolist [$key] ['attach'] as $k => $v) {
                    if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                        $weibolist [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                    } elseif ($v ['size'] < 1024) {
                        $weibolist [$key] ['attach'] [$k] ['size'] .= 'B';
                    } else {
                        $weibolist [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                    }
                }
            } elseif ($weibolist [$key] ['type'] === 'weiba_repost') {
                if ($weibolist [$key] ['api_source'] ['is_del'] === '1' || $weibolist [$key] ['transpond_data'] ['is_del'] === '1') {
                    $weibolist [$key] ['type'] = 'weiba_repost-removed';
                }
            }
        }
        $this->assign('weibolist', $weibolist);
        // dump($weibolist['0']);
        $this->assign('atMe', 1);
        $this->assign('headtitle', '@我的');
        $this->display('atme');
    }

    // msg box
    public function msgbox()
    {
        $mcount = D('UserCount')->getUnreadCount($this->mid);
        $memus = array('atme', 'comment', 'message', 'notify');
        $selected = array(
            'atme' => false,
            'comment' => false,
            'message' => false,
            'notify' => false,
        );
        foreach ($selected as $memu => $value) {
            if ($mcount['unread_'.$memu] > 0) {
                $selected[$memu] = true;
                break; //跳出循环
            }
        }
        $this->assign('selected', $selected);
        $des = 'msgbox';
        $this->assign('des', $des);
        $this->display('msgbox');
    }

    // 通知数目
    public function MCount()
    {
        // $amap['uid'] = $this->mid;
        $mcount = D('UserCount')->getUnreadCount($this->mid);
        $this->assign('mcount', $mcount);
        echo json_encode($mcount);
    }

    // 评论我的
    public function replyMe()
    {
        $data ['page'] = $this->_page;
        $data ['count'] = 20;
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile);
        // 评论的分享列表
        $commentlist = api('WeiboStatuses')->data($data)->comments_to_me_true();
        // $commentlist = $this->__formatByContent($commentlist);
        foreach ($commentlist as &$value) {
            $value ['from'] = getFromClient($value ['client_type'], $value ['app']);
            // 因为评论我的页面中没有is_del，下句是加上
            $value ['sourceInfo'] ['is_del'] = M('Feed')->where('feed_id='.$value ['sourceInfo'] ['feed_id'])->getField('is_del');
            // 微吧
            if (in_array($value ['app'], array(
                    'weiba',
            ))) {
                $feedInfo = model('Feed')->getFeedInfo($value ['sourceInfo'] ['source_id']);
                $value ['sourceInfo'] ['uname'] = $feedInfo ['api_source'] ['source_user_info'] ['uname'];
                $value ['sourceInfo'] ['api_source'] = $feedInfo ['api_source'];
            }
            // 转发分享标志
            $value ['repost'] = $value ['app_row_id'];
            // 如果是转发，看是否有评论当前用户的权限
            $privacy1 = $this->privacy($value ['uid']);
            $value ['cancomment_current'] = 0;
            if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
                $value ['cancomment_current'] = 1;
            }
            // 判断是否有评论作者或原文作者权限
            $origin_uid = $value ['api_source'] ['uid'] ? $value ['api_source'] ['uid'] : 0;
            $value ['cancomment'] = 0;
            // 如果是转发，判断是否有评论给原作者的权限
            if ($origin_uid && $origin_uid != $this->mid) {
                $privacy = $this->privacy($origin_uid);
                if ($privacy === true || $privacy ['comment_weibo'] == 0) {
                    $value ['cancomment'] = 1;
                }
            }
            // if(empty($value['to_comment_id'])){
            // continue;
            // }
            // $preCom = M('Comment')->where('uid, content')->where("comment_id='{$value['to_comment_id']}'")->find();
            // $uinfo = model('User')->getUserInfo($preCom['uid']);
        }
        // $this->assign('weibolist', $commentlist);
        /*
         * foreach($this->$weibolist as $key => $value){//optimize data
         * if (( ($this->$weibolist[$key]['type'] === 'repost' && $weibolist[$key]['api_source']['is_del'] === '0') || ($this->$weibolist[$key]['type'] === 'repost' && $this->$weibolist[$key]['transpond_data']['api_source']['is_del'] === '0')) && isset($weibolist[$key]['transpond_data']['feed_content']) ) {
         * switch($weibolist[$key]['transpond_data']['type']){
         * case 'postimage':
         * $this->$weibolist[$key]['type']='repost-postimage';
         * break;
         *
         * case 'postfile':
         * $weibolist[$key]['type']='repost-postfile';
         * foreach($weibolist[$key]['transpond_data']['attach'] as $k => $v){
         * if($v['size'] > 1024 && $v['size']< 1024*1024){
         * $weibolist[$key]['transpond_data']['attach'][$k]['size']=round($v['size']/1024,2).'K';
         * }else if($v['size'] < 1024){
         * $weibolist[$key]['transpond_data']['attach'][$k]['size'].='B';
         * }else{
         * $weibolist[$key]['transpond_data']['attach'][$k]['size']=round($v['size']/1024/1024,2).'M';
         * }
         * }
         * break;
         *
         * case 'postvideo':
         * $weibolist[$key]['type']='repost-postvideo';
         * break;
         * }
         * }else if(($this->$weibolist[$key]['api_source']['is_del'] === '1' || $weibolist[$key]['transpond_data']['api_source']['is_del'] === '1') && $weibolist[$key]['type'] === 'repost'){
         * $weibolist[$key]['type']='repost-removed';
         * }else if($weibolist[$key]['type'] === 'postfile'){
         * foreach($weibolist[$key]['attach'] as $k => $v){
         * if($v['size'] > 1024 && $v['size']< 1024*1024){
         * $weibolist[$key]['attach'][$k]['size']=round($v['size']/1024,2).'K';
         * }else if($v['size'] < 1024){
         * $weibolist[$key]['attach'][$k]['size'].='B';
         * }else{
         * $weibolist[$key]['attach'][$k]['size']=round($v['size']/1024/1024,2).'M';
         * }
         * }
         * }else if($weibolist[$key]['type'] === 'weiba_repost'){
         * if($weibolist[$key]['api_source']['is_del'] === '1' || $weibolist[$key]['transpond_data']['is_del'] === '1'){
         * $weibolist[$key]['type']='weiba_repost-removed';
         * }
         * }
         * }
         */
// 		print_r($commentlist);
// 		exit;
        $this->assign('commentlist', $commentlist);
        // 分页模块
        $count = D('W3gPage', 'w3g')->getComCount($this->mid);
        $this->assign('count', $count);
        // 提示数字归0
        model('UserCount')->resetUserCount($this->mid, 'unread_comment');
        $this->assign('headtitle', '评论我的');
        $this->assign('type', 'receive');
        $this->display('replyMe');
    }

    // 我发出的评论
    public function myreply()
    {
        $data ['page'] = $this->_page;
        $data ['count'] = 20;
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile);
        // 评论的分享列表
        $commentlist = api('WeiboStatuses')->data($data)->comments_by_me();
        // $commentlist = $this->__formatByContent($commentlist);
        foreach ($commentlist as &$value) {
            $value ['from'] = getFromClient($value ['client_type'], $value ['app']);
            // 因为评论我的页面中没有is_del，下句是加上
            $value ['sourceInfo'] ['is_del'] = M('Feed')->where('feed_id='.$value ['sourceInfo'] ['feed_id'])->getField('is_del');
            // 微吧
            if (in_array($value ['app'], array(
                    'weiba',
            ))) {
                $feedInfo = model('Feed')->getFeedInfo($value ['sourceInfo'] ['source_id']);
                $value ['sourceInfo'] ['uname'] = $feedInfo ['api_source'] ['source_user_info'] ['uname'];
                $value ['sourceInfo'] ['api_source'] = $feedInfo ['api_source'];
            }
            // 转发分享标志
            $value ['repost'] = $value ['app_row_id'];
            // 如果是转发，看是否有评论当前用户的权限
            $privacy1 = $this->privacy($value ['uid']);
            $value ['cancomment_current'] = 0;
            if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
                $value ['cancomment_current'] = 1;
            }
            // 判断是否有评论作者或原文作者权限
            $origin_uid = $value ['api_source'] ['uid'] ? $value ['api_source'] ['uid'] : 0;
            $value ['cancomment'] = 0;
            // 如果是转发，判断是否有评论给原作者的权限
            if ($origin_uid && $origin_uid != $this->mid) {
                $privacy = $this->privacy($origin_uid);
                if ($privacy === true || $privacy ['comment_weibo'] == 0) {
                    $value ['cancomment'] = 1;
                }
            }
            // if(empty($value['to_comment_id'])){
            // continue;
            // }
            // $preCom = M('Comment')->where('uid, content')->where("comment_id='{$value['to_comment_id']}'")->find();
            // $uinfo = model('User')->getUserInfo($preCom['uid']);
        }
        $this->assign('commentlist', $commentlist);
        // 分页模块
        $count = D('W3gPage', 'w3g')->getComCount($this->mid);
        $this->assign('count', $count);
        // 提示数字归0
        model('UserCount')->resetUserCount($this->mid, 'unread_comment');
        $this->assign('headtitle', '我发出的评论');
        $this->assign('type', 'send');
        $this->display();
    }

    // 评论列表
    public function comments()
    {
        // 安全过滤
        $type = t($_GET ['type']);
        if (empty($_GET ['type'])) {
            $type = $_GET ['type'] = 'receive';
        }

        if ($type == 'send') {
            $keyword = '发出';
            $map ['uid'] = $this->uid;
        } else {
            // 分享配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $this->assign('weibo_premission', $weiboSet ['weibo_premission']);
            $keyword = '收到';
            // 获取未读评论的条数
            $this->assign('unread_comment_count', model('UserData')->where('uid='.$this->mid." and `key`='unread_comment'")->getField('value'));
            // 收到的
            $map ['_string'] = " (to_uid = '{$this->uid}' OR app_uid = '{$this->uid}') AND uid !=".$this->uid;
        }
        // 获取tab类型
        // $d['tab'] = model('Comment')->getTab($map);
        // $d['tab'] = model('Comment')->getTabForApp($map);
        // foreach ($d['tab'] as $key=>$vo){
        // if($key=='feed'){
        // $d['tabHash']['feed'] = L('PUBLIC_WEIBO');
        // } elseif($key == 'webpage') {
        // $d['tabHash']['webpage'] = '评论箱';
        // } else {
        // // 微吧
        // strtolower($key) === 'weiba_post' && $key = 'weiba';

        // $langKey = 'PUBLIC_APPNAME_' . strtoupper ( $key );
        // $lang = L($langKey);
        // if($lang==$langKey){
        // $d['tabHash'][$key] = ucfirst ( $key );
        // }else{
        // $d['tabHash'][$key] = $lang;
        // }
        // }
        // }
        // $this->assign($d);

        // 安全过滤
        $t = t($_GET ['t']);
        // !empty($t) && $map['table'] = $t;
        !empty($t) && $map ['app'] = $t;
        if ($t == 'feed') {
            $map ['app'] = 'public';
        }
        $list = model('Comment')->setAppName(t($_GET ['app_name']))->getCommentList($map, 'comment_id DESC', null, true);
        foreach ($list ['data'] as $k => $v) {
            if ($v ['sourceInfo'] ['app'] == 'weiba') {
                $list ['data'] [$k] ['sourceInfo'] ['source_body'] = str_replace($v ['sourceInfo'] ['row_id'], $v ['comment_id'], $v ['sourceInfo'] ['source_body']);
            }
            if ($v ['table'] === 'webpage') {
                $list ['data'] [$k] ['hasComment'] = false;
            } else {
                $list ['data'] [$k] ['hasComment'] = true;
            }
        }
        model('UserCount')->resetUserCount($this->mid, 'unread_comment', 0);
        $this->assign('_count', $list ['totalPages']);
        $this->assign('commentlist', $list ['data']);
        $this->assign('type', $type);
        $this->setTitle($keyword.'的评论'); // 我的评论
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->setKeywords($userInfo ['uname'].$keyword.'的评论');
        $this->display('comment_list');
    }
    // 我的收藏
    public function favorite()
    {
        $data ['user_id'] = isset($_GET ['uid']) ? intval($_GET ['uid']) : $this->mid;
        $userPrivacy = $this->privacy($data ['user_id']);
        $isAllowed = 0;
        $isMessage = 1;
        ($userPrivacy ['space'] == 1) && $isMessage = 0;
        $this->assign('sendmsg', $isMessage);

        $data ['page'] = $this->_page;
        $data ['count'] = 10;
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile);
        if ($userPrivacy === true || $userPrivacy ['space'] == 0) {
            $isAllowed = 1;
            // 收藏列表
            $weibolist = api('WeiboStatuses')->data($data)->favorite_weibo();
            $weibolist = $this->__formatByContent($weibolist);
            foreach ($weibolist as $k => $v) {
                if ($v ['feed_id']) {
                    $weibolist [$k] ['content'] = wapFormatContent($v ['feed_content'], true, $this->_self_url, '视频');
                    $weibolist [$k] ['transpond_data'] ['content'] = wapFormatContent($v ['transpond_data'] ['feed_content'], true, $this->_self_url, '视频');
                    $weibolist [$k] ['uid'] = $weibolist [$k] ['source_user_info'] ['uid'];
                    $weibolist [$k] ['favorited'] = 1;
                    $weibolist [$k] ['weibo_id'] = $weibolist [$k] ['feed_id'];
                    $weibolist [$k] ['userGroupData'] = $this->_usergroup($weibolist [$k] ['uid']);
                } else {
                    unset($weibolist [$k]);
                }
            }
            // 当前用户的总收藏数
            $this->assign('count', $profile ['count_info'] ['favorite_count']);
            // digg
            $feedIds = getSubByKey($weibolist, 'feed_id');
            $feedIds = array_filter($feedIds);
            $sdiggArr = model('FeedDigg')->checkIsDigg($feedIds, $this->mid);
            $sdiggArr = array_keys($sdiggArr);
            foreach ($weibolist as &$value) {
                if (!empty($value ['feed_id']) && in_array($value ['feed_id'], $sdiggArr)) {
                    $value ['is_digg'] = 1;
                } else {
                    $value ['is_digg'] = 0;
                }
            }
            $this->assign('weibolist', $weibolist);
        }
        $this->assign('isAllowed', $isAllowed);
        $this->assign('favorite', 1);
        if ($this->mid == $this->uid) {
            $this->assign('datatitle', '我的收藏');
        } else {
            $this->assign('datatitle', 'TA的收藏');
        }
        $this->display();
    }

    // 下面的方法是判断是否被收藏，在WeiboStatuses这个接口里面封装好了判断是否被收藏的信息，所以不用下边这个了
    private function __formatByFavorite($weibolist)
    { // format 格式化的意思
        $ids = implode(',', getSubByKey($weibolist, 'weibo_id'));
        $favorite = D('Favorite', 'weibo')->isFavorited($ids, $this->mid); // D('Favorite,'weibo') 实例blog项目下的Favorite模型
        foreach ($weibolist as $k => $v) {
            if (in_array($v ['weibo_id'], $favorite)) {
                $weibolist [$k] ['is_favorite'] = 1;
            } else {
                $weibolist [$k] ['is_favorite'] = 0;
            }
        }

        return $weibolist;
    }
    private function __formatByContent($weibolist)
    {
        $self_url = urlencode($this->_self_url);
        foreach ($weibolist as $k => $v) {
            if ($v ['app'] === 'blog' || $v ['app'] === 'weiba' || $v ['app'] === 'group') {
                unset($weibolist [$k]);
                continue;
            }
            // 转发分享标志
            $weibolist [$k] ['repost'] = $v ['api_source'] ['feed_id'] ? $v ['api_source'] ['feed_id'] : $v ['app_row_id'];
            // 如果是转发，看是否有评论当前用户的权限
            $privacy1 = $this->privacy($v ['uid']);
            $weibolist [$k] ['cancomment_current'] = 0;
            if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
                $weibolist [$k] ['cancomment_current'] = 1;
            }
            // 判断是否有评论作者或原文作者权限
            $origin_uid = $v ['api_source'] ['uid'] ? $v ['api_source'] ['uid'] : 0;
            $weibolist [$k] ['cancomment'] = 0;
            // 如果是转发，判断是否有评论给原作者的权限
            if ($origin_uid && $origin_uid != $this->mid) {
                $privacy = $this->privacy($origin_uid);
                if ($privacy === true || $privacy ['comment_weibo'] == 0) {
                    $weibolist [$k] ['cancomment'] = 1;
                }
            }
            $weibolist [$k] ['userGroupData'] = $this->_usergroup($v ['uid']);
            switch ($v ['app']) {
                case 'blog':
                    unset($weibolist [$k]);
                    continue;
                    /*
                     * if($v['feed_id']){
                     * $weibolist[$k]['weibo_id'] = $weibolist[$k]['feed_id'];
                     * // $weibolist[$k]['content'] = wapFormatContent($v['content'], true, $self_url);
                     * // 视频处理
                     * $weibolist[$k]['content'] = wapFormatContent($v['api_source']['content'], true, $self_url);
                     *
                     * //if($v['type'] == 'postvideo'){
                     * // //$weibolist[$k]['content'] = $v['source_body'];
                     * // $weibolist[$k]['content'] = $v['feed_content'] ? $v['feed_content'] : $v['source_body'];
                     * // $weibolist[$k]['content'] = wapFormatContent($weibolist[$k]['content'], true, $self_url, "知识");
                     * //}else{
                     * // $weibolist[$k]['content'] = wapFormatContent($weibolist[$k]['content'], true, $self_url);
                     * //}
                     * // 非视频分享
                     * if ($v['transpond_data']['content']) {
                     * if(strpos($weibolist[$k]['type'], 'video')){
                     * $weibolist[$k]['transpond_data']['content'] = wapFormatContent($v['transpond_data']['content'], true, $self_url, "视频");
                     * }else{
                     * $weibolist[$k]['transpond_data']['content'] = wapFormatContent($v['transpond_data']['content'], true, $self_url);
                     * }
                     * $weibolist[$k]['transpond_data']['weibo_id'] = $weibolist[$k]['transpond_data']['feed_id'];
                     * }else{
                     * $row_id = model('Feed')->where('feed_id='.$v['feed_id'])->getField('app_row_id');
                     * $uid = model('Feed')->where('feed_id='.$row_id)->getField('uid');
                     * $weibolist[$k]['transpond_data'] = model('User')->getUserInfo($this->uid);
                     * }
                     * $weibolist[$k]['ctime'] = date('Y-m-d H:i', $v['publish_time']);
                     * }else{
                     * if($weibolist[$k]['row_id']){
                     * $weibolist[$k]['ctime'] = strtotime($weibolist[$k]['ctime']);
                     * }else{
                     * unset($weibolist[$k]);
                     * }
                     *
                     * }
                     */
                    break;
                case 'public':
                    if ($v ['feed_id']) {
                        $weibolist [$k] ['weibo_id'] = $weibolist [$k] ['feed_id'];
                        // $weibolist[$k]['content'] = wapFormatContent($v['content'], true, $self_url);
                        // 视频处理
                        if ($v ['type'] == 'postvideo') {
                            // $weibolist[$k]['content'] = $v['source_body'];
                            $weibolist [$k] ['content'] = $v ['feed_content'] ? $v ['feed_content'] : $v ['source_body'];
                            $weibolist [$k] ['content'] = wapFormatContent($weibolist [$k] ['content'], true, $self_url, '视频');
                        } else {
                            $weibolist [$k] ['content'] = wapFormatContent($weibolist [$k] ['content'], true, $self_url, '访问链接');
                        }
                        // 非视频分享
                        if ($v ['transpond_data'] ['content']) {
                            if (strpos($weibolist [$k] ['type'], 'video')) {
                                $weibolist [$k] ['transpond_data'] ['content'] = wapFormatContent($v ['transpond_data'] ['content'], true, $self_url, '视频');
                            } else {
                                $weibolist [$k] ['transpond_data'] ['content'] = wapFormatContent($v ['transpond_data'] ['content'], true, $self_url);
                            }
                            $weibolist [$k] ['transpond_data'] ['weibo_id'] = $weibolist [$k] ['transpond_data'] ['feed_id'];
                        } else {
                            $row_id = model('Feed')->where('feed_id='.$v ['feed_id'])->getField('app_row_id');
                            $uid = model('Feed')->where('feed_id='.$row_id)->getField('uid');
                            $weibolist [$k] ['transpond_data'] = model('User')->getUserInfo($this->uid);
                        }
                        $weibolist [$k] ['ctime'] = date('Y-m-d H:i', $v ['publish_time']);
                    } else {
                        if ($weibolist [$k] ['row_id']) {
                            $weibolist [$k] ['ctime'] = strtotime($weibolist [$k] ['ctime']);
                        } else {
                            unset($weibolist [$k]);
                        }
                    }
                    break;
                case 'weiba':
                    $weiba_post = D('WeibaPost', 'weiba')->where('post_id='.$v ['app_row_id'])->find();
                    $weibolist [$k] ['weibo_id'] = $weibolist [$k] ['feed_id'];
                    $weibolist [$k] ['transpond_data'] = $weiba_post;
                    $weibolist [$k] ['transpond_data'] ['weibo_id'] = $weibolist [$k] ['feed_id'];
                    $weibolist [$k] ['transpond_data'] ['uname'] = model('User')->where('uid='.$weiba_post ['post_uid'])->getField('uname');
                    $weibolist [$k] ['transpond_data'] ['uid'] = $weiba_post ['post_uid'];
                    break;

                default:

                    // code...
                    break;
            }
            // 处理视频链接
            /*
             * if(strpos($weibolist[$k]['type'], 'video')){
             * $weibolist[$k]['content'] = preg_replace("/(.*)<a([^>*])>([^<*])</a>(.*)/i", "\\1<a\\2>视频</a>\\4", $weibolist[$k]['content']);
             * $weibolist[$k]['transpond_data']['content'] = preg_replace("/(.*)<a([^>*])>([^<*])</a>(.*)/i", "\\1<a\\2>视频</a>\\4", $weibolist[$k]['transpond_data']['content']);
             * }
             */
            $weibolist [$k] ['from'] = getFromClient($weibolist [$k] ['from'], $v ['app']);
            $map ['source_id'] = $v ['feed_id'];
            $map ['uid'] = $this->mid;
            $fav = model('Collection')->where($map)->getField('source_id');
            if ($fav) {
                $weibolist [$k] ['favorited'] = 1;
            } else {
                $weibolist [$k] ['favorited'] = 0;
            }
        }

        return array_values($weibolist);
    }
    private function _usergroup($uid)
    {
        $var ['uid'] = $uid;
        // 获取用户信息
        // $var['userInfo'] = model('User')->getUserInfo($var['uid']);
        // 获取用户用户组信息
        $userGids = model('UserGroupLink')->getUserGroup($var ['uid']);
        $userGroupData = model('UserGroup')->getUserGroupByGids($userGids [$var ['uid']]);
        foreach ($userGroupData as $key => $value) {
            if ($value ['user_group_icon'] == -1) {
                unset($userGroupData [$key]);
                continue;
            }
            $userGroupData [$key] ['user_group_icon_url'] = THEME_PUBLIC_URL.'/image/usergroup/'.$value ['user_group_icon'];
        }

        return $userGroupData;
    }
    private function __formatByComment($comment)
    {
        $self_url = urlencode($this->_self_url);
        foreach ($comment as $k => $v) {
            $comment [$k] ['content'] = wapFormatComment($v ['content'], true, $self_url);
        }

        return $comment;
    }

    // 话题
    public function topic()
    {
        $map ['recommend'] = 1;
        $order = 'recommend_time DESC';
        $topic = M('feed_topic')->where($map)->field('topic_id,topic_name,count')->order($order)->findAll();
        $this->assign('topic', $topic);
        $this->display();
    }

    // 关注列表
    public function following()
    {
        $this->__followlist('user_following');
    }

    // 粉丝列表
    public function followers()
    {
        $this->__followlist('user_followers');
    }

    // 分享详情
    public function detail()
    {
        if (($_GET ['weibo_id'])) {
            $data ['id'] = intval($_GET ['weibo_id']);
        } elseif (($_GET ['id'])) {
            $data ['id'] = intval($_GET ['id']);
        }
        $detail = api('WeiboStatuses')->data($data)->show();

        $detail ['userGroupData'] = $this->_usergroup($detail ['uid']);

        $map ['source_id'] = $data ['id'];
        $map ['uid'] = $this->mid;
        $detail ['iscoll'] ['colled'] = model('Collection')->where($map)->count() ? 1 : 0;
        $detail ['favorited'] = $detail ['iscoll'] ['colled'];
        // $detail['is_favorite'] = api('Favorites')->data($data)->isFavorite() ? 1 : 0;
        // $detail['content'] = wapFormatContent($detail['content'], false, urlencode($this->_self_url));
        // $detail = $this->__formatByContent($detail);
        // optimize data

        if ((($detail ['type'] === 'repost' && $detail ['api_source'] ['is_del'] === '0') || ($detail ['type'] === 'repost' && $detail ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($detail ['transpond_data'] ['feed_content'])) {
            switch ($detail ['transpond_data'] ['type']) {
                case 'postimage':
                    $detail ['type'] = 'repost-postimage';
                    break;

                case 'postfile':
                    $detail ['type'] = 'repost-postfile';
                    foreach ($detail ['transpond_data'] ['attach'] as $k => $v) {
                        if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                        } elseif ($v ['size'] < 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                        } else {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                        }
                    }
                    break;

                case 'postvideo':
                    $detail ['type'] = 'repost-postvideo';
                    break;
            }
        } elseif (($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['api_source'] ['is_del'] === '1') && $detail ['type'] === 'repost') {
            $detail ['type'] = 'repost-removed';
        } elseif ($detail ['type'] === 'postfile') {
            foreach ($detail ['attach'] as $k => $v) {
                if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                } elseif ($v ['size'] < 1024) {
                    $detail ['attach'] [$k] ['size'] .= 'B';
                } else {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                }
            }
        } elseif ($detail ['type'] === 'weiba_repost') {
            if ($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['is_del'] === '1') {
                $detail ['type'] = 'weiba_repost-removed';
            }
        }
        $detail ['from'] = getFromClient($detail ['from'], $detail ['app']);

        if ($detail ['type'] == 'postvideo') {
            // $weibolist[$k]['content'] = $v['source_body'];
            $detail ['content'] = $detail ['feed_content'] ? $detail ['feed_content'] : $detail ['source_body'];
            $detail ['content'] = wapFormatContent($detail ['content'], true, $self_url, '视频');
        } else {
            $detail ['content'] = wapFormatContent($detail ['content'], true, $self_url);
        }
        // 非视频分享
        if ($detail ['transpond_data'] ['content']) {
            if (strpos($weibolist [$k] ['type'], 'video')) {
                $detail ['transpond_data'] ['content'] = wapFormatContent($detail ['transpond_data'] ['content'], true, $self_url, '视频');
            } else {
                $detail ['transpond_data'] ['content'] = wapFormatContent($detail ['transpond_data'] ['content'], true, $self_url);
            }
        }

        // 转发分享标志
        $detail ['repost'] = $detail ['app_row_id'];
        // 如果是转发，看是否有评论当前用户的权限
        $privacy1 = $this->privacy($detail ['uid']);
        $detail ['cancomment_current'] = 0;
        if ($privacy1 === true || $privacy1 ['comment_weibo'] == 0) {
            $detail ['cancomment_current'] = 1;
        }
        // 判断是否有评论作者或原文作者权限
        $origin_uid = $detail ['api_source'] ['uid'] ? $detail ['api_source'] ['uid'] : 0;
        $detail ['cancomment'] = 0;
        // 如果是转发，判断是否有评论给原作者的权限
        if ($origin_uid && $origin_uid != $this->mid) {
            $privacy = $this->privacy($origin_uid);
            if ($privacy === true || $privacy ['comment_weibo'] == 0) {
                $detail ['cancomment'] = 1;
            }
        }

        $this->assign('feed', $detail);
        // dump($detail);exit;
        // dump($detail);
        $data ['page'] = $this->_page;
        $data ['count'] = 20;
        $comment = api('WeiboStatuses')->data($data)->comments();
        foreach ($comment as $key => $value) {
            $comment [$key] ['level'] = M('credit_user')->where('uid='.$value ['uid'])->find();
            $comment [$key] ['userGroupData'] = $this->_usergroup($value ['uid']);
        }
        $this->assign('count', $detail ['comment_count']);
        $this->assign('comment', $comment);
        $this->assign('headtitle', '分享详情');
        $this->assign('uid', $this->mid);
        // dump($detail['api_source']);
        $this->display();
    }

    // 图片
    public function image()
    {
        $weibo_id = intval($_GET ['weibo_id']);
        if ($weibo_id <= 0) {
            $this->redirect(U('w3g/Index/index'), 3, '参数错误');
        }
        $weibo = api('Statuses')->data(array(
                'id' => $weibo_id,
        ))->show();

        $image = intval($weibo ['transpond_id']) == 0 ? $weibo ['type_data'] : $weibo ['transpond_data'] ['type_data'];
        if (empty($image)) {
            $this->redirect(U('w3g/Index/index'), 3, '无图片信息');
        }

        $this->assign('weibo_id', $weibo_id);
        $this->assign('image', $image);
        $this->display();
    }
    private function __followlist($type)
    {
        $data ['user_id'] = $_GET ['uid'] <= 0 ? $this->mid : intval($_GET ['uid']);
        $userPrivacy = $this->privacy($data ['user_id']);
        $data ['page'] = $this->_page;
        $data ['count'] = 10;
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile);
        $isAllowed = 0;
        $isMessage = 1;
        ($userPrivacy ['space'] == 1) && $isMessage = 0;
        $this->assign('sendmsg', $isMessage);
        if ($userPrivacy === true || $userPrivacy ['space'] == 0) {
            $isAllowed = 1;
            // 粉丝OR关注列表
            $followlist = api('User')->data($data)->$type ();
            // 数组组装符合T2 格式
            foreach ($followlist as $key => $value) {
                unset($followlist [$key]);
                $followlist [$key] ['user'] = $value;
                $followlist [$key] ['user'] ['userGroupData'] = $this->_usergroup($value ['uid']);
                $following = $followlist [$key] ['user'] ['follow_state'] ['following'];
                $follower = $followlist [$key] ['user'] ['follow_state'] ['follower'];

                if ($following) {
                    $followlist [$key] ['user'] ['follow_state'] ['value'] = '已关注';
                } else {
                    $followlist [$key] ['user'] ['follow_state'] ['value'] = '加关注';
                }

                if ($following && $follower) {
                    $followlist [$key] ['user'] ['follow_state'] ['value'] = '已互粉';
                }
            }
            // 分页模块
            if ($type === 'user_followers') {
                $count = D('W3gPage', 'w3g')->getMyFansCount($data ['user_id']);
            } else {
                $count = D('W3gPage', 'w3g')->getMyFollCount($data ['user_id']);
            }
        }
        $this->assign('isAllowed', $isAllowed);
        // 加标题
        if ($type == 'user_following' && $_GET ['uid'] == $this->mid) {
            $this->assign('datatitle', '我的关注');
        } elseif ($type == 'user_following' && $_GET ['uid'] != $this->mid) {
            $this->assign('datatitle', 'TA的关注');
        }
        if ($type == 'user_followers' && $_GET ['uid'] == $this->mid) {
            $this->assign('datatitle', '我的粉丝');
        } elseif ($type == 'user_followers' && $_GET ['uid'] != $this->mid) {
            $this->assign('datatitle', 'TA的粉丝');
        }

        $this->assign('count', $count);
        $this->assign('userlist', $followlist);
        // dump($followlist[2]);
        $this->assign('type', $type);
        $this->display('followlist');
    }

    // 关注
    public function doFollow()
    {
        $user_id = intval($_GET ['user_id']);
        if (!in_array($_GET ['from'], array(
                'user_following',
                'user_followers',
                'search',
                'weibo',
        )) || !in_array($_GET ['type'], array(
                'follow',
                'unfollow',
        )) || $user_id <= 0) {
            // redirect(U('w3g/Index/index'), 3, '参数错误');
            echo '0';
            exit();
        }
        $data ['user_id'] = $user_id;
        $method = $_GET ['type'] == 'follow' ? 'follow_create' : 'follow_destroy';
        if (api('User')->data($data)->$method ()) {
            echo '1';
        } else {
            echo '0';
        }
    }
    // 3.0W3G版这个没有用到
    public function post()
    {
        // 自动携带搜索的关键字
        $this->assign('keyword', isset($_REQUEST ['key']) ? '#'.$_REQUEST ['key'].'# ' : '');

        $this->assign('headtitle', '发表分享');

        // 检查可同步的平台的key值是否可用
        $config = model('AddonData')->lget('login');
        $this->assign('sync', count($config ['publish']));

        $this->display();
    }
    public function doPost()
    {
        $_POST ['content'] = preg_replace('/^\s+|\s+$/i', '', $_POST ['content']);

        $pathinfo = pathinfo($_FILES ['pic'] ['name']); // pathinfo() 函数以数组的形式返回文件路径的信息。
        $ext = $pathinfo ['extension']; // extension上传文件的后缀类型
        $allowExts = array(
                'jpg',
                'png',
                'gif',
                'jpeg',
        );

        $uploadCondition = $_FILES ['pic'] && in_array(strtolower($ext), $allowExts, true);

        if (!empty($_FILES ['pic'] ['tmp_name']) && !$uploadCondition) {
            // redirect(U('w3g/Index/index'), 3, '只能上传图片附件');
            $this->ajaxReturn(null, '只能上传图片附件', 0);
        }
        if (empty($_POST ['content']) && !$_FILES ['pic']) {
            // $this->redirect(U('w3g/Index/post'), 2, '内容不能为空');
            $this->ajaxReturn(null, '内容不能为空', 0);
        }
        // 下面是分拆选项中选择不分拆后的跳转
        if (isset($_POST ['nosplit'])) {
            $this->assign('content', $_POST ['content']);
            // $this->index();
            // $this->redirect(U('w3g/Index/index'), 2, '发布失败，字数超过限制');
            $this->ajaxReturn(null, '发布失败，字数超过限制', 0);
        }
        $data = array();
        // 获取附件
        if (isset($_POST ['feed_attach_type'])) {
            $feed_attach_type = strval($_POST ['feed_attach_type']);
            if ($feed_attach_type === 'image') {
                $data ['type'] = 'postimage';
            } elseif ($feed_attach_type === 'file') {
                $data ['type'] = 'postfile';
            }
        }
        if (isset($_POST ['attach_id'])) {
            $attach_id = strval($_POST ['attach_id']);
            if ($attach_id !== '') {
                $data ['attach_id'] = $attach_id;
            }
        }
        // 发布分享限制字数,与后台设置一样
        $admin_Config = model('Xdata')->lget('admin_Config');
        $weibo_nums = $admin_Config ['feed'] ['weibo_nums'];
        // 字数统计
        $length = mb_strlen($_POST ['content'], 'UTF8');
        $parts = ceil($length / $weibo_nums);
        /*
         * if (!isset($_POST['split']) && $length > $weibo_nums) {
         * // 自动发一条图片分享
         * if(!empty($_FILES['pic']['name'])){
         * $data['pic'] = $_FILES['pic'];
         * $data['content'] = '图片分享';
         * $data['from'] = $this->_type_wap;
         * $res = api('weiboStatuses')->data($data)->upload();
         * }
         * //echo 'many';
         * //exit();
         * // 提示是否自动拆分
         * // $this->assign('content', $_POST['content']);
         * // $this->assign('length', $length);
         * // $this->assign('parts', $parts);
         * //$this->display('split');
         * }else {
         */
        $api_method = 'update';
        if ($_FILES ['pic']) {
            $data ['pic'] = $_FILES ['pic'];
            $api_method = 'upload';
        }
        // 自动拆分成多条
        for ($i = 1; $i <= $parts; ++$i) {
            $sub_content = mb_substr($_POST ['content'], 0, 140, 'UTF8');
            $data ['content'] = $sub_content;
            $data ['from'] = $this->_type_wap;
            $data ['app_name'] = 'public';
            $_POST ['content'] = mb_substr($_POST ['content'], 140, -1, 'UTF8');
            $res = api('WeiboStatuses')->data($data)->$api_method ();
            // $res = $this->__formatByContent($res);
            if (!$res) {
                $this->ajaxReturn(null, '数据错误，请重试。', 0);
                // return ;
            } else {
                // 添加话题
                model('FeedTopic')->addTopic(html_entity_decode($data ['content'], ENT_QUOTES, 'UTF-8'), $res, 'post');
                model('Cache')->rm('fd_'.$res);
                model('Cache')->rm('feed_info_'.$res);
                // 添加积分
                X('Credit')->setUserCredit($this->mid, 'add_weibo');
                model('Credit')->setUserCredit($this->mid, 'forum_post');
                // $this->redirect(U('w3g/Index/doPostTrue'), 3, '发布成功');
                $this->ajaxReturn(null, '发布成功', 1);
                // echo 1;
                // $this->doPostTrue();
                // header("location:".U('w3g/Index/doPostTrue'));
            }
        }
        // }
        $this->ajaxReturn(null, '发布成功', 1);
    }

    // 发表成功后用来传递给首页发表成功的页面
    public function doPostTrue()
    {
        $uid = $data ['user_id'] = $_GET ['uid'] <= 0 ? $this->mid : $_GET ['uid'];
        $profile = api('User')->data($data)->show();
        $data ['id'] = $profile ['last_feed'] ["$uid"] ['feed_id'];
        $feed = api('WeiboStatuses')->data($data)->show();
        $feed ['from'] = getFromClient($feed ['from'], 'public');
        // $feed = $this->__formatByContent($feed);
        // dump($feed);
        $this->assign('feed', $feed);
        $this->display('doPostTrue');
    }

    /**
     * 发布分享操作，用于AJAX.
     *
     * @return json 发布分享后的结果信息JSON数据
     */
    public function feedPost()
    {
        // 返回数据格式
        // $return = array('status'=>1, 'data'=>'');
        // 用户发送内容
        $d ['content'] = isset($_POST ['content']) ? h($_POST ['content']) : '';
        $filterContentStatus = filter_words($d ['content']);
        if (!$filterContentStatus ['status']) {
            echo $filterContentStatus ['data'];
            exit();
            // exit(json_encode(array('status'=>0, 'data'=>$filterContentStatus['data'])));
        }
        $d ['content'] = $filterContentStatus ['data'];

        // 原始数据内容
        $filterBodyStatus = filter_words($_POST ['body']);
        if (!$filterBodyStatus ['status']) {
            echo $filterBodyStatus ['data'];
            exit();
            // $return = array('status'=>0,'data'=>$filterBodyStatus['data']);
            // exit(json_encode($return));
        }
        $d ['body'] = $filterBodyStatus ['data'];

        // 安全过滤
        foreach ($_POST as $key => $val) {
            $_POST [$key] = t($_POST [$key]);
        }
        $d ['source_url'] = urldecode($_POST ['source_url']); // 应用分享到分享，原资源链接
                                                            // 滤掉话题两端的空白
        $d ['body'] = preg_replace("/#[\s]*([^#^\s][^#]*[^#^\s])[\s]*#/is", '#'.trim('${1}').'#', $d ['body']);
        // 附件信息
        $d ['attach_id'] = trim(t($_POST ['attach_id']), '|');
        if (!empty($d ['attach_id'])) {
            $d ['attach_id'] = explode('|', $d ['attach_id']);
            array_map('intval', $d ['attach_id']);
        }
        if ($_POST ['video_id']) {
            $d ['video_id'] = intval($_POST ['video_id']);
        }
        // 发送分享的类型
        $type = t($_POST ['type']);
        $d ['from'] = $this->_type_wap;
        // 所属应用名称
        $app = isset($_POST ['app_name']) ? t($_POST ['app_name']) : APP_NAME; // 当前动态产生所属的应用
        if (!$data = model('Feed')->put($this->uid, $app, $type, $d)) {
            echo model('Feed')->getError();
            exit();
            // $return = array('status'=>0,'data'=>model('Feed')->getError());
            // exit(json_encode($return));
        }
        // 发布邮件之后添加积分
        model('Credit')->setUserCredit($this->uid, 'add_weibo');
        // 分享来源设置
        $data ['from'] = $this->_type_wap;
        // $data ['from'] = getFromClient ( $data ['from'], 'public');
        $this->assign($data);
        // 分享配置
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $this->assign('weibo_premission', $weiboSet ['weibo_premission']);
        $return ['data'] = $this->fetch('PostFeed');
        // echo 1;exit;
        // 分享ID
        $return ['feedId'] = $data ['feed_id'];
        $return ['is_audit'] = $data ['is_audit'];
        // 添加话题
        model('FeedTopic')->addTopic(html_entity_decode($d ['body'], ENT_QUOTES, 'UTF-8'), $data ['feed_id'], $type);
        // 更新用户最后发表的分享
        $last ['last_feed_id'] = $data ['feed_id'];
        $last ['last_post_time'] = $_SERVER ['REQUEST_TIME'];
        model('User')->where('uid='.$this->uid)->save($last);
        $isOpenChannel = model('App')->isAppNameOpen('channel');
        if (!$isOpenChannel) {
            echo $return ['data'];
            exit();
            // exit ( json_encode ( $return ) );
        }
        // 添加分享到投稿数据中
        $channelId = t($_POST ['channel_id']);
        // echo $channelId;exit;
        // 绑定用户
        $bindUserChannel = D('Channel', 'channel')->getCategoryByUserBind($this->mid);
        if (!empty($bindUserChannel)) {
            $channelId = array_merge($bindUserChannel, explode(',', $channelId));
            $channelId = array_filter($channelId);
            $channelId = array_unique($channelId);
            $channelId = implode(',', $channelId);
        }
        // 绑定话题
        $content = html_entity_decode($d ['body'], ENT_QUOTES, 'UTF-8');
        $content = str_replace('＃', '#', $content);
        preg_match_all("/#([^#]*[^#^\s][^#]*)#/is", $content, $topics);
        $topics = array_unique($topics [1]);
        foreach ($topics as &$topic) {
            $topic = trim(preg_replace('/#/', '', t($topic)));
        }
        $bindTopicChannel = D('Channel', 'channel')->getCategoryByTopicBind($topics);
        if (!empty($bindTopicChannel)) {
            $channelId = array_merge($bindTopicChannel, explode(',', $channelId));
            $channelId = array_filter($channelId);
            $channelId = array_unique($channelId);
            $channelId = implode(',', $channelId);
        }
        if (!empty($channelId)) {
            // 获取后台配置数据
            $channelConf = model('Xdata')->get('channel_Admin:index');
            $return ['is_audit_channel'] = $channelConf ['is_audit'];
            // 添加频道数据
            D('Channel', 'channel')->setChannel($data ['feed_id'], $channelId, false);
        }
        $this->doPostTrue();
        // exit(json_encode($return));
    }

    // 因为评论的方式变了，所以没有用到以下方法
    public function comment()
    {
        $weibo_id = intval($_GET ['weibo_id']);
        $comment_id = intval($_GET ['comment_id']);
        $uid = intval($_GET ['uid']);
        if ($weibo_id <= 0 || $uid <= 0) {
            // $this->redirect(U('w3g/Index/index'), 3, '参数错误');
            // return ;
            echo '参数错误';
            exit();
        }
        $this->assign('weibo_id', $weibo_id);
        $this->assign('comment_id', $comment_id);
        $this->assign('uname', getUserName($uid));
        $this->display();
    }

    /**
     * 添加评论接口，目前只支持分享与微吧.
     *
     * @return int 返回状态
     */
    public function doComment()
    {
        $r = array();
        $feed_id = intval($_POST ['feed_id']);
        if ($feed_id <= 0) {
            $r ['success'] = '0';
            $r ['des'] = '参数错误';
            echo json_encode($r);
            exit();
        }
        $content = t($_POST ['content']);
        if (empty($content)) {
            $r ['success'] = '0';
            $r ['des'] = '内容不能为空';
            echo json_encode($r);
            exit();
        }
        // 仅取前140字
        $content = mb_substr($content, 0, 140, 'UTF8');

        $type = t($_POST ['type']);

        if (in_array($type, array(
                'weiba_repost',
                'weiba_post',
        ))) {
            $data ['app_name'] = 'weiba';
            $data ['app_row_id'] = model('Feed')->where('feed_id='.$feed_id)->getField('app_row_id');
        } else {
            $data ['app_name'] = 'public';
        }
        $data ['table_name'] = 'feed';

        // app_uid 是被评论分享作者的ID
        $data ['app_uid'] = isset($_POST ['appid']) ? intval($_POST ['appid']) : '0';
        $data ['comment_old'] = isset($_POST ['comment_old']) ? (string) $_POST ['comment_old'] : '0';
        // 评论内容
        $data ['content'] = isset($content) ? $content : '';
        // row_id 是被评论分享的ID
        $data ['row_id'] = isset($_POST ['rowid']) ? intval($_POST ['rowid']) : '0';
        // 评论所需内容组装
        $data ['ifShareFeed'] = isset($_POST ['ifShareFeed']) ? intval($_POST ['ifShareFeed']) : '0';
        $data ['from'] = $this->_type_wap;
        $data ['at'] = isset($_POST ['at']) ? (string) $_POST ['at'] : '';
        $data ['uid'] = isset($_POST ['uid']) ? (int) $_POST ['uid'] : '0';
        $res = api('WeiboStatuses')->data($data)->comment();
        if ($res) {
            $this->doCommentTrue($data ['row_id'], intval($_POST ['feed_id']), $data ['uid']);
            // header("location:?app=w3g&mod=Index&act=doCommentTrue&rowid=".$data['row_id']."&weibo_id=".$_POST['feed_id']);
        } else {
            $r ['success'] = '0';
            $r ['des'] = '回复过于频繁，休息一下吧:)';
            echo json_encode($r);
            exit();
        }
    }

    // 发表成功后用来传递给首页发表成功的页面
    public function doCommentTrue($id, $feedId, $uid)
    {
        $_GET ['weibo_id'] = $feedId;
        $data ['id'] = $id;
        $data ['page'] = $this->_page;
        $data ['count'] = 10;
        $comment = api('WeiboStatuses')->data($data)->comments();
        $detail = api('WeiboStatuses')->data($data)->show();
        foreach ($comment as $key => $value) {
            $comment [$key] ['level'] = M('credit_user')->where('uid='.$value ['uid'])->find();
        }
        // $comment = $this->__formatByComment($comment);
        // dump($comment);
        $this->assign('weibo', $detail);
        $this->assign('comment', $comment);

        $r = array();
        $r ['success'] = '1';
        $r ['des'] = 'ok';
        // get comment ccid
        for ($i = 0; $i < count($comment); ++$i) {
            if ($comment [$i] ['uid'] == $uid) {
                $r ['ccid'] = $comment [$i] ['comment_id'];
                break;
            }
        }
        echo json_encode($r);
        exit();
        // $this->display('doCommentTrue');
    }

    // 对一条评论 评论后 用来传递给详情页回复成功的页面
    public function doCommentD()
    {
        $r = array();
        if (($feed_id = intval($_POST ['rowid'])) <= 0) {
            // $this->redirect(U('w3g/Index/index'), 3, '参数错误');
            $r ['success'] = '0';
            $r ['des'] = '参数错误';
            echo json_encode($r);
            exit();
        }
        if (empty($_POST ['content'])) {
            // $this->redirect(U('w3g/Index/detail',array('feed_id'=>$feed_id)), 3, '内容不能为空');
            // return ;
            $r ['success'] = '0';
            $r ['des'] = '内容不能为空';
            echo json_encode($r);
            exit();
        }
        // 原分享的内容
        $map ['comment_id'] = $_POST ['comment_id'];
        $preComment = M('Comment')->where($map)->find();
        // 仅取前140字
        $_POST ['content'] = mb_substr($_POST ['content'], 0, 140, 'UTF8');
        $data ['user_id'] = $_POST ['touid'];
        $commentd = api('User')->data($data)->show();
        // 整合被转发的内容
        // $_POST['content'] = "回复@{$commentd['uname']}：".$_POST['content']."//@{$commentd['uname']}：".$preComment['content'];
        $_POST ['content'] = "回复@{$commentd['uname']}：".$_POST ['content'];

        $data ['app'] = 'public';
        $data ['table'] = 'feed';
        // $data['app_row_id'] = isset($this->data['app_row_id']) ? $this->data['app_row_id'] : '0';
        $data ['app_uid'] = isset($_POST ['appid']) ? $_POST ['appid'] : '0'; // app_uid 是被评论分享作者的ID
        $data ['comment_old'] = isset($_POST ['comment_old']) ? $_POST ['comment_old'] : '0';
        $data ['content'] = isset($_POST ['content']) ? $_POST ['content'] : ''; // 评论内容
                                                                                // $data['content'] = mb_substr($_POST['content'], 0, $_POST['weibo_nums'], 'UTF8');
        $data ['row_id'] = isset($_POST ['rowid']) ? $_POST ['rowid'] : '0'; // row_id 是被评论分享的ID
        $commentInfo = model('Comment')->getCommentInfo(intval($_POST ['comment_id']));
        if ($commentInfo ['app'] == 'weiba') {
            $feedInfo = model('Feed')->getFeedInfo($data ['row_id']);
            $data ['app_row_id'] = $feedInfo ['app_row_id'];
        }
        $data ['to_comment_id'] = isset($_POST ['comment_id']) ? $_POST ['comment_id'] : '0';
        $data ['to_uid'] = isset($_POST ['touid']) ? $_POST ['touid'] : '0';
        $data ['ifShareFeed'] = isset($_POST ['ifShareFeed']) ? $_POST ['ifShareFeed'] : '0';
        $data ['at'] = $_POST ['at'];
        $data ['from'] = $this->_type_wap;
        $res = api('WeiboStatuses')->data($data)->comment();
        // $res = $this->__formatByContent($res);
        if ($res) {
            // header("location:?app=w3g&mod=Index&act=doCommentTrue&rowid=".$data['row_id']."&weibo_id=".$_POST['rowid']);
            $this->doCommentTrue($data ['row_id'], intval($_POST ['rowid']));
        } else {
            $r ['success'] = '0';
            $r ['des'] = 'error';
            echo json_encode($r);
            exit();
        }
    }

    // 转发 forward转发的意思
    public function forward()
    {
        $r = array();
        $weibo_id = intval($_GET ['weibo_id']);
        if ($weibo_id <= 0) {
            // $this->redirect(U('w3g/Index/index'), 3, '参数错误');
            // return ;
            echo '参数错误';
            exit();
        }
        $data ['id'] = $weibo_id;
        $weibo = api('WeiboStatuses')->data($data)->show();
        // $weibo = $this->__formatByContent($weibo);
        if (!$weibo) {
            // $this->redirect(U('w3g/Index/index'), 3, '参数错误');
            // return ;
            echo '参数错误';
            exit();
        }

        $this->assign('weibo', $weibo);
        $this->assign('headtitle', '转发分享');
        $this->display();
    }

    // 接受转发数据
    public function doForward()
    {
        // 获取传入的值
        $post = $_POST;
        // 安全过滤
        foreach ($post as $key => $val) {
            $post [$key] = t($post [$key]);
        }
        // 过滤内容值
        // $post['body'] = filter_keyword($post['body']);
        $filterBodyStatus = filter_words($post ['body']);
        if (!$filterBodyStatus ['status']) {
            echo $filterBodyStatus ['data'];
            exit();
            // $return = array('status'=>0,'data'=>$filterBodyStatus['data']);
            // exit(json_encode($return));
        }
        $post ['body'] = $filterBodyStatus ['data'];

        // 判断资源是否删除
        if (empty($post ['curid'])) {
            $map ['feed_id'] = intval($post ['sid']);
        } else {
            $map ['feed_id'] = intval($post ['curid']);
        }
        $map ['is_del'] = 0;
        $isExist = model('Feed')->where($map)->count();
        if ($isExist == 0) {
            // $return['status'] = 0;
            echo $return ['data'] = '内容已被删除，转发失败';
            exit();
            // exit(json_encode($return));
        }

        // 进行分享操作
        $return = model('Share')->shareFeed($post, 'share');
        if ($return ['status'] == 1) {
            $app_name = $post ['app_name'];

            // 添加积分
            if ($app_name == 'public') {
                model('Credit')->setUserCredit($this->uid, 'forward_weibo');
                // 分享被转发
                $suid = model('Feed')->where($map)->getField('uid');
                model('Credit')->setUserCredit($suid, 'forwarded_weibo');
            }
            if ($app_name == 'weiba') {
                model('Credit')->setUserCredit($this->uid, 'forward_topic');
                // 分享被转发
                $suid = D('Feed')->where('feed_id='.$map ['feed_id'])->getField('uid');
                model('Credit')->setUserCredit($suid, 'forwarded_topic');
            }

            $this->assign($return ['data']);
            // 分享配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $this->assign('weibo_premission', $weiboSet ['weibo_premission']);
            $return ['data'] = $this->fetch('PostFeed');
            $this->doForwardTrue();
            exit();
        } else {
            echo '0';
            exit();
        }
        // exit(json_encode($return));
        // $r=array();
        // $weibo_id = intval($_POST['feed_id']);
        // if ($weibo_id <= 0) {
        // echo '参数错误';
        // exit();
        // }
        // if (empty($_POST['content'])) {
        // echo '内容不能为空';
        // exit();
        // }

        // $data['id'] = $weibo_id;
        // $weibo = api('WeiboStatuses')->data($data)->show();
        // unset($data);
        // if ( empty($weibo) ) {
        // // redirect(U('wap/Index/index'), 3, '参数错误');
        // echo '参数错误';
        // exit();
        // }
        // $p['comment'] = $_POST['comment'];
        // // 整合被转发的内容
        // if ( $weibo['is_repost'] == 1 ) {
        // $_POST['content'] .= "//@{$weibo['uname']}：{$weibo['feed_content']}";
        // }

        // // 仅取前140字
        // $admin_Config = model('Xdata')->lget('admin_Config');
        // $weibo_nums = $admin_Config['feed']['weibo_nums'];
        // $_POST['content'] = mb_substr($_POST['content'], 0,$weibo_nums , 'UTF8');

        // $data['content'] = $_POST['content'];
        // $data['from'] = $this->_type_wap;
        // $data['transpond_id'] = $weibo['transpond_id'] ? $weibo['transpond_id'] : $weibo_id;
        // if (intval($_POST['isComment']) == 1) {
        // $weibo = api('WeiboStatuses')->data(array('id'=>$weibo_id))->show();
        // // $weibo = $this->__formatByContent($weibo);
        // $data['reply_data'] = $weibo['weibo_id'];
        // if ( !empty($weibo['transpond_data']) ) {
        // $data['reply_data'] .= ',' . $weibo['transpond_data']['weibo_id'];
        // }
        // }
        // // 组装接口数据
        // $p['app_name'] = $weibo['app'];
        // $p['body'] = $_POST['content'];
        // $p['content'] = $_POST['content'];
        // if(!in_array($weibo['type'], array('repost', 'weiba_post', 'weiba_repost'))) {
        // $p['id'] = $weibo['feed_id'];
        // $weibo['type'] = 'feed';
        // } elseif ($weibo['type'] == 'weiba_post' || $weibo['type'] == 'weiba_repost'){
        // $p['id'] = $weibo['app_row_id'];
        // $weibo['type'] = 'weiba_post';
        // $weibo['app_row_table'] = 'feed';
        // } else {
        // $p['id'] = $weibo['app_row_id'];
        // $weibo['type'] = 'feed';
        // }
        // $p['type'] = $weibo['type'];
        // $p['from'] = $data['from'] ? intval($data['from']) : '0';
        // $p['forApi'] = true;
        // $p['curid'] = $weibo_id;
        // $p['curtable'] = $weibo['app_row_table'];
        // $p['sid'] = $p['id'];
        // $p['comment_touid'] = intval($_POST['comment_touid']);
        // $res = api('WeiboStatuses')->data($p)->repost();
        // if ($res) {
        // // redirect(U('wap/Index/detail', array('weibo_id'=>$weibo_id,'type'=>$weibo['type'])), 1, '转发成功');
        // // redirect(U('wap/Index/index'), 1, '转发成功');
        // //添加积分
        // X('Credit')->setUserCredit($this->mid,'add_weibo');
        // model('Credit')->setUserCredit($this->mid, 'forum_post');
        // // $this->redirect(U('w3g/Index/doPostTrue'), 3, '发布成功');
        // // header("location:".U('w3g/Index/doForwardTrue'));
        // $this->doForwardTrue();
        // }else {
        // // redirect(U('wap/Index/detail', array('weibo_id'=>$weibo_id)), 3, '转发失败, 请稍后重试');
        // echo '0';
        // }
    }

    // 转发成功后用来传递给首页发表成功的页面
    public function doForwardTrue()
    {
        $uid = $data ['user_id'] = $_GET ['uid'] <= 0 ? $this->mid : $_GET ['uid'];
        $profile = api('User')->data($data)->show();
        $data ['id'] = $profile ['last_feed'] ["$uid"] ['feed_id'];
        $detail = api('WeiboStatuses')->data($data)->show();
        // optimize data
        if ((($detail ['type'] === 'repost' && $detail ['api_source'] ['is_del'] === '0') || ($detail ['type'] === 'repost' && $detail ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($detail ['transpond_data'] ['feed_content'])) {
            switch ($detail ['transpond_data'] ['type']) {
                case 'postimage':
                    $detail ['type'] = 'repost-postimage';
                    break;
                case 'postvideo':
                    $detail ['type'] = 'repost-postvideo';
                    break;
                case 'postfile':
                    $detail ['type'] = 'repost-postfile';
                    foreach ($detail ['transpond_data'] ['attach'] as $k => $v) {
                        if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                        } elseif ($v ['size'] < 1024) {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                        } else {
                            $detail ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                        }
                    }
                    break;
            }
        } elseif (($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['api_source'] ['is_del'] === '1') && $detail ['type'] === 'repost') {
            $detail ['type'] = 'repost-removed';
        } elseif ($detail ['type'] === 'postfile') {
            foreach ($detail ['attach'] as $k => $v) {
                if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                } elseif ($v ['size'] < 1024) {
                    $detail ['attach'] [$k] ['size'] .= 'B';
                } else {
                    $detail ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                }
            }
        } elseif ($detail ['type'] === 'weiba_repost') {
            if ($detail ['api_source'] ['is_del'] === '1' || $detail ['transpond_data'] ['is_del'] === '1') {
                $detail ['type'] = 'weiba_repost-removed';
            }
        }
        // $feed = $this->__formatByContent($feed);
        $this->assign('feed', $detail);
        $this->display('doForwardTrue');
    }

    // 删除分享
    public function doDelete()
    {
        $weibo_id = intval($_POST ['weibo_id']);
        $type = $_POST ['type'];
        if ($weibo_id <= 0) {
            // $this->redirect(U('w3g/Index/index', 3, '参数错误'));
            // return ;
            echo '参数错误';
            exit();
        }
        $data ['id'] = $weibo_id;
        $detail = api('WeiboStatuses')->data($data)->show();
        $data ['source_table_name'] = $detail ['app_row_table'];

        // 不存在时
        if (!$detail) {
            echo 0;
            exit();
        }
        // 非作者时
        if ($detail ['uid'] != $this->mid) {
            // 没有管理权限不可以删除
            if (!CheckPermission('core_admin', 'feed_del')) {
                echo 0;
                exit();
            }
            // 是作者时
        } else {
            // 没有前台权限不可以删除
            if (!CheckPermission('core_normal', 'feed_del')) {
                echo 0;
                exit();
            }
        }

        $res = api('WeiboStatuses')->data($data)->destroy();
        // 微吧帖子删除
        switch ($type) {
            case 'weiba_post':
                $postInfo = D('weiba_post')->where('feed_id='.$weibo_id)->find();
                $postId = $postInfo ['post_id'];
                $weibaId = $postInfo ['weiba_id'];
                if (D('weiba_post')->where('post_id='.$postId)->setField('is_del', 1)) {
                    $postDetail = D('weiba_post')->where('post_id='.$postId)->find();
                    D('Log', 'weiba')->writeLog($postDetail ['weiba_id'], $this->mid, '删除了帖子“'.$postDetail ['title'].'”', 'posts');
                    D('weiba')->where('weiba_id='.$weibaId)->setDec('thread_count');
                    model('Credit')->setUserCredit($this->mid, 'delete_topic');
                }
                break;
        }
        if ($res) {
            echo '1';
            exit();
        } else {
            echo '0';
            exit();
        }
    }

    // 收藏
    public function doFavorite()
    {
        $r = array();
        $weibo_id = intval($_POST ['feed_id']);
        if ($weibo_id <= 0) {
            // redirect(U('w3g/Index/index', 3, '参数错误'));
            echo '参数错误';
            exit();
        }
        $data ['id'] = $weibo_id;
        // 收藏数据组合
        $detail = api('WeiboStatuses')->data($data)->show();
        // $data['source_table_name'] = $detail['app_row_table'];
        $data ['source_table_name'] = 'feed';
        $data ['source_id'] = $detail ['feed_id'];
        // $data['source_app'] = $detail['app'];
        $data ['source_app'] = 'public';
        $res = api('WeiboStatuses')->data($data)->favorite_create();
        /*$res = */$this->__formatByContent($res);
        if ($res) {
            $r ['success'] = '1';
            $r ['des'] = 'OK';
            echo json_encode($r);
            exit();
        } else {
            $r ['success'] = '0';
            $r ['des'] = 'error';
            echo json_encode($r);
            exit();
        }
    }

    // 取消收藏
    public function doUnFavorite()
    {
        $r = array();
        $type = empty($_POST ['type']) ? $type = 'feed' : $type = $_POST ['type'];
        $weibo_id = intval($_POST ['feed_id']);
        if ($weibo_id <= 0) {
            // redirect(U('w3g/Index/index', 3, '参数错误'));
            echo '参数错误';
            exit();
        }
        $data ['id'] = $weibo_id;
        // $res = api('Favorites')->data($data)->destroy();
        $res = model('Collection')->delCollection($data ['id'], $type);
        // dump($res);
        if ($res) {
            $r ['success'] = '1';
            $r ['des'] = 'OK';
            echo json_encode($r);
            exit();
        } else {
            $r ['success'] = '0';
            $r ['des'] = 'error';
            echo json_encode($r);
            exit();
        }
    }
    public function urlalert()
    {
        if (!isset($_GET ['url']) || !isset($_GET ['from_url'])) {
            redirect(U('w3g/Index/index'), 3, '参数错误');
        }
        $this->assign('url', $_GET ['url']);
        $this->assign('from_url', $_GET ['from_url']);
        $this->display();
    }

    // URL重定向
    public function redirect($url, $time = 0, $msg = '')
    {
        // 多行URL地址支持
        $url = str_replace(array(
                "\n",
                "\r",
        ), '', $url);
        if (empty($msg)) {
            $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: '.$url);
            } else {
                header("refresh:{$time};url={$url}");
                // 防止手机浏览器下的乱码
                $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $str .= $msg;
            }
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
        }
        $this->assign('msg', $str);
        $this->display('redirect');
        exit();
    }

    // 获取最新分享数
    public function countnew()
    {
        $map = "weibo_id>{$_POST['nowMaxID']} AND isdel=0";
        $map .= ' AND ( uid IN (SELECT fid FROM '.C('DB_PREFIX')."weibo_follow WHERE uid=$this->uid) OR uid=$this->uid )";
        $countnew = M('Weibo')->where($map)->count();
        echo $countnew ? $countnew : '0';
    }

    // 搜索话题
    public function doSearch()
    {
        $data ['search_key'] = t($_REQUEST ['key']);
        // 专题信息
        if (false == $data ['topics'] = model('FeedTopic')->getTopic($data ['search_key'], false)) {
            if (!$data ['topics']) {
                $this->error('此话题不存在');
            }
            $data ['topics'] ['name'] = t($data ['search_key']);
        }
        if ($data ['topics'] ['lock'] == 1) {
            $this->error('该话题已被屏蔽');
        }
        if ($data ['topics'] ['pic']) {
            $pic = D('attach')->where('attach_id='.$data ['topics'] ['pic'])->find();
            // $data['topics']['pic'] = UPLOAD_URL.'/'.$pic['save_path'].$pic['save_name'];
            $pic_url = $pic ['save_path'].$pic ['save_name'];
            $data ['topics'] ['pic'] = getImageUrl($pic_url);
        }
        $data ['topic'] = $data ['search_key'] ? $data ['search_key'] : html_entity_decode($data ['topics'] ['name'], ENT_QUOTES, 'UTF-8');
        $data ['topic_id'] = $data ['topics'] ['topic_id'] ? $data ['topics'] ['topic_id'] : model('FeedTopic')->getTopicId($data ['search_key']);
        $initHtml = '#'.$data ['search_key'].'#';
        $this->assign('initHtml', $initHtml);
        $this->assign($data);
        // seo
        $seo = model('Xdata')->get('admin_Config:seo_feed_topic');
        $replace ['topicName'] = $data ['topic'];
        $replace ['topicNote'] = $data ['topics'] ['note'];
        $replace ['topicDes'] = $data ['topics'] ['des'];
        if ($lastTopic = D('feed_data')->where('feed_id='.D('feed_topic_link')->where('topic_id='.$data ['topic_id'])->order('feed_topic_id desc')->limit(1)->getField('feed_id'))->getField('feed_content')) {
            $replace ['lastTopic'] = $lastTopic;
        }
        $replaces = array_keys($replace);
        foreach ($replaces as &$v) {
            $v = '{'.$v.'}';
        }
        $max_id = intval($_REQUEST ['max_id']);
        // 搜索分享
        $weibolist = model('Feed')->searchFeed($data ['search_key'], 'topic', $max_id, 20);
        $this->assign('count', $weibolist ['totalPages']);
        $fids = getSubByKey($weibolist ['data'], 'feed_id');
        // $maps['feed_id'] = array('in', $fids);
        // $weibolist = api('WeiboStatuses')->data($maps)->friends_timeline();
        $weibolist = model('Feed')->formatFeed($fids, true);
        $weibolist = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $weibolist);
        $seo ['title'] = str_replace($replaces, $replace, $seo ['title']);
        $seo ['keywords'] = str_replace($replaces, $replace, $seo ['keywords']);
        $seo ['des'] = str_replace($replaces, $replace, $seo ['des']);
        !empty($seo ['title']) && $this->setTitle($seo ['title']);
        !empty($seo ['keywords']) && $this->setKeywords($seo ['keywords']);
        !empty($seo ['des']) && $this->setDescription($seo ['des']);
        $this->display('searchtopic');
    }
    // ajax上传-iframe页
    public function ajax_iframe()
    {
        $this->assign('headtitle', 'AJAX_iframe');
        $this->display('ajax_iframe');
    }
    public function ajax_image_upload()
    {
        $data ['attach_type'] = t($_REQUEST ['attach_type']);
        $data ['upload_type'] = $_REQUEST ['upload_type'] ? t($_REQUEST ['upload_type']) : 'file';

        $thumb = intval($_REQUEST ['thumb']);
        $width = intval($_REQUEST ['width']);
        $height = intval($_REQUEST ['height']);
        $cut = intval($_REQUEST ['cut']);

        $option ['attach_type'] = $data ['attach_type'];
        $info = model('Attach')->upload($data, $option);

        if ($info ['status']) {
            $data = $info ['info'] [0];
            if ($thumb == 1) {
                $data ['src'] = getImageUrl($data ['save_path'].$data ['save_name'], $width, $height, $cut);
            } else {
                $data ['src'] = $data ['save_path'].$data ['save_name'];
            }

            $data ['extension'] = strtolower($data ['extension']);
            $return = array(
                    'status' => 1,
                    'data' => $data,
            );
        } else {
            $return = array(
                    'status' => 0,
                    'data' => $info ['info'],
            );
        }
        if ($_REQUEST['ischannel']) {
            $this->assign('ischannel', 1);
        }
        $this->assign('return', $return);
        $this->assign('attach_id', $return ['data'] ['attach_id']);
        $this->assign('attach_src', $return ['data'] ['src']);
        $this->assign('attach_type', $return ['data'] ['type']);

        $this->display('ajax_image_upload');
    }
    public function listbyid($uid = 0)
    {
        $data ['user_id'] = $uid <= 0 ? $this->mid : $uid;
        $data ['since_id'] = $_REQUEST ['since_id'] ? intval($_REQUEST ['since_id']) : '';
        $data ['max_id'] = $_REQUEST ['max_id'] ? intval($_REQUEST ['max_id']) : '';
        // $data['page'] = $this->_page;
        $data ['count'] = $_REQUEST ['count'] ? intval($_REQUEST ['count']) : 20;
        // dump($data);
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile ['avatar_small']);
        // 分享列表friends_timeline
        $weibolist = api('WeiboStatuses')->data($data)->friends_timeline();
        $weibolist = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $weibolist);
        // dump($weibolist['0']);
        // 分页模块
        // $count = D('W3gPage', 'w3g')->getWeiboCount($data['type'], $data['user_id']);
        $count = D('W3gPage', 'w3g')->getWeiboLatestId($data ['type'], $data ['user_id']);
        $this->assign('count', (int) $count [0] ['feed_id']);
        $this->assign('eachpage', $data ['count']); // weibo counts in eachpage
        /*
         * $maxWeiboID = $weibolist['0']['weibo_id'];
         * $this->assign('maxWeioboID',$maxWeiboID);
         * $this->assign('xin','xin');
         */
        $this->assign('headtitle', '分享');
        foreach ($this->tVar ['weibolist'] as $key => $value) { // optimize data
            if ((($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '0') || ($this->tVar ['weibolist'] [$key] ['type'] === 'repost' && $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '0')) && isset($this->tVar ['weibolist'] [$key] ['transpond_data'] ['feed_content'])) {
                switch ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['type']) {
                    case 'postimage':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postimage';
                        break;

                    case 'postfile':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postfile';
                        foreach ($this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] as $k => $v) {
                            if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                            } elseif ($v ['size'] < 1024) {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] .= 'B';
                            } else {
                                $this->tVar ['weibolist'] [$key] ['transpond_data'] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                            }
                        }
                        break;

                    case 'postvideo':
                        $this->tVar ['weibolist'] [$key] ['type'] = 'repost-postvideo';
                        break;
                }
            } elseif (($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['api_source'] ['is_del'] === '1') && $this->tVar ['weibolist'] [$key] ['type'] === 'repost') {
                $this->tVar ['weibolist'] [$key] ['type'] = 'repost-removed';
            } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'postfile') {
                foreach ($this->tVar ['weibolist'] [$key] ['attach'] as $k => $v) {
                    if ($v ['size'] > 1024 && $v ['size'] < 1024 * 1024) {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024, 2).'K';
                    } elseif ($v ['size'] < 1024) {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] .= 'B';
                    } else {
                        $this->tVar ['weibolist'] [$key] ['attach'] [$k] ['size'] = round($v ['size'] / 1024 / 1024, 2).'M';
                    }
                }
            } elseif ($this->tVar ['weibolist'] [$key] ['type'] === 'weiba_repost') {
                if ($this->tVar ['weibolist'] [$key] ['api_source'] ['is_del'] === '1' || $this->tVar ['weibolist'] [$key] ['transpond_data'] ['is_del'] === '1') {
                    $this->tVar ['weibolist'] [$key] ['type'] = 'weiba_repost-removed';
                }
            }
        }
        $this->display('listbyid');
    }
    // image & text page
    public function imgtext()
    {
        $this->display();
    }
    public function imgdetail()
    {
        $map ['id'] = intval($_REQUEST ['id']);
        $info = M('img')->where($map)->find();
        $this->assign('info', $info);
        // dump($info);
        $this->display('imgtx_detail');
    }
    public function profile()
    {
        $uid = intval($_GET ['uid']) ? intval($_GET ['uid']) : $this->mid;
        // 判断隐私设置
        $userPrivacy = $this->privacy($uid);
        $isAllowed = 0;
        $isMessage = 1;
        ($userPrivacy ['space'] == 1) && $isMessage = 0;
        $this->assign('sendmsg', $isMessage);

        if ($userPrivacy === true || $userPrivacy ['space'] == 0) {
            $isAllowed = 1;
        }
        $this->assign('isAllowed', $isAllowed);
        $this->assign('uid', $uid);
        // 获取我的个人信息
        // $user = getUserInfo($uid);
        $data ['user_id'] = $uid;
        $data ['page'] = 1;
        $profile = api('User')->data($data)->show();
        // dump($profile);exit;
        // if(!$profile['uname']){
        // redirect(U('w3g/Public/home'), 3, '参数错误');
        // }
        $this->assign('profile', $profile);
        if ($this->mid == $this->uid) {
            $this->assign('datatitle', '我的资料');
        } else {
            $this->assign('datatitle', 'TA的资料');
        }
        $this->display();
    }

    /**
     * 发送私信弹窗.
     */
    public function sendmsg()
    {
        $touid = t($_GET ['uid']);
        $max = $_REQUEST ['max'] ? intval($_REQUEST ['max']) : 10;
        $this->assign('max', $max);
        $this->assign('touid', $touid);
        // 是否能够编辑用户
        $editable = $_REQUEST ['editable'] === 0 ? 0 : 1;
        $this->assign('editable', $editable);

        $this->display();
    }
    /**
     * 返回好友分组列表.
     */
    public function atwho()
    {
        $groupusers = model('Follow')->getFollowingListAll($this->mid);
        $users = array();
        if (is_array($groupusers)) {
            foreach ($groupusers as $k => $gu) {
                $users [] = model('User')->getUserInfoForSearch($gu ['fid'], 'uid,uname');
            }
        } else {
            echo '';
            exit();
        }
        $this->assign('groupusers', $users);
        $this->display();
    }
    /* 检索好友 */
    public function SearchUser()
    {
        $name = t($_POST ['at_search']);
        $groupusers = model('Follow')->getFollowingListAll($this->mid);
        if (is_array($groupusers)) {
            $fids = getSubByKey($groupusers, 'fid');
            $fid_str = implode(',', $fids);
            $user_id_list = array();
            if ($name) {
                $where = 'uid in('.$fid_str.') and uname like"%'.$name.'%"';
                $user_list = M('User')->where($where)->findAll();
                is_array($user_list) and $user_id_list = getSubByKey($user_list, 'uid');
            } else {
                $user_id_list = $fids;
            }
            $users = array();
            $str = '';
            foreach ($user_id_list as $gu) {
                $u = model('User')->getUserInfoForSearch($gu, 'uid,uname');
                $str .= '<ul><li class="ts-listen" data-listen="weibo-at-add" data-at="'.$u ['uname'].'"><a href="javascript:void(0);"><img alt="'.$u ['uname'].'" src="'.$u ['avatar_small'].'">'.$u ['uname'].'</a></li></ul>';
            }
            echo $str;
            exit();
        } else {
            echo '';
            exit();
        }
    }

    // 获取推荐话题
    public function rec_topic()
    {
        $map ['recommend'] = 1;
        $map ['lock'] = 0;
        $list = model('Cache')->get('feed_topic_recommend');
        if (!$list) {
            $list = model('FeedTopic')->where($map)->order('count desc')->limit(10)->findAll();
            !$list && $list = 1;
            model('Cache')->set('feed_topic_recommend', $list, 86400);
        }
        if (!is_array($list)) {
            $list = array();
        }
        $this->assign('topic_list', $list);
        $this->display();
    }

    // 频道列表
    public function channel_list()
    {
        $max_id = $_REQUEST ['max_id'] ? intval($_REQUEST ['max_id']) : 0;
        $count = $_REQUEST ['count'] ? intval($_REQUEST ['count']) : 20;
        !empty($max_id) && $where = " sort > {$max_id}";
        $channels = D('channel_category')->where($where)->limit($count)->field('channel_category_id,title,sort')->order('sort ASC')->findAll();
        foreach ($channels as $k => $v) {
            // $big_image = D('channel')->where('channel_category_id='.$v['channel_category_id'].' and width>=590 and height>=245')->max('feed_id');
            // if($big_image && false){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$big_image)->getField('feed_data'));
            // $big_image_info = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][0] = getImageUrl($big_image_info['save_path'].$big_image_info['save_name'], 590, 245, true);;
            // }else{
            // $channels[$k]['image'][0] = SITE_URL.'/apps/channel/_static/image/api_big.png';
            // }
            $small_image = D('channel')->where('channel_category_id='.$v ['channel_category_id'].' and width>=196 and width<590 and height>=156 and height<245')->order('feed_id desc')->limit(6)->findAll();
            for ($i = 0; $i < 6; ++$i) {
                $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image [$i] ['feed_id'])->getField('feed_data'));
                $small_image_info_3 = model('Attach')->getAttachById($feed_data ['attach_id'] [0]);
                if (!$small_image [$i] || !is_array($feed_data) || !is_array($small_image_info_3)) {
                    continue;
                }
                // echo $small_image_info_3['save_path'].$small_image_info_3['save_name'];exit;
                $channels [$k] ['image'] [$i + 1] = getImageUrl($small_image_info_3 ['save_path'].$small_image_info_3 ['save_name'], 196, 156, true);
                $channels [$k] ['feed_id'] [$i + 1] = $small_image [$i] ['feed_id'];
            }
            // if($small_image[0]){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[0]['feed_id'])->getField('feed_data'));
            // $small_image_info_1 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][0] = getImageUrl($small_image_info_1['save_path'].$small_image_info_1['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][0] = SITE_URL.'/apps/channel/_static/image/api_small_1.png';
            // }
            // $channels[$k]['feed_id'][0] = $small_image[0]['feed_id'];
            // if($small_image[1]){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[1]['feed_id'])->getField('feed_data'));
            // $small_image_info_2 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][1] = getImageUrl($small_image_info_2['save_path'].$small_image_info_2['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][1] = SITE_URL.'/apps/channel/_static/image/api_small_2.png';
            // }
            // $channels[$k]['feed_id'][1] = $small_image[1]['feed_id'];
            // if($small_image[2]){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[2]['feed_id'])->getField('feed_data'));
            // $small_image_info_3 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][2] = getImageUrl($small_image_info_3['save_path'].$small_image_info_3['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][2] = SITE_URL.'/apps/channel/_static/image/api_small_3.png';
            // }
            // $channels[$k]['feed_id'][2] = $small_image[2]['feed_id'];
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[3]['feed_id'])->getField('feed_data'));
            // $small_image_info_3 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // if($small_image[3] && is_array($feed_data) && is_array($small_image_info_3)){
            // $channels[$k]['image'][3] = getImageUrl($small_image_info_3['save_path'].$small_image_info_3['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][3] = SITE_URL.'/apps/channel/_static/image/api_small_3.png';
            // }
            // $channels[$k]['feed_id'][3] = $small_image[3]['feed_id'];
            // if($small_image[4]){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[4]['feed_id'])->getField('feed_data'));
            // $small_image_info_3 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][4] = getImageUrl($small_image_info_3['save_path'].$small_image_info_3['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][4] = SITE_URL.'/apps/channel/_static/image/api_small_3.png';
            // }
            // $channels[$k]['feed_id'][4] = $small_image[4]['feed_id'];
            // if($small_image[5]){
            // $feed_data = unserialize(D('feed_data')->where('feed_id='.$small_image[5]['feed_id'])->getField('feed_data'));
            // $small_image_info_3 = model('Attach')->getAttachById($feed_data['attach_id'][0]);
            // $channels[$k]['image'][5] = getImageUrl($small_image_info_3['save_path'].$small_image_info_3['save_name'], 196, 156, true);
            // }else{
            // $channels[$k]['image'][5] = SITE_URL.'/apps/channel/_static/image/api_small_3.png';
            // }
            // $channels[$k]['feed_id'][5] = $small_image[5]['feed_id'];
            $channels [$k] ['is_follow'] = intval(D('ChannelFollow', 'channel')->getFollowStatus($this->mid, $v ['channel_category_id']));
        }
        $this->assign('channels', $channels);
        $this->display();
    }

    public function delfeed()
    {
        print_r($_POST);
    }
}
