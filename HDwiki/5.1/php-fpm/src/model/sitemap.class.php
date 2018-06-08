<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class sitemapmodel {

	var $db;
	var $base;
	var $xml;
	var $baiduxml;

	function sitemapmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->setting = unserialize($this->base->setting['sitemap_config']);
		$this->baiduxml = HDWIKI_ROOT.'/baidu.xml';
	}
	
	/**
	 * 创建分页的词条 sitemap. 有剩余返回当前offset，无剩余返回false
	 *
	 * @return mixed
	 */
	function create_doc_page() {
		//获取上次创建分页；
		$current_page = $this->_lastpage_get();
		//如果实际总分页小于当前分页（生成sitemap后又删除一些词条的情况下），则当前页=总分页。
		$rs = $this->db->fetch_first("SELECT count(did) as count_id from ".DB_TABLEPRE."doc where visible = 1");
		$total_page = empty($rs['count_id']) ? 0 : floor($rs['count_id']/1000);
		if($total_page < $current_page) {
			$current_page = $total_page;
		}
				
		$current_offset = $current_page * 1000;
		$query = $this->db->query("SELECT did, title, lastedit FROM ".DB_TABLEPRE."doc where visible = 1 order by did asc limit {$current_offset}, 1000");
		$this->_sitemap_start_new();
		$doc = array();
		$page_last_did = 0;
		while($row = $this->db->fetch_array($query)){
			$doc_id = ('1'==$this->base->setting['seo_type'] && '1'==$this->base->setting['seo_type_doc']) ? rawurlencode(string::hiconv($row['title'])) : $row['did'];
			$doc['loc']        = WIKI_URL.'/'.$this->base->view->url("doc-view-{$doc_id}");
			$doc['lastmod']    = gmdate('Y-m-d\TH:i:s+00:00', $row['lastedit']);
			$doc['changefreq'] = $this->setting['doc_changefreq']; //////////////////
			$doc['priority']   = "0.8"; ////////////////
			$this->_sitemap_add_item($doc);
			$page_last_did = $row['did'];
		}		
		$this->_sitemap_end_save('sitemap_doc_'.$current_page);
		
		if($this->db->affected_rows() < 1000) { //如果当前不足一页（每页1000条），则记录最后页为当前页，下次更新继续以当前页更新。
			$this->_lastpage_log($current_page);
		} else { //否则记录下次更新以下页开始更新。
			$this->_lastpage_log($current_page + 1);
		}
		
		$rs = $this->db->fetch_first("SELECT max(did) as max_id from ".DB_TABLEPRE."doc where visible = 1");
		if(!empty($rs['max_id']) && $page_last_did < $rs['max_id']) { //如果当前页最后一条的did小于数据库中的最大did，则表示尚未完毕
			return $current_offset;
		} else { //如果是最后，创建索引页
			$this->_create_index();
			return false;
		}
	}
	
	function _lastpage_get() {
		$fh = fopen(HDWIKI_ROOT.'/data/sitemap_last_page.log', 'a+');
		$page = fgets($fh);
		return $page ? $page : 0;
	}
	
	function _lastpage_log($page) {
		$fh = fopen(HDWIKI_ROOT.'/data/sitemap_last_page.log', 'wb+');
		fwrite($fh, $page);
		fclose($fh);
	}
	
	function rebuild() { //重置Sitemap
		$this->_remove_all_sitemaps();
		$this->_lastpage_log(0);
	}
	
	function _remove_all_sitemaps() {
		$dh = opendir(HDWIKI_ROOT); 
		while($filename = readdir($dh)){
		    if(strpos($filename, '.xml') !== false && strpos($filename, 'sitemap') !== false) {
				unlink(HDWIKI_ROOT.'/'.$filename);
		    }
		}
		closedir($dh);
	}
	
	function _create_index() {
		$this->_create_home();
		$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!-- Created by HDWiki -->
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;
		$dh = opendir(HDWIKI_ROOT); 
		while($filename = readdir($dh)){
		    if($this->_is_sitemap($filename)) {
		    	$filemtime = gmdate('Y-m-d\TH:i:s+00:00', filemtime($filename));
		    	$xml .= "<sitemap><loc>".WIKI_URL."/{$filename}</loc><lastmod>{$filemtime}</lastmod></sitemap>";
		    }
		}
		closedir($dh);
		$xml .= '</sitemapindex>';
		
		$filename = 'sitemap';
		if($this->setting['use_gzip']) {
			$filename = $filename.'.xml.gz';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, gzencode($xml));
		} else {
			$filename = $filename.'.xml';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, $xml);
		}
		fclose($fh);
		$xml = null;
	}
	
	function _is_sitemap($filename) {
		if($this->setting['use_gzip']) {
			return $filename != 'sitemap.xml.gz' && substr($filename, -7) == '.xml.gz' && strpos($filename, 'sitemap') !== false;
		} else {
			return $filename != 'sitemap.xml' && substr($filename, -4) == '.xml' && strpos($filename, 'sitemap') !== false;
		}
	}
	
	//生成首页sitemap
	function _create_home() {
		$this->_sitemap_start_new();
		$url['loc']        = WIKI_URL;
		$url['lastmod']    = gmdate('Y-m-d\TH:i:s+00:00');
		$url['changefreq'] = $this->setting['idx_changefreq']; //////////////////
		$url['priority']   = "1.0"; ////////////////
		$this->_sitemap_add_item($url);
		$this->_sitemap_end_save('sitemap_idx');
	}
	
	function _sitemap_start_new() {
		$this->xml = <<<XML
<?xml version='1.0' encoding='UTF-8'?>
<!-- Created by HDWiki -->
<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>
XML;
	}
	
	function _sitemap_add_item($item) {
		if(empty($this->xml)) { return false; }
		$this->xml .= '<url>';
		foreach ($item as $key=>$value) {
			$this->xml .= "<{$key}>{$value}</{$key}>";
		}
		$this->xml .= '</url>';
		return true;
	}
		
	function _sitemap_end_save($filename) {
		$this->xml .= '</urlset>';

		if($this->setting['use_gzip']) {
			$filename = $filename.'.xml.gz';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, gzencode($this->xml));
		} else {
			$filename = $filename.'.xml';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, $this->xml);
		}
		fclose($fh);
		$this->xml = null;
		return $filename;
	}
	
	function submit() {	
    	$filename = $this->setting['use_gzip'] == '1' ? 'sitemap.xml.gz' : 'sitemap.xml';
    	if(!file_exists(HDWIKI_ROOT.'/'.$filename)) {
    		return false;
    	}
    	$services = array('Google'=>'http://www.google.com/webmasters/sitemaps',
    		'Ask.com'=>'http://submissions.ask.com');
    	$result = array();
    	foreach ($services as $site=>$url) {
    		$url .= '/ping?sitemap='.urlencode(WIKI_URL.'/'.$filename);
    		$result[$site] = $this->_fetchUrl($url);
    	}
    	return $result;
    }
    
    function _fetchUrl($url) {
    	$result = '';
    	$url = parse_url($url);
    	$errno = $errstr = '';
    	$fp = fsockopen($url['host'], 80, $errno, $errstr, 30);
		if ($fp) {
		    $out = "GET {$url['path']}?{$url['query']} HTTP/1.1\r\n";
		    $out .= "Host: {$url['host']}\r\n";
		    $out .= "Connection: Close\r\n\r\n";
		    fwrite($fp, $out);
		    $result = fgets($fp);
		    fclose($fp);
		}
		return $result;
    }
    
    
    function create_baiduxml() {
    	$this->base->load('user');
    	$fh = fopen($this->baiduxml, 'wb+');
    	$website = parse_url(WIKI_URL);
    	$website = $website['host'];
    	$webmaster = $_ENV['user']->get_admin_email();
    	$updateperi = intval($this->setting['updateperi']);
    	if($this->setting['textcolumn'] == 'content') {
    		$textcolumn = 'd.content as `text`,';
    	} else if($this->setting['textcolumn'] == 'summary')  {
    		$textcolumn = 'd.summary as `text`,';
    	} else {
    		$textcolumn = '';
    	}
    	$xml_encoding = WIKI_CHARSET;
    	$xml = <<<XML
<?xml version='1.0' encoding='{$xml_encoding}'?>
<!-- Created by HDWiki -->
<document>
	<webSite>{$website}</webSite>
	<webMaster>$webmaster</webMaster>
	<updatePeri>$updateperi</updatePeri>

XML;
    	fwrite($fh, $xml);
    	$query = $this->db->query("SELECT d.did, d.title, $textcolumn d.lastedit, c.name as category FROM ".DB_TABLEPRE."doc d left join ".DB_TABLEPRE."category c on d.cid = c.cid order by did desc limit 100");
    	while($row = $this->db->fetch_array($query)){
    		$doc_id = ('1'==$this->base->setting['seo_type'] && '1'==$this->base->setting['seo_type_doc']) ? rawurlencode($row['title']) : $row['did'];
			$doc_link = WIKI_URL.'/'.$this->base->view->url("doc-view-{$doc_id}");
    		$item = "<item>\n\r";
    		$item .= "<link>{$doc_link}</link>\n\r";
    		$item .= "<title>".$this->_escapedata($row['title'])."</title>\n\r";
    		if($this->setting['textcolumn'] != 'none') {
    			$item .= "<text><![CDATA[".strip_tags($row['text'])."]]></text>\n\r";
    		}
    		$item .= "<category>".$this->_escapedata($row['category'])."</category>\n\r";
    		$item .= "<pubDate>".date('Y/m/d H:i:s', $row['lastedit'])."</pubDate>\n\r";
    		$item .= "</item>\n\r";
    		
    		fwrite($fh, $item);
    		
    	}
    	//...
    	
    	fwrite($fh, '</document>');
    	fclose($fh);
    }
    
    function autoupdate_baiduxml() {
    	if($this->base->setting['auto_baiduxml'] 
    		&& file_exists($this->baiduxml) 
    		&& (time() - filemtime($this->baiduxml) >= 60*intval($this->setting['updateperi']))) 
    	{
    		register_shutdown_function(array($this, 'create_baiduxml'));
    	}
    }

    function get_last_update($filename) {
    	return file_exists($filename) ? filemtime($filename) : false;
    }
    	
	function _escapedata($data)
    {
        $position=0;
        $length=strlen($data);
        $escapeddata='';
        for(;$position<$length;)
        {
            $character=substr($data,$position,1);
            $code=Ord($character);
            switch($code)
            {
                case 34:
                    $character='&quot;';
                    break;
                case 38:
                    $character='&amp;';
                    break;
                case 39:
                    $character='&apos;';
                    break;
                case 60:
                    $character='&lt;';
                    break;
                case 62:
                    $character='&gt;';
                    break;
                default:
                    if($code<32)
                        $character=('&#'.strval($code).';');
                    break;
            }
            $escapeddata.=$character;
            $position++;
        }
        return $escapeddata;
    }
 
}	

?>
