<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class actionsmodel {
	var $db;
	var $base;
	
	function actionsmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getHTML($action, $text, $kws, &$num){
		$html=array();
		$action = explode(',', $action);
		$action = array_map('trim', $action);
		foreach($action as $i => $menu){
			if($i==0){
				$html[]="<a href='index.php?admin_{$this->index[$menu]}' onclick='go(this)'>".$this->data[$menu].'</a>';
			}else {
				if($i == 1){
					$text=$this->data[$action[0].', '.$menu];
				}else if($i == 2){
					$text=$this->data[$action[0].', '.$action[1].', '.$menu];
				}
				
				foreach($kws as $kw) {
					if(strpos(strtolower($text), strtolower($kw)) !== FALSE) {
						if(strpos(strtolower($text), strtolower(implode('', $kws))) !== FALSE){
							$num+=10;
						}
						
						$text = str_replace($kw, '<font color="#c60a00">'.$kw .'</font>', $text);
						$num++;
					}
				}
				
				$html[]="<a menu='$i' href='index.php?admin_$menu' onclick='go(this)'>$text</a>";
			}
			
		}
		
		return implode('&gt;&gt;', $html);
	}

	function getMap(){
		$outstr = $prenum = '';
		$text = array(1=>'</dl></li>',2=>'</dd>',3=>'</ul>');
		foreach($this->data as $key=>$value) { 
			if(preg_match("/-[0-9]+$/", $key)||in_array($key, $this->ignorelist)){
				continue;
			}
			$kws = explode(',', $key);
			$kws = array_map('trim', $kws);
			$num = count($kws);
			if($num==3){
				$outstr .=$prenum!=3?'<ul>':'';
			}else{
				$subnum = $prenum?$prenum-$num:-1;
				if($subnum >= 0){
					if($subnum==0){
						$outstr .= $text[$num];
					}elseif($subnum==1){
						$outstr .= $text[$num+1].$text[$num];
					}elseif($subnum==2){
						$outstr .= $text[$num+2].$text[$num+1].$text[$num];
					}
				}
			}
			switch($num){
				case 1:
					$outstr .= '<li><dl><dt><a href="index.php?admin_'.$this->index[$key].'">'.$value."</a></dt>";
					break;
				case 2:
					$outstr .= '<dd> <a href="index.php?admin_'.$kws[1].'">'.$value.'</a>';
					break;
				case 3:
					$outstr .= '<li><a href="index.php?admin_'.$kws[2].'">'.$value."</a></li>";
					break;
			}
			$prenum = $num;
		}
		for($i=1;$i<=$prenum;$prenum--){
			$outstr .= $text[$prenum];
		}
		return $outstr;
	}
	var $ignorelist = array('index', 'index, main-mainframe', 'global, setting-base');
	var $index = array(
		'index'=>'main-mainframe',
		'global'=>'setting-base',
		'user'=>'user',
		'content'=>'doc',
		'plug'=>'theme',
		'db'=>'db-backup',
		'unions'=>'hdapi',
		'moduls'=>'image',
		'stat'=>'statistics-stand'
	);
	
	var $data = array(
		'index'=>'首页',
			'index, main-mainframe'=>'首页信息',
				
		'global'=>'全局',
			'global, setting-base'=>'站点设置',
			'global, setting-base-1'=>'站点设置 网站名称',
			'global, setting-base-2'=>'站点设置 网站URL',
			'global, setting-base-3'=>'站点设置 站内公告',
			'global, setting-base-4'=>'站点设置 网站备案信息',
			'global, setting-base-5'=>'站点设置 第三方统计代码',
			'global, setting-base-6'=>'站点设置 风格设置',
			'global, setting-base-7'=>'站点设置 是否需要兼容以前版本模板',
			'global, setting-base-8'=>'站点设置 关闭网站',
			'global, setting-base-9'=>'站点设置 关闭原因',
			
			'global, channel'=>'基本设置',
				'global, channel, channel'=>'频道管理',
				'global, channel, setting-cache'=>'缓存设置',
				'global, channel, setting-seo'=>'SEO设置',
				'global, channel, setting-code'=>'验证码',
				'global, channel, setting-time'=>'时间设置',
				
				'global, channel, setting-time-1'=>'时间设置 默认时区设置',
				'global, channel, setting-time-2'=>'时间设置 本地时间与服务器的时间差(分钟)',
				'global, channel, setting-time-3'=>'时间设置 网站显示日期格式',
				'global, channel, setting-time-4'=>'时间设置 网站显示时间格式',
				
				'global, channel, setting-cookie'=>'COOKIE设置',
				'global, channel, setting-credit'=>'经验金币设置',
				'global, channel, setting-logo'=>'LOGO设置',

			
			'global, setting-sec'=>'扩展设置',
				'global, setting-sec, setting-sec'=>'防灌水设置',
				'global, setting-sec, setting-sec-1'=>'防灌水设置 创建词条需输入验证码',
				'global, setting-sec, setting-sec-2'=>'防灌水设置 编辑词条需输入验证码',
				'global, setting-sec, setting-sec-3'=>'防灌水设置 禁言时间',
				
				'global, setting-sec, setting-anticopy'=>'防采集设置',
				'global, setting-sec, setting-mail'=>'邮件设置',
				'global, setting-sec, setting-noticemail'=>'邮件提醒设置',
				'global, setting-sec, banned'=>'IP禁止',
				'global, setting-sec, setting-passport'=>'通行证设置',
				'global, setting-sec, setting-ucenter'=>'UCenter设置',
				 
			'global, setting-index'=>'内容设置',
				'global, setting-index, setting-index'=>'首页设置',
				'global, setting-index, setting-listdisplay'=>'列表设置',
				'global, setting-index, setting-watermark'=>'图片设置',
				
				'global, setting-index, setting-watermark-1'=>'图片设置 图片本地化',
				'global, setting-index, setting-watermark-2'=>'图片设置 词条大图尺寸',
				'global, setting-index, setting-watermark-3'=>'图片设置 词条小图尺寸',
				'global, setting-index, setting-watermark-4'=>'图片设置 图片处理库类型',
				'global, setting-index, setting-watermark-5'=>'图片设置 ImageMagick 程序安装路径',
				'global, setting-index, setting-watermark-6'=>'图片设置 水印',
				'global, setting-index, setting-watermark-7'=>'图片设置 水印添加条件',
				'global, setting-index, setting-watermark-8'=>'图片设置 水印图片类型',
				'global, setting-index, setting-watermark-9'=>'图片设置 水印融合度',
				'global, setting-index, setting-watermark-10'=>'图片设置 JPEG 水印质量',
				'global, setting-index, setting-watermark-11'=>'图片设置 文本水印文字',
				'global, setting-index, setting-watermark-12'=>'图片设置 文本水印 TrueType 字体文件名',
				'global, setting-index, setting-watermark-13'=>'图片设置 文本水印字体大小',
				'global, setting-index, setting-watermark-14'=>'图片设置 文本水印字体颜色',
				'global, setting-index, setting-watermark-15'=>'图片设置 文本水印阴影横向偏移量',
				'global, setting-index, setting-watermark-16'=>'图片设置 文本水印阴影纵向偏移量',
				'global, setting-index, setting-watermark-17'=>'图片设置 文本水印阴影颜色',
				'global, setting-index, setting-watermark-18'=>'图片设置 文本水印横向偏移量(ImageMagick)',
				'global, setting-index, setting-watermark-19'=>'图片设置 文本水印纵向偏移量(ImageMagick)',
				'global, setting-index, setting-watermark-20'=>'图片设置 文本水印横向倾斜角度(ImageMagick)',
				'global, setting-index, setting-watermark-21'=>'图片设置 文本水印纵向倾斜角度(ImageMagick)',

				'global, setting-index, setting-docset'=>'词条设置',
				'global, setting-index, setting-docset-1'=>'词条设置 指定编辑实验词条的ID',
				'global, setting-index, setting-docset-2'=>'词条设置 审核词条',
				'global, setting-index, setting-docset-3'=>'词条设置 是否允许以匿名形式发表评论',
				'global, setting-index, setting-docset-4'=>'词条设置 是否在编辑器中过滤外部链接',
				'global, setting-index, setting-docset-5'=>'词条设置 新创建及最新编辑词条是否保存为历史版本',
				
				'global, setting-index, setting-search'=>'搜索设置',
				'global, setting-index, setting-attachment'=>'附件设置',
				 
			'global, friendlink'=>'友情链接',
				'global, friendlink, friendlink'=>'友情链接列表',
				'global, friendlink, friendlink-add'=>'添加友情链接',

			'global, adv'=>'广告管理',
				'global, adv, adv-default'=>'管理广告',
				'global, adv, adv-config'=>'设置广告',
				'global, adv, adv-add'=>'添加广告',
						
			'global, sitemap'=>'Sitemap',
				'global, sitemap, sitemap'=>'更新操作',
				'global, sitemap, sitemap-setting'=>'参数设置',

			'global, upgrade'=>'自动升级',
				
		'user'=>'用户管理',
			'user, setting-baseregister'=>'注册设置',
			'user, setting-baseregister-1'=>'注册设置 允许新用户注册',
			'user, setting-baseregister-2'=>'注册设置 邀请者奖励经验值',
			'user, setting-baseregister-3'=>'注册设置 被邀请者奖励经验值',
			'user, setting-baseregister-4'=>'注册设置 邀请邮件标题',
			'user, setting-baseregister-5'=>'注册设置 邀请邮件内容',
			'user, setting-baseregister-7'=>'注册设置 关闭用户注册的原因',
			'user, setting-baseregister-8'=>'注册设置 禁止注册的用户名',
			'user, setting-baseregister-9'=>'注册设置 新用户是否需审核',
			'user, setting-baseregister-10'=>'注册设置 注册用户名最小长度',
			'user, setting-baseregister-11'=>'注册设置 用户名最大长度',
			'user, setting-baseregister-12'=>'注册设置 IP 注册间隔限制',
			'user, setting-baseregister-13'=>'注册设置 发送欢迎信息',
			'user, setting-baseregister-14'=>'注册设置 欢迎信息标题',
			'user, setting-baseregister-15'=>'注册设置 欢迎信息内容',

			
			'user, user'=>'管理用户',
				'user, user, user'=>'用户列表',
				'user, user, user-uncheckeduser'=>'待审核用户',
				'user, user, user-add'=>'添加用户',
				
			'user, regular-groupset'=>'管理权限',
				'user, regular-groupset, regular-groupset-2'=>'管理权限',
				'user, regular-groupset, regular'=>'权限列表',
			
			'user, usergroup'=>'管理用户组',	
			
		'content'=>'内容管理',
			'content, category-list'=>'分类管理',
				'content, category-list, category-list'=>'管理分类',
				'content, category-list, category-add'=>'添加分类',
				'content, category-list, category-merge'=>'合并分类',
				
			'content, doc'=>'词条管理',
				'content, doc, doc'=>'管理词条',
				'content, doc, focus-focuslist'=>'推荐词条',
				'content, doc, synonym'=>'管理同义词',
				'content, doc, relation'=>'相关词条',
				'content, doc, edition'=>'版本评审',
				'content, doc, cooperate'=>'待完善词条',
				
			'content, attachment'=>'附件管理',
			'content, comment'=>'评论管理',
			'content, tag-hottag'=>'热门标签',
			'content, hotsearch'=>'热门搜索',
			'content, word'=>'词语过滤',
			'content, datacall'=>'数据调用',
				'content, datacall, datacall'=>' 调用列表',
				'content, datacall, datacall-addsql'=>'SQL调用',
				
			'content, recycle'=>'回收站',
			
			
		'plug'=>'模板/插件',
			'plug, theme'=>'模板管理',
				'plug, theme, theme'=>'设置默认风格',
				'plug, theme, theme-create'=>'创建风格',
				'plug, theme, theme-list'=>'在线安装',
				'plug, theme, theme-edit'=>'模板编辑',
			'plug, plugin'=>'插件管理',
				'plug, plugin, plugin'=>'已安装插件',
				'plug, plugin, plugin-will'=>'全部推荐插件',
				'plug, plugin, plugin-find'=>'本地已有插件',
			'plug, language'=>'网站语言编辑',
			
			
		'db'=>'数据库管理',
			'db, db-backup'=>'数据库备份',
			'db, db-tablelist'=>'数据库优化',
			'db, db-sqlwindow'=>'SQL查询窗口',
			'db, db-storage'=>'数据存储设置',
				'db, db-storage, db-storage'=>'数据存储设置',
				'db, db-storage, db-convert'=>'数据转换',

		'unions'=>'百科联盟',
			'unions, hdapi'=>'联盟首页',
			'unions, hdapi-set'=>'云搜索',
			'unions, share-set'=>'分享到新知社',
				'unions, share-set, share-set'=>'分享设置',
				'unions, share-set, share'=>'手动分享',
			'unions, hdapi-down'=>'下载词条',
				'unions, hdapi-down, hdapi-down'=>'下载词条',
				'unions, hdapi-down, hdapi-nosynset'=>'不同步列表',
			'unions, hdapi-info'=>'修改联盟资料',
			
		'moduls'=>'模块',
			'moduls, image'=>'图片百科',
			'moduls, gift'=>'礼品商店',
				'moduls, gift, gift'=>'礼品管理',
				'moduls, gift, gift-add'=>'添加礼品',
				'moduls, gift, gift-price'=>'礼品价格区间',
				'moduls, gift, gift-notice'=>'礼品公告',
				'moduls, gift, gift-notice-1'=>'礼品公告 礼品商店开关',
				'moduls, gift, gift-log'=>'礼品兑换日志',
			'moduls, safe'=>'木马扫描',
				'moduls, safe, filecheck-create'=>'创建文件校验镜像',
				'moduls, safe, safe-list'=>'上次扫描结果',
			
		'stat'=>'站内统计',
			'stat, statistics-stand'=>'基本概况',
			'stat, statistics-cat_toplist'=>'分类排行',
			'stat, statistics-doc_toplist'=>'词条排行',
			'stat, statistics-edit_toplist'=>'编辑排行榜',
			'stat, statistics-credit_toplist'=>'经验排行',
			'stat, statistics-admin_team'=>'管理团队',
			'stat, log'=>'后台操作记录'	
	);

}