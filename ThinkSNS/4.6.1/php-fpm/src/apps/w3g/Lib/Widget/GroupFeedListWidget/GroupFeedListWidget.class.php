<?php
/**
 * 群组分享列表.
 *
 * @example {:W('GroupFeedList',array('type'=>'space','feed_type'=>$feed_type,'feed_key'=>$feed_key,'loadnew'=>0,'gid'=>$gid))}
 *
 * @author jason
 *
 * @version TS3.0
 */
class GroupFeedListWidget extends Widget
{
    private static $rand = 1;
    private $limitnums = 10;

    /**
     * @param string type 获取哪类分享 following:我关注的 space：
     * @param string feed_type 分享类型
     * @param string feed_key 分享关键字
     * @param int fgid 关注的分组id
     * @param int gid 群组id
     * @param int loadnew 是否加载更多 1:是  0:否
     */
    public function render($data)
    {
        $var = array();
        $var['loadmore'] = 1;
        $var['loadnew'] = 1;
        $var['tpl'] = 'GroupFeedList.html';
        //dump($var);exit;
        is_array($data) && $var = array_merge($var, $data);

//  		$weiboSet = model('Xdata')->get('admin_Config:feed');
//         $var['initNums'] = $weiboSet['weibo_nums'];
//         $var['weibo_type'] = $weiboSet['weibo_type'];
//         $var['weibo_premission'] = $weiboSet['weibo_premission'];
        // 我关注的频道
//         $var['channel'] = M('channel_follow')->where('uid='.$this->mid)->count();
        $content['html'] = $this->renderFile(dirname(__FILE__).'/'.$var['tpl'], $var);
        ++self::$rand;
        unset($var, $data);
        //输出数据
        return $content['html'];
    }

    /**
     * 显示更多分享.
     *
     * @return array 更多分享信息、状态和提示
     */
    public function loadMore()
    {
        // 获取GET与POST数据
        $_REQUEST = $_GET + $_POST;
        // 查询是否有分页
        if (!empty($_REQUEST['p']) || intval($_REQUEST['load_count']) == 4) {
            unset($_REQUEST['loadId']);
            $this->limitnums = 40;
        } else {
            $return = array('status' => -1, 'msg' => L('PUBLIC_LOADING_ID_ISNULL'));
            $_REQUEST['loadId'] = intval($_REQUEST['loadId']);
            $this->limitnums = 10;
        }
        // 查询是否有话题ID
        if ($_REQUEST['topic_id']) {
            $content = $this->getTopicData($_REQUEST, '_FeedList.html');
        } else {
            $content = $this->getData($_REQUEST, '_GroupFeedList.html');
        }
        // 查看是否有更多数据
        if (empty($content['html'])) {
            // 没有更多的
            $return = array('status' => 0, 'msg' => L('PUBLIC_WEIBOISNOTNEW'));
        } else {
            $return = array('status' => 1, 'msg' => L('PUBLIC_SUCCESS_LOAD'));
            $return['html'] = $content['html'];
            $return['loadId'] = $content['lastId'];
            $return['firstId'] = (empty($_REQUEST['p']) && empty($_REQUEST['loadId'])) ? $content['firstId'] : 0;
            $return['pageHtml'] = $content['pageHtml'];
        }
        exit(json_encode($return));
    }

    /**
     * 显示最新分享.
     *
     * @return array 最新分享信息、状态和提示
     */
    public function loadNew()
    {
        $return = array('status' => -1, 'msg' => '');
        $_REQUEST['maxId'] = intval($_REQUEST['maxId']);
        if (empty($_REQUEST['maxId'])) {
            echo json_encode($return);
            exit();
        }
        $content = $this->getData($_REQUEST, '_FeedList.html');
        if (empty($content['html'])) {
            //没有最新的
            $return = array('status' => 0, 'msg' => L('PUBLIC_WEIBOISNOTNEW'));
        } else {
            $return = array('status' => 1, 'msg' => L('PUBLIC_SUCCESS_LOAD'));
            $return['html'] = $content['html'];
            $return['maxId'] = intval($content['firstId']);
            $return['count'] = intval($content['count']);
        }
        echo json_encode($return);
        exit();
    }

    /**
     * 获取分享数据，渲染分享显示页面.
     *
     * @param array  $var 分享数据相关参数
     * @param string $tpl 渲染的模板
     *
     * @return array 获取分享相关模板数据
     */
    private function getData($var, $tpl = 'FeedList.html')
    {
        $var['feed_key'] = t($var['feed_key']);
        $var['cancomment'] = isset($var['cancomment']) ? $var['cancomment'] : 1;
        //$var['cancomment_old_type'] = array('post','repost','postimage','postfile');
        $var['cancomment_old_type'] = array('post', 'repost', 'postimage', 'postfile', 'postvideo');
        // 获取分享配置
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $var = array_merge($var, $weiboSet);
        $var['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
        $map = $list = array();
        $type = $var['new'] ? 'new'.$var['type'] : $var['type'];    // 最新的分享与默认分享类型一一对应

        if (!empty($var['feed_key'])) {
            //关键字匹配 采用搜索引擎兼容函数搜索 后期可能会扩展为搜索引擎
            $list = D('GroupFeed')->searchFeed($var['feed_key'], 'all', $var['loadId'], $this->limitnums);
        } else {
            $where = 'is_del=0 AND gid='.$var['gid'];
            if ($var['loadId'] > 0) { //非第一次
                $where .= " AND feed_id < '".intval($var['loadId'])."'";
            }
            if (!empty($var['feed_type'])) {
                if ($var['feed_type'] == 'post') {
                    $where .= ' AND is_repost = 0';
                } else {
                    $where .= " AND type = '".t($var['feed_type'])."'";
                }
            }
            $list = D('GroupFeed')->getList($where, $this->limitnums);
        }
        // 分页的设置
        isset($list['html']) && $var['html'] = $list['html'];
        if (!empty($list['data'])) {
            $content['firstId'] = $var['firstId'] = $list['data'][0]['feed_id'];
            $content['lastId'] = $var['lastId'] = $list['data'][(count($list['data']) - 1)]['feed_id'];
            $var['data'] = $list['data'];
            $uids = array();
            foreach ($var['data'] as &$v) {
                switch ($v['app']) {
                    case 'weiba':
                        $v['from'] = getFromClient(0, $v['app'], '微吧');
                        break;
                    case 'tipoff':
                        $v['from'] = getFromClient(0, $v['app'], '爆料');
                        break;
                    default:
                        $v['from'] = getFromClient($v['from'], 'public');
                        break;
                }
                !isset($uids[$v['uid']]) && $v['uid'] != $GLOBALS['ts']['mid'] && $uids[] = $v['uid'];
            }
            if (!empty($uids)) {
                $map = array();
                $map['uid'] = $GLOBALS['ts']['mid'];
                $map['fid'] = array('in', $uids);
                $var['followUids'] = model('Follow')->where($map)->getAsFieldArray('fid');
            } else {
                $var['followUids'] = array();
            }
        }
        $content['pageHtml'] = $list['html'];
        // 渲染模版
        $content['html'] = $this->renderFile(dirname(__FILE__).'/'.$tpl, $var);

        return $content;
    }

    /**
     * 获取话题分享数据，渲染分享显示页面.
     *
     * @param array  $var 分享数据相关参数
     * @param string $tpl 渲染的模板
     *
     * @return array 获取分享相关模板数据
     */
    private function getTopicData($var, $tpl = 'FeedList.html')
    {
        $var['cancomment'] = isset($var['cancomment']) ? $var['cancomment'] : 1;
        //$var['cancomment_old_type'] = array('post','repost','postimage','postfile');
        $var['cancomment_old_type'] = array('post', 'repost', 'postimage', 'postfile', 'weiba_post', 'weiba_repost');
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $var = array_merge($var, $weiboSet);
        $var['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
        $map = $list = array();
        $type = $var['new'] ? 'new'.$var['type'] : $var['type'];    //最新的分享与默认分享类型一一对应

        if ($var['loadId'] > 0) { //非第一次
            $topics['topic_id'] = $var['topic_id'];
            $topics['feed_id'] = array('lt', intval($var['loadId']));
            $map['feed_id'] = array('in', getSubByKey(D('feed_topic_link')->where($topics)->field('feed_id')->select(), 'feed_id'));
        } else {
            $map['feed_id'] = array('in', getSubByKey(D('feed_topic_link')->where('topic_id='.$var['topic_id'])->field('feed_id')->select(), 'feed_id'));
        }
        if (!empty($var['feed_type'])) {
            $map['type'] = t($var['feed_type']);
        }
        //$map['is_del'] = 0;
        $map['_string'] = ' (is_audit=1 OR is_audit=0 AND uid='.$GLOBALS['ts']['mid'].') AND is_del = 0 ';
        $list = model('Feed')->getList($map, $this->limitnums);
        //分页的设置
        isset($list['html']) && $var['html'] = $list['html'];

        if (!empty($list['data'])) {
            $content['firstId'] = $var['firstId'] = $list['data'][0]['feed_id'];
            $content['lastId'] = $var['lastId'] = $list['data'][(count($list['data']) - 1)]['feed_id'];
            $var['data'] = $list['data'];
            $uids = array();
            foreach ($var['data'] as &$v) {
                switch ($v['app']) {
                    case 'weiba':
                        $v['from'] = getFromClient(0, $v['app'], '微吧');
                        break;
                    default:
                        $v['from'] = getFromClient($v['from'], $v['app']);
                        break;
                }
                !isset($uids[$v['uid']]) && $v['uid'] != $GLOBALS['ts']['mid'] && $uids[] = $v['uid'];
            }
            if (!empty($uids)) {
                $map = array();
                $map['uid'] = $GLOBALS['ts']['mid'];
                $map['fid'] = array('in', $uids);
                $var['followUids'] = model('Follow')->where($map)->getAsFieldArray('fid');
            } else {
                $var['followUids'] = array();
            }
        }

        $content['pageHtml'] = $list['html'];

        //渲染模版
        $content['html'] = $this->renderFile(dirname(__FILE__).'/'.$tpl, $var);

        return $content;
    }
}
