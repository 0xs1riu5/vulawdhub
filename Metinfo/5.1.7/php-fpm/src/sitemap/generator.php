<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
if($htmsitemap==$met_member_force && ($met_sitemap_html||$met_sitemap_xml||$met_sitemap_txt)){
	$sitemaplist=sitemaplist();
	if($met_sitemap_lang_return){
		echo 'metinfo';
		$return = count($sitemaplist)?json_encode($sitemaplist):'not';
		echo $return;
		die;
	}
	if($met_sitemap_lang){
		require_once ROOTPATH.'include/export.func.php';
		$met_host = str_replace("http://","",$met_weburl);
		if(substr($met_host,-1,1)=="/")$met_host=substr($met_host, 0, -1);
		$sitqz='';
		if(strstr($met_host,"/")){
			$met_host=explode("/",$met_host);
			$sitqz='/'.$met_host[1];
			$met_host=$met_host[0];
		}
		$met_file =$sitqz.'/sitemap/index.php';
		if(count($met_langok)>1){
			foreach($met_langok as $key=>$val){
				if($val[mark]!=$lang && $val[link]==''){
					$post=array('lang'=>$val[mark],'met_sitemap_lang_return'=>1,'htmsitemap'=>$met_member_force);
					$json=curl_post($post,30);
					if($json!='not'){
						$list = json_decode($json,true);
						foreach($list as $key=>$val){
							$sitemaplist[]=$val;
						}
					}
				}
			}
		}
	}
	$met_sitemap_max=50000;
	/*htmlÍøÕ¾µØÍ¼*/
	if($met_sitemap_html){
		$config_save ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		$config_save.="<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		$config_save.="<head>\n";
		$config_save.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		$config_save.="<title>{$met_title}</title>\n";
		$config_save.="</head>\n";
		$config_save.="<body>\n";
		$config_save.="<ul>\n";
		$i=0;
		foreach($sitemaplist as $key=>$val){
			$i++;
			$val[updatetime]=date("Y-m-d",strtotime($val[updatetime]));
			$config_save.="<li><a href='".$val[url]."' title='".$val[title]."' target='_blank'>".$val[title]."</a><span>".$val[updatetime]."</span></li>\n";
			if($i>=$met_sitemap_max)break;
		}
		$config_save.="</ul>\n</body>";
		$sitemap_hz='.html';
		$sitemapname='../sitemap'.$sitemap_hz;
		$fp = fopen($sitemapname,w);
		fputs($fp, $config_save);
		fclose($fp);
	}
	/*xmlÍøÕ¾µØÍ¼*/
	if($met_sitemap_xml){
		$i=0;
		foreach($sitemaplist as $key=>$val){
			$val[url]=str_replace('../','',$val[url]);
			$val[url]=str_replace('&','&amp;',$val[url]);
			$val[url]=str_replace("'",'&apos;',$val[url]);
			$val[url]=str_replace('"','&quot;',$val[url]);
			$val[url]=str_replace('>','&gt;',$val[url]);
			$val[url]=str_replace('<','&lt;',$val[url]);
			$i++;
			$val[updatetime]=date("Y-m-d",strtotime($val[updatetime]));
			$val[priority]=$val[priority]?$val[priority]:'0.5';
			$sitemaptext.="<url>\n";
			$sitemaptext.="<loc>$val[url]</loc>\n";
			$sitemaptext.="<priority>$val[priority]</priority>\n";
			$sitemaptext.="<lastmod>$val[updatetime]</lastmod>\n";
			$sitemaptext.="<changefreq>weekly</changefreq>\n";
			$sitemaptext.="</url>\n";
			if($i>=$met_sitemap_max)break;
		}
		$config_save="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$config_save.="<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		$config_save.=$sitemaptext;
		$config_save.="</urlset>";
		$sitemap_hz='.xml';
		$sitemapname='../sitemap'.$sitemap_hz;
		$fp = fopen($sitemapname,w);
		fputs($fp, $config_save);
		fclose($fp);
	}
	/*TxtÍøÕ¾µØÍ¼*/
	if($met_sitemap_txt){
		$config_save="";
		$i=0;
		foreach($sitemaplist as $key=>$val){
			$i++;
			$config_save.="{$val[url]}"."\r\n";
			if($i>=$met_sitemap_max)break;
		}
		$sitemap_hz='.txt';
		$sitemapname='../sitemap'.$sitemap_hz;
		if(stristr(PHP_OS,"WIN")){
			$config_save=@iconv("utf-8","GBK",$config_save);
		}
		$fp = fopen($sitemapname,w);
		fputs($fp, $config_save);
		fclose($fp);
	}
	die();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>