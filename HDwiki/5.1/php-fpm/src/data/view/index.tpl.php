<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript">
$(document).ready(function(){
	$("#tpbk img,#tjct img").each(function(i){
		var w = this.width;
		var h = this.height;
		if(w > 100 || h > 75){
			if(w/h>4/3){
				this.style.width = "100px"
			}else{
				this.style.height = "75px"
			}
		}
	});
	
	$("input[name*='searchtext']") .focus();
});
</script>

<div class="l w-710 o-v">

	<div class="l w-270">
    	<div id="block_ctop1"><?php $data= $GLOBALS['blockdata'][1];$bid="1"?><div id="reci" class="columns  reci <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">热门词条</h2>
	<a href="index.php?list-focus-2" class="more">更多>></a>
	<?php foreach((array)$data['list'] as $key=>$hotdoc) {?>
		<dl class="col-dl <?php if($indexcache['hotdocounts']==$key+1) { ?>bor_no<?php } ?>">
			<dt><a href="index.php?doc-view-<?php echo $hotdoc['did']?>"  title="<?php echo $hotdoc['title']?>"><?php echo $hotdoc['shorttitle']?></a></dt>
			<dd><?php echo $hotdoc['summary']?>[<a href="index.php?doc-view-<?php echo $hotdoc['did']?>"  class="entry">详细</a>]</dd>
		</dl>
	<?php }?>
</div></div>
	</div>
	<div class="w-430 r">
    	<div id="block_ctop2"><?php $data= $GLOBALS['blockdata'][2];$bid="2"?><div id="jcct" class="columns jcct <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
<h2 class="col-h2">精彩词条</h2>
<a href="index.php?list-focus-3"  class="more">更多>></a>
<?php if(isset($data['fistwonderdoc'])) { ?>
    <dl class="col-dl">
        <dd class="l"><a href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>"  class="a-img"><img title="<?php echo $data['fistwonderdoc']['title']?>" src="<?php echo $data['fistwonderdoc']['image']?>"/></a></dd>
        <dt class="h1 a-c bold"><a href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>" title="<?php echo $data['fistwonderdoc']['title']?>" ><?php echo $data['fistwonderdoc']['shorttitle']?></a></dt>
        <dd><p><?php echo $data['fistwonderdoc']['summary']?>...<a href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>" >阅读全文&gt;&gt;</a></p></dd>
    </dl>
    <ul class="col-ul point font-14 link_blue ">
        <?php foreach((array)$data['list'] as $wondoc) {?>
            <li><a href="index.php?doc-view-<?php echo $wondoc['did']?>" ><?php echo $wondoc['title']?></a>: <?php echo $wondoc['summary']?></li>
        <?php } ?>
    </ul>
<?php } ?>
</div></div>
	</div>

<!--ad start -->

<?php if(isset($advlist[2]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
<div class="ad" id="advlist_2">
<?php echo $advlist[2][code]?>
</div>
<?php } elseif(isset($advlist[2]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
<div class="ad" id="advlist_2">
</div>
<?php } ?>

<!--ad end -->	
</div>
<div class="r w-230">
	<div id="block_right"><?php $data= $GLOBALS['blockdata'][3];$bid="3"?><div id="login-static" class="columns login-static i-login <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">登录用户</h2>
	<?php if($user['groupid']=='1') { ?>
		<?php if($data['data']['passport']) { ?>
		<span class="red error" style="top:80px">已开启通行证,请直接点击<a href="index.php?user-login">登录</a></span>
		<?php } else { ?>
		<span class="error" id="logintip"></span>
		<form action="" onsubmit="return docheck();">
		<ul class="col-ul" id="nologin" style="display:block">
			<li><span>用户名：</span><input name="username" id="username" tabindex="3" type="text" class="inp_txt" onblur="check_username()" maxlength="32" /></li>
			<li><span>密&nbsp;&nbsp;码：</span><input name="password" id="password"  tabindex="4" type="password" class="inp_txt" onblur="check_passwd()" maxlength="32" /></li>
			<?php if($data['data']['checkcode'] != 3) { ?>
			<li class="yzm"><span>验证码：</span><input name="code" id="code"  tabindex="5" type="text" onblur="check_code()" maxlength="4" /><label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label><a href="javascript:updateverifycode();">换一个</a>
			</li>
			<?php } ?>
			<li><input name="submit" type="submit" value="登录" class="btn_inp" tabindex="6" /><input name="Button1" type="button" value="我要注册" class="btn_inp" onclick="location.href='index.php?user-register';" /></li>
		</ul>
		</form>
		<?php } ?>
	<?php } else { ?>
	<dl id="islogin" class="col-dl twhp" >
	<dd class="block"><a href="index.php?user-space-<?php echo $user['uid']?>" class="a-img1"><img alt="点击进入用户中心" src="<?php if($user['image']) { ?><?php echo $user['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="36"/></a></dd>
	<dt><a href="index.php?user-space-<?php echo $user['uid']?>" class="m-r8 bold black"><?php echo $user['username']?></a><img title="您现在拥有<?php echo $user['credit1']?>金币 " src="style/default/jb.gif" class="sign"/></dt>
	<dd class="m-b8"><span>头衔：<font color="<?php echo $user['color']?>"><?php echo $user['grouptitle']?></font></span></dd>
	<dd><span>经验：<?php echo $user['credit2']?></span></dd>		
	<dd><span>创建词条：<?php echo $user['creates']?></span><span>人气指数：<?php echo $user['views']?></span></dd>
	<dd class="twhp_dd"><span>编辑词条：<?php echo $user['edits']?></span><a href="index.php?user-space-<?php echo $user['uid']?>" class="red">我的百科</a></dd>
	</dl>
	<?php } ?>
	<p class="novice">
	<a href="index.php?doc-innerlink-<?php echo urlencode('初来乍到，了解一下')?>" >初来乍到，了解一下</a>
	<a href="index.php?doc-innerlink-<?php echo urlencode('我是新手，怎样编写词条')?>" >我是新手，怎样编写词条</a>
	<a href="index.php?doc-innerlink-<?php echo urlencode('我要成为词条达人')?>" >我要成为词条达人</a>
	</p>
<script>
	var indexlogin = 1;
	var loginTip1 = '用户名不能为空!';
	var loginTip2 = "<?php echo $data['data']['loginTip2']?>";
	var loginTip3 = '用户不存在!';
	var logincodewrong = '验证码不匹配!';
	var name_max_length = "<?php echo $data['data'][name_max_length]?>";
	var name_min_length = "<?php echo $data['data'][name_min_length]?>";
	var editPassTip1 = '密码不能为空，最多32位!';
	var loginTip4 = '不匹配!';
	var checkcode = "<?php echo $data['data']['checkcode']?>";
</script>
</div><?php $data= $GLOBALS['blockdata'][4];$bid="4"?><div id="notice" class="columns qh notice p-b8 <?php echo $data['config']['style']?>"  bid="<?php echo $bid?>">
    <h2 class="col-h2 qh-h2 h3">
    <a href="#zngg" target="_self" <?php if($setting['base_toplist']==1) { ?>class="on"<?php } ?>>站内公告</a>
    <a href="#zxdt" target="_self" <?php if($setting['base_toplist']==0) { ?>class="on"<?php } ?>>最新动态</a>
    </h2>
    <div id="zxdt" <?php if($setting['base_toplist']==1) { ?>class='none'<?php } ?>>
        <?php foreach((array)$data['list'] as $newslist) {?>
            <p class="col-p"><?php echo $newslist?></p>
        <?php } ?>
    </div>
    <div id="zngg" <?php if($setting['base_toplist']==0) { ?>class='none'<?php } ?>><p class="col-p" ><?php echo $setting['site_notice']?></p></div>
<script>
function s(zxdt, delay, speed){
	this.rotator = $("#"+zxdt);
	this.delay = delay || 2000;
	this.speed = speed || 30;
	this.tid = this.tid2 = this.firstp = null;
	this.pause = false;
	this.num=0;
	this.p_length=$("#zxdt p").length;
	this.tgl=1;
}
s.prototype = {
	bind:function(){
		var o = this;
		this.rotator.hover(function(){o.end();},function(){o.start();});
	},
	start:function(){
		this.pause=false;
		if($("#zxdt p").length==this.p_length){
			this.firstp=$("#zxdt p:first-child");
			this.rotator.append(this.firstp.clone());
		}
		var o = this;
		this.tid = setInterval(function(){o.rotation();}, this.speed);
	},
	end:function(){
		this.pause=true;
		clearInterval(this.tid);
		clearTimeout(this.tid2);
	},
	rotation:function(){
		if(this.pause)return;
		var o=this;
		var firstp=$("#zxdt p:first-child");
		this.num++;
		this.rotator[0].scrollTop=this.num;
		if (this.num == this.firstp[0].scrollHeight+4){
			clearInterval(this.tid);
			this.firstp.remove();
			this.num = 0;
			this.rotator[0].scrollTop = 0;
			this.tid2 = setTimeout(function(){o.start();},this.delay);
		}
	},
	toggle:function(){
	    if(this.tgl>0){
		this.end();
	    }else{
		this.start();
	    }
	    this.tgl*=-1;
	}
}

$("#notice h2 a").click(function(){
	var id = $(this).attr('href');
	$("#zxdt, #zngg").hide();
	$("#notice h2  a").toggleClass('on');
	$(id).show();
	if(scroll){
		scroll.toggle();
	}
	return false;
});
if($("#zxdt").height()>300){
	$("#zxdt").height(300);
	$("#zxdt").css("overflow","hidden");
	var scroll=new s('zxdt',2000,30);
	scroll.bind();
	scroll.start();
}
</script>
</div><?php $data= $GLOBALS['blockdata'][5];$bid="5"?><div  bid="<?php echo $bid?>" class="columns dwsct i6-ff list2 <?php echo $data['config']['style']?>">
	<h2 class="col-h2">待完善词条</h2>
	<a href="index.php?doc-cooperate"  class="more">更多>></a>
	<ul class="col-ul" >
		<?php foreach((array)$data['list'] as $coopdoc) {?>
			<li><a href="index.php?doc-innerlink-<?php echo urlencode(string::hiconv($coopdoc['title'], 'utf-8'))?>"  title="<?php echo $coopdoc['title']?>"><?php echo $coopdoc['shorttitle']?></a></li>
		<?php } ?>
	</ul>
</div><?php $data= $GLOBALS['blockdata'][17];$bid="17"?><div class=" columns i6-ff p-b10 azmsx<?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">按字母顺序浏览</h2>
	<ul class="col-ul list-s">
		<li><a href="index.php?list-letter-A">A</a></li>
		<li><a href="index.php?list-letter-B">B</a></li>
		<li><a href="index.php?list-letter-C">C</a></li>
		<li><a href="index.php?list-letter-D">D</a></li>
		<li><a href="index.php?list-letter-E">E</a></li>
		<li><a href="index.php?list-letter-F">F</a></li>
		<li><a href="index.php?list-letter-G">G</a></li>
		<li><a href="index.php?list-letter-H">H</a></li>
		<li><a href="index.php?list-letter-I">I</a></li>
		<li><a href="index.php?list-letter-J">J</a></li>
		<li><a href="index.php?list-letter-K">K</a></li>
		<li><a href="index.php?list-letter-L">L</a></li>
		<li><a href="index.php?list-letter-M">M</a></li>
		<li><a href="index.php?list-letter-N">N</a></li>
		<li><a href="index.php?list-letter-O">O</a></li>
		<li><a href="index.php?list-letter-P">P</a></li>
		<li><a href="index.php?list-letter-Q">Q</a></li>
		<li><a href="index.php?list-letter-R">R</a></li>
		<li><a href="index.php?list-letter-S">S</a></li>
		<li><a href="index.php?list-letter-T">T</a></li>
		<li><a href="index.php?list-letter-U">U</a></li>
		<li><a href="index.php?list-letter-V">V</a></li>
		<li><a href="index.php?list-letter-W">W</a></li>
		<li><a href="index.php?list-letter-X">X</a></li>
		<li><a href="index.php?list-letter-Y">Y</a></li>
		<li><a href="index.php?list-letter-Z">Z</a></li>
		<li><a href="index.php?list-letter-0">0</a></li>
		<li><a href="index.php?list-letter-1">1</a></li>
		<li><a href="index.php?list-letter-2">2</a></li>
		<li><a href="index.php?list-letter-3">3</a></li>
		<li><a href="index.php?list-letter-4">4</a></li>
		<li><a href="index.php?list-letter-5">5</a></li>
		<li><a href="index.php?list-letter-6">6</a></li>
		<li><a href="index.php?list-letter-7">7</a></li>
		<li><a href="index.php?list-letter-8">8</a></li>
		<li><a href="index.php?list-letter-9">9</a></li>
		<li><a href="index.php?list-letter-*" style="width:auto">其他</a></li>
	</ul>
</div></div>
</div>
<div class="l w-710 o-v">
	<div class="l w-270">
    	<div id="block_cbottoml"><?php $data= $GLOBALS['blockdata'][6];$bid="6"?>
<div id="zjgx" class="columns zjgx o-v <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
    <h2 class="col-h2">最近更新</h2>
    <a href="index.php?list-recentupdate" class="more">更多>></a>
    <ul class="col-ul font-14 ">
       <?php foreach((array)$data['doclist'] as $doc) {?>
            <li><a href="index.php?doc-view-<?php echo $doc['did']?>"  class="ctm" title="<?php echo $doc['title']?>"><?php echo $doc['shorttitle']?></a><span><?php echo $doc['lastedit']?></span></li>
       <?php } ?>
    </ul>
</div><?php $data= $GLOBALS['blockdata'][7];$bid="7"?>
<div id="zjpl" class="columns zjpl <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">最近评论</h2>
	<?php foreach((array)$data['list'] as $comment) {?>
		<div class="pl_unit">
			<a href="index.php?user-space-<?php echo $comment['authorid']?>"  class="a-img1"><img alt="" src="<?php if($comment['image'] ) { ?><?php echo $comment['image']?><?php } else { ?><?php } ?>"/></a>
			<p><a href="index.php?comment-view-<?php echo $comment['did']?>" title="<?php echo $comment['comment']?>"  class="block"><?php echo $comment['tipcomment']?></a><?php echo $comment['time']?></p>
		</div>
	<?php } ?>
</div></div>
	</div>
	<div class="w-430 r">
    	<div id="block_cbottomr"><?php $data= $GLOBALS['blockdata'][8];$bid="8"?><div id="block-pic-getlist-<?php echo $bid?>" class="columns tpbk i6-ff <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">百科图片</h2>
	<a href="index.php?pic-piclist"  class="more">更多>></a>
	<?php foreach((array)$data['list'] as $picname) {?>
		<div class="jc_tj">
			<a href="index.php?pic-view-<?php echo $picname['id']?>-<?php echo $picname['did']?>"  class="a-img"><img title="<?php echo $picname['description']?>"  src="<?php echo $picname['attachment']?>"/></a>
			<p class="a-c"><a href="index.php?pic-view-<?php echo $picname['id']?>-<?php echo $picname['did']?>" ><?php echo $picname['description']?></a></p>
		</div>
	<?php } ?>
</div><?php $data= $GLOBALS['blockdata'][9];$bid="9"?><div id="tjct" class="columns tjct i6-ff <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">推荐词条</h2>
	<a href="index.php?list-focus" class="more">更多>></a>
	<div>
	<?php foreach((array)$data['list'] as $key=>$fistcomdoc) {?>
		<?php if($key<3) { ?>
		<div class="jc_tj">
			<a href="index.php?doc-view-<?php echo $fistcomdoc['did']?>"  class="a-img"><img alt="<?php echo $fistcomdoc['title']?>" title="<?php echo $fistcomdoc['title']?>" src="<?php echo $fistcomdoc['image']?>"/></a>
			<p class="a-c"><a href="index.php?doc-view-<?php echo $fistcomdoc['did']?>"  title="<?php echo $fistcomdoc['title']?>"><?php echo $fistcomdoc['shorttitle']?></a></p>
		</div>
		<?php } ?>
	<?php }?>
	</div>
	<ul class="col-ul point c-b">
		<?php foreach((array)$data['list'] as $key=>$commenddoc) {?>
			<?php if($key>=3) { ?>
			<li><a href="index.php?doc-view-<?php echo $commenddoc['did']?>"  title="<?php echo $commenddoc['title']?>"><?php echo $commenddoc['shorttitle']?></a></li>
			<?php } ?>
		<?php }?>
	</ul>
</div></div>
	</div>
</div>


<div class="c-b"></div>

<div id="block_bottom"><?php $data= $GLOBALS['blockdata'][10];$bid="10"?><div  class="columns i6-ff p-b8 rmbq <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">热门标签</h2>
	<ul class="col-ul list-s">
	<?php foreach((array)$data['hottag'] as $tag) {?>
		<?php if($tag['tagcolor']=='red') { ?>
			<li><a href="index.php?search-tag-<?php echo urlencode($tag['tagname'])?>" class="red"><?php echo $tag['tagname']?></a></li>
		<?php } else { ?>
			<li><a href="index.php?search-tag-<?php echo urlencode($tag['tagname'])?>" ><?php echo $tag['tagname']?></a></li>
	    <?php } ?>
    <?php } ?>
	</ul>
</div><?php $data= $GLOBALS['blockdata'][16];$bid="16"?><div id="yqlj" class="columns no_col-h2 bg-gray yqlj i6-ff <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<ul class="col-ul list-s">
	<li><span class="bold">友情链接:</span></li>
	<?php foreach((array)$data['links'] as $link) {?>
	<li><a href="<?php echo $link['url']?>"  title="<?php echo $link['description']?>" target="_blank"><?php echo $link['name']?></a></li>
	<?php } ?>
	</ul>
</div></div>

<?php include $this->gettpl('footer');?>