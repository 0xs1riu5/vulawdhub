<?php

/**
 * FineCMS
 */

/**
 * 站点配置文件
 */

return array(

	'SITE_CLOSE'                    => 0, //网站是否是关闭状态
	'SITE_CLOSE_MSG'                => '网站升级中....', //网站关闭时的显示信息
	'SITE_NAME'                     => 'FineCMS', //网站的名称
	'SITE_TIME_FORMAT'              => 'Y-m-d H:i', //时间显示格式，与date函数一致，默认Y-m-d H:i:s
	'SITE_LANGUAGE'                 => 'zh-cn', //网站的语言
	'SITE_THEME'                    => 'default', //网站的主题风格
	'SITE_TEMPLATE'                 => 'default', //网站的模板目录
	'SITE_TIMEZONE'                 => 8, //所在的时区常量
	'SITE_DOMAINS'                  => '', //网站的其他域名
	'SITE_REWRITE'                  => 6, //
	'SITE_MOBILE_OPEN'              => 1, //是否自动识别移动端并强制定向到移动端域名
	'SITE_MOBILE'                   => '', //移动端域名
	'SITE_SEOJOIN'                  => '_', //网站SEO间隔符号
	'SITE_TITLE'                    => 'FineCMS公益软件', //网站首页SEO标题
	'SITE_KEYWORDS'                 => '免费cms,开源cms', //网站SEO关键字
	'SITE_DESCRIPTION'              => '公益软件产品介绍', //网站SEO描述信息
	'SITE_IMAGE_RATIO'              => 1, //是否宽度自动适应
	'SITE_IMAGE_WATERMARK'          => 0, //
	'SITE_IMAGE_VRTALIGN'           => 'top', //
	'SITE_IMAGE_HORALIGN'           => 'left', //
	'SITE_IMAGE_VRTOFFSET'          => '', //
	'SITE_IMAGE_HOROFFSET'          => '', //
	'SITE_IMAGE_TYPE'               => 0, //
	'SITE_IMAGE_OVERLAY'            => 'default.png', //
	'SITE_IMAGE_OPACITY'            => '', //
	'SITE_IMAGE_FONT'               => 'default.ttf', //
	'SITE_IMAGE_COLOR'              => '', //
	'SITE_IMAGE_SIZE'               => '', //
	'SITE_IMAGE_TEXT'               => '', //
	'SITE_DOMAIN'                   => '', //网站的域名
	'SITE_IMAGE_CONTENT'            => 0, //是否内容编辑器显示水印图片

);