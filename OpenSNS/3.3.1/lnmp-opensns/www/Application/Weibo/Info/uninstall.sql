DROP TABLE IF EXISTS `ocenter_weibo`;
DROP TABLE IF EXISTS `ocenter_weibo_comment`;
DROP TABLE IF EXISTS `ocenter_weibo_top`;
DROP TABLE IF EXISTS `ocenter_weibo_topic`;
DROP TABLE IF EXISTS `ocenter_weibo_topic_link`;
DROP TABLE IF EXISTS `ocenter_weibo_cache`;




/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `ocenter_menu` where `title` = '动态' ;
delete from `ocenter_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `ocenter_menu` where  `title` = '动态' ;

delete from `ocenter_menu` where  `url` like 'Weibo/%';

delete from `ocenter_auth_rule` where  `module` = 'Weibo';

delete from `ocenter_action` where  `module` = 'Weibo';

delete from `ocenter_action_limit` where  `module` = 'Weibo';