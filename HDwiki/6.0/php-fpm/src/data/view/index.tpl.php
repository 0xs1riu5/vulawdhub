<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#tpbk img,#tjct img").each(function(i) {
            var w = this.width;
            var h = this.height;
            if (w > 100 || h > 75) {
                if (w / h > 4 / 3) {
                    this.style.width = "100px"
                } else {
                    this.style.height = "75px"
                }
            }
        });

        $("input[name*='searchtext']").focus();
    });

</script>



<section class="wrap clearfix">
    <div class="group2 home">
        <div id="block_right"><?php $data= $GLOBALS['blockdata'][3];$bid="3"?><div id="login-static" class="columns login-static i-login <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">登录用户</h2>
	<?php if($user['groupid']=='1') { ?>
		<?php if($data['data']['passport']) { ?>
		<span class="red error" style="top:80px">已开启通行证,请直接点击<a href="index.php?user-login">登录</a></span>
		<?php } else { ?>
		<form action="" onsubmit="return docheck();">
		<ul class="col-ul" id="nologin" style="display:block">
			<li><span>用户名：</span><input name="username" id="username" tabindex="3" type="text" class="inp_txt" onblur="check_username()" maxlength="32" /></li>
			<li><span>密&nbsp;&nbsp;码：</span><input name="password" id="password"  tabindex="4" type="password" class="inp_txt" onblur="check_passwd()" maxlength="32" /></li>
			<?php if($data['data']['checkcode'] != 3) { ?>
			<li class="yzm"><span>验证码：</span><input name="code" id="code"  tabindex="5" type="text" onblur="check_code()" maxlength="4" class="inp_txt" /><label class="yzm-img"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label><a href="javascript:updateverifycode();">换一个</a>
			</li>
			<?php } ?>
			<li class="error" id="logintip"></li>
			<li class="submit"><input name="submit" type="submit" value="登录" class="btn_inp blue" tabindex="6" /><input name="Button1" type="button" value="我要注册" class="btn_inp org" onclick="location.href='index.php?user-register';" /></li>
		</ul>
		</form>
		<?php } ?>
	<?php } else { ?>
	<dl id="islogin" class="col-dl twhp" >
	<dd class="block"><a href="index.php?user-space-<?php echo $user['uid']?>" class="a-img1"><img alt="点击进入用户中心" src="<?php if($user['image']) { ?><?php echo $user['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="50"/></a></dd>
	<dt><a href="index.php?user-space-<?php echo $user['uid']?>" class="m-r8 bold black"><?php echo $user['username']?></a><img title="您现在拥有<?php echo $user['credit1']?>金币 " src="style/default/jb.gif" class="sign"/></dt>
	<dd class="gray9"><?php echo $user['grouptitle']?></dd>
	<dd class="gray"><span>经验：<?php echo $user['credit2']?></span><span class="r">人气指数：<?php echo $user['views']?></span></dd>		
	<dd class="gray"><span>创建词条：<?php echo $user['creates']?></span><span class="r">编辑词条：<?php echo $user['edits']?></span></dd>
	<dd><a href="index.php?user-space-<?php echo $user['uid']?>" class="btn_inp ico-user">我的百科</a></dd>
	</dl>
	<?php } ?>
	<p class="novice">
	<a class="gray" href="index.php?doc-innerlink-<?php echo urlencode('初来乍到，了解一下')?>" >初来乍到，了解一下</a>
	<a class="gray" href="index.php?doc-innerlink-<?php echo urlencode('我是新手，怎样编写词条')?>" >我是新手，怎样编写词条</a>
	<a class="gray" href="index.php?doc-innerlink-<?php echo urlencode('我要成为词条达人')?>" >我要成为词条达人</a>
	</p>
<script>
	var indexlogin = 1;
	var loginTip1 = '用户名不能为空!';
	var loginTip2 = "<?php echo $data['data']['loginTip2']?>";
	var loginTip3 = '用户不存在!';
	var logincodewrong = '验证码不匹配!';
	var name_max_length = "<?php echo $data['data']['name_max_length']?>";
	var name_min_length = "<?php echo $data['data']['name_min_length']?>";
	var editPassTip1 = '密码不能为空，最多32位!';
	var loginTip4 = '不匹配!';
	var checkcode = "<?php echo $data['data']['checkcode']?>";
</script>
</div><?php $data= $GLOBALS['blockdata'][4];$bid="4"?><div id="notice" class="columns qh notice <?php echo $data['config']['style']?>"  bid="<?php echo $bid?>">
    <h2 class="col-h2 qh-h2">
    <a href="#zngg" target="_self" <?php if($setting['base_toplist']==1) { ?>class="on"<?php } ?>>站内公告</a>
    <a href="#zxdt" target="_self" <?php if($setting['base_toplist']==0) { ?>class="on"<?php } ?>>最新动态</a>
    </h2>
    <div id="zxdt" class='timeline-list <?php if($setting['base_toplist']==1) { ?>none<?php } ?>'>
        <?php foreach((array)$data['list'] as $newslist) {?>
            <p class="col-p"><?php echo $newslist?></p>
        <?php } ?>
		<div class="timeline" id="time_line_p"></div>
    </div>
    <div id="zngg" class='timeline-list <?php if($setting['base_toplist']==0) { ?>none<?php } ?>'><p class="col-p" ><?php echo $setting['site_notice']?></p><div class="timeline"></div></div>
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
			$('#time_line_p').before(this.firstp.clone());
			this.rotator = $("#zxdt");
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
		if ($(firstp)[0] == undefined) {
			console.log('不存在了');
		}

		var temp = $(firstp)[0].scrollHeight + 4;
		if (this.num == temp){
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
</div><?php $data= $GLOBALS['blockdata'][5];$bid="5"?><div  bid="<?php echo $bid?>" class="columns dwsct <?php echo $data['config']['style']?>">
	<h2 class="col-h2">待完善词条</h2>
	<a href="index.php?doc-cooperate"  class="more">更多>></a>
	<ul class="col-ul clearfix" >
		<?php foreach((array)$data['list'] as $coopdoc) {?>
			<li><a href="index.php?doc-innerlink-<?php echo urlencode(string::hiconv($coopdoc['title'], 'utf-8'))?>"  title="<?php echo $coopdoc['title']?>"><?php echo $coopdoc['shorttitle']?></a></li>
		<?php } ?>
	</ul>
</div><?php $data= $GLOBALS['blockdata'][17];$bid="17"?><div class=" columns azmsx <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">按字母顺序浏览</h2>
	<ul class="col-ul clearfix">
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
    <div class="group1-2 home">
        <div id="block_ctop1"><?php $data= $GLOBALS['blockdata'][1];$bid="1"?><div id="reci" class="columns  reci <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">热门词条</h2>
	<a href="index.php?list-focus-2" class="more">更多>></a>
	<?php foreach((array)$data['list'] as $key=>$hotdoc) {?>
		<dl class="col-dl">
			<dt><a href="index.php?doc-view-<?php echo $hotdoc['did']?>"  title="<?php echo $hotdoc['title']?>" class="clink"><?php echo $hotdoc['shorttitle']?></a></dt>
			<dd><?php echo $hotdoc['summary']?>[<a href="index.php?doc-view-<?php echo $hotdoc['did']?>"  class="entry">详细</a>]</dd>
		</dl>
	<?php }?>
</div></div>
    </div>
    <div class="group1-2 home">
        <div id="block_ctop2"><?php $data= $GLOBALS['blockdata'][2];$bid="2"?><div id="jcct" class="columns jcct <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
<h2 class="col-h2">精彩词条</h2>
<a href="index.php?list-focus-3"  class="more">更多>></a>
<?php if(isset($data['fistwonderdoc'])) { ?>
    <dl class="col-dl clearfix">
        <dd class="l"><a href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>"  class="a-img"><img title="<?php echo $data['fistwonderdoc']['title']?>" src="<?php echo $data['fistwonderdoc']['image']?>"/></a></dd>
        <dt><a class="clink" href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>" title="<?php echo $data['fistwonderdoc']['title']?>" ><?php echo $data['fistwonderdoc']['shorttitle']?></a></dt>
        <dd><p><?php echo $data['fistwonderdoc']['summary']?>...<a href="index.php?doc-view-<?php echo $data['fistwonderdoc']['did']?>" >阅读全文&gt;&gt;</a></p></dd>
    </dl>
    <ul class="col-ul point">
        <?php foreach((array)$data['list'] as $wondoc) {?>
            <li><a class="clink" href="index.php?doc-view-<?php echo $wondoc['did']?>" ><?php echo $wondoc['title']?></a>: <?php echo $wondoc['summary']?></li>
        <?php } ?>
    </ul>
<?php } ?>
</div></div>
    </div>

    <!--ad start -->

    <?php if(isset($advlist[2]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
    <div class="group1 home" id="advlist_2">
        <div class="ad"><?php echo $advlist[2]['code']?></div>
    </div>
    <?php } elseif(isset($advlist[2]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
    <div class="group1 home" id="advlist_2">
        <div class="ad"></div>
    </div>
    <?php } ?>

    <!--ad end -->
    <div class="group1 home">
        <div id="block_cbottomr"><?php $data= $GLOBALS['blockdata'][8];$bid="8"?><div id="block-pic-getlist-<?php echo $bid?>" class="columns tpbk clearfix <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">百科图片</h2>
	<a href="index.php?pic-piclist"  class="more">更多>></a>
	<?php foreach((array)$data['list'] as $picname) {?>
		<div class="jc_tj">
			<a href="index.php?pic-view-<?php echo $picname['id']?>-<?php echo $picname['did']?>"  class="a-img"><img title="<?php echo $picname['description']?>"  src="<?php echo $picname['attachment']?>"/></a>
			<p class="a-c"><a href="index.php?pic-view-<?php echo $picname['id']?>-<?php echo $picname['did']?>" ><?php echo $picname['description']?></a></p>
		</div>
	<?php } ?>
</div><?php $data= $GLOBALS['blockdata'][9];$bid="9"?><div id="tjct" class="columns tjct <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">推荐词条</h2>
	<a href="index.php?list-focus" class="more">更多>></a>
	<div class="clearfix">
	<?php foreach((array)$data['list'] as $key=>$fistcomdoc) {?>
		<?php if($key<3) { ?>
		<div class="jc_tj">
			<a href="index.php?doc-view-<?php echo $fistcomdoc['did']?>"  class="a-img"><img alt="<?php echo $fistcomdoc['title']?>" title="<?php echo $fistcomdoc['title']?>" src="<?php echo $fistcomdoc['image']?>"/></a>
			<p class="a-c"><a href="index.php?doc-view-<?php echo $fistcomdoc['did']?>"  title="<?php echo $fistcomdoc['title']?>"><?php echo $fistcomdoc['shorttitle']?></a></p>
		</div>
		<?php } ?>
	<?php }?>
	</div>
	<ul class="col-ul point">
		<?php foreach((array)$data['list'] as $key=>$commenddoc) {?>
			<?php if($key>=3) { ?>
			<li><a href="index.php?doc-view-<?php echo $commenddoc['did']?>"  title="<?php echo $commenddoc['title']?>"><?php echo $commenddoc['shorttitle']?></a></li>
			<?php } ?>
		<?php }?>
	</ul>
</div></div>
    </div>
    <div class="group1-2 home">
        <div id="block_cbottoml"><?php $data= $GLOBALS['blockdata'][7];$bid="7"?>
<div id="zjpl" class="columns zjpl <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">最近评论</h2>
	<div class="timeline-list">
	<?php foreach((array)$data['list'] as $comment) {?>
		<div class="pl_unit">
			<a href="index.php?user-space-<?php echo $comment['authorid']?>"  class="a-img1"><img alt="" src="<?php if($comment['image'] ) { ?><?php echo $comment['image']?><?php } else { ?><?php } ?>"/></a>
			<p><a href="index.php?comment-view-<?php echo $comment['did']?>" title="<?php echo $comment['comment']?>"  class="block"><?php echo $comment['tipcomment']?></a><span class="gray9 f12"><?php echo $comment['time']?></span></p>
		</div>
	<?php } ?>
	<div class="timeline"></div></div>
</div></div>
    </div>
    <div class="group1-2 home">
        <div id="block_dbottomr"><?php $data= $GLOBALS['blockdata'][6];$bid="6"?>
<div id="zjgx" class="columns zjgx o-v <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
    <h2 class="col-h2">最近更新</h2>
    <a href="index.php?list-recentchange" class="more">更多>></a>
    <div class="timeline-list">
       <?php foreach((array)$data['doclist'] as $doc) {?>
            <p class="col-p"><a href="index.php?doc-view-<?php echo $doc['did']?>"  class="ctm" title="<?php echo $doc['title']?>"><?php echo $doc['shorttitle']?></a><br><span class="gray9 f12"><?php echo $doc['lastedit']?></span></p>
       <?php } ?>
    <div class="timeline"></div></div>
</div></div>
    </div>
</section>

<div class="wrap" id="block_bottom"><?php $data= $GLOBALS['blockdata'][10];$bid="10"?><div  class="columns p-b8 rmbq <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">热门标签</h2>
	<ul class="col-ul list-s clearfix">
	<?php foreach((array)$data['hottag'] as $tag) {?>
		<?php if($tag['tagcolor']=='red') { ?>
			<li><a href="index.php?search-tag-<?php echo urlencode($tag['tagname'])?>" class="red"><?php echo $tag['tagname']?></a></li>
		<?php } else { ?>
			<li><a href="index.php?search-tag-<?php echo urlencode($tag['tagname'])?>" ><?php echo $tag['tagname']?></a></li>
	    <?php } ?>
    <?php } ?>
	</ul>
</div><?php $data= $GLOBALS['blockdata'][16];$bid="16"?><div id="yqlj" class="columns yqlj <?php echo $data['config']['style']?>" bid="<?php echo $bid?>">
	<h2 class="col-h2">友情链接</h2>
	<ul class="col-ul list-s clearfix">
	<?php foreach((array)$data['links'] as $link) {?>
	<li><a href="<?php echo $link['url']?>"  title="<?php echo $link['description']?>" target="_blank"><?php echo $link['name']?></a></li>
	<?php } ?>
	</ul>
</div></div>

<?php include $this->gettpl('footer');?>