<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$install = file_get(DT_CACHE.'/install.lock');
$url = decrypt('b974CcIv9scL8IVr0m9ZzTcwBBzOqzV0vOn5wNSRNZvuVb9C9qi3f7sYLdzmVdr6T9eFlYfNRze9x4DAmRTaf2KC', 'DESTOON').'?action='.$action.'&product=b2b&version='.DT_VERSION.'&release='.DT_RELEASE.'&lang='.DT_LANG.'&charset='.DT_CHARSET.'&install='.$install.'&os='.PHP_OS.'&soft='.urlencode($_SERVER['SERVER_SOFTWARE']).'&php='.urlencode(phpversion()).'&mysql='.urlencode($db->version()).'&url='.urlencode($DT_URL).'&site='.urlencode($DT['sitename']).'&auth='.strtoupper(md5($DT_URL.$install.$_SERVER['SERVER_SOFTWARE']));
if(isset($mfa)) $url .= '&mfa='.$mfa;
dheader($url);
?>