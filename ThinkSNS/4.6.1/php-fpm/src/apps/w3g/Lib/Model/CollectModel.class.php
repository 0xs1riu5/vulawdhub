<?php

class CollectModel extends Model
{
    public $tableName = 'group_topic_collect';
    public function isCollect($tid, $mid)
    {
        return $this->where('tid='.$tid.' AND mid='.$mid)->count();
    }
}
