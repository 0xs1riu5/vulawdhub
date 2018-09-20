<?php
/**
 * 消息类型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-27
 * Time: 上午10:33
 * @author 郑钟良<zzl@ourstu.com>
 */

return array(
    'session'=>array(
        array('name'=>'system','title'=>'系统消息','logo'=>'system.png','sort'=>100,'block_tpl'=>'_message_block','default'=>1),
        array('name'=>'announce','title'=>'全站公告','logo'=>'announce.png','sort'=>99)
    ),
    'tpl'=>array(
        array('name'=>'default','title'=>'默认模板','module'=>'Common','tpl_name'=>'_message_li','example_content'=>'html','default'=>1),//默认模板
        array('name'=>'announce','title'=>'公告模板','module'=>'Common','tpl_name'=>'_announce','example_content'=>array('keyword1'=>'公告内容','keyword2'=>'公告创建时间')),//默认模板
        array('name'=>'comment','title'=>'评论类消息模板','module'=>'Common','tpl_name'=>'_comment','example_content'=>array('keyword1'=>'评论内容','keyword2'=>'操作描述','keyword3'=>'原文内容html'))//默认模板
    )
);