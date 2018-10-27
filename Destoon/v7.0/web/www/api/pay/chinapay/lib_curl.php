<?php
defined('IN_DESTOON') or exit('Access Denied');
function HttpGet($http, $url) {
	// create curl resource 
	curl_setopt($http, CURLOPT_HTTPGET, 1);
	//curl_setopt($http, CURLOPT_URL, $url); 
	//$output = curl_exec($http);
	$output = curl_redir_exec($http, $url);
	return $output;
}   

function HttpPost($http, $post_data, $url) {
	// create curl resource 
	curl_setopt($http, CURLOPT_POST, 1);
  curl_setopt($http, CURLOPT_POSTFIELDS, $post_data); // $post_data  string or hash array
	//curl_setopt($http, CURLOPT_URL, $url); 
	//$output = curl_exec($http);
	$output = curl_redir_exec($http, $url);
	return $output;
}   

function curl_redir_exec($ch, $url){
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $ret = $data;
    list($header, $data) = explode("\r\n\r\n", $data, 2);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 301 || $http_code == 302) {
        $matches = array();
        preg_match('/Location:(.*?)\n/', $header, $matches);
        $url = @parse_url(trim(array_pop($matches)));
        if (!$url)
        {
          //couldn't process the url to redirect to
          $curl_loops = 0;
          return $data;
        }
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
        if (!$url['scheme'])
            $url['scheme'] = $last_url['scheme'];
        if (!$url['host'])
            $url['host'] = $last_url['host'];
        if (!$url['path'])
            $url['path'] = $last_url['path']; 
        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query']) ? '?'.$url['query'] : '');
        return curl_redir_exec($ch, $new_url);
    } else if ($http_code == 200) {
        list($header, $data) = explode("\r\n\r\n", $ret, 2);
        return $data;
    } else {
    	  return false;
    }
}

function HttpInit() {
	$http = curl_init(); 
	curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
	/* 
	if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')){
		curl_setopt($http, CURLOPT_FOLLOWLOCATION, 1);
	}
	*/
	curl_setopt($http, CURLOPT_ENCODING, "gzip"); 
	curl_setopt($http, CURLOPT_TIMEOUT, 30); 
	return $http;
}

function HttpDone($http) {
	// close curl resource to free up system resources 
	curl_close($http); 
}
?>