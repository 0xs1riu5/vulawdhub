<?php

class template{
	
	var $tplname;
	var $tpldir;
	var $objdir;
	var $tplfile;
	var $objfile;
	var $vars=array();
	var $force =0;
	var $var_regexp = "\@?\\\$[a-z_][\\\$\w]*(?:\[[\w\-\.\"\'\[\]\$]+\])*";
	var $vtag_regexp = "\<\?php echo (\@?\\\$[a-zA-Z_][\\\$\w]*(?:\[[\w\-\.\"\'\[\]\$]+\])*)\?\>";
	var $const_regexp = "\{([\w]+)\}";
	var $lang = array();

	function template($tplname='default') {
		$this->tplname = ($tplname!=='default'&&is_dir(HDWIKI_ROOT.'/view/'.$tplname))?$tplname:'default';
		$this->tpldir = HDWIKI_ROOT.'/view/'.$this->tplname;
		$this->objdir = HDWIKI_ROOT.'/data/view';
	}

	function assign($k, $v) {
		$this->vars[$k] = $v;
	}

	function setlang($langtype='zh',$filename){
		include HDWIKI_ROOT.'/lang/'.$langtype.'/'.$filename.'.php';
		$this->lang = &$lang;
	}

 
	
	function display($file){
		GLOBAL $starttime,$mquerynum;
		$mtime = explode(' ', microtime());
		$this->assign('runtime', number_format($mtime[1] + $mtime[0] - $starttime,6));
		$this->assign('querynum',$mquerynum);
		extract($this->vars, EXTR_SKIP);
		include $this->gettpl($file);
	}

	function gettpl($file){
		if(substr($file,0,7)=="file://"){
			$ppos=strrpos($file,"/");
			$dir_name=explode('/',substr($file,7));
			$this->tplfile = HDWIKI_ROOT."/".substr($file,7).'.htm';
			$this->objfile = $this->objdir.'/'.$dir_name[1].'_'.substr($file,$ppos+1).'.tpl.php';
		}else{
			if($this->tplname!=='default'&&is_file($this->tpldir.'/'.$file.'.htm')){
				$this->tplfile = $this->tpldir.'/'.$file.'.htm';
				$this->objfile = $this->objdir.'/'.$this->tplname."_".$file.'.tpl.php';
			}else{
				$this->tplfile = HDWIKI_ROOT.'/view/default/'.$file.'.htm';
				$this->objfile = $this->objdir.'/'.$file.'.tpl.php';
			}
		}
		if(!file_exists($this->tplfile)){
			exit;
		}
		if($this->force || @filemtime($this->objfile) < @filemtime($this->tplfile)){
			$this->compile();
		}
		return $this->objfile;
	}

	function  compile() {
		$template = file::readfromfile($this->tplfile);
		$template = preg_replace("/\{block:([^\}]+?)\/\}/ies", "\$this->block('\\1')", $template);
		
		$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
		$template = preg_replace("/\{lang.(\w+?)\}/ise", "\$this->lang('\\1')", $template);
		if('1'==$this->vars['setting']['seo_type'] && '1'==$this->vars['setting']['seo_type_doc']){
			$template = preg_replace("/\{url.doc\-view\-(.+?)\['did'\]\}/ise", "\$this->stripvtag('{url doc-view-{eval echo rawurlencode(\\1[\'rawtitle\']);}}')", $template);
		}
		$template = preg_replace("/\{($this->var_regexp)\}/", "<?php echo \\1?>", $template);
		$template = preg_replace("/\{($this->const_regexp)\}/", "<?php echo \\1?>", $template);
		$template = preg_replace("/(?<!\<\?php echo |\\\\)$this->var_regexp/", "<?php echo \\0?>", $template);
		$template = preg_replace("/\{\{eval (.*?)\}\}/ies", "\$this->stripvtag('<?php \\1?>')", $template);
		$template = preg_replace("/\{eval (.*?)\}/ies", "\$this->stripvtag('<?php \\1?>')", $template);
		$template = preg_replace("/\{for (.*?)\}/ies", "\$this->stripvtag('<?php for(\\1) {?>')", $template);
		$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->stripvtag('<?php } elseif(\\1) { ?>')", $template);
		
		$template = preg_replace("/\{hdwiki:([^\}]+?)\/\}/ies", "\$this->hdwiki('\\1')", $template);
		for($i=0; $i<2; $i++) {
			$template = preg_replace("/\{hdwiki:(.+?)\}(.+?)\{\/hdwiki\}/ies", "\$this->hdwiki('\\1', '\\2')", $template);
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '\\2', '\\3', '\\4')", $template);
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '', '\\2', '\\3')", $template);
		}
		
		$template = preg_replace("/\{if\s+(.+?)\}/ies", "\$this->stripvtag('<?php if(\\1) { ?>')", $template);
		$template = preg_replace("/\{template\s+(\w+?)\}/is", "<?php include \$this->gettpl('\\1');?>", $template);
		$template = preg_replace("/\{template\s+(.+?)\}/ise", "\$this->stripvtag('<?php include \$this->gettpl(\\1); ?>')", $template);
		$template = preg_replace("/\{else\}/is", "<?php } else { ?>", $template);
		$template = preg_replace("/\{\/if\}/is", "<?php } ?>", $template);
		$template = preg_replace("/\{\/for\}/is", "<?php } ?>", $template);
		$template = preg_replace("/$this->const_regexp/", "<?php echo \\1?>", $template);
		$template = "<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>\r\n$template";
		$template = preg_replace("/(\\\$[a-zA-Z_]\w+\[)([a-zA-Z_]\w+)\]/i", "\\1'\\2']", $template);
		$template = preg_replace("/\{url.(.+?)\}/ise", "\$this->url('\\1')", $template);
		
		$template = preg_replace("/\{datacall:([^\}]+?)\/\}/ies", "\$this->datacall('\\1')", $template);
		$fp = fopen($this->objfile, 'w');
		fwrite($fp, $template);
		fclose($fp);
	}

	function stripvtag($s) {
		return preg_replace("/$this->vtag_regexp/is", "\\1", str_replace("\\\"", '"', $s));
	}

	function loopsection($arr, $k, $v, $statement){
		$arr = $this->stripvtag($arr);
		$k = $this->stripvtag($k);
		$v = $this->stripvtag($v);
		$statement = str_replace("\\\"", '"', $statement);
		return $k ? "<?php foreach((array)$arr as $k=>$v) {?>$statement<?php }?>" : "<?php foreach((array)$arr as $v) {?>$statement<?php } ?>";
	}

	function lang($k){
		return !empty($this->lang[$k]) ? stripslashes($this->lang[$k]) : "{ $k }";
	}
	
	function url($u){
		if(substr($u,0,10)=='user-login'||substr($u,0,11)=='user-logout'||substr($u,0,13)=='user-register'||substr($u,0,9)=='user-code'
			|| substr($u,0,10)=='pic-search'|| substr($u,0,7)=='search-'){
			return 'index.php?'.$u;
		}elseif('1'==$this->vars['setting']['seo_type'] &&'1'==$this->vars['setting']['seo_type_doc'] && 'doc-view-'==substr($u,0,9)){
			return "wiki/".substr($u,9);
		}else{
			return $this->vars['setting']['seo_prefix'].$u.$this->vars['setting']['seo_suffix'];
		}
	}
	
	function hdwiki($taglist, $statement=''){
		$tag=preg_split("/\s+/",trim($taglist));//接收到参数按空格分开。
		$taglist = str_replace("'", "\'", $taglist);
		if(''!=$statement){
			$statement = str_replace("\\\"", '"', $statement);
			$statement = preg_replace_callback("/\[field:([^\]]+?)\/\]/is", array($this,'callback'), $statement);
			
			return "<?php foreach((array)\$_ENV['tag']->$tag[0]('$taglist') as \$data) {?>$statement<?php } ?>" ;
		}else{
			return "<?php echo \$_ENV['tag']->$tag[0]('$taglist');?>" ;
		}
	}
	
	function callback($matches){
		$cmd=trim($matches[1]);
		$firstspace=strpos($cmd,' ');
		if(!$firstspace){
			return '<?php echo $data['.$cmd.']?>';
		}else{
			$field=substr($cmd,0,$firstspace);
			$func=substr($cmd,$firstspace);
			return '<?php echo '.str_replace('@me','$data['.$field.']',$func)." ?>";
		}
	}

	function datacall($datatag){
		$datatag = trim($datatag);
		return  "<?php \$_ENV['datacall']->call('$datatag');?>" ;
	}
	
	function block($area){
		$area = trim($area);
		$datastr='';
		if(!empty($GLOBALS['blocklist'][$area])) {
			foreach((array)$GLOBALS['blocklist'][$area] as $block){
				$datastr.='{eval $data= $GLOBALS[\'blockdata\']['.$block['id'].'];$bid="'.$block['id'].'"}';
				$tplfile=HDWIKI_ROOT.'/block/'.$block['theme'].'/'.$block['block'].'/'.$block['tpl'];
				if(!file_exists($tplfile)){
					$tplfile=HDWIKI_ROOT.'/block/default/'.$block['block'].'/'.$block['tpl'];
				}
				$datastr.=file::readfromfile($tplfile);
			}
		}
		return $datastr;
	}
}
?>