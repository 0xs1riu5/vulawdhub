<?PHP
/**
 * common
 */
$lang['zh'] = '简体中文';
$lang['en'] = 'English';
$lang['tw'] = '繁体中文';
$lang['commonInstallTitle'] = 'HDWiki安装程序';
$lang['commonDBCharset'] = 'utf8';
$lang['commonCharset'] = 'UTF-8';
$lang['commonSetupLanguage'] = '安装语言';
$lang['commonSetupNavigate'] = '安装导航';
$lang['commonPrevStep'] = '上一步';
$lang['commonNextStep'] = '下一步';
$lang['commonHelp'] = '帮助';
$lang['commonOS'] = '操作系统';
$lang['commonVersion'] = '版本';
$lang['commonAttachUpload'] = '附件上传';
$lang['commonDiskSpace'] = '磁盘空间';
$lang['commonConfigRequire'] = '所需配置';
$lang['commonConfigOptimized'] = '最佳配置';
$lang['commonConfigCurrent'] = '当前服务器';
$lang['commonDirName'] = '目录名称';
$lang['commonDirDescribe'] = '目录描述';
$lang['commonStateOptimized'] = '最佳状态';
$lang['commonStateCurrent'] = '当前状态';
$lang['commonDirAttach'] = '附件目录';
$lang['commonDirUserface'] = '用户头像存放目录';
$lang['commonDirCache'] = '缓存目录';
$lang['commonDirTemplate'] = '模板目录';
$lang['commonDirTemplateCache'] = '模板缓存目录';
$lang['commonDirSysData'] = '系统数据目录';
$lang['commonDirSysPlugin'] = '插件目录';
$lang['commonFileConfig'] = '配置文件';
$lang['commonFileLogo'] = 'LOGO文件';
$lang['commonUnsupport'] = '不支持';
$lang['commonSupport'] = '支持';
$lang['commonWriteable'] = '可写';
$lang['commonNotWriteable'] = '不可写';
$lang['commonUnlimited'] = '不限';
$lang['commonAllow'] = '允许';
$lang['commonDirPower'] = '目录权限';
$lang['commonFilePower'] = '文件权限';
$lang['commonSetupOption'] = '设置选项';
$lang['commonSetupParameterValue'] = '参数值';
$lang['commonSetupComment'] = '注释';
$lang['commonInfotip'] = '提示信息';
$lang['commonFailed'] = '失败';
$lang['commonSuccess'] = '成功';


//Setup Step Title
$lang['commonLicenseInfo'] ='版权信息';
$lang['commonSystemCheck'] = '检查安装配置';
$lang['commonDatabaseSetup'] = '设置数据库';
$lang['commonAdministratorSetup'] = '设置管理员';
$lang['commonInstallComplete'] = '安装完成';

//Setup Common Tip
$lang['tipAlreadyInstall'] = '&#x60A8;&#x5DF2;&#x7ECF;&#x5B89;&#x88C5;&#x8FC7;HDWiki&#x7CFB;&#x7EDF;,<a href="../">&#x70B9;&#x51FB;&#x8FDB;&#x5165;&#x9996;&#x9875;</a>';
$lang['tipLeftHelp'] = '<strong>技术支持：</strong><br />安装中遇到任何问题请到HDwiki爱好者QQ群<span class="red">41970329</span>提问，您的问题将在第一时间得到解答。';
$lang['tipGenErrInfo'] = '由于您目录属性或服务器配置原因, 无法继续安装HDWiki, 请仔细阅读安装须知！';

/**
 * step1
 */

//Setup Step 1
$lang['step1ReadLicense'] = '阅读许可协议';
$lang['step1LicenseInfo'] = '在开始安装和使用 HDWiki 之前，请务必仔细阅读本授权文档，在确定您理解和同意以下全部条款后， 方可继续安装和使用。

北京互动百科网络技术股份有限公司为互动维客产品的开发商，依法独立拥有互动维客产品著作权。北京互动百科网络技术股份有限公司网址为 http://www.baike.com。

互动维客著作权已在中华人民共和国国家版权局注册，著作权受到法律和国际公约保护。使用者无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用互动维客软件。

本授权协议适用且仅适用于不同版本的互动维客产品，北京互动百科网络技术股份有限公司拥有对本授权协议的最终解释权。

I. 协议许可的权利

1. 您可以在完全遵守本最终用户授权协议的基础上应用本软件，而不必支付软件版权授权费用。

2. 您可以在本协议规定的约束和限制范围内修改互动维客源代码（如果被提供的话）或界面风格以适应您的网站要求。

3. 您可以在协议规定的约束和限制范围内为互动维客提供相关的插件，插件版权归作者所有。您可以将拥有版权的插件无偿提供给北京互动百科网络技术股份有限公司，但没有一定被采纳的承诺或保证。如若在新版本里采用了您开发的插件，插件源代码部分将标注作者的名字。

4. 您若需要本协议授权外的技术支持，可以和北京互动百科网络技术股份有限公司签订有偿技术支持的协议，并且享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。 自协议签订时刻起，您可以在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。

5. 您若是没有和北京互动百科网络技术股份有限公司签订有偿技术支持协议的授权用户，您也同样享有反映和提出意见的权力，但没有一定被采纳的承诺或保证。

II. 协议规定的约束和限制

1. 不得对本软件或与之关联的授权进行出租、出售、抵押或发放子许可证。

2. 无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用互动维客的整体或任何部分，未经书面许可，系统页面页脚处的互动维客名称和北京互动百科网络技术股份有限公司下属网站（http://www.baike.com） 的链接都必须保留，而不能清除或修改。

3. 禁止在互动维客的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。

4. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。

III. 有限担保和免责声明

1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。

2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。

3. 北京互动百科网络技术股份有限公司不对使用本软件构建的系统中的文章或信息承担任何责任。

有关互动维客最终用户授权协议、授权与技术服务的详细内容，均由互动维客官方网站独家提供。北京互动百科网络技术股份有限公司拥有在不事先通知的情况下，修改授权协议和技术服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。

电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装互动维客，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。';
$lang['step1Agree'] = '我同意';
$lang['step1Disagree'] = '我不同意';


/**
 * step2
 */
$lang['step2Tip'] = '<div class="log">提示信息</div><div class="mes"><p><ul><li>将压缩包中 HDWiki 目录下全部文件和目录上传到服务器。</li>
					<li>如果您使用非 Windows 系统请修改以下属性：</li>
					</ul>
					<ol><li>./uploads 附件目录权限为 0777 </li>
					<li>./uploads/userface 用户头像存放目录权限为 0777</li>
					<li>./data 数据目录权限为 0777</li>
					<li>./plugins 插件目录权限为 0777</li>
					<li>./style/default/logo.gif 网站LOGO文件权限为 0777</li>
					<li>./config.php 系统配置文件权限为 0777</li>
					</ol></p></div>';
$lang['step2AttachAllowSize'] = '允许/最大尺寸';
$lang['step2AttachDisabled'] = '不允许上传附件';
$lang['step2AttachDisabledTip'] = '附件上传或相关操作被服务器禁止。';
$lang['step2PHPVersionTooLowTip'] = '您的PHP版本低于5.2.x, 无法使用HDWiki';
$lang['step2PHPVersionTooHighTip'] = 'HDWiki尚不支持PHP7.0及以上版本';

/**
 * step3
 */
$lang['step3IsNull'] = '请返回并输入所有选项。';
$lang['step3DBPrefix'] = '数据表前缀';
$lang['step3NoConnDB'] = '无法连接到数据库，请确认MySQL地址、用户名、密码和数据库名都正确无误。';
$lang['step3DBNoPower'] = '您无权建立数据库！';
$lang['step3DBConfigWriteErrorTip'] = '安装程序无法写入系统核心配置文件, 请修改配置文件./config.php权限！';
$lang['step3DBConfigNotWriteTip'] = './config.php文件不可写，请修改为可写！';
$lang['step3MySQLExtErrorTip'] = '不支持MySQL扩展！';
$lang['step3Tip'] = '<div class="log">提示信息</div><div class="mes"><p>请在下面填写您的数据库账号信息, 通常情况下不需要修改红色选项内容。</p></div>';
$lang['step3MySqlHost'] = '数据库服务器';
$lang['step3MySqlHostComment'] = 'MySQL数据库服务器地址, 一般为 localhost:3306';
$lang['step3MySqlUser'] = '数据库用户名';
$lang['step3MySqlUserComment'] = 'MySQL数据库用户名';
$lang['step3MySqlPass'] = '数据库密码';
$lang['step3MySqlPassComment'] = 'MySQL数据库密码';
$lang['step3MySqlDBName'] = '数据库名';
$lang['step3MySqlDBNameComment'] = '数据库名称 (如果数据库不存在，则建立！)';
$lang['step3MySqlDBTablePrefix'] = '表名前缀';
$lang['step3MySqlDBTablePrefixComment'] = '同一数据库安装多个HDWiki时可改变默认前缀';
$lang['step3MySqlVersionToLowTip'] = '您的MySQL版本低于3.23, 由于程序没有经过此平台的测试, 建议您换 MySQL4或MySQL5 的数据库服务器！';
$lang['step3DBAlreadyExist'] = '数据库中已经安装过 HDWiki, 继续安装会清空原有数据。';
$lang['step3DBDropTableConfirm'] = '继续安装会清空全部原有数据，您确定要继续吗？';

/**
 * step4
 */
$lang['step4Tip'] = '设置管理员帐号。';
$lang['step4AdministratorNick'] = '管理员昵称';
$lang['step4AdministratorNickComment'] = '中、英文均可使用。';
$lang['step4AdministratorEmail'] = '管理员Email地址';
$lang['step4AdministratorEmailComment'] = 'E-mail请一定填写正确有效的地址。';
$lang['step4AdministratorPass'] = '管理员密码';
$lang['step4AdministratorPassComment'] = '密码长度不能小于6位，并且区分大小写。';
$lang['step4AdministratorRePass'] = '确认密码';
$lang['step4AdministratorRePassComment'] = '请重复输入一次密码，确认。';
$lang['step4AdministratorPassTooShortTip'] = '管理员密码至少是6个字符，建议使用英文和符号混合。';
$lang['step4AdministratorPassNotSame'] = '两次输入的密码不相同';
$lang['step4AdministratorEmailInvalid'] = '管理员Email地址不合法！';
$lang['step4ImportDefaultData'] = '导入默认数据';
$lang['step4DefaultSiteName'] = '我的HDWiki';
$lang['step4DefaultCategory'] = '默认分类';
$lang['step4Index'] = '首页';
$lang['step4Wikier'] = '维客';
$lang['step4Help'] = '帮助';
$lang['step4AnonymityUser'] = '匿名用户';
$lang['step4DocBaseInfo'] = '词条基本信息';
$lang['step4DocCatalog'] = '词词条录';
$lang['step4DocCreator'] = '词条创建者';
$lang['step4DocLatestEdit'] = '词条最后编辑';
$lang['step4SplitTags'] = '拆分Tag';
$lang['step4UpdateIndex'] = '更新首页';
$lang['step4DBExecError'] = '数据库执行错误:';


$lang['styleName1']='默认风格';
$lang['styleName2']='绿色心情';
$lang['styleName3']='血色浪漫';
$lang['styleName4']='金色年华';
$lang['styleName5']='黑色幽默';
$lang['styleName6']='白璧无瑕';
$lang['styleName7']='紫微高照';


$lang['langName1']='简体中文';
$lang['langName2']='繁體中文';
$lang['langName3']='English';

$lang['stepSetupDelInstallDirTip'] = '<div class="log">提示信息</div><div class="mes"><p style="color:red;font-size:14px">注意：请尽快删除整个 install 目录或把install.php改名，以免被他人恶意利用。</p></div>';
$lang['stepSetupSuccessTip'] = '祝贺您安装成功HDWiki！';
$lang['stepSetupGoTOIndex'] = '去首页看看先！';

$lang['shortOpenTagInvalid'] = '&#x5BF9;&#x4E0D;&#x8D77;,&#x8BF7;&#x5C06; php.ini &#x4E2D;&#x7684; short_open_tag &#x8BBE;&#x7F6E;&#x4E3AOn;&#x5426;&#x5219;&#x7A0B;&#x5E8F;&#x65E0;&#x6CD5;&#x6B63;&#x5E38;&#x8FD0;&#x884C;';

	
?>