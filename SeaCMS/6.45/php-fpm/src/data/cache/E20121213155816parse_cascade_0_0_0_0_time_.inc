<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="/templets/default/images/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/templets/default/images/system.css">
<script src="/templets/default/images/jquery/1.11.3/jquery.min.js"></script>
<script src="/templets/default/images/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/templets/default/images/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="/templets/default/images/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>var sitePath=''</script>
<script src="/templets/default/images/system.js"></script>
<script src="/js/common.js"></script>
<script src="/js/function.js"></script>
<title>海洋CMS-高级搜索</title>
<meta name="keywords" content="{seacms:searchword},海洋CMS" />
<meta name="description" content="{seacms:searchword},海洋CMS" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<div class="sea-header hidden-xs">
<div class="container">
	<div class="row">
  	<div class="col-xs-3">
    	<a href="http://localhost" class="sea-logo"></a>
    </div>
  	<div class="col-xs-7">
    	<form class="form-horizontal sea-search" id="sea-search" role="search" action="" method="post">
        <div class="form-group">
        	<div class="col-sm-9">
          <input type="text" class="form-control" id="searchword" name="searchword" placeholder="Search">
          </div>
          <div class="col-sm-3 sea-col-0">    
				<button type="submit" class="btn btn-default" data-module="vod" data-action="/search.php">搜视频</button>		  
        		<button type="submit" class="btn btn-default btn-success" data-module="news" data-action="/so.php">搜资讯</button>
				
          </div>
        </div>
      </form>
    </div><!-- /.col-xs-5 -->
  </div><!-- /.row -->
</div>
</div>
<nav class="navbar navbar-inverse visible-lg visible-md" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav navbar-left">
<li class=""><a href="http://localhost" class="dropdown-toggle" data-toggle="">首页</a></li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">新闻<b class="caret"></b></a>
          <ul class="dropdown-menu">
            
			<li><a href="/list/?5.html">体育</a></li>
			
			<li><a href="/list/?6.html">汽车</a></li>
			
			<li><a href="/list/?7.html">科技</a></li>
			
			<li><a href="/list/?8.html">财经</a></li>
			
			<li><a href="/list/?9.html">股市</a></li>
			
			<li><a href="/list/?10.html">地方</a></li>
			
			<li><a href="/list/?11.html">母婴</a></li>
			
			<li><a href="/list/?12.html">生活</a></li>
			
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">娱乐<b class="caret"></b></a>
          <ul class="dropdown-menu">
            
			<li><a href="/list/?13.html">明星</a></li>
			
			<li><a href="/list/?14.html">音乐</a></li>
			
			<li><a href="/list/?15.html">游戏</a></li>
			
			<li><a href="/list/?16.html">原创</a></li>
			
          </ul>
        </li>
	


        <li class="">
          <a href="/list/?3.html" class="dropdown-toggle" data-toggle="">军事</a>
        </li>

        <li class="">
          <a href="/list/?4.html" class="dropdown-toggle" data-toggle="">猎奇</a>
        </li>
	
<li class=""><a href="/topic/?1.html" class="dropdown-toggle" data-toggle="">专辑</a></li>
<li class=""><a href="/news/" class="dropdown-toggle" data-toggle="">文章</a></li>		
<li class=""><a href="/gbook.php"  class="dropdown-toggle" data-toggle="">留言求片</a></li>
<li class=""><a href="/member.php" class="dropdown-toggle" data-toggle="">会员中心</a></li>
<li class=""><a href="/new.html" class="dropdown-toggle" data-toggle="">最近更新</a></li>
<li class=""><a href="/top/hot.html" class="dropdown-toggle" data-toggle="">热门排行</a></li>

    </ul>
  </div><!-- /.container -->
</nav><!-- /.navbar -->
<div class="container sea-nav visible-xs visible-sm">
	<div class="row">
    <ul class="list-inline">
      <li class="col-xs-3 text-left">
        <h4><a href="javascript:;" id="sea-goback"><span class="glyphicon glyphicon-chevron-left"></span></a></h4>
      </li>
      <li class="col-xs-6 text-center">
        <h4>海洋CMS</h4>
      </li>
      <li class="col-xs-3 text-right"><h4>
        <a href="http://localhost"><span class="glyphicon glyphicon-home"></span></a>
        <a href="/search.php?searchword=请输入关键词"><span class="glyphicon glyphicon-search"></span></a></h4>
      </li>
    </ul>
  </div>
</div>
<switch name="list_id" >
<case value="1"><script>seacms.cms.nav('dianying');</script></case>
<case value="2"><script>seacms.cms.nav('tv');</script></case>
<case value="3"><script>seacms.cms.nav('dongman');</script></case>
<case value="4"><script>seacms.cms.nav('zongyi');</script></case>
<case value="5"><script>seacms.cms.nav('gaoxiao');</script></case>
<default /><script>seacms.cms.nav('{$model}');</script>
</switch>
<div class="container sea-bg vod-type">
  <div class="row">
  	<div class="col-xs-12 sea-col">
      <div class="page-header">
        <h4><span class="glyphicon glyphicon-film sea-text"></span> <a href="#">{searchpage:typename}</a> <small>共有<span class="sea-text">{cascadelist:recordcount}</span>个影片 第<span class="sea-text">{cascadelist:page}</span>页</small> <a class="btn btn-success btn-sm pull-right" href="http://localhost/search.php?page=1&searchtype=5" >筛选</a></h4>
      </div>
    </div>
	<div id="sx">
  	<div class="clearfix"></div>
  	<dl class="dl-horizontal">
      <dt>类型：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:typeitemlist type=5,6,7,8,9,10,11,12,13,14,15,16,3,4}
	  <a href="[typeitemlist:link]" {if:"[typeitemlist:value]"=="{searchpage:typename}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[typeitemlist:value]</a>
	  {/seacms:typeitemlist}
	  </dd>
	  
      <dt>地区：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:areaitemlist}<a href="[areaitemlist:link]" {if:"[areaitemlist:value]"=="{searchpage:area}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[areaitemlist:value]</a>{/seacms:areaitemlist}
	  </dd>
	  
	  <dt>年份：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:yearitemlist}<a href="[yearitemlist:link]"  {if:"[yearitemlist:value]"=="{searchpage:year}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[yearitemlist:value]</a>{/seacms:yearitemlist}
	  </dd>
	  
	  <dt>字母：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:letteritemlist}<a href="[letteritemlist:link]"  {if:"[letteritemlist:value]"=="{searchpage:letter}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[letteritemlist:value]</a>{/seacms:letteritemlist}
	  </dd>
	  
	  <dt>语言：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:langitemlist}<a href="[langitemlist:link]"  {if:"[langitemlist:value]"=="{searchpage:lang}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[langitemlist:value]</a>{/seacms:langitemlist}
	  </dd>
	  
	  <dt>剧情：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:jqitemlist}<a href="[jqitemlist:link]"  {if:"[jqitemlist:value]"=="{searchpage:jq}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[jqitemlist:value]</a>{/seacms:jqitemlist}
	  </dd>
	  
	  <dt>状态：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:stateitemlist}<a href="[stateitemlist:link]"  {if:"[stateitemlist:value]"=="{searchpage:state}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[stateitemlist:value]</a>{/seacms:stateitemlist}
	  </dd>
	  
	  <dt>版本：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:veritemlist}<a href="[veritemlist:link]"  {if:"[veritemlist:value]"=="{searchpage:ver}"}class="btn btn-sm  btn-success gallery-cell "{else} class="btn btn-sm btn-default gallery-cell"{end if}>[veritemlist:value]</a>{/seacms:veritemlist}
	  </dd>
	  
	  <dt>收费：</dt>
      <dd class="sea-text-hidden sea-gallery">
	  {seacms:moneyitemlist}{if:"[moneyitemlist:value]"=="{searchpage:money}"}<a href="[moneyitemlist:link]"  class="btn btn-sm  btn-success gallery-cell ">[moneyitemlist:value]</a>{else}<a href="[moneyitemlist:link]"  class="btn btn-sm  btn-default gallery-cell ">[moneyitemlist:value]</a>{end if}{/seacms:moneyitemlist}
	  </dd>
	  
    </dl>
	</div>
  </div>
</div>
<div class="clearfix sea-clearfix"></div>
<div class="container sea-bg vod-type">
<div class="row">
  <div class="btn-toolbar" role="toolbar">
    <div class="btn-group">
      <a href="{searchpage:order-time-link}" {if:"{searchpage:ordername}"=="time"} class="btn btn-success" {else} class="btn btn-default" {end if} id="orderhits">最新上映</a>
      <a href="{searchpage:order-hit-link}" {if:"{searchpage:ordername}"=="hit"} class="btn btn-success" {else} class="btn btn-default" {end if} id="orderaddtime">最近热播</a>
      <a href="{searchpage:order-score-link}" {if:"{searchpage:ordername}"=="score"} class="btn btn-success" {else} class="btn btn-default" {end if} id="ordergold">评分最高</a>
    </div>
  </div>
  <!-- -->
  <div class="clearfix"></div>
  <ul class="list-unstyled">
      {seacms:cascadelist size=24 order=time}
      <li class="col-md-2 col-sm-2 col-xs-4 text-center sea-vod-img-new sea-col">
        <a href="[cascadelist:link]">
        <img class="img-responsive img-thumbnail sea-img" data-original="[cascadelist:pic]" alt="[cascadelist:name]" src="[cascadelist:pic]">
        </a>
        <h4 class="sea-text-hidden text-left">
          <a href="[cascadelist:link]" title="[cascadelist:name]">[cascadelist:name]</a>
        </h4>
        <h6 class="sea-text-hidden text-left">
          [cascadelist:actor]
        </h6>
      </li>
      {/seacms:cascadelist}
    </ul>
  <!-- -->
  <div class="clearfix"></div>
  <div class="col-xs-12 sea-col text-center">
        <ul class="pagination pagination-lg hidden-xs page">
          [cascadelist:pagenumber len=3]
        </ul>
        <ul class="pager visible-xs">
         {if:{cascadelist:page}>1}
            <li><a href="{cascadelist:backlink}">上一页</a></li>
         {else}{end if}
         {if:{cascadelist:page}<{cascadelist:pagecount}}
            <li><a href="{cascadelist:nextlink}">下一页</a></li>
         {else}{end if}
         </ul> 
      </div>
</div>
</div>
<div class="clearfix sea-clearfix"></div>
<div class="container sea-bg sea-footer">
  <div class="row">
    <div class="col-md-10 col-sm-12 col-xs-12">
      <p>友情提示：请勿长时间观看影视，注意保护视力并预防近视，合理安排时间，享受健康生活。</p>
      <p>网站简介：努力打造海洋影视管理系统为最好的PHP影视系统!</p>
      <p>版权声明：本网站为非赢利性站点，本网站所有内容均来源于互联网相关站点自动搜索采集信息，相关链接已经注明来源。</p>
      <p>本站程序基于<a href="http://www.seacms.net" target="_blank"  style="color:#0000FF">海洋CMS</a>，<font color="#0000FF">{seacms:runinfo}</font></p>
    </div>
    <ul class="col-md-2 hidden-sm hidden-xs">
      <li>{seacms:member}</li>
      <li><a href="/gbook.php" >留言反馈</a></li>
	  <li><a href="/new.html" >最近更新</a></li>
	  <li><a href="/hot/hot.html" >热门排行</a></li>
      <li><a href="http://www.seacms.net" target="_blank">海洋cms</a></li>
    </ul>  
  </div>
</div>
<span style="display:none"></span>
</body>
</html>