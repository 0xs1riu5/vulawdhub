<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no">
<title><?php if(($_title)  !=  ""): ?><?php echo ($_title); ?> - <?php echo ($site["site_name"]); ?><?php else: ?><?php echo ($site["site_name"]); ?> - <?php echo ($site["site_slogan"]); ?><?php endif; ?></title>
<meta content="<?php if(($_keywords)  !=  ""): ?><?php echo ($_keywords); ?><?php else: ?><?php echo ($site["site_header_keywords"]); ?><?php endif; ?>" name="keywords">
<?php if (isset($_description)) { ?>
<meta content="<?php echo $_description; ?>" name="description">
<?php } elseif(isset($site['site_header_keywords'])) { ?>
<meta content="<?php echo $site['site_header_keywords']; ?>" name="description">
<?php } ?>
<meta property="qc:admins" content="345471037076401633636375" />
<?php echo Addons::hook('public_meta');?>
<link href="favicon.ico?v=<?php echo ($site["sys_version"]); ?>" type="image/x-icon" rel="shortcut icon">
<link href="__THEME__/css/css.php?t=css&f=global.css,module.css,menu.css,form.css,message.css,jquery.atwho.css&v=<?php echo ($site["sys_version"]); ?>.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="__THEME__/js/uploadify/uploadify.css?v=<?php echo ($site["sys_version"]); ?>" type="text/css">

<?php if(!empty($appCssList)): ?>
<?php if(is_array($appCssList)): ?><?php $i = 0;?><?php $__LIST__ = $appCssList?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$cl): ?><?php ++$i;?><?php $mod = ($i % 2 )?><link href="<?php echo APP_PUBLIC_URL;?>/<?php echo ($cl); ?>?v=<?php echo ($site["sys_version"]); ?>" rel="stylesheet" type="text/css"/><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<?php endif; ?>
<script>
/**
 * 全局变量
 */
var SITE_URL  = '<?php echo SITE_URL; ?>';
var UPLOAD_URL= '<?php echo UPLOAD_URL; ?>';
var THEME_URL = '__THEME__';
var APPNAME   = '<?php echo APP_NAME; ?>';
var MID		  = '<?php echo $mid; ?>';
var UID		  = '<?php echo $uid; ?>';
var initNums  =  '<?php echo $initNums; ?>';
var SYS_VERSION = '<?php echo $site["sys_version"]; ?>';
var UMEDITOR_HOME_URL = '__THEME__/js/um/';
var _CP       = '<?php echo C("COOKIE_PREFIX");?>';
// Js语言变量
var LANG = new Array();
</script>
<?php if(!empty($langJsList)) { ?>
<?php if(is_array($langJsList)): ?><?php $i = 0;?><?php $__LIST__ = $langJsList?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><script src="<?php echo ($vo); ?>?v=<?php echo ($site["sys_version"]); ?>"></script><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<?php } ?>

<script src="__THEME__/js/jquery.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/jquery.form.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/common.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/core.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/module.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/module.common.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/jwidget_1.0.0.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/jquery.atwho.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/jquery.caret.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/ui.core.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/ui.draggable.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/plugins/core.digg.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/plugins/core.comment.js?v=<?php echo ($site["sys_version"]); ?>"></script>
<script src="__THEME__/js/plugins/core.digg.js?v=<?php echo ($site["sys_version"]); ?>"></script>

<?php if (empty($mid)) { ?>
<style type="text/css">
body, #header { padding-right: 0; }
</style>
<?php } ?>
<?php echo Addons::hook('public_head',array('uid'=>$uid));?>
</head>
<body>
<script>
    core.plugFunc('message', function(){
        core.message.init();
        
    });
</script>

<style type="text/css">
  .login-info .error-box{top:440px;height: 32px;}
  .login-info .error-box p{line-height: 32px;}
</style>
<div id="page-wrap" class="clearfix"> 
  <!--***登录***-->
  <div class="login-main clearfix">
    <!-- <div class="login-banner"><img  src="<?php echo ($login_bg); ?>"/></div> -->
    <!--登录面板-->
    <div class="login-board">
      <h3>欢迎来<?php echo ($GLOBALS['ts']['site']['site_name']); ?></h3>
      <form id="ajax_login_form" method="POST" action="<?php echo U('public/Passport/doLogin');?>">
        <div class="login-info">
          <div class="input-outer"> <span class="ui-user"></span>
            <input type="text" name="login_email" class="text" onfocus=" if(this.value=='输入邮箱、手机或用户名') this.value=''" onblur="if(this.value=='') this.value='输入邮箱、手机或用户名'" value="输入邮箱、手机或用户名"/>
          </div>
          <div class="input-outer">
            <span class="ui-loginPwd"></span><label class="l-login login_password" style="color:#888;">输入密码</label>
            <input type="password" name="login_password" class="text" style=" position:absolute; z-index:100;" onfocus="$('.login_password').hide()" onblur="if(this.value=='') $('.login_password').show()" value=""/>
          </div>
          <div class="mb20"><a class="act-but submit" href="javascript:;" onclick="$('#ajax_login_form').submit();" >登录</a></div>
          <div class="clearfix mb20"> <a class="login-rm" event-node="login_remember" href="javascript:;"> <span class="check-ok">
            <input type="hidden" name="login_remember" value="0" id="check-box" class="checkbox"/>
            <!-- JS选择记住密码时要JS将login_remember值变为1 --></span>记住用户名</a> <a href="<?php echo U('public/Passport/findPassword');?>" class="login-fgetpwd">忘记密码？</a></div>
          <?php if(Addons::requireHooks('login_input_footer') && Addons::hook('login_input_footer')): ?>
              <div class="login-ft">
                  <div class="login-ft-head">使用第三方账号登录</div>
                  <?php echo Addons::hook('login_input_footer');?>
              </div>
         	<?php endif; ?>
          <div class="hasno-account">
            <p>还没有帐号？</p>
            <div class="other-but"> <a  onclick="javascript:window.open('<?php echo U('public/Register');?>','_self')" class="white-but fl"><i class="arow-left"></i>去注册</a> <a href="<?php echo U('square/Index/index');?>" class="white-but fr">先看看<i class="arow-right"></i></a> </div>
          </div>
          <div id="js_login_input" style="display:none" class="error-box"></div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if(($site["site_online_count"])  ==  "1"): ?><script src="<?php echo SITE_URL;?>/online_check.php?uid=<?php echo ($mid); ?>&uname=<?php echo ($user["uname"]); ?>&mod=<?php echo MODULE_NAME;?>&app=<?php echo APP_NAME;?>&act=<?php echo ACTION_NAME;?>&action=trace"></script><?php endif; ?>
<script src="__THEME__/js/login.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    //回车自动提交
    $('body').keypress(function(event){
        if(event.which==13){
          $('#ajax_login_form').submit();
        }
    });
    var bg = "<?php if($login_bg) { echo $login_bg; } else { ?>__THEME__/image/login_bj.jpg<?php } ?>";
    $('body').css('background','url('+bg+')');
    $('body').css('background-repeat','no-repeat');
    $('body').css('background-position','center');
    $('body').css('background-attachment','fixed');
})
</script>