<?php
/**
 * 菜单地图
 *
 * @version        $Id: inc_menu_map.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");

$maparray = array(1=>'文档相关',2=>'系统设置',3=>'必须辅助功能',4=>'网站更新操作',5=>'会员相关',6=>'基本模块插件');

//载入可发布频道
$addset = '';

//检测可用的内容模型
if($cfg_admin_channel = 'array' && count($admin_catalogs) > 0)
{
    $admin_catalog = join(',', $admin_catalogs);
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` WHERE id IN({$admin_catalog}) GROUP BY channeltype ");
}
else
{
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` GROUP BY channeltype ");
}
$dsql->Execute();
$candoChannel = '';
while($row = $dsql->GetObject())
{
    $candoChannel .= ($candoChannel=='' ? $row->channeltype : ','.$row->channeltype);
}
if(empty($candoChannel)) $candoChannel = 1;
$dsql->SetQuery("SELECT id,typename,addcon,mancon FROM `#@__channeltype` WHERE id IN({$candoChannel}) AND id<>-1 AND isshow=1 ORDER BY id ASC");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    $addset .= "  <m:item name='{$row->typename}' ischannel='1' link='{$row->mancon}?channelid={$row->id}' linkadd='{$row->addcon}?channelid={$row->id}' channelid='{$row->id}' rank='' target='main' />\r\n";
}
//////////////////////////
$menusMain = "
-----------------------------------------------

<m:top mapitem='1' item='1_' name='常用操作' display='block'>
  <m:item name='网站栏目管理' link='catalog_main.php' ischannel='1' addalt='创建栏目' linkadd='catalog_add.php?listtype=all' rank='t_List,t_AccList' target='main' />
  <m:item name='所有档案列表' link='content_list.php' rank='a_List,a_AccList' target='main' />
  <m:item name='等审核的档案' link='content_list.php?arcrank=-1' rank='a_Check,a_AccCheck' target='main' />
  <m:item name='我发布的文档' link='content_list.php?mid=".$cuserLogin->getUserID()."' rank='a_List,a_AccList,a_MyList' target='main' />
  <m:item name='评论管理' link='feedback_main.php' rank='sys_Feedback' target='main' />
  <m:item name='内容回收站' link='recycling.php' ischannel='1' addalt='清空回收站' addico='img/gtk-del.png' linkadd='archives_do.php?dopost=clear&aid=no' rank='a_List' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='内容管理' display='block'>
  $addset
  <m:item name='专题管理' ischannel='1' link='content_s_list.php' linkadd='spec_add.php' channelid='-1' rank='spec_New' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='频道模型' display='block' rank='t_List,t_AccList,c_List,temp_One'>
  <m:item name='内容模型管理' link='mychannel_main.php' rank='c_List' target='main' />
  <m:item name='单页文档管理' link='templets_one.php' rank='temp_One' target='main'/>
  <m:item name='联动类别管理' link='stepselect_main.php' rank='c_Stepseclect' target='main' />
  <m:item name='自由列表管理' link='freelist_main.php' rank='c_List' target='main' />
  <m:item name='自定义表单' link='diy_main.php' rank='c_List' target='main' />
</m:top>

<m:top mapitem='3' item='3_' name='采集管理' display='none' rank='co_NewRule,co_ListNote,co_ViewNote,co_Switch,co_GetOut'>
  <m:item name='采集节点管理' link='co_main.php' rank='co_ListNote' target='main' />
  <m:item name='临时内容管理' link='co_url.php' rank='co_ViewNote' target='main' />
  <m:item name='导入采集规则' link='co_get_corule.php' rank='co_GetOut' target='main'/>
  <m:item name='监控采集模式' link='co_gather_start.php' rank='co_GetOut' target='main'/>
  <m:item name='采集未下载内容' link='co_do.php?dopost=coall' rank='co_GetOut' target='main'/>
</m:top>

<m:top mapitem='3' item='1_3_3' name='批量维护' display='block'>
  <m:item name='更新系统缓存' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='文档批量维护' link='content_batch_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='搜索关键词维护' link='search_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='文档关键词维护' link='article_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='自动摘要|分页' link='article_description_main.php' rank='sys_Keyword' target='main' />
  <m:item name='TAG标签管理' link='tags_main.php' rank='sys_Keyword' target='main' />
  <m:item name='数据库内容替换' link='sys_data_replace.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='4' item='5_' name='自动任务' notshowall='1'  display='block' rank='sys_MakeHtml'>
  <m:item name='一键更新网站' link='makehtml_all.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新系统缓存' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='4' item='5_' name='HTML更新' notshowall='1' display='none' rank='sys_MakeHtml'>
  <m:item name='更新主页HTML' link='makehtml_homepage.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新栏目HTML' link='makehtml_list.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新文档HTML' link='makehtml_archives.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新网站地图' link='makehtml_map_guide.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新RSS文件' link='makehtml_rss.php' rank='sys_MakeHtml' target='main' />
  <m:item name='获取JS文件' link='makehtml_js.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新专题HTML' link='makehtml_spec.php' rank='sys_MakeHtml' target='main' />
</m:top>

<m:top mapitem='3' item='1_6_' name='附件管理' display='none' rank='sys_Upload,sys_MyUpload,plus_文件管理器'>
  <m:item name='上传新文件' link='media_add.php' rank='' target='main' />
  <m:item name='附件数据管理' link='media_main.php' rank='sys_Upload,sys_MyUpload' target='main' />
  <m:item name='文件式管理器' link='media_main.php?dopost=filemanager' rank='plus_文件管理器' target='main' />
</m:top>

<m:top mapitem='5' item='6_' name='会员管理' display='none' rank='member_List,member_Type'>
  <m:item name='注册会员列表' link='member_main.php' rank='member_List' target='main' />
  <m:item name='会员级别设置' link='member_rank.php' rank='member_Type' target='main' />
  <m:item name='积分头衔设置' link='member_scores.php' rank='member_Type' target='main' />
  <m:item name='会员模型管理' link='member_model_main.php' rank='member_Type' target='main' />
  <m:item name='会员短信管理' link='member_pm.php' rank='member_Type' target='main' />
  <m:item name='会员留言管理' link='member_guestbook.php' rank='member_Type' target='main' />
  <m:item name='会员动态管理' link='member_info_main.php?type=feed' rank='member_Type' target='main' />
  <m:item name='会员心情管理' link='member_info_main.php?type=mood' rank='member_Type' target='main' />
</m:top>

<m:top mapitem='2' item='10_' name='系统设置' display='none' rank='sys_User,sys_Group,sys_Edit,sys_Log,sys_Data'>
  <m:item name='系统基本参数' link='sys_info.php' rank='sys_Edit' target='main' />
  <m:item name='系统用户管理' link='sys_admin_user.php' rank='sys_User' target='main' />
  <m:item name='用户组设定' link='sys_group.php' rank='sys_Group' target='main' />
  <m:item name='服务器分布/远程' link='sys_multiserv.php' rank='sys_Group' target='main' />
  <m:item name='系统日志管理' link='log_list.php' rank='sys_Log' target='main' />
  <m:item name='验证安全设置' link='sys_safe.php' rank='sys_verify' target='main' />
  <m:item name='图片水印设置' link='sys_info_mark.php' rank='sys_Edit' target='main' />
  <m:item name='自定义文档属性' link='content_att.php' rank='sys_Att' target='main' />
  <m:item name='软件频道设置' link='soft_config.php' rank='sys_SoftConfig' target='main' />
  <m:item name='防采集串混淆' link='article_string_mix.php' rank='sys_StringMix' target='main' />
  <m:item name='随机模板设置' link='article_template_rand.php' rank='sys_StringMix' target='main' />
  <m:item name='计划任务管理' link='sys_task.php' rank='sys_Task' target='main' />
  <m:item name='数据库备份/还原' link='sys_data.php' rank='sys_Data' target='main' />
  <m:item name='SQL命令行工具' link='sys_sql_query.php' rank='sys_Data' target='main' />
  <m:item name='文件校验[S]' link='sys_verifies.php' rank='sys_verify' target='main' />
  <m:item name='病毒扫描[S]' link='sys_safetest.php' rank='sys_verify' target='main' />
  <m:item name='系统错误修复[S]' link='sys_repair.php' rank='sys_verify' target='main' />
</m:top>

<m:top mapitem='5' item='10_6_' name='支付工具' display='none' rank='sys_Data'>
  <m:item name='点卡产品分类' link='cards_type.php' rank='sys_Data' target='main' />
  <m:item name='点卡产品管理' link='cards_manage.php' rank='sys_Data' target='main' />
  <m:item name='会员产品分类' link='member_type.php' rank='sys_Data' target='main' />
  <m:item name='会员消费记录' link='member_operations.php' rank='sys_Data' target='main' />
  <m:item name='商店订单记录' link='shops_operations.php' rank='sys_Data' target='main' />
  <m:item name='支付接口设置' link='sys_payment.php' .php' rank='sys_Data' target='main' />
  <m:item name='配货方式设置' link='shops_delivery.php' rank='sys_Data' target='main' />
</m:top>

<m:top mapitem='2' item='10_7_' name='模板管理' display='none' rank='temp_One,temp_Other,temp_MyTag,temp_test,temp_All'>
  <m:item name='默认模板管理' link='templets_main.php' rank='temp_All' target='main'/>
  <m:item name='标签源码管理' link='templets_tagsource.php' rank='temp_All' target='main'/>
  <m:item name='自定义宏标记' link='mytag_main.php' rank='temp_MyTag' target='main'/>
  <m:item name='智能标记向导' link='mytag_tag_guide.php' rank='temp_Other' target='main'/>
  <m:item name='全局标记测试' link='tag_test.php' rank='temp_Test' target='main'/>
</m:top>

";

//载入插件菜单
$plusset = '';
$dsql->SetQuery("SELECT * FROM `#@__plus` WHERE isshow=1 ORDER BY aid ASC");
$dsql->Execute();
while($row = $dsql->GetObject()) 
{
    $plusset .= $row->menustring."\r\n";
}

$menusMain .= "
<m:top mapitem='6' name='模块管理' c='6,' display='block'>
  <m:item name='模块管理' link='module_main.php' rank='sys_module' target='main' />
  <m:item name='上传新模块' link='module_upload.php' rank='sys_module' target='main' />
  <m:item name='模块生成向导' link='module_make.php' rank='sys_module' target='main' />
</m:top>

<m:top mapitem='6' item='7' name='辅助插件' display='block'>
  <m:item name='插件管理器' link='plus_main.php' rank='10' target='main' />
  $plusset
</m:top>
";

$mapstring = '';
$dtp = new DedeTagparse();
$dtp->SetNameSpace('m','<','>');
$dtp->LoadString($menusMain);

foreach($maparray as $k=>$bigname)
{
    $mapstring .= "<dl class='maptop'>\r\n";
    $mapstring .= "<dt class='bigitem'>$bigname</dt>\r\n";
    $mapstring .= "<dd>\r\n";
    foreach($dtp->CTags as $ctag)
    {
        if($ctag->GetAtt('mapitem') == $k)
        {
            $mapstring .= "<dl class='mapitem'>\r\n";
            $mapstring .= "<dt>".$ctag->GetAtt('name')."</dt>\r\n";
            $mapstring .= "<dd>\r\n<ul class='item'>\r\n";
            $dtp2 = new DedeTagParse();
            $dtp2->SetNameSpace('m', '<', '>');
            $dtp2->LoadSource($ctag->InnerText);
            foreach($dtp2->CTags as $j=>$ctag2)
            {
                $mapstring .= "<li><a href='".$ctag2->GetAtt('link')."' target='".$ctag2->GetAtt('target')."'>".$ctag2->GetAtt('name')."</a></li>\r\n";
            }
            $mapstring .= "</ul>\r\n</dd>\r\n</dl>\r\n";
        }
    }
    $mapstring .= "</dd>\r\n</dl>\r\n";
}