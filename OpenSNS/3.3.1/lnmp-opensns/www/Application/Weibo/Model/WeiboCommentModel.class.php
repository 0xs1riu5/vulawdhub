<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM9:01
 */

namespace Weibo\Model;

use Think\Model;

require_once('./Application/Weibo/Common/function.php');

class WeiboCommentModel extends Model
{
    protected $_validate = array(
        array('content', '1,500', '内容不能为空或内容太长,长度必须在1到500之间！', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addComment($uid, $weibo_id, $content, $comment_id = 0)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'weibo_id' => $weibo_id, 'comment_id' => $comment_id);
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);

        //增加动态评论数量
        D('Weibo/Weibo')->where(array('id' => $weibo_id))->setInc('comment_count');

        S('weibo_' . $weibo_id, null);
        //返回评论编号
        return $comment_id;
    }

    public function deleteComment($comment_id)
    {
        //获取动态编号
        $comment = D('Weibo/WeiboComment')->find($comment_id);
        if ($comment['status'] == -1) {
            return false;
        }
        $weibo_id = $comment['weibo_id'];

        //将评论标记为已经删除
        D('Weibo/WeiboComment')->where(array('id' => $comment_id))->setField('status', -1);

        //减少动态的评论数量
        D('Weibo/Weibo')->where(array('id' => $weibo_id))->setDec('comment_count');
        S('weibo_' . $weibo_id, null);
        clean_weibo_html_cache($weibo_id);
        //返回成功结果
        return true;
    }

    public function getComment($id)
    {
        $comment = S('weibo_comment_' . $id);
        if (!$comment) {
            $comment = $this->find($id);
            $comment['content'] = $this->parseComment($comment['content']);
            $comment['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $comment['uid']);
            S('weibo_comment_' . $id, $comment);
        }
        $comment['content'] = parse_at_users($comment['content'], true);
        $comment['user']['nickname'] = get_nickname($comment['uid']);
        $comment['can_delete'] = check_auth('Weibo/Index/doDelComment', $comment['uid']);
        return $comment;
    }

    public function parseComment($content)
    {
        $content = op_t($content, false);
        $content = parse_url_link($content);

        $content = parse_expression($content);
        $content = parse_comment_content($content);

        return $content;
    }


    public function getAllComment($weibo_id)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIBO') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('weibo_id' => $weibo_id, 'status' => 1))->order($order)->field('id')->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;

    }


    public function getCommentList($weibo_id, $page = 1, $show_more = 0)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIBO') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('weibo_id' => $weibo_id, 'status' => 1))->order($order)->page($page, 10)->field('id')->select();
        /*        if($page == 1){
                    $t = array_chunk($comment,5);
                    if($show_more ){
                        $comment = $t[1];
                    }else{
                        $comment = $t[0];
                    }
                }*/
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;

    }
}