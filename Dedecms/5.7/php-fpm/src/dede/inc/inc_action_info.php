<?php
/**
 * 后台操作记录信息
 *
 * @version        $Id: inc_action_info.php 2 14:55 2010-11-11 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");
$cuserLogin = new userLogin();
//后台功能操作配置项
$actionSearch[0] =array(
    'toptitle' => '核心',
    'title'  => '常规操作',
    'description' =>'站点档案常规功能操作',
    'soniterm' =>  array(
        0  =>  array(
            'title' =>'网站栏目管理',
            'description' =>'站点所有栏目管理',
            'purview' =>'t_List,t_AccList',
            'linkurl' =>'catalog_main.php'
        ),
        1  =>  array(
            'title' =>'等审核的档案',
            'description' =>'所有内容模型发表的未经审核内容列表',
            'purview' =>'a_Check,a_AccCheck',
            'linkurl' =>'content_list.php?arcrank=-1'
        ),
        2  =>  array(
            'title' =>'我发布的文档',
            'description' =>'现在登录的管理员所发表的所有内容模型中的文档',
            'purview' =>'a_List,a_AccList,a_MyList',
            'linkurl' =>'content_list.php?mid='.$cuserLogin->userID
        ),
        3  =>  array(
            'title' =>'评论管理',
            'description' =>'网站所有评论管理',
            'purview' =>'sys_Feedback',
            'linkurl' =>'feedback_main.php'
        ),
        4  =>  array(
            'title' =>'内容回收站',
            'description' =>'如果在"系统基本参数"的"核心设置"中开启了"文章回收站(是/否)开启功能",后台删除的文档将会存放在此处',
            'purview' =>'a_List,a_AccList,a_MyList',
            'linkurl' =>'recycling.php'
        )
    )
);
$actionSearch[1] = array(
    'toptitle' => '核心', 
    'title' => '内容管理',
    'description' => '网站对应内容模型的文档管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'专题管理',
            'description' =>'所有专题内容的管理',
            'purview' =>'spec_New',
            'linkurl' =>'content_s_list.php'
        ),
    )
);
$actionSearch[2] = array(
    'toptitle' => '核心', 
    'title' => '附件管理',
    'description' => '所有上传的附件管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'上传新文件 ',
            'description' =>'通过这可以上传图片、FLASH、视频/音频、附件/其它等附件 ',
            'purview' =>'',
            'linkurl' =>'media_add.php'
        ),
        1  =>  array(
            'title' =>'附件数据管理 ',
            'description' =>'列出所有上传的附件',
            'purview' =>'sys_Upload,sys_MyUpload',
            'linkurl' =>'media_main.php'
        ),
        2  =>  array(
            'title' =>'文件式管理器 ',
            'description' =>'应用文件浏览的模式进行附件的管理',
            'purview' =>'plus_文件管理器',
            'linkurl' =>'media_main.php?dopost=filemanager'
        ),
    )
);
$actionSearch[3] = array(
    'toptitle' => '核心', 
    'title' => '频道模型',
    'description' => '所有上传的附件管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'内容模型管理 ',
            'description' =>'可以对现有商品、软件、图片集、普通文章、专题、分类信息等模型就行管理，也可以创建新的内容模型',
            'purview' =>'c_List',
            'linkurl' =>'mychannel_main.php'
        ),
        1  =>  array(
            'title' =>'单页文档管理 ',
            'description' =>'创建和管理单页面',
            'purview' =>'temp_One',
            'linkurl' =>'templets_one.php'
        ),
        2  =>  array(
            'title' =>'联动类别管理 ',
            'description' =>'创建和管理所有的联动',
            'purview' =>'c_Stepseclect',
            'linkurl' =>'stepselect_main.php?dopost=filemanager'
        ),
        3  =>  array(
            'title' =>'自由列表管理 ',
            'description' =>'创建不同的列表形式',
            'purview' =>'c_List',
            'linkurl' =>'freelist_main.php'
        ),
        4  =>  array(
            'title' =>'自定义表单 ',
            'description' =>'创建和管理自定义表单',
            'purview' =>'c_List',
            'linkurl' =>'diy_main.php'
        ),
    )
);
$actionSearch[4] = array(
    'toptitle' => '核心', 
    'title' => '批量维护',
    'description' => '对一些东西进行批量的删除，添加等等',
    'soniterm' => array(
        0  =>  array(
            'title' =>'更新系统缓存 ',
            'description' =>'更新栏目缓存、更新枚举缓存 、清理arclist调用缓存 、清理过期会员访问历史 、删除过期短信',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_cache_up.php'
        ),
        1  =>  array(
            'title' =>'文档批量维护 ',
            'description' =>'批量的对某个栏目或者全部栏目的内容进行审核文档、更新HTML、移动文档、删除文档',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'content_batch_up.php'
        ),
        2  =>  array(
            'title' =>'搜索关键词维护 ',
            'description' =>'对已经进行的所有所搜的关键词进行管理',
            'purview' =>'sys_Keyword',
            'linkurl' =>'search_keywords_main.php?dopost=filemanager'
        ),
        3  =>  array(
            'title' =>'文档关键词维护 ',
            'description' =>'对文档中的关键词进行批量的维护',
            'purview' =>'sys_Keyword',
            'linkurl' =>'article_keywords_main.php'
        ),
        4  =>  array(
            'title' =>'重复文档检测 ',
            'description' =>'可以对网站中出现的重复标题的文档进行处理',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'article_test_same.php'
        ),
        5  =>  array(
            'title' =>'自动摘要|分页 ',
            'description' =>'用于自动更新您系统没有填写摘要的文档的摘要信息或更新没分页的文档的自动分页标识',
            'purview' =>'sys_Keyword',
            'linkurl' =>'article_description_main.php'
        ),
        6  =>  array(
            'title' =>'TAG标签管理 ',
            'description' =>'对整个网站的tag进行批量的维护',
            'purview' =>'sys_Keyword',
            'linkurl' =>'tags_main.php'
        ),
        7  =>  array(
            'title' =>'数据库内容替换 ',
            'description' =>'可以对数据库中的某张表中的字段进行内容的批量替换',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_data_replace.php'
        ),
    )
);
$actionSearch[5] = array(
    'toptitle' => '会员', 
    'title' => '会员管理',
    'description' => '注册会员及积分等配置管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'注册会员列表',
            'description' =>'所有注册会员的管理项,其中包含修改,删除,查看会员文档以及提升管理员等操作',
            'purview' =>'member_List',
            'linkurl' =>'member_main.php'
        ),
        1  =>  array(
            'title' =>'会员级别设置',
            'description' =>'设置会员的级别,可以通过设计不同会员的访问权限来对会员级别进行一个扩展',
            'purview' =>'member_Type',
            'linkurl' =>'member_rank.php'
        ),
        2  =>  array(
            'title' =>'积分头衔设置',
            'description' =>'会员积分等级设置,根据会员活动积分对会员进行头衔划分',
            'purview' =>'member_Type',
            'linkurl' =>'member_scores.php'
        ),
        3  =>  array(
            'title' =>'会员模型管理',
            'description' =>'为会员制定不同的会员分类,默认有个人,企业两种用户类型,并且同时可以为用户模型添加不同的字段',
            'purview' =>'member_Type',
            'linkurl' =>'member_model_main.php'
        ),
        4  =>  array(
            'title' =>'会员短信管理',
            'description' =>'会员之间发送的短消息管理,其中包含群发短消息和对单个会员发送短消息两种',
            'purview' =>'member_Type',
            'linkurl' =>'member_pm.php'
        ),
        5  =>  array(
            'title' =>'会员留言管理',
            'description' =>'会员空间留言的管理项目,可以对留言进行批量删除等操作',
            'purview' =>'member_Type',
            'linkurl' =>'member_guestbook.php'
        ),
        6  =>  array(
            'title' =>'会员动态管理',
            'description' =>'会员添加内容,删除内容,评论内容产生的会员动态,在会员中心首页调用显示,这里可以进行批量管理操作',
            'purview' =>'member_Type',
            'linkurl' =>'member_info_main.php?type=feed'
        ),
        7  =>  array(
            'title' =>'会员心情管理',
            'description' =>'会员中心会员添加的会员心情,这里可以进行批量操作',
            'purview' =>'member_Type',
            'linkurl' =>'member_info_main.php?type=mood'
        ),
    )
);
$actionSearch[6] = array(
    'toptitle' => '会员', 
    'title' => '支付工具',
    'description' => '站点财务相关设置,包含点卡,商店订单等操作',
    'soniterm' => array(
        0  =>  array(
            'title' =>'点卡产品分类',
            'description' =>'网站点卡产品分类,可以添加不同点数的点卡产品类型',
            'purview' =>'sys_Data',
            'linkurl' =>'cards_type.php'
        ),
        1  =>  array(
            'title' =>'点卡产品管理',
            'description' =>'管理网站点卡,可以在这里生成点卡以及查看点卡的当前状态',
            'purview' =>'sys_Data',
            'linkurl' =>'cards_manage.php'
        ),
        2  =>  array(
            'title' =>'会员产品分类',
            'description' =>'可以将会员类型进行产品划分,比如出售高级会员1年这种,在这里可以对会员产品进行定义',
            'purview' =>'sys_Data',
            'linkurl' =>'member_type.php'
        ),
        3  =>  array(
            'title' =>'会员消费记录',
            'description' =>'会员在前台进行操作、消费积分的消费记录，同时可以查看消费充值订单的付款情况',
            'purview' =>'sys_Data',
            'linkurl' =>'member_operations.php'
        ),
        4  =>  array(
            'title' =>'商店订单记录',
            'description' =>'前台会员商店提交的订单记录，这里可以对这些订单进行一个统一的管理',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_operations.php'
        ),
        5  =>  array(
            'title' =>'支付接口设置',
            'description' =>'商店以及会员产品付款用到的在线付款方式需要设置的支付接口，这里含有常用的接口，例如：支付宝，易宝等',
            'purview' =>'sys_Data',
            'linkurl' =>'sys_payment.php'
        ),
        6  =>  array(
            'title' =>'配货方式设置',
            'description' =>'网站在线商城的送货方式，这里可以对其进行编辑管理',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_delivery.php'
        ),
        7  =>  array(
            'title' =>'汇款账号设置',
            'description' =>'银行付款的账号设置,用户可以查看到你的银行付款账号方便支付',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_bank.php'
        ),
    )
);
$actionSearch[7] = array(
    'toptitle' => '生成', 
    'title' => '自动任务',
    'description' => '一键生成静态管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'一键更新网站',
            'description' =>'可以一键生成所有静态页面',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_all.php'
        ),
        1  =>  array(
            'title' =>'更新系统缓存',
            'description' =>'更新栏目缓存、更新枚举缓存、清理arclist调用缓存、清理过期会员访问历史、删除过期短信 ',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_cache_up.php'
        ),
    )
);
$actionSearch[8] = array(
    'toptitle' => '生成', 
    'title' => 'HTML更新',
    'description' => '针对主页、栏目、文档、专题等等进行更新',
    'soniterm' => array(
        0  =>  array(
            'title' =>'更新主页HTML',
            'description' =>'生成网站主页面的HTML',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_homepage.php'
        ),
        1  =>  array(
            'title' =>'更新栏目 HTML',
            'description' =>'对每个栏目进行静态HTML页面的生成',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_list.php'
        ),
        2  =>  array(
            'title' =>'更新文档HTML',
            'description' =>'对每个栏目下的文档进行静态HTML页面的生成',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_archives.php'
        ),    
        3  =>  array(
            'title' =>'更新网站地图',
            'description' =>'生成网站地图的静态HTML页面',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_map_guide.php'
        ),
        4  =>  array(
            'title' =>'更新RSS文件 HTML',
            'description' =>'对全站的RSS进行更新',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_rss.php'
        ),
        5  =>  array(
            'title' =>'获取JS文件',
            'description' =>'可以获取某个栏目的js连接',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_js.php'
        ),
        6  =>  array(
            'title' =>'更新专题 HTML',
            'description' =>'对专题进行静态HTML页面的生成',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_spec.php'
        ),
    )
);
$actionSearch[9] = array(
    'toptitle' => '模板', 
    'title' => '模板管理',
    'description' => '针对主页、栏目、文档、专题等等进行更新',
    'soniterm' => array(
        0  =>  array(
            'title' =>'默认模板管理 ',
            'description' =>'对网站正在采用的模板文件进行管理',
            'purview' =>'temp_All',
            'linkurl' =>'templets_main.php'
        ),
        1  =>  array(
            'title' =>'标签源码管理 ',
            'description' =>'对现有的标签文件进行修改、添加',
            'purview' =>'temp_All',
            'linkurl' =>'templets_tagsource.php'
        ),
        2  =>  array(
            'title' =>'自定义宏标记',
            'description' =>'管理自定义标记',
            'purview' =>'temp_MyTag',
            'linkurl' =>'mytag_main.php'
        ),    
        3  =>  array(
            'title' =>'智能标记向导',
            'description' =>'可以根据需要生成相应的调用标签',
            'purview' =>'temp_Other',
            'linkurl' =>'mytag_tag_guide.php'
        ),
        4  =>  array(
            'title' =>'全局标记测试 ',
            'description' =>'可以对全局的标签调用进行测试',
            'purview' =>'temp_Test',
            'linkurl' =>'tag_test.php'
        ),
    )
);
$actionSearch[10] = array(
    'toptitle' => '系统', 
    'title' => '系统设置',
    'description' => '对网站的一些基本信息和配置进行管理',
    'soniterm' => array(
        0  =>  array(
            'title' =>'系统基本参数',
            'description' =>'包含站点设置、核心设置 、附件设置、会员设置、互动设置、性能选项、其它选项、模块设置、添加新变量等分类，其中有网站基本信息和网站的基本设置选项',
            'purview' =>'sys_Edit',
            'linkurl' =>'sys_info.php'
        ),
        1  =>  array(
            'title' =>'系统用户管理',
            'description' =>'对现有的网站管理员进行管理',
            'purview' =>'sys_User',
            'linkurl' =>'sys_admin_user.php'
        ),
        2  =>  array(
            'title' =>'用户组设定',
            'description' =>'对网站管理员进行用户组别的划分',
            'purview' =>'sys_Group',
            'linkurl' =>'sys_group.php'
        ),
        3  =>  array(
            'title' =>'系统日志管理',
            'description' =>'对每个登陆后台的管理员进行的操作进行记录',
            'purview' =>'sys_Log',
            'linkurl' =>'log_list.php'
        ),
        4  =>  array(
            'title' =>'验证安全设置',
            'description' =>'对验证码和验证问题进行设置',
            'purview' =>'sys_safe.php',
            'linkurl' =>'sys_verify'
        ),
        5  =>  array(
            'title' =>'图片水印设置',
            'description' =>'对于上传的图片添加的水印进行配置',
            'purview' =>'sys_Edit',
            'linkurl' =>'sys_info_mark.php'
        ),
        6  =>  array(
            'title' =>'自定义文档属性',
            'description' =>'在以往的版本中，网站主页、频道封面的设计，都只能单调的用 arclist 标记把某栏目最新或按特定排序方式的文档无选择的读出来，这样做法存在很大的不足，在发布的时候对适合的文档选择专门的属性，那么使用arclist的地方就会按您的意愿显示指定的文档。',
            'purview' =>'sys_Att',
            'linkurl' =>'content_att.php'
        ),
        7  =>  array(
            'title' =>'软件频道设置',
            'description' =>'可以对软件下载时的连接显示方式，下载方式，镜像服务器等等进行配置',
            'purview' =>'sys_SoftConfig',
            'linkurl' =>'soft_config.php'
        ),
        8  =>  array(
            'title' =>'防采集串混淆',
            'description' =>'防采集混淆字符串管理',
            'purview' =>'sys_StringMix',
            'linkurl' =>'article_string_mix.php'
        ),
        9  =>  array(
            'title' =>'随机模板设置',
            'description' =>'本设置仅适用于系统默认的文章模型，设置后发布文章时会自动按指定的模板随机获取一个，如果不想使用此功能，把它设置为空即可！',
            'purview' =>'sys_StringMix',
            'linkurl' =>'article_template_rand.php'
        ),
        10  =>  array(
            'title' =>'计划任务管理',
            'description' =>'可以添加一个指定时间运行的程序',
            'purview' =>'sys_task',
            'linkurl' =>'sys_task.php'
        ),
        11  =>  array(
            'title' =>'数据库备份/还原',
            'description' =>'对数据库进行备份和还原',
            'purview' =>'sys_data',
            'linkurl' =>'sys_data.php'
        ),
        12  =>  array(
            'title' =>'SQL命令行工具',
            'description' =>'可以在针对每张数据表执行单行或者多行的SQL语句',
            'purview' =>'sys_data',
            'linkurl' =>'sys_sql_query.php'
        ),
        13  =>  array(
            'title' =>'文件校验[S]',
            'description' =>'文件校验将检查本站文件是否和dedecms原始文件是否完全一致',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_verifies.php'
        ),
        14  =>  array(
            'title' =>'病毒扫描[S]',
            'description' =>'以DedeCms开发模式为标准对现有的文件进行扫描并进行判断',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_safetest.php'
        ),
        15  =>  array(
            'title' =>'系统错误修复[S]',
            'description' =>'由于手动升级时用户没运行指定的SQL语句，或自动升级的遗漏处理或处理出错，可能会导致一些错误，使用本工具会自动检测并处理。',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_repair.php'
        ),
    )
);
$actionSearch[11] = array(
    'toptitle' => '采集', 
    'title' => '采集管理',
    'description' => '内容采集管理操作',
    'soniterm' => array(
        0  =>  array(
            'title' =>'采集节点管理 ',
            'description' =>'单个采集节点的管理页面,可以添加采集,导入,导出采集节点等',
            'purview' =>'co_ListNote',
            'linkurl' =>'co_main.php'
        ),
        1  =>  array(
            'title' =>'临时内容管理 ',
            'description' =>'采集的临时内容存放处',
            'purview' =>'co_ViewNote',
            'linkurl' =>'co_url.php'
        ),
        2  =>  array(
            'title' =>'导入采集规则',
            'description' =>'导入采集的规则',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_get_corule.php'
        ),    
        3  =>  array(
            'title' =>'智能标记向导',
            'description' =>'可以根据需要生成相应的调用标签',
            'purview' =>'temp_Other',
            'linkurl' =>'mytag_tag_guide.php'
        ),
        4  =>  array(
            'title' =>'监控采集模式 ',
            'description' =>'监控采集模式',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_gather_start.php'
        ),
        5  =>  array(
            'title' =>'采集未下载内容 ',
            'description' =>'采集没有下载完成的内容',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_do.php?dopost=coall'
        ),
    )
);