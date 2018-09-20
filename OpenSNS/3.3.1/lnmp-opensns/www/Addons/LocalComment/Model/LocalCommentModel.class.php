<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 1/22/14
 * Time: 11:05 PM
 */

namespace Addons\LocalComment\Model;

use Think\Model;

class LocalCommentModel extends Model
{

    /* 用户模型自动验证 */
    protected $_validate = array(
        array('content', '0,10000', '评论内容太长', self::EXISTS_VALIDATE, 'length'),
        array('content', '1,99999', '评论内容不能为空', self::EXISTS_VALIDATE, 'length'),
    );

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', 1, self::MODEL_BOTH),
    );

    //此方法未被调用。


    public function addComment($data)
    {
        $data = $this->create($data);
        if (!$data) return false;
        $result = $this->add($data);
        if (!$result) {
            return false;
        }
        return $result;
    }



    public function getComment($id){

            $comment = S('local_comment_' . $id);
            if (is_bool($comment)) {
                $comment = $this->where(array('id' => $id, 'status' => 1))->find();
                if ($comment) {
                    $comment['user'] = query_user(array('avatar64', 'nickname', 'uid', 'space_url'), $comment['uid']);
                }
                S('local_comment_' . $id, $comment, 60 * 60);
            }
            return $comment;
        }

    public function deleteComment($comment_id)
    {
        //获取微博编号
        $comment = $this->getComment($comment_id);
        if ($comment['status'] == -1) {
            return false;
        }
        $this->where(array('id' => $comment_id))->setField('status', -1);
        S('local_comment_' . $comment_id, null);
        //返回成功结果
        return true;
    }

}