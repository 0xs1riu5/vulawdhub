<?php

class GroupFeedDataModel extends Model
{
    //表名
    public $tableName = 'group_feed_data';
    //表结构
    protected $fields = array(
            1 => 'feed_id',
            2 => 'feed_data',
            3 => 'client_ip',
            4 => 'feed_content',
            5 => 'from_data',
    );
}
