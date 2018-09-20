<?php
namespace Weibo\Model;

use Think\Model;
use Think\Hook;

require_once('./Application/Weibo/Common/function.php');

class WeiboModel extends Model
{
    protected $_validate = array(
        array('content', '1,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('content', '0,65535', '内容太长', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addWeibo($uid, $content = '', $type = 'feed', $feed_data = array(), $from = '')
    {

        if ($content == '') {
            $aContent = I('post.content', '', 'html');
            $content = str_replace(' ', '/nb', $aContent);
        } else {
            $content = str_replace(' ', '/nb', $content);
        }
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content = text($content);
        //$topic = get_topic($content);

        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'type' => $type, 'data' => serialize($feed_data), 'from' => $from);
        $data = $this->create($data);
        if (!$data) return false;
        $weibo_id = $this->add($data);

        //返回动态编号
        return $weibo_id;
    }


    public function getWeiboCount($map)
    {
        return $this->where($map)->count();
    }

    public function getWeiboList($param = null)
    {
        !empty($param['field']) && $this->field($param['field']);
        !empty($param['where']) && $this->where($param['where']);
        !empty($param['limit']) && $this->limit($param['limit']);
        empty($param['order']) && $param['order'] = 'create_time desc';
        !empty($param['page']) && $this->page($param['page'], empty($param['count']) ? 10 : $param['count']);
        $this->order($param['order']);
        $list = $this->select();
        $list = getSubByKey($list, 'id');
        return $list;
    }


    public function getWeiboDetail($id)
    {
        $weibo = S('weibo_' . $id);
        $check_empty = empty($weibo);
        if ($check_empty) {
            $weibo = $this->where(array('status' => 1, 'id' => $id))->find();
            if (!$weibo) {
                return null;
            }
            $before_weibo = $this->where(array('status' => 1, 'create_time' => array('lt', $weibo['create_time']), 'uid' => $weibo['uid']))->find();
            if (!$before_weibo) {
                $weibo['is_first'] = 1;
            }
            $weibo_data = unserialize($weibo['data']);
            $class_exists = true;

            $type = array('repost', 'feed', 'image', 'share', 'redbag');
            if (!in_array($weibo['type'], $type)) {
                $class_exists = class_exists('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon');
            }
            $weibo['content'] = parse_topic(parse_weibo_content($weibo['content']));
            if ($weibo['type'] === 'feed' || $weibo['type'] == '' || !$class_exists) {
                $fetchContent = "<p class='word-wrap'>" . $weibo['content'] . "</p>";
            } elseif ($weibo['type'] === 'repost') {
                $fetchContent = A('Weibo/Type')->fetchRepost($weibo);
            } elseif ($weibo['type'] === 'image') {
                $fetchContent = A('Weibo/Type')->fetchImage($weibo);
            } elseif ($weibo['type'] === 'share') {
                $fetchContent = R('Weibo/Share/getFetchHtml', array('param' => unserialize($weibo['data']), 'weibo' => $weibo), 'Widget');
            } elseif ($weibo['type'] === 'redbag') {
                $fetchContent = Hook::exec('Addons\\RedBag' . '\\' . 'RedBagAddon', 'fetch' . ucfirst($weibo['type']), $weibo);
            } else {
                $fetchContent = Hook::exec('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon', 'fetch' . ucfirst($weibo['type']), $weibo);
            }
            if ($weibo['comment_count'] >= modC('HOT_WEIBO_COMMENT_NUM', 10, 'Weibo')) {
                $is_hot = 1;
            }
            $weibo = array(
                'id' => intval($weibo['id']),
                'content' => strval($weibo['content']),
                'create_time' => intval($weibo['create_time']),
                'type' => $weibo['type'],
                'data' => unserialize($weibo['data']),
                'weibo_data' => $weibo_data,
                'comment_count' => intval($weibo['comment_count']),
                'repost_count' => intval($weibo['repost_count']),
                'can_delete' => 0,
                'is_top' => $weibo['is_top'],
                'uid' => $weibo['uid'],
                'fetchContent' => $fetchContent,
                'from' => $weibo['from'],
                'user' => query_user(array('uid', 'avatar64', 'space_url', 'rank_link', 'title','avatar_html64'), $weibo['uid']),
                'is_first' => $weibo['is_first'],
                'status' => $weibo['status'],
                'is_hot' => $is_hot ? 1 : 0
            );
            S('weibo_' . $id, $weibo);
        }

        $weibo['fetchContent'] = parse_at_users($weibo['fetchContent']);
        $weibo['user']['nickname'] = get_nickname($weibo['uid']);
        $weibo['can_delete'] = $this->canDeleteWeibo($weibo);
        // 判断转发的原动态是否已经删除
        if ($weibo['type'] == 'repost') {
            $source_weibo = $this->getWeiboDetail($weibo['weibo_data']['sourceId']);
            if (!$source_weibo['uid']) {
                if (!$check_empty) {
                    S('weibo_' . $id, null);
                    $weibo = $this->getWeiboDetail($id);
                }
            }
        }
        return $weibo;
    }


    private function canDeleteWeibo($weibo)
    {
        //如果是管理员，则可以删除动态
        if (check_auth('Weibo/Index/doDelWeibo', $weibo['uid'])) {
            return true;
        }

        //返回，不能删除动态
        return false;
    }


    public function deleteWeibo($weibo_id)
    {
        $weibo = $this->getWeiboDetail($weibo_id);


        //从数据库中删除动态、以及附属评论
        $result = $this->where(array('id' => $weibo_id))->save(array('status' => -1, 'comment_count' => 0));
        D('Weibo/WeiboComment')->where(array('weibo_id' => $weibo_id))->setField('status', -1);

        if ($weibo['type'] == 'repost') {
            $this->where(array('id' => $weibo['weibo_data']['sourceId']))->setDec('repost_count');
            S('weibo_' . $weibo['weibo_data']['sourceId'], null);
        }

        S('weibo_' . $weibo_id, null);
        return $result;
    }

    public function getSupportedPeople($weibo_id, $user_fields = array('nickname', 'space_url', 'avatar128', 'space_link'), $num = 8)
    {
        $user_fields == null ? array('nickname', 'space_url', 'avatar128', 'space_link') : $user_fields;
        $supported = D('Support')->getSupportedUser('Weibo', 'weibo', $weibo_id, $user_fields, $num);

        return $supported;
    }

}