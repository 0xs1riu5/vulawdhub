<?php

class groupStatisticsModel extends Model
{
    public function statistics()
    {
        $app_alias = getAppAlias('group');
        $groupDao = M('group');
        $bbsDao = M('group_topic');
        $fileDao = M('group_attachment');

        $allCount = $groupDao->field('COUNT(*) AS `groupcount`,AVG(`membercount`) AS `membercount`')
                                 ->where('`status`=1 AND `is_del`=0')->find();
        $bbscount = $bbsDao->field('COUNT(*) AS bbscount')
                               ->where('`is_del`=0')->find();
        $filecount = $fileDao->field('COUNT(*) AS filecount')
                                ->where('`is_del`=0')->find();

        return array(
            "{$app_alias}总数" => $allCount['groupcount'],
            '平均成员数' => number_format($allCount['membercount'], 1, '.', ''),
            '平均帖子数' => number_format($bbscount['bbscount'] / $allCount['groupcount'], 1, '.', ''),
            '平均文档数' => number_format($filecount['filecount'] / $allCount['groupcount'], 1, '.', ''),
        );
    }
}
