<?php
/**
 * 群组 群聊类.
 *
 * @author Stream
 */
class GroupFeedModel extends Model
{
    //表名
    public $tableName = 'group_feed';
    //表结构
    protected $fields = array(
            1 => 'feed_id',
            2 => 'gid',
            3 => 'uid',
            4 => 'type',
            5 => 'app',
            6 => 'app_row_table',
            7 => 'app_row_id',
            8 => 'publish_time',
            9 => 'is_del',
            10 => 'from',
            11 => 'comment_count',
            12 => 'repost_count',
            13 => 'comment_all_count',
            14 => 'is_repost',
            15 => 'is_audit',
            );
    /**
     * 添加分享.
     *
     * @param int    $uid       操作用户ID
     * @param string $app       分享应用类型，默认为public
     * @param string $type      分享类型，
     * @param array  $data      分享相关数据
     * @param int    $app_id    应用资源ID，默认为0
     * @param string $app_table 应用资源表名，默认为feed
     * @param array  $extUid    额外用户ID，默认为null
     * @param array  $lessUids  去除的用户ID，默认为null
     * @param bool   $isAtMe    是否为进行发送，默认为true
     *
     * @return mix 添加失败返回false，成功返回新的分享ID
     */
    public function put($uid, $app = 'group', $type = '', $data = array(), $app_id = 0, $app_table = 'group_feed', $extUid = null, $lessUids = null, $isAtMe = true, $is_repost = 0)
    {

        // 判断数据的正确性
        if (!$uid || $type == '') {
            return false;
        }
        if (strpos($type, 'postvideo') !== false) {
            $type = 'postvideo';
        }
        //分享类型合法性验证 - 临时解决方案
        if (!in_array($type, array('post', 'repost', 'postvideo', 'postfile', 'postimage'))) {
            $type = 'post';
        }
        // //应用类型验证 用于分享框 - 临时解决方案
        // if ( !in_array( $app , array('public','weiba','tipoff') ) ){
        //     $app = 'public';
        //     $type = 'post';
        //     $app_table = 'feed';
        // }

        $app_table = strtolower($app_table);
        // 添加feed表记录
        $data['gid'] = $data['gid'];
        $data['uid'] = $uid;
        $data['app'] = $app;
        $data['type'] = $type;
        $data['app_row_id'] = $app_id;
        $data['app_row_table'] = $app_table;
        $data['publish_time'] = time();
        $data['from'] = isset($data['from']) ? intval($data['from']) : getVisitorClient();
        $data['is_del'] = $data['comment_count'] = $data['repost_count'] = 0;
        $data['is_repost'] = $is_repost;
        //判断是否先审后发
//         $weiboSet = model('Xdata')->get('admin_Config:feed');
//         $weibo_premission = $weiboSet['weibo_premission'];
//         if(in_array('audit',$weibo_premission) || CheckPermission('core_normal','feed_audit')){
//             $data['is_audit'] = 0;
//         }else{
        $data['is_audit'] = 1;
//         }
        // 分享内容处理
        if (Addons::requireHooks('weibo_publish_content')) {
            Addons::hook('weibo_publish_content', array(&$data));
        } else {
            // 拼装数据，如果是评论再转发、回复评论等情况，需要额外叠加对话数据
            $data['body'] = str_replace(SITE_URL, '[SITE_URL]', preg_html($data['body']));
            // 获取用户发送的内容，仅仅以//进行分割
            $scream = explode('//', $data['body']);
            // 截取内容信息为分享内容字数 - 重点
            $feedConf = model('Xdata')->get('admin_Config:feed');
            $feedNums = $feedConf['weibo_nums'];
            $body = array();
            foreach ($scream as $value) {
                $tbody[] = $value;
                $bodyStr = implode('//', $tbody);
                if (get_str_length($bodyStr) > $feedNums) {
                    break;
                }
                $body[] = $value;
                unset($bodyStr);
            }
            $data['body'] = implode('//', $body);
            // 获取用户发布内容
            $data['content'] = trim($scream[0]);
        }
        //分享到分享的应用资源，加入原资源链接
        $data['body'] .= $data['source_url'];
        $data['content'] .= $data['source_url'];

        // 分享类型插件钩子
        // if($type){
        //  $addonsData = array();
        //  Addons::hook("weibo_type",array("typeId"=>$type,"typeData"=>$type_data,"result"=>&$addonsData));
        //  $data = array_merge($data,$addonsData);
        // }
        if ($type == 'postvideo') {
            $typedata = model('Video')->_weiboTypePublish($_POST['videourl']);
            if ($typedata && $typedata['flashvar'] && $typedata['flashimg']) {
                $data = array_merge($data, $typedata);
            } else {
                $data['type'] = 'post';
            }
        }
        // 添加分享信息
        $feed_id = $this->data($data)->add();
        if (!$feed_id) {
            return false;
        }
//         if(!$data['is_audit']){
//             $touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
//             foreach($touid as $k=>$v){
//                 model('Notify')->sendNotify($v['uid'], 'feed_audit');
//             }
//         }
        // 添加关联数据
        $feed_data = D('group_feed_data')->data(array('feed_id' => $feed_id, 'feed_data' => serialize($data), 'client_ip' => get_client_ip(), 'feed_content' => $data['body']))->add();
        // 添加分享成功后
        if ($feed_id && $feed_data) {

            //分享发布成功后的钩子
//             Addons::hook("weibo_publish_after",array('weibo_id'=>$feed_id,'post'=>$data));

            // 发送通知消息 - 重点 - 需要简化把上节点的信息去掉.
            if ($data['is_repost'] == 1) {
                // 转发分享
                $isAtMe && $content = $data['content'];                                 // 内容用户
                $extUid[] = $data['sourceInfo']['transpond_data']['uid'];               // 资源作者用户
                if ($isAtMe && !empty($data['curid'])) {
                    // 上节点用户
                    $appRowData = $this->get($data['curid']);
                    $extUid[] = $appRowData['uid'];
                }
            } else {
                // 其他分享
                $content = $data['content'];
            }
            // 发送@消息
            D('GroupAtme')->setAppName('group')->setAppTable('group_feed')->addAtme($content, $feed_id, $extUid, $lessUids, $data['gid']);

            $data['client_ip'] = get_client_ip();
            $data['feed_id'] = $feed_id;
            $data['feed_data'] = serialize($data);
            // 主动创建渲染后的缓存
            $return = $this->setFeedCache($data);
            $return['user_info'] = model('User')->getUserInfo($uid);
            $return['GroupData'] = model('UserGroupLink')->getUserGroupData($uid);   //获取用户组信息
            $return['feed_id'] = $feed_id;
            $return['app_row_id'] = $data['app_row_id'];
            $return['is_audit'] = $data['is_audit'];
            // 统计数修改
//             model('UserData')->setUid($uid)->updateKey('feed_count', 1);
            // if($app =='public'){ //TODO 分享验证条件
//                 model('UserData')->setUid($uid)->updateKey('weibo_count', 1);
            // }
            if (!$return) {
                $this->error = L('PUBLIC_CACHE_FAIL');              // Feed缓存写入失败
            }

            return $return;
        } else {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失败
            return false;
        }
    }
    /**
     * 同步到分享.
     *
     * @param string content 内容
     * @param int uid 发布者uid
     * @param mixed attach_ids 附件ID
     *
     * @return int feed_id 分享ID
     */
    public function syncToFeed($content, $uid, $attach_ids, $from, $gid)
    {
        $d['content'] = '';
        $d['body'] = $content;
        $d['from'] = 0; //TODO
        $d['gid'] = $gid;
        if ($attach_ids) {
            $type = 'postimage';
            $d['attach_id'] = $attach_ids;
        } else {
            $type = 'post';
        }
        $feed = $this->put($uid, 'group', $type, $d, '', 'group_feed');

        return $feed['feed_id'];
    }
    /**
     * 获取分享列表.
     *
     * @param array $map   查询条件
     * @param int   $limit 结果集数目，默认为10
     *
     * @return array 分享列表数据
     */
    public function getList($map, $limit = 10, $order = 'publish_time DESC')
    {
        $feedlist = $this->field('feed_id')->where($map)->order($order)->findPage($limit);
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);

        return $feedlist;
    }
    /**
     * 获取指定分享的信息.
     *
     * @param int $feed_id 分享ID
     *
     * @return mix 获取失败返回false，成功返回分享信息
     */
    public function get($feed_id)
    {
        $feed_list = $this->getFeeds(array($feed_id));
        if (!$feed_list) {
            $this->error = L('PUBLIC_INFO_GET_FAIL');            // 获取信息失败
            return false;
        } else {
            return $feed_list[0];
        }
    }
    /**
     * 获取给定分享ID的分享信息.
     *
     * @param array $feed_ids 分享ID数组
     *
     * @return array 给定分享ID的分享信息
     */
    public function getFeeds($feed_ids)
    {
        $feedlist = array();
        $feed_ids = array_filter(array_unique($feed_ids));

        // 获取数据
        if (count($feed_ids) > 0) {
            $cacheList = model('Cache')->getList('gfd_', $feed_ids);
        } else {
            return false;
        }

        // 按照传入ID顺序进行排序
        foreach ($feed_ids as $key => $v) {
            if ($cacheList[$v]) {
                $feedlist[$key] = $cacheList[$v];
            } else {
                $feed = $this->setFeedCache(array(), $v);
                $feedlist[$key] = $feed[$v];
            }
        }

        return $feedlist;
    }

    /**
     * 生成指定分享的缓存.
     *
     * @param array $value   分享相关数据
     * @param array $feed_id 分享ID数组
     */
    public function setFeedCache($value = array(), $feed_id = array())
    {
        if (!empty($feed_id)) {
            !is_array($feed_id) && $feed_id = explode(',', $feed_id);
            $map['a.feed_id'] = array('IN', $feed_id);
            $list = $this->where($map)
                         ->field('a.*,b.client_ip,b.feed_data')
                         ->table("{$this->tablePrefix}group_feed AS a LEFT JOIN {$this->tablePrefix}group_feed_data AS b ON a.feed_id = b.feed_id")
                         ->findAll();

            $r = array();
            foreach ($list as &$v) {
                // 格式化数据模板
                $parseData = $this->__paseTemplate($v);
                $v['info'] = $parseData['info'];
                $v['title'] = $parseData['title'];
                $v['body'] = $parseData['body'];
                $v['actions'] = $parseData['actions'];
                $v['user_info'] = $parseData['userInfo'];
                $v['GroupData'] = model('UserGroupLink')->getUserGroupData($v['uid']);
                model('Cache')->set('gfd_'.$v['feed_id'], $v);           // 1分钟缓存
                $r[$v['feed_id']] = $v;
            }

            return $r;
        } else {
            // 格式化数据模板
            $parseData = $this->__paseTemplate($value);
            $value['info'] = $parseData['info'];
            $value['title'] = $parseData['title'];
            $value['body'] = $parseData['body'];
            $value['actions'] = $parseData['actions'];
            $value['user_info'] = $parseData['userInfo'];
            $value['GroupData'] = model('UserGroupLink')->getUserGroupData($value['uid']);
            model('Cache')->set('gfd_'.$value['feed_id'], $value);       // 1分钟缓存
            return $value;
        }
    }
    /**
     * 清除指定用户指定分享的列表缓存.
     *
     * @param array $feed_ids 分享ID数组，默认为空
     * @param int   $uid      用户ID，默认为空
     */
    public function cleanCache($feed_ids = array(), $uid = '')
    {
        if (!empty($uid)) {
            model('Cache')->rm('gfd_foli_'.$uid);
            model('Cache')->rm('gfd_uli_'.$uid);
        }
        if (empty($feed_ids)) {
            return true;
        }
        if (is_array($feed_ids)) {
            foreach ($feed_ids as $v) {
                model('Cache')->rm('gfd_'.$v);
            }
        } else {
            model('Cache')->rm('gfd_'.$feed_ids);
        }
    }
    /**
     * 解析分享模板标签.
     *
     * @param array $_data 分享的原始数据
     *
     * @return array 解析分享模板后的分享数据
     */
    private function __paseTemplate($_data)
    {
        // 获取作者信息
        $user = model('User')->getUserInfo($_data['uid']);
        // 处理数据
        $_data['data'] = unserialize($_data['feed_data']);
        // 模版变量赋值
        $var = $_data['data'];
        if (!empty($var['attach_id'])) {
            $var['attachInfo'] = model('Attach')->getAttachByIds($var['attach_id']);
            foreach ($var['attachInfo'] as $ak => $av) {
                $_attach = array(
                            'attach_id' => $av['attach_id'],
                            'attach_name' => $av['name'],
                            'attach_url' => getImageUrl($av['save_path'].$av['save_name']),
                            'extension' => $av['extension'],
                            'size' => $av['size'],
                        );
                if ($_data['type'] == 'postimage') {
                    $_attach['attach_small'] = getImageUrl($av['save_path'].$av['save_name'], 100, 100, true);
                    $_attach['attach_middle'] = getImageUrl($av['save_path'].$av['save_name'], 740);
                }
                $var['attachInfo'][$ak] = $_attach;
            }
        }
        if ($_data['type'] == 'postvideo' && !$var['flashimg']) {
            $var['flashimg'] = '__THEME__/image/video.png';
        }
        $var['uid'] = $_data['uid'];
        $var['actor'] = "<a href='{$user['space_url']}' class='name' event-node='face_card' uid='{$user['uid']}'>{$user['uname']}</a>";
        $var['actor_uid'] = $user['uid'];
        $var['actor_uname'] = $user['uname'];
        $var['feedid'] = $_data['feed_id'];
        //微吧类型分享用到
        // $var["actor_groupData"] = model('UserGroupLink')->getUserGroupData($user['uid']);
        //需要获取资源信息的分享：所有类型的分享，只要有资源信息就获取资源信息并赋值模版变量，交给模版解析处理
        if (!empty($_data['app_row_id'])) {
            //empty($_data['app_row_table']) && $_data['app_row_table'] = 'group_weibo';
            $var['sourceInfo'] = model('Source')->getSourceInfo($_data['app_row_table'], $_data['app_row_id'], false, $_data['app']);
            $var['sourceInfo']['groupData'] = model('UserGroupLink')->getUserGroupData($var['sourceInfo']['source_user_info']['uid']);
        }
        // 解析Feed模版
        $feed_template_file = APPS_PATH.'/'.$_data['app'].'/Conf/'.$_data['type'].'.feed.php';
        if (!file_exists($feed_template_file)) {
            $feed_template_file = APPS_PATH.'/public/Conf/post.feed.php';
        }
        $feed_xml_content = fetch($feed_template_file, $var);
        $s = simplexml_load_string($feed_xml_content);
        if (!$s) {
            return false;
        }
        $result = $s->xpath("//feed[@type='".t($_data['type'])."']");
        $actions = (array) $result[0]->feedAttr;
        //输出模版解析后信息
        $return['userInfo'] = $user;
        $return['actor_groupData'] = $var['actor_groupData'];
        $return['title'] = trim((string) $result[0]->title);
        $return['body'] = trim((string) $result[0]->body);
        // $return['sbody'] = trim((string) $result[0]->sbody);
        $return['info'] = trim((string) $result[0]['info']);
        //$return['title'] =  parse_html($return['title']);
        // $return['body']  =  parse_html($return['body']);
        $return['body'] = $this->parseHtml($return['body']);
        // $return['sbody'] =  parse_html($return['sbody']);
        $return['actions'] = $actions['@attributes'];
        //验证转发的原信息是否存在
        // if(!$this->_notDel($_data['app'],$_data['type'],$_data['app_row_id'])) {
        //     $return['body'] = L('PUBLIC_INFO_ALREADY_DELETE_TIPS');             // 此信息已被删除〜
        // }
        return $return;
    }

    // 临时解决方案 - 去除#话题#
    private function parseHtml($html)
    {
        $html = htmlspecialchars_decode($html);
        //以下三个过滤是旧版兼容方法-可屏蔽
        $html = preg_replace('/img{data=([^}]*)}/', ' ', $html);
        $html = preg_replace('/topic{data=([^}]*)}/', '<a href="$1" topic="true">#$1#</a>', $html);
        $html = preg_replace_callback('/@{uid=([^}]*)}/', '_parse_at_by_uid', $html);
        //链接替换
        $html = str_replace('[SITE_URL]', SITE_URL, $html);
        //表情处理
        $html = preg_replace_callback("/(\[.+?\])/is", '_parse_expression', $html);
        //@提到某人处理
        $html = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", '_parse_at_by_uname', $html);

        return $html;
    }

    /**
     * 从group_weibo中提取资源数据.
     *
     * @param string $table  资源表名
     * @param int    $row_id 资源ID
     * @param bool   $forApi 是否提供API，默认为false
     *
     * @return array 格式化后的资源数据
     */
    public function getSourceInfo($row_id, $forApi)
    {
        $info = $this->getFeedInfo($row_id, $forApi);
        $info['source_user_info'] = model('User')->getUserInfo($info['uid']);
        $info['source_user'] = $info['uid'] == $GLOBALS['ts']['mid'] ? L('PUBLIC_ME') : $info['source_user_info']['space_link'];         // 我
        $info['source_type'] = L('PUBLIC_WEIBO');
        $info['source_title'] = $forApi ? parseForApi($info['user_info']['space_link']) : $info['user_info']['space_link'];   //分享title暂时为空
        $info['source_url'] = U('group/Group/detail', array('feed_id' => $row_id, 'uid' => $info['uid'], 'gid' => $info['gid']));
        $info['source_content'] = $info['content'];
        $info['ctime'] = $info['publish_time'];
        unset($info['content']);

        return $info;
    }

    /**
     * 获取指定分享的信息，用于资源模型输出???
     *
     * @param int  $id     分享ID
     * @param bool $forApi 是否提供API数据，默认为false
     *
     * @return array 指定分享数据
     */
    public function getFeedInfo($id, $forApi = false)
    {
        $data = model('Cache')->get('group_feed_info_'.$id);
        if ($data) {
            return $data;
        }
        $map['a.feed_id'] = $id;
        // $map['a.is_del'] = 0;//过滤已删除的分享
        $data = $this->where($map)
                     ->table("{$this->tablePrefix}group_feed AS a LEFT JOIN {$this->tablePrefix}group_feed_data AS b ON a.feed_id = b.feed_id ")
                     ->find();
        $fd = unserialize($data['feed_data']);

        $userInfo = model('User')->getUserInfo($data['uid']);
        $data['ctime'] = date('Y-m-d H:i', $data['publish_time']);
        $data['content'] = $forApi ? parseForApi($fd['body']) : $fd['body'];
        $data['uname'] = $userInfo['uname'];
        $data['avatar_big'] = $userInfo['avatar_big'];
        $data['avatar_middle'] = $userInfo['avatar_middle'];
        $data['avatar_small'] = $userInfo['avatar_small'];
        unset($data['feed_data']);
        // 分享转发
        if ($data['type'] == 'repost') {
            $data['transpond_id'] = $data['app_row_id'];
            $data['transpond_data'] = $this->getFeedInfo($data['transpond_id'], $forApi);
        }
        // 附件处理
        if (!empty($fd['attach_id'])) {
            $data['has_attach'] = 1;
            $attach = model('Attach')->getAttachByIds($fd['attach_id']);
            foreach ($attach as $ak => $av) {
                $_attach = array(
                            'attach_id' => $av['attach_id'],
                            'attach_name' => $av['name'],
                            'attach_url' => getImageUrl($av['save_path'].$av['save_name']),
                            'extension' => $av['extension'],
                            'size' => $av['size'],
                        );
                if ($data['type'] == 'postimage') {
                    $_attach['attach_small'] = getImageUrl($av['save_path'].$av['save_name'], 100, 100, true);
                    $_attach['attach_middle'] = getImageUrl($av['save_path'].$av['save_name'], 740);
                }
                $data['attach'][] = $_attach;
            }
        } else {
            $data['has_attach'] = 0;
        }
        if ($data['type'] == 'postvideo') {
            $data['host'] = $fd['host'];
            $data['flashvar'] = $fd['flashvar'];
            $data['source'] = $fd['source'];
            $data['flashimg'] = $fd['flashimg'];
            $data['title'] = $fd['title'];
        }
        $data['feedType'] = $data['type'];

        // 是否收藏分享
        if ($forApi) {
            $data['iscoll'] = model('Collection')->getCollection($data['feed_id'], 'feed');
            if (empty($data['iscoll'])) {
                $data['iscoll']['colled'] = 0;
            } else {
                $data['iscoll']['colled'] = 1;
            }
        }

        // 分享详细信息
        $feedInfo = $this->get($id);
        $data['source_body'] = $feedInfo['body'];
        //一分钟缓存
        model('Cache')->set('group_feed_info_'.$id, $data, 60);

        return $data;
    }
    /**
     * 分享操作，彻底删除、假删除、回复.
     *
     * @param int    $feed_id 分享ID
     * @param string $type    分享操作类型，deleteFeed：彻底删除，delFeed：假删除，feedRecover：恢复
     * @param string $title   知识内容，目前没没有该功能
     * @param string $uid     删除分享的用户ID（区别超级管理员）
     *
     * @return array 分享操作后的结果信息数组
     */
    public function doEditFeed($feed_id, $type, $title, $uid = null)
    {
        $return = array('status' => '0');
        if (empty($feed_id)) {
            //$return['data'] = '分享ID不能为空！';
        } else {
            $map['feed_id'] = is_array($feed_id) ? array('IN', $feed_id) : intval($feed_id);
            $save['is_del'] = $type == 'delFeed' ? 1 : 0;

            if ($type == 'deleteFeed') {
                $res = $this->where($map)->delete();
                // 删除分享相关信息
                if ($res) {
                    $this->_deleteFeedAttach($feed_id, 'deleteAttach');
                }
            } else {
                $ids = !is_array($feed_id) ? array($feed_id) : $feed_id;
                $feedList = $this->getFeeds($ids);
                $res = $this->where($map)->save($save);
                if ($type == 'feedRecover') {
                    //     				// 添加分享数
//     				foreach($feedList as $v) {
//     					model('UserData')->setUid($v['user_info']['uid'])->updateKey('feed_count', 1);
//     					model('UserData')->setUid($v['user_info']['uid'])->updateKey('weibo_count', 1);
//     				}
                    $this->_deleteFeedAttach($ids, 'recoverAttach');
                } else {
                    //     				// 减少分享数
//     				foreach($feedList as $v) {
//     					model('UserData')->setUid($v['user_info']['uid'])->updateKey('feed_count', -1);
//     					model('UserData')->setUid($v['user_info']['uid'])->updateKey('weibo_count', -1);
//     				}
                    $this->_deleteFeedAttach($ids, 'delAttach');
                }
                $this->cleanCache($ids);        // 删除分享缓存信息
                // 资源分享缓存相关分享
                $sids = $this->where('app_row_id='.$feed_id)->getAsFieldArray('feed_id');
                $this->cleanCache($sids);
            }
            // 删除评论信息
            $cmap['app'] = 'group';
            $cmap['table'] = 'group_feed';
            $cmap['row_id'] = is_array($feed_id) ? array('IN', $feed_id) : intval($feed_id);
            $commentIds = D('GroupComment')->where($cmap)->getAsFieldArray('comment_id');
            D('GroupComment')->setAppName('group')->setAppTable('group_feed')->deleteComment($commentIds);
            if ($res) {
                // TODO:是否记录知识，以及后期缓存处理
                $return = array('status' => 1);
//     			//添加积分
//     			model('Credit')->setUserCredit($uid,'delete_weibo');
            }
        }

        return $return;
    }

    /**
     * 删除分享相关附件数据.
     *
     * @param array  $feedIds 分享ID数组
     * @param string $type    删除附件类型
     */
    private function _deleteFeedAttach($feedIds, $type)
    {
        // 查询分享内是否存在附件
        $feeddata = $this->getFeeds($feedIds);
        $feedDataInfo = getSubByKey($feeddata, 'feed_data');
        $attachIds = array();
        foreach ($feedDataInfo as $value) {
            $value = unserialize($value);
            !empty($value['attach_id']) && $attachIds = array_merge($attachIds, $value['attach_id']);
        }
        array_filter($attachIds);
        array_unique($attachIds);
        !empty($attachIds) && model('Attach')->doEditAttach($attachIds, $type, '');
    }
}
