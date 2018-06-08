<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">

<head>
    <meta charset="<?php echo WIKI_CHARSET?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <title><?php echo $navtitle?> <?php echo $setting['site_name']?> <?php echo $setting['seo_title']?> - Powered by HDWiki!</title>
    <?php echo $setting['seo_headers']?>

    <meta name="keywords" content="<?php echo $setting['seo_keywords']?>" />
    <meta name="description" content="<?php echo $setting['seo_description']?>" />
    <meta name="generator" content="HDWiki <?php echo HDWIKI_VERSION?>" />
    <meta name="author" content="HDWiki Team" />
    <meta name="csrf-token" content="<?php echo csrf_token()?>">
    <meta name="copyright" content="2005-2017 baike.com" />
    <?php if(!empty($docrewrite) && $docrewrite=='1') { ?>
    <base href="<?php echo WIKI_URL?>/" />
    <?php } ?>
<!--[if IE]>
<script src="js/html5.js" type="text/javascript"></script>
<![endif]-->

    <link href="style/<?php echo $theme?>/hdwiki.css?20170207" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="lang/<?php echo $setting['lang_name']?>/front.js"></script>
    <script type="text/javascript" src="js/jquery.dialog-2.8.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <script type="text/javascript">
        $.dialog.setConfig('base', '<?php echo WIKI_URL?>/style/default');
        var g_isLogin, g_isUcenter = false,
            g_forward = '',
            g_api_url = '',
            g_regulars = '',
            g_uname_minlength, g_uname_maxlength;
        <?php if($user['groupid']=='1') { ?>
        g_regulars = "<?php echo $header_regulars?>";
        g_isLogin = false;
        <?php } else { ?>
        g_isLogin = true;
        <?php } ?>
        <?php if(isset($pp_api)) { ?>
        g_api_url = '<?php echo $pp_api?>';
        <?php } ?>
        <?php if(!empty($isUcenter)) { ?>
        g_isUcenter = true;
        <?php } ?>
        g_seo_prefix = "<?php echo $setting['seo_prefix']?>";
        g_seo_suffix = "<?php echo $setting['seo_suffix']?>";
        g_uname_minlength = "<?php echo $setting['name_min_length']?>" || 3;
        g_uname_maxlength = "<?php echo $setting['name_max_length']?>" || 15; 
        
    <?php if($newpms[0]) { ?>
        var titlestate = 0, clock, flashingtime = 20;
        var oldtitle = "<?php echo $navtitle?> <?php echo $setting['site_name']?> <?php echo $setting['seo_title']?> - Powered by HDWiki!";
        function changeTitle(){
            if(titlestate%2 == 0){
                document.title='【新消息】'+oldtitle;
            }else{
                document.title='【　　　】'+oldtitle;
            }
            titlestate++;
            if(titlestate == flashingtime){
                clearInterval(clock);
                document.title = oldtitle;
            }
        }
        clock = setInterval("changeTitle()", 1000);

    <?php } ?>
    
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    </script>
</head>

<body>
    <!--编辑异常中断，下次登录提示-->
    <?php if($unpubdoc) { ?>
    <div class="edit_tips a-r" id="unpubdoc">
        <span class="bold l">你上次编辑的词条“<label class="red"><?php echo $unpubdoc['title']?></label>”还未发布，赶快去处理吧！
	<input name="Button1" type="button" value="继续编辑" onclick="location.href='index.php?doc-edit-<?php echo $unpubdoc['did']?>'"/></span>
        <span class="close r" onclick='$("#unpubdoc").hide()'>×</span>
    </div>
    <?php } ?>

    <div class="wrap topbar">
        <?php if(!empty($channellist[1])) { ?>
        <ul class="topnav">
            <?php foreach((array)$channellist[1] as $channel) {?>
            <li><a href="<?php echo $channel['url']?>" target="_blank"><?php echo $channel['name']?></a></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <ul class="loginbar" id="login">
            <?php if($user['groupid']=='1') { ?>
            <li name="login"><a href="index.php?user-login">登录</a></li>
            <li name="register" class="bor_no"><a href="index.php?user-register">注册</a></li>
            <?php } else { ?>
            <li class="hover-menu" title="欢迎你，"><a href="index.php?user-space-<?php echo $user['uid']?>"><?php echo $user['username']?></a>
                <?php if($user['password']!='') { ?>
                <p>
                    <a href="index.php?user-profile">个人管理</a>
                    <?php if($adminlogin ) { ?><a href="index.php?admin_main">系统设置</a>
                    <?php } ?>
                    <a href="index.php?user-logout<?php echo $referer?>">退出</a>
                </p>
                <?php } ?>
            </li>
            <?php if($user['password']!='') { ?>
            <li><a href="<?php if($newpms[3]) { ?>
index.php?pms-box-inbox-system
<?php } else { ?>
index.php?pms
<?php } ?>" id="header-pms" class="h-msg">
	<?php if($newpms[0]) { ?>
		<i><?php echo $newpms[0]?></i>
	<?php } ?>
	消息</a></li>
            <?php } else { ?>
            <li><a href="index.php?user-login">待激活</a></li>
            <li class="bor_no"><a href="index.php?user-logout<?php echo $referer?>">退出</a></li>
            <?php } ?>
            <?php } ?>
            <li class="bor_no help"><a href="index.php?doc-innerlink-<?php echo urlencode('帮助')?>">帮助</a></li>
        </ul>
    </div>
    <header class="searchbar">
        <div class="wrap">
            <a href="<?php echo WIKI_URL?>" id="logo" class="header-logo"><img alt="HDWiki" width="<?php echo $setting['logowidth']?>" src="style/default/logo.gif" /></a>
            <?php if(!empty($isimage) ) { ?>
            <form name="searchform" method="post" action="index.php?pic-search">
                <p class="searchform" id="search">
                    <input name="searchtext" type="text" class="btn_txt" maxlength="80" size="42" value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>" />
                    <input name="searchfull" type="submit" value="图片搜索" class="btn_inp img_sea_inp" />
                </p>
            </form>
            <?php } else { ?>
            <form name="searchform" method="post" action="index.php?search-kw">
                <p class="searchform" id="search">
                    <?php if($cloudsearchhead) { ?>
                    <input name="searchtext" class="btn_txt" maxlength="80" size="42" value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>" type="text" />
                    <input name="search" value="搜 索" tabindex="1" class="btn_inp sea_doc" type="submit" />
                    <?php } else { ?>
                    <input name="searchtext" class="btn_txt" maxlength="80" size="42" value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>" type="text" />
                    <input name="default" value="进入词条" tabindex="2" class="btn_inp enter_doc" type="button" />
                    <input name="full" value="1" tabindex="1" type="hidden" />
                    <input name="search" value="搜 索" tabindex="1" class="btn_inp sea_doc" type="submit" />
                    <a href="index.php?search-fulltext" class="sea_advanced clink">高级搜索</a>
                    <?php } ?>

                </p>
            </form>
            <?php } ?>
            <script>
                $('input[name=default]').click(function(){
                    var obj = $('input[name=searchtext]');
                    var flag = check_access(obj);
                    if(flag){
                        $('form[name=searchform]').attr('action','index.php?search-default');
                        $('form[name=searchform]').submit();
                    }else{
                        alert('请输入正常格式的词条名称！');
                        return false;
                    }
                })
                $('input[name=search]').click(function(){
                    var obj = $('input[name=searchtext]');
                    var flag = check_access(obj);
                    if(flag){
                        $('form[name=searchform]').submit();
                    }else{
                        alert('请输入正常格式的词条名称！');
                        return false;
                    }
                })
                function check_access(obj){
                    var min_array = ['cast', 'exec','show ','show/*','alter ','alter/*','create ','create/*','insert ','insert/*', 'select ','select/*','delete ','delete/*','update ', 'update/*','drop ','drop/*','truncate ','truncate/*','replace ','replace/*','union ','union/*','execute', 'from', 'declare', 'varchar', 'script', 'iframe', ';', '0x', '<', '>', '\\', '%27', '%22', '(', ')'];
                    var coun = min_array.length;
                    var input_value = obj.val();
                    for(var i = 0;i<coun;i++) {
                        if (input_value.indexOf(min_array[i]) > -1) {
                            obj.val('');
                            return false;
                        }
                    }
                    return true;
                }
            </script>
        </div>
    </header>
    <nav class="sitenav">
        <div id="nav" class="wrap">
            <ul>
                <?php if(!empty($channellist[2])) { ?>
                <?php foreach((array)$channellist[2] as $channel) {?>
                <li><a href="<?php echo $channel['url']?>"><?php echo $channel['name']?></a></li>
                <?php } ?>
                <?php } ?>

                <?php foreach((array)$pluginlist as $plugin) {?>
                <?php if($plugin['type']) { ?>
                <li><a href="index.php?plugin-<?php echo $plugin['identifier']?>"><?php echo $plugin['name']?></a></li>
                <?php } ?>
                <?php } ?>
            </ul>
            <div class="box"><a href="index.php?doc-create" class="ico-edit">创建词条</a><a href="index.php?doc-sandbox" class="ico-create">编辑实验</a></div>
        </div>
    </nav>
    <!--ad start -->
    <?php if(isset($advlist[0]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
    <div class="wrap ad" id="advlist_0">
        <?php echo $advlist[0]['code']?>
    </div>
    <?php } elseif(isset($advlist[0]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
    <div class="wrap ad" id="advlist_0">
    </div>
    <?php } ?>

    <!--ad end -->