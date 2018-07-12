<?php

class ChannelAction extends BaseAction
{
    public function index()
    {
        $cid = intval($_REQUEST['cid']);
        $channelConf = model('Xdata')->get('channel_Admin:index');
        if (empty($cid)) {
            $cid = $channelConf['default_category'];
        }
        $cid or $cid = D('channel_category')->limit(1)->order('channel_category_id ASC')->getField('channel_category_id');
        //echo $cid;exit;
        $cate = model('CategoryTree')->setTable('channel_category')->getCategoryById($cid);
        if (!is_array($cate)) {
            $this->error('参数错误', 3, U('w3g/Index/channel_list'));
        }
        $channelCategory = model('CategoryTree')->setTable('channel_category')->getCategoryList();
        $this->assign('channelCategory', $channelCategory);
        // 获取分享数据
        $list = D('Channel', 'channel')->getDataWithCid($cid, $loadId, $loadLimit);
        $fids = getSubByKey($list['data'], 'feed_id');
        $weibolist = model('Feed')->formatFeed($fids, true);
        // 分页的设置
//    	if(!empty($list['data'])) {
//            $content['firstId'] = $['firstId'] = $list['data'][0]['feed_channel_link_id'];
//            $content['lastId'] = $list['data'][(count($list['data'])-1)]['feed_channel_link_id'];
//            // 分享配置
//            $weiboSet = model('Xdata')->get('admin_Config:feed');
//            //$var['weibo_premission'] = $weiboSet['weibo_premission'];
//    	}
        $list['data'] = $this->__formatByContent($weibolist);
        $this->assign('weibolist', $list['data']);
        $this->assign('count', $list['totalRows']);
        $this->assign('cid', $cid);
//        dump($channelCategory);exit;
        $this->assign('cate', $cate);
        $this->display();
    }

    private function _usergroup($uid)
    {
        $var['uid'] = $uid;
        // 获取用户信息
        //$var['userInfo'] = model('User')->getUserInfo($var['uid']);
        // 获取用户用户组信息
        $userGids = model('UserGroupLink')->getUserGroup($var['uid']);
        $userGroupData = model('UserGroup')->getUserGroupByGids($userGids[$var['uid']]);
        if (is_array($userGroupData)) {
            foreach ($userGroupData as $key => $value) {
                if ($value['user_group_icon'] == -1) {
                    unset($userGroupData[$key]);
                    continue;
                }
                $userGroupData[$key]['user_group_icon_url'] = THEME_PUBLIC_URL.'/image/usergroup/'.$value['user_group_icon'];
            }
        } else {
            $userGroupData = array();
        }

        return $userGroupData;
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
                            $weibolist [$k] ['content'] = wapFormatContent($weibolist [$k] ['content'], true, $self_url);
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

        return $weibolist;
    }

//     private function __formatByContent($weibolist)
//     {
//             $self_url = urlencode($this->_self_url);
//             foreach ($weibolist as $k => $v) {
//                 if($v['app'] === 'blog' || $v['app'] === 'weiba' || $v['app'] === 'group'){
//                     unset($weibolist[$k]);
//                     continue;
//                 }
//                 $weibolist[$k]['userGroupData'] = $this->_usergroup($v['uid']);
//                     switch ($v['app']) {
//                             case 'blog':
//                                 break;
//                             case 'public':
//                                     if($v['feed_id']){
//                                             $weibolist[$k]['weibo_id'] = $weibolist[$k]['feed_id'];
//                                             // $weibolist[$k]['content'] = wapFormatContent($v['content'], true, $self_url);
//                                             // 视频处理
//                                             if($v['type'] == 'postvideo'){
//                                                 //$weibolist[$k]['content'] = $v['source_body'];
//                                                 $weibolist[$k]['content'] = $v['feed_content'] ? $v['feed_content'] : $v['source_body'];
//                                                 if(strpos($weibolist[$k]['type'], 'video')){
//                                                         $weibolist[$k]['content'] = wapFormatContent($weibolist[$k]['content'], true, $self_url, "视频");
//                                                    }else{
//                                                        $weibolist[$k]['content'] = wapFormatContent($weibolist[$k]['content'], true, $self_url);
//                                                    }
//                                            }
//                                            // 非视频分享
//                                            if ($v['transpond_data']['content']) {
//                                                if(strpos($weibolist[$k]['type'], 'video')){
//                                                    $weibolist[$k]['transpond_data']['content'] = wapFormatContent($v['transpond_data']['content'], true, $self_url, "视频");
//                                                }else{
//                                                    $weibolist[$k]['transpond_data']['content'] = wapFormatContent($v['transpond_data']['content'], true, $self_url);
//                                                }
//                                                $weibolist[$k]['transpond_data']['weibo_id'] = $weibolist[$k]['transpond_data']['feed_id'];
//                                            }else{
//                                                     $row_id = model('Feed')->where('feed_id='.$v['feed_id'])->getField('app_row_id');
//                                                     $uid = model('Feed')->where('feed_id='.$row_id)->getField('uid');
//                                                     $weibolist[$k]['transpond_data'] = model('User')->getUserInfo($this->uid);
//                                             }
//                                             $weibolist[$k]['ctime'] = date('Y-m-d H:i', $v['publish_time']);
//                                     }else{
//                                             if($weibolist[$k]['row_id']){
//                                                     $weibolist[$k]['ctime'] = strtotime($weibolist[$k]['ctime']);
//                                             }else{
//                                                     unset($weibolist[$k]);
//                                             }

//                                     }
//                                     break;
//                                     case 'weiba':
//                                             $weiba_post = D('WeibaPost', 'weiba')->where('post_id='.$v['app_row_id'])->find();
//                                             $weibolist[$k]['weibo_id'] = $weibolist[$k]['feed_id'];
//                                             $weibolist[$k]['transpond_data'] = $weiba_post;
//                                             $weibolist[$k]['transpond_data']['weibo_id'] = $weibolist[$k]['feed_id'];
//                                             $weibolist[$k]['transpond_data']['uname'] = model('User')->where('uid='.$weiba_post['post_uid'])->getField('uname');
//                                             $weibolist[$k]['transpond_data']['uid'] = $weiba_post['post_uid'];
//                                             break;

//                             default:
//                                     # code...
//                                     break;
//                     }
//         $weibolist[$k]['from'] = getFromClient($weibolist[$k]['from'] , $v['app']);
//                     $map['source_id'] = $v['feed_id'];
//                     $map['uid'] = $this->mid;
//                     $fav = model('Collection')->where($map)->getField('source_id');
//                     if($fav){
//                             $weibolist[$k]['favorited'] = 1;
//                     }else{
//                             $weibolist[$k]['favorited'] = 0;
//                     }

//             }
//             return $weibolist;
//     }
}
