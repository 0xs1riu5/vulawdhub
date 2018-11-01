<?php
//#####################@ 万能文本缓存类-20111128-koyshe @#####################//
class cache { 
	/**
	 * 读取相应缓存文件
	 *
	 * @param string $cachename
	 */
	public static $cache_arr = array();
	public static function get($cachename)
	{
		global $pe;
		if (!array_key_exists($cachename, self::$cache_arr)) {
			include("{$pe['path_root']}data/cache/{$cachename}.cache.php");
			$cache_arr[$cachename] = $cache;
		}
		return $cache_arr[$cachename];
	}
	/**
	 * 生成相应缓存文件
	 *
	 * @param string $cachename 缓存文件名
	 * @param string $index_arr 缓存索引 or 带索引的自定义数组
	 */
	public static function write($cachename, $index_arr = '')
	{
		if (is_array($index_arr)) {
			self::write_diy($cachename, $index_arr, 0);	
		}
		else {
			self::write_default($cachename, $index_arr, 0);		
		}
	}
	/**
	 * 生成通用缓存操作
	 *
	 * @param string $cachename 缓存文件名
	 * @param string $index 缓存索引
	 * @param int $js 是否同时生成js缓存
	 */
	public static function write_default($cachename, $index, $js)
	{
		global $pe, $db;
		$rows = $db->pe_selectall($cachename);
		$cache_arr = array();
		//默认是主键为索引
		if (!$index) {
			foreach ($rows as $v) {
				$cache_arr[$v[$cachename.'_id']] = $v;
			}
		}
		//自定义索引
		elseif ($index && stripos($index, '|') === false) {
			foreach ($rows as $v) {
				$cache_arr[$v[$index]] = $v;
			}
		}
		//父子二级索引(最多只支持二级索引)
		elseif ($index && stripos($index, '|') !== false) {
			$indexarr = explode('|', $index);
			foreach ($rows as $v) {
				$cache_arr[$v[$indexarr[0]]][$v[$indexarr[1]]] = $v;
			}
		}
		self::write_diy($cachename, $cache_arr, 0);
		if ($js == 1) {
			$cache = "var {$cachename}=".json_encode($cache_arr);
			file_put_contents("{$pe['path_root']}data/cache/{$cachename}.cache.js", $cache);
		}
	}
	/**
	 * 生成自定义缓存操作
	 *
	 * @param string $cachename 缓存文件名
	 * @param string $index 缓存索引
	 * @param int $js 是否同时生成js缓存
	 */
	public static function write_diy($cachename, $index_arr, $js)
	{
		global $pe;
		$cache = "<?php\n\r\$cache=unserialize(stripslashes('".addslashes(serialize($index_arr))."'));\n\r?>";
		file_put_contents("{$pe['path_root']}data/cache/{$cachename}.cache.php", $cache);	
	}
}
?>