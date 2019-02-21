<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>会员中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex,nofollow" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="pic/member/bootstrap.min.css" rel="stylesheet" type="text/css" />				
<link href="pic/member/swiper.min.css" rel="stylesheet" type="text/css" >		
<link href="pic/member/iconfont.css" rel="stylesheet" type="text/css" />
<link href="pic/member/color.css" rel="stylesheet" type="text/css" />
<link href="pic/member/style.min.css" rel="stylesheet" type="text/css" />
<script src="pic/member/jquery.min.js"></script>
<script type="text/javascript" src="pic/member/bootstrap.min.js"></script>
</head>
<?php
session_start();
require_once("include/common.php");
require_once(sea_INC.'/main.class.php');
if($cfg_user==0)
{
	ShowMsg('系统已关闭会员功能!','-1');
	exit();
}

$action = isset($action) ? trim($action) : 'cc';
$page = isset($page) ? intval($page) : 1;
$uid=$_SESSION['sea_user_id'];
$uid = intval($uid);

$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser);//构造session安全码
if(empty($uid) OR $_SESSION['hashstr'] !== $hashstr)
{
	showMsg("请先登录","login.php");
	exit();
}
if($action=='chgpwdsubmit')
{
	if(trim($newpwd)<>trim($newpwd2))
	{
		ShowMsg('两次输入密码不一致','-1');	
		exit();	
	}
	if(!empty($newpwd)||!empty($email))
	{
	if(empty($newpwd)){$pwd = $oldpwd;} else{$pwd = substr(md5($newpwd),5,20);};
	$dsql->ExecuteNoneQuery("update `sea_member` set password = '$pwd' ".(empty($email)?'':",email = '$email'")." where id= '$uid'");
	ShowMsg('资料修改成功','-1');	
	exit();	
	}
}
elseif($action=='cancelfav')
{
	$id=intval($id);
	$dsql->executeNoneQuery("delete from sea_favorite where id=".$id);
	echo "<script>location.href='?action=favorite'</script>";
	exit();
}elseif($action=='cancelfavs')
{
	if(empty($fid))
	{
		showMsg("请选择要取消收藏的视频","-1");
		exit();
	}
	foreach($fid as $id)
	{
		$id=intval($id);
		$dsql->executeNoneQuery("delete from sea_favorite where id=".$id);
	}
	echo "<script>location.href='?action=favorite'</script>";
	exit();
}
elseif($action=='cz')
{
	$key=mysql_real_escape_string($_POST['cckkey'],$dsql->linkID);
	$key = RemoveXSS(stripslashes($key));
	$key = addslashes(cn_substr($key,200));
	if($key==""){showMsg("没有输入充值卡号","-1");exit;}
	$sqlt="SELECT * FROM sea_cck where ckey='$key'";
	$row1 = $dsql->GetOne($sqlt);
    if(!is_array($row1) OR $row1['status']<>0){
        showMsg("充值卡不正确或已被使用","-1");exit;
    }else{
		$uname=$_SESSION['sea_user_name'];
		$points=$row1['climit'];
        $dsql->executeNoneQuery("UPDATE sea_cck SET usetime=NOW(),uname='$uname',status='1' WHERE ckey='$key'");
		$dsql->executeNoneQuery("UPDATE sea_member SET points=points+$points WHERE username='$uname'");
		showMsg("恭喜！充值成功！","member.php?action=cc");exit;
    }
}
elseif($action=='hyz')
{
	//对所有数据进行重新查询，防止伪造POST数据进行破解
	
	//获取会员组基本信息
	$gid = intval($gid);
	if(empty($gid))
	{showMsg("请选择要购买的会员组","member.php?action=cc");exit;}
	$sqlhyz1="SELECT * FROM sea_member_group where gid='$gid'"; 
	$rowhyz1 = $dsql->GetOne($sqlhyz1);
    if(!is_array($rowhyz1)){
        showMsg("会员组不存在","-1");exit;
    }else{
		$hyzjf=$rowhyz1['g_upgrade']; //购买会员组所需积分  
    }
	//获取会员基本信息
	$uname=$_SESSION['sea_user_name'];
	$uname = RemoveXSS($uname);
	$sqlhyz2="SELECT * FROM sea_member where username='$uname'"; 
	$rowhyz2 = $dsql->GetOne($sqlhyz2);
    if(!is_array($rowhyz2)){
        showMsg("会员信息不存在","-1");exit;
    }else{
		$userjf=$rowhyz2['points']; //购买会员组所需积分
    }
	
	if($userjf<$hyzjf)
	{
		showMsg("积分不足","-1");exit; //判断积分是否足够购买
	} 
	else
	{
		$dsql->executeNoneQuery("UPDATE sea_member SET points=points-$hyzjf,gid=$gid where username='$uname'");
		showMsg("恭喜！购买会员组成功，重新登陆后会员组生效！","member.php?action=cc");exit;
	}
	
}
elseif($action=='cc')
{
	$ccgid=intval($_SESSION['sea_user_group']);
	$ccuid=intval($_SESSION['sea_user_id']);
	$cc1=$dsql->GetOne("select * from sea_member_group where gid=$ccgid");
	$ccgroup=$cc1['gname'];
	$ccgroupupgrade=$cc1['g_upgrade'];
	$cc2=$dsql->GetOne("select * from sea_member where id=$ccuid");
	$ccjifen=$cc2['points'];
	$ccemail=$cc2['email'];
	$cclog=$cc2['logincount'];
	echo <<<EOT
	        
<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="<?php _e('搜索'); ?>" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
				    <ul class="menulist hidden-xs">
						<li><a href="/">首页</a></li>
						<li><a href="list/?1.html">电影</a></li>		
						<li><a href="list/?2.html">电视剧</a></li>	
						<li><a href="list/?3.html">综艺</a></li>	
						<li><a href="list/?4.html">动漫</a></li>
					</ul>													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	<div class="hy-member-user hy-layout clearfix">
    			<div class="item">
    				<div class="integral">当前积分：{$ccjifen}</div>
    				<dl class="margin-0 clearfix">
    					<dt><span class="user"></span></dt>
    					<dd>
    						<span class="name">{$_SESSION['sea_user_name']}<span>
    						<span class="group">{$ccgroup}<span>
    					</dd>
    			   </dl>   				
    			</div>
	    	</div>	    	
		    <div class="hy-member hy-layout clearfix">
		    	<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li class="active"><a href="?action=cc" title="播放线路">基本资料</a></li>							
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li><a href="?action=buy" title="购买记录">购买记录</a></li>	
					</ul>
				</div>
		    	<div class="tab-content">
					<div class="tab-pane fade in active">
						<div class="col-md-9 col-sm-12 col-xs-12">					
							<ul class="user">
								<li><span class="text-muted">您的序号：</span>{$_SESSION['sea_user_id']}</li>
								<li><span class="text-muted">您的账户：</span>{$_SESSION['sea_user_name']}</li>
								<li><span class="text-muted">您的邮箱：</span>{$ccemail}</li>
								<li><span class="text-muted">登陆次数：</span>{$cclog}</li>
EOT;
			                echo  "<li><span class=\"text-muted\">用户级别：</span>{$ccgroup}</li>";
							$sql="select * from sea_member_group where g_upgrade > $ccgroupupgrade";
							$dsql->SetQuery($sql);
							$dsql->Execute('al');
							while($rowr=$dsql->GetObject('al'))
							{
								echo "<input type=\"submit\" class=\"btn btn-warning\" value='".升级."".$rowr->gname."' onClick=\"self.location='?action=hyz&gid=".$rowr->gid."';\"></span>";
							}
								echo
			                     "<li><span class=\"text-muted\">当前积分：</span>{$ccjifen}</li>".
			                     "<li><span class=\"text-muted\">推广链接：</span>http://{$_SERVER['HTTP_HOST']}/i.php?uid={$_SESSION['sea_user_id']}</li>".
			                    "<form action=\"?action=cz\" method=\"post\"><li class=\"cckkey\"><span class=\"text-muted\">充值积分：</span><input type=text name=cckkey class=\"form-control\" id=cckkey placeholder=\"输入充值卡卡号\" > <input type=submit name=cckb id=cckb value='提交' class=\"btn btn-warning\"></li></form></div>";
			echo <<<EOT
												
																			
											</ul>
										</div>
										<div class="col-md-3 col-sm-12 col-xs-12">
											<ul class="password">
												<h3 class="text-muted">修改密码</h3>
EOT;
						$row1=$dsql->GetOne("select * from sea_member where id='$uid'");
							$oldpwd=$row1['password'];
							$oldemail=$row1['email'];
							echo "<form id=\"f_Activation\"   action=\"?action=chgpwdsubmit\" method=\"post\">".
								"<li><input type=\"password\" name=\"oldpwd\" value=\"$oldpwd\" class=\"form-control\" placeholder=\"输入旧密码\" /></li>".    
								"<li><input type=\"password\" name=\"newpwd\"  class=\"form-control\" placeholder=\"输入新密码\" /></li>".  
								"<li><input type=\"password\" name=\"newpwd2\" class=\"form-control\" placeholder=\"再次确认\" /></li>".  
								"<li><input type=\"test\" name=\"email\" value=\"$oldemail\" class=\"form-control\" placeholder=\"邮箱地址\" /></li>". 
								"<li><input type=\"submit\" name=\"gaimi\" class=\"btn btn-block btn-warning\" value=\"确认修改\"></li>".   
						        "</form>";
						echo <<<EOT
							</ul>
						</div>
					</div>            
			   </div>               
		    </div>
		    <a class="btn btn-block btn-warning visible-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
	    </div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item">
	        <i class="icon iconfont icon-home"></i>
	        <p class="text">首页</p>
	    </a>
	    <a href="list/?1.html" class="item">
	        <i class="icon iconfont icon-film"></i>
	        <p class="text">电影</p>
	    </a>
	    <a href="list/?2.html" class="item">
	        <i class="icon iconfont icon-show"></i>
	        <p class="text">电视剧</p>
	    </a>
	    <a href="list/?3.html" class="item">
	        <i class="icon iconfont icon-flag"></i>
	        <p class="text">综艺 </p>
	    </a>
	    <a href="list/?4.html" class="item">
	        <i class="icon iconfont icon-mallanimation"></i>
	        <p class="text">动漫 </p>
	    </a>
<a href="member.php" class="item active">
        <i class="icon iconfont icon-member1"></i>
        <p class="text">会员</p>
    </a>	
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright © 2008-2017</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='favorite')
{
	$page = $_GET["page"]; 
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_favorite where uid=".$uid);
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}  
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 	
	$dsql->setQuery("select * from sea_favorite where uid=".$uid." limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('favlist');
	echo <<<EOT
	
	<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="<?php _e('搜索'); ?>" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
				    <ul class="menulist hidden-xs">
						<li><a href="index.php">首页</a></li>
						<li><a href="list/?1.html">电影</a></li>		
						<li><a href="list/?2.html">电视剧</a></li>	
						<li><a href="list/?3.html">综艺</a></li>	
						<li><a href="list/?4.html">动漫</a></li>
					</ul>													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="播放线路">基本资料</a></li>							
						<li class="active"><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li><a href="?action=buy" title="购买记录">购买记录</a></li>	
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
							<thead>
		                    <tr>
		                        <th class="text-muted"> 视频</th>
		                        <th class="text-muted">收藏时间</th>
		                        <th class="text-muted hidden-xs">播放数</th>
		                        <th class="text-muted hidden-xs"> 连载集数</th>
		                        <th class="text-muted hidden-xs">状态</th>
		                        <th class="text-muted"> 操作 </th>	
		                    </tr>
		                    </thead>
EOT;
							while($row=$dsql->getArray('favlist'))
							{
								$rs=$dsql->getOne("select v_hit,v_state,v_pic,v_name,v_enname,v_note,v_addtime,tid from sea_data where v_id=".$row['vid']);
								if(!$rs) {continue;}
								$hit=$rs['v_hit'];
								$pic=$rs['v_pic'];
								$name=$rs['v_name'];
								$state=$rs['v_state'];
								$note=$rs['v_note'];
							
							echo <<<EOT
							    <tr>
									<td>
										<a href="
EOT;
								echo getContentLink($rs['tid'],$row['vid'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']);
								echo <<<EOT
													" target="_blank" >
EOT;
								echo $name;
								echo <<<EOT
													</a>
													</td>					
EOT;
								echo date('Y-m-d',$row['kptime']);
								echo <<<EOT
													</td>
													<td class="hidden-xs">{$hit}</td>
							                        <td class="hidden-xs">{$state}</td>
							                        <td class="hidden-xs">{$note}</td>			
							                        <td>
								<a onClick="return(confirm('确定取消收藏该影片？'))" href="?action=cancelfav&id=
EOT;
								echo $row['id'];
		echo <<<EOT
								">取消收藏</a>				
								</td>
		                    </tr>
EOT;
												  }			
							 echo <<<EOT
	 </table>
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=favorite&page=1">首页</a> </li>
								<li><a href="?action=favorite&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=favorite&page={$next_page}">下一页</a></li>
								<li><a href="?action=favorite&page={$page_count}">尾页</a></li>							
							</ul>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item">
	        <i class="icon iconfont icon-home"></i>
	        <p class="text">首页</p>
	    </a>
	    <a href="list/?1.html" class="item">
	        <i class="icon iconfont icon-film"></i>
	        <p class="text">电影</p>
	    </a>
	    <a href="list/?2.html" class="item">
	        <i class="icon iconfont icon-show"></i>
	        <p class="text">电视剧</p>
	    </a>
	    <a href="list/?3.html" class="item">
	        <i class="icon iconfont icon-flag"></i>
	        <p class="text">综艺 </p>
	    </a>
	    <a href="list/?4.html" class="item">
	        <i class="icon iconfont icon-mallanimation"></i>
	        <p class="text">动漫 </p>
	    </a>  
<a href="member.php" class="item active">
        <i class="icon iconfont icon-member1"></i>
        <p class="text">会员</p>
    </a>		
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright © 2008-2017</p>
			</div>
		</div>
	</div>	
</body>
EOT;
?>
<script src="js/common.js" type="text/javascript"></script>
<script>
function submitForm()
{
	$('favform').submit()
}
function showpic(event,imgsrc){	
	var left = event.clientX+document.documentElement.scrollLeft+20;
	var top = event.clientY+document.documentElement.scrollTop+20;
	$("preview").style.display="";
	$("preview").style.left=left+"px";
	$("preview").style.top=top+"px";
	$("pic_a1").setAttribute('src',imgsrc);
}
function hiddenpic(){
	$("preview").style.display="none";
}
</script>
<?php
}
elseif($action=='buy')
{
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_buy where uid=".$uid);
	$rcount=$row['dd'];	
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_buy where uid=".$uid." limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('buylist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="<?php _e('搜索'); ?>" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
				    <ul class="menulist hidden-xs">
						<li><a href="/">首页</a></li>
						<li><a href="list/?1.html">电影</a></li>		
						<li><a href="list/?2.html">电视剧</a></li>	
						<li><a href="list/?3.html">综艺</a></li>	
						<li><a href="list/?4.html">动漫</a></li>
					</ul>													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="播放线路">基本资料</a></li>							
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li class="active"><a href="?action=buy" title="购买记录">购买记录</a></li>	
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
						<thead>
		                 <tr>
                        <th class="text-muted"> 视频</th>
                        <th class="text-muted">  购买时间 </th>
                        <th class="text-muted hidden-xs">播放数</th>
                        <th class="text-muted hidden-xs"> 连载集数</th>
                        <th class="text-muted hidden-xs">状态</th>
                        <th class="text-muted hidden-xs">操作</th>
                    </tr>
                    </thead>
EOT;
	while($row=$dsql->getArray('buylist'))
{
	$rs=$dsql->getOne("select v_hit,v_state,v_pic,v_name,v_enname,v_note,v_addtime,tid from sea_data where v_id=".$row['vid']);
	if(!$rs) {echo "<tr><td align=\"left\"><input type=\"checkbox\"></td><td colspan=\"5\">该视频不存在或已经删除</td></tr>";continue;}
	$hit=$rs['v_hit'];
	$pic=$rs['v_pic'];
	$name=$rs['v_name'];
	$state=$rs['v_state'];
	$note=$rs['v_note'];
	echo <<<EOT
                    <tr>
                        <td>
						<a href="
EOT;
						echo getContentLink($rs['tid'],$row['vid'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']);
						echo <<<EOT
						" target="_blank">
EOT;
						echo $name;
						echo <<<EOT
						</a>
                        </td>
                        <td>
EOT;
                            echo date('Y-m-d',$row['kptime']);
							echo <<<EOT
                        </td>
                        <td class="hidden-xs">
						{$hit}
                        </td>
                        <td class="hidden-xs">
						{$state}
                        </td>
                        <td class="hidden-xs">
						{$note}
                        </td>
                        <td class="hidden-xs">
						已购买
                        </td>
                    </tr>
EOT;
					}
					echo <<<EOT
                </table>
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=buy&page=1">首页</a> </li>
								<li><a href="?action=buy&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=buy&page={$next_page}">下一页</a></li>
								<li><a href="?action=buy&page={$page_count}">尾页</a></li>							
							</ul>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="index.php" class="item">
	        <i class="icon iconfont icon-home"></i>
	        <p class="text">首页</p>
	    </a>
	    <a href="list/?1.html" class="item">
	        <i class="icon iconfont icon-film"></i>
	        <p class="text">电影</p>
	    </a>
	    <a href="list/?2.html" class="item">
	        <i class="icon iconfont icon-show"></i>
	        <p class="text">电视剧</p>
	    </a>
	    <a href="list/?3.html" class="item">
	        <i class="icon iconfont icon-flag"></i>
	        <p class="text">综艺 </p>
	    </a>
	   <a href="list/?4.html" class="item">
	        <i class="icon iconfont icon-mallanimation"></i>
	        <p class="text">动漫 </p>
	    </a> 
<a href="member.php" class="item active">
        <i class="icon iconfont icon-member1"></i>
        <p class="text">会员</p>
    </a>		
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright © 2008-2017</p>
			</div>
		</div>
	</div>	
</body>
EOT;
?>
<script src="js/common.js" type="text/javascript"></script>
<script>
function submitForm()
{
	$('favform').submit()
}
function showpic(event,imgsrc){	
	var left = event.clientX+document.documentElement.scrollLeft+20;
	var top = event.clientY+document.documentElement.scrollTop+20;
	$("preview").style.display="";
	$("preview").style.left=left+"px";
	$("preview").style.top=top+"px";
	$("pic_a1").setAttribute('src',imgsrc);
}
function hiddenpic(){
	$("preview").style.display="none";
}
</script>
<?php
}
else
{
	
}
?>