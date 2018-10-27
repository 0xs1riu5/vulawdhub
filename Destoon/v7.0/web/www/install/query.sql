INSERT INTO `destoon_ad` VALUES ('1','网站首页图片轮播1','14','5','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/player_1.jpg','http://www.destoon.com/','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('2','网站首页图片轮播2','14','5','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/player_2.jpg','http://www.destoon.com/','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('3','首页旗帜A1','21','3','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367132435','1262275200','1577894399','0','','','','','','','file/image/a1.jpg','','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('4','首页旗帜A2','22','3','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/a2.jpg','','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('5','首页旗帜A3','23','3','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/a3.jpg','','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('6','首页旗帜A4','24','3','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/a4.jpg','','','','','1','0','0','','0','0','3');
INSERT INTO `destoon_ad` VALUES ('7','首页旗帜A5','25','3','0','0','','http://www.destoon.com/','','0','destoon','1367129092','destoon','1367129092','1262275200','1577894399','0','','','','','','','file/image/a5.jpg','','','','','1','0','0','','0','0','3');

INSERT INTO `destoon_ad_place` VALUES ('1','5','6','1','供应排名','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('2','6','6','1','求购排名','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('3','16','6','1','商城排名','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('4','4','6','1','公司排名','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('14','0','5','1','首页图片轮播','','','','','660','300','0','2','0','1367129092','destoon','1367132316','');
INSERT INTO `destoon_ad_place` VALUES ('15','5','7','1','供应赞助商链接','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('17','4','7','1','公司赞助商链接','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('18','0','7','1','求购赞助商链接','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('19','8','7','1','展会赞助商链接','','','','','0','0','0','0','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('21','0','3','1','首页旗帜A1','','','','','116','212','0','1','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('22','0','3','1','首页旗帜A2','','','','','116','212','0','1','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('23','0','3','1','首页旗帜A3','','','','','116','212','0','1','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('24','0','3','1','首页旗帜A4','','','','','116','212','0','1','0','1367129092','destoon','1367129092','');
INSERT INTO `destoon_ad_place` VALUES ('25','0','3','1','首页旗帜A5','','','','','116','212','0','1','0','1367129092','destoon','1367129092','');

INSERT INTO `destoon_admin` VALUES (1, 1, 0, '生成首页', '?action=html', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (2, 1, 0, '更新缓存', '?action=cache', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (3, 1, 0, '网站设置', '?file=setting', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (4, 1, 0, '模块管理', '?file=module', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (5, 1, 0, '数据维护', '?file=database', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (6, 1, 0, '模板管理', '?file=template', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (7, 1, 0, '会员管理', '?moduleid=2', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (8, 1, 0, '单页管理', '?moduleid=3&file=webpage', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (9, 1, 0, '排名推广', '?moduleid=3&file=spread', '', 0, '', '', '');
INSERT INTO `destoon_admin` VALUES (10, 1, 0, '广告管理', '?moduleid=3&file=ad', '', 0, '', '', '');

INSERT INTO `destoon_area` VALUES ('1','默认地区','0','0','0','1','1');

INSERT INTO `destoon_category` VALUES 
(1, 5, '供应默认分类', '', '1', 'list.php?catid=1', '', 1, 0, 0, 0, '0', 0, '', 1, '', '', '', '', '', '', '', '');
INSERT INTO `destoon_category` VALUES 
(2, 6, '求购默认分类', '', '1', 'list.php?catid=2', '', 1, 0, 0, 0, '0', 0, '', 1, '', '', '', '', '', '', '', '');
INSERT INTO `destoon_category` VALUES 
(3, 4, '公司默认分类', '', '1', 'list.php?catid=3', '', 1, 0, 0, 0, '0', 0, '', 1, '', '', '', '', '', '', '', '');


INSERT INTO `destoon_cron` VALUES (1, '更新在线状态', 1, 'online', '10', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (2, '内容分表创建', 1, 'split', '0,0', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (3, '清理过期文件缓存', 0, 'cache', '30', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (20, '清理过期禁止IP', 0, 'banip', '0,10', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (21, '清理系统临时文件', 0, 'temp', '0,20', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (40, '清理3天前未付款充值记录', 0, 'charge', '1,0', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (41, '清理30天前404日志', 0, '404', '1,10', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (42, '清理30天前登录日志', 0, 'loginlog', '1,20', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (43, '清理30天前管理日志', 0, 'adminlog', '1,30', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (44, '清理30天前站内交谈', 0, 'chat', '1,40', 0, 0, 0, '');
INSERT INTO `destoon_cron` VALUES (60, '清理90天前已读信件', 0, 'message', '2,0', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (61, '清理90天前资金流水', 0, 'money', '2,10', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (62, '清理90天前积分流水', 0, 'credit', '2,20', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (63, '清理90天前短信流水', 0, 'sms', '2,30', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (64, '清理90天前短信记录', 0, 'smssend', '2,40', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (65, '清理90天前邮件记录', 0, 'maillog', '2,50', 0, 0, 1, '');
INSERT INTO `destoon_cron` VALUES (100, 'DESTOON', 0, 'destoon', '10', 0, 0, 0, '');
DELETE FROM `destoon_cron` WHERE `itemid`=100;

INSERT INTO `destoon_member` VALUES (1, 'destoon', 'destoon', 'DESTOON B2B网站管理系统', 'bd9c47538b8f5a798ab0a333b7161ebd', '3LMVMGeT', 'bd9c47538b8f5a798ab0a333b7161ebd', '3LMVMGeT', 'admin@yourdomain.com', 0, 0, 0, 0, 1, 1, '姓名', '', '', '', '', '', '', '', '', 1, '', 0, 1, 6, 1, 0, 0, 0.00, 0.00, 1445261241, '127.0.0.1', 1208446566, '192.168.31.3', 1519960776, 1, 1, 1, 1, 1, 0, 0, 0, '', '', '', '');
INSERT INTO `destoon_member_misc` VALUES (1, 'destoon', '', 0, '', '', '', '', 1);
INSERT INTO `destoon_company` VALUES (1, 'destoon', 1, 'DESTOON B2B网站管理系统', 0, 0, '', 0, 0, 0, 0, '企业单位', '', '', 1, '', 0, '人民币', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', 0, 0, '', '', '', '', '', '');
INSERT INTO `destoon_company_data` VALUES ('1','');
INSERT INTO `destoon_link` VALUES 
(1, 0, 0, 'DESTOON B2B', '', 'http://static.destoon.com/logo.gif', 'DESTOON B2B网站管理系统', '', 1320045233, 'destoon', 1320045287, 0, 1, 3, 'http://www.destoon.com/');

INSERT INTO `destoon_member_group` VALUES ('1','管理员','0','1');
INSERT INTO `destoon_member_group` VALUES ('2','禁止访问','0','2');
INSERT INTO `destoon_member_group` VALUES ('3','游客','0','3');
INSERT INTO `destoon_member_group` VALUES ('4','待审核会员','0','4');
INSERT INTO `destoon_member_group` VALUES ('5','个人会员','0','5');
INSERT INTO `destoon_member_group` VALUES ('6','企业会员','0','6');
INSERT INTO `destoon_member_group` VALUES ('7','VIP会员','1','7');

INSERT INTO `destoon_module` VALUES ('1','destoon','核心','','','','http://demo.destoon.com/','','1','0','0','0','0','0','0');
INSERT INTO `destoon_module` VALUES ('2','member','会员','member','','','http://demo.destoon.com/member/','','2','0','0','0','0','0','0');
INSERT INTO `destoon_module` VALUES ('3','extend','扩展','extend','','','http://demo.destoon.com/extend/','','0','0','0','0','0','0','1221828889');
INSERT INTO `destoon_module` VALUES ('4','company','公司','company','','','http://demo.destoon.com/company/','','7','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('5','sell','供应','sell','','','http://demo.destoon.com/sell/','','5','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('6','buy','求购','buy','','','http://demo.destoon.com/buy/','','6','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('7','quote','行情','quote','','','http://demo.destoon.com/quote/','','9','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('8','exhibit','展会','exhibit','','','http://demo.destoon.com/exhibit/','','10','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('9','job','人才','job','','','http://demo.destoon.com/job/','','14','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('10','know','知道','know','','','http://demo.destoon.com/know/','','15','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('11','special','专题','special','','','http://demo.destoon.com/special/','','16','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('12','photo','图库','photo','','','http://demo.destoon.com/photo/','','17','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('13','brand','品牌','brand','','','http://demo.destoon.com/brand/','','13','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('14','video','视频','video','','','http://demo.destoon.com/video/','','18','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('15','down','下载','down','','','http://demo.destoon.com/down/','','19','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('16','mall','商城','mall','','','http://demo.destoon.com/mall/','','4','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('17','group','团购','group','','','http://demo.destoon.com/group/','','8','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('18','club','商圈','club','','','http://demo.destoon.com/club/','','20','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('21','article','资讯','news','','','http://demo.destoon.com/news/','','11','0','1','0','0','0','1205992896');
INSERT INTO `destoon_module` VALUES ('22','info','招商','invest','','','http://demo.destoon.com/invest/','','12','0','1','0','0','0','1223991464');

INSERT INTO `destoon_question` VALUES (1, '5+6=?', '11');
INSERT INTO `destoon_question` VALUES (2, '7+8=?', '15');
INSERT INTO `destoon_question` VALUES (3, '11*11=?', '121');
INSERT INTO `destoon_question` VALUES (4, '12-5=?', '7');
INSERT INTO `destoon_question` VALUES (5, '21-9=?', '12');


INSERT INTO `destoon_style` VALUES ('1','0','默认模板','default','homepage','DESTOON.COM',',6,7,','0','money','0','0','0','1284090844','destoon','1322548920','0');
INSERT INTO `destoon_style` VALUES ('2','0','深蓝模板','blue','homepage','DESTOON.COM',',6,7,','0','money','0','0','0','1221742594','destoon','1256865140','0');
INSERT INTO `destoon_style` VALUES ('3','0','绿色模板','green','homepage','DESTOON.COM',',6,7,','0','money','0','0','0','1221742745','destoon','1256865136','0');
INSERT INTO `destoon_style` VALUES ('4','0','紫色模板','purple','homepage','DESTOON.COM',',6,7,','0','money','0','0','0','1221742783','destoon','1319971971','0');
INSERT INTO `destoon_style` VALUES ('5','0','橙色模板','orange','homepage','DESTOON.COM',',6,7,','0','money','0','0','0','1221742811','destoon','1319971979','0');

INSERT INTO `destoon_webpage` VALUES ('1','1','0','0','关于我们','','关于我们','','','','destoon','1319006891','5','0','0','about/index.html','','');
INSERT INTO `destoon_webpage` VALUES ('2','1','0','0','联系方式','','联系方式','','','','destoon','1310696453','4','0','0','about/contact.html','','');
INSERT INTO `destoon_webpage` VALUES ('3','1','0','0','使用协议','','使用协议','','','','destoon','1310696460','3','0','0','about/agreement.html','','');
INSERT INTO `destoon_webpage` VALUES ('4','1','0','0','版权隐私','','版权隐私','','','','destoon','1310696468','2','0','0','about/copyright.html','','');
