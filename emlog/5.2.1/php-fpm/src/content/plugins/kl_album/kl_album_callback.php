<?php
function callback_init()
{
	global $CACHE;
	$DB = MySql::getInstance();
	$kl_album_config = Option::get('kl_album_config');
	$is_exist_album_query = $DB->query('show tables like "'.DB_PREFIX.'kl_album"');
	if($DB->num_rows($is_exist_album_query) == 0)
	{
		$dbcharset = 'utf8';
		$type = 'MYISAM';
		$add = $DB->getMysqlVersion() > '4.1' ? "ENGINE=".$type." DEFAULT CHARSET=".$dbcharset.";":"TYPE=".$type.";";
		$sql = "
CREATE TABLE `".DB_PREFIX."kl_album` (
`id` int(10) unsigned NOT NULL auto_increment,
`truename` varchar(255) NOT NULL,
`filename` varchar(255) NOT NULL,
`description` text,
`album` varchar(255) NOT NULL,
`addtime` int(10) NOT NULL default '0',
PRIMARY KEY  (`id`)
)".$add;
		$DB->query($sql);
	}

	kl_album_callback_do('n');
}

function callback_rm()
{
	kl_album_callback_do('y');
}

function kl_album_callback_do($hide)
{
	global $CACHE;
	$DB = MySql::getInstance();
	$kl_album_config = Option::get('kl_album_config');

	if(is_null($kl_album_config)){
		$kl_album_config = mysql_escape_string(serialize(array()));
		$DB->query("INSERT INTO ".DB_PREFIX."options(option_name, option_value) VALUES('kl_album_config', '$kl_album_config')");
		$CACHE->updateCache('options');
	}

	$isExists = false;
	$Navi_Model = new Navi_Model();
	$navis = $Navi_Model->getNavis();
	foreach($navis as $navi){
		if($navi['url'] == '?plugin=kl_album' && $navi['isdefault'] == 'y'){
			$Navi_Model->updateNavi(array('hide'=>$hide), $navi['id']);
			$CACHE->updateCache('navi');
			$isExists = true;
			break;
		}
	}
	if(!$isExists){
		$DB->query("insert into ".DB_PREFIX."navi (naviname,url,newtab,hide,taxis,isdefault) values('相册','?plugin=kl_album', 'n', '$hide', 4, 'y')");
		$CACHE->updateCache('navi');
	}
}
?>