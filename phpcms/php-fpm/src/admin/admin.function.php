<?php 
/**
 * 后台公用函数库
 */

/**
 * 分页函数
 *
 * @param int $num	
 * @param int $perpage
 * @param int $curpage
 * @param string $mpurl
 * @param int $maxpages
 * @param int $page
 * @param bool $autogoto
 * @param bool $simple
 * @return string
 */
function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = TRUE, $simple = FALSE) {
	global $maxpage;
		$shownum = $showkbd = TRUE;
		$lang['prev'] = '上一页';
		$lang['next'] = '下一页';

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	$realpages = 1;									
	if($num > $perpage) {
		$offset = 2;

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="first">首页</a> ' : '').
			($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="prev">'.$lang['prev'].'</a> ' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong><font style="color:#ff0000">'.$i.'</font></strong> ' :
				'<a href="'.$mpurl.'page='.$i.($i == $pages && $autogoto ? '#' : '').'">'.$i.'</a> ';
		}

		$multipage .= ($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="last">... '.$realpages.'</a> ': '').
			($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="next">'.$lang['next'].'</a> ' : '').
			($showkbd && !$simple && $pages > $page ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /></kbd>' : '');

		$multipage = $multipage ? '<div class="pages">'.($shownum && !$simple ? '共&nbsp;<font style="color:#ff0000">'.$num.'</font>&nbsp;条 ' : '').$multipage.'</div>' : '';
	}
	$maxpage = $realpages;
	return $multipage;
}

/**
 * 栏目分类下拉框 <option></option>
 *
 * @param int $pid
 * @param int $id
 * @param int $level
 */
function getCategorySelect($select_id=0,$id = 0,$level = 0){
	global $db;
	$category_arr = $db->getList ( "select * from cms_category where pid = " . $id . " order by seq" );
	for($lev = 0; $lev < $level * 2 - 1; $lev ++) {
		$level_nbsp .= "　";
	}
	if ($level++) $level_nbsp .= "┝";
	foreach ( $category_arr as $category ) {
		$id = $category ['id'];
		$name = $category ['name'];
		$selected = $select_id==$id?'selected':'';
		echo "<option value=\"".$id."\" ".$selected.">".$level_nbsp . " " . $name."</option>\n";
		getCategorySelect ($select_id, $id, $level );
	}
}

/***********************************
			图片上传函数
***********************************/
function uploadFile($filename){
	global $db;
	global $config;
	$attachment_dir = "attachment/".date('Ym')."/";
	!is_dir(ROOT_PATH.$attachment_dir)&&mkdir(ROOT_PATH.$attachment_dir);	
	$AllowedExtensions = array('bmp','gif','jpeg','jpg','png');
	$Extensions = end(explode(".",$_FILES[$filename]['name']));
	
	if(!in_array(strtolower($Extensions),$AllowedExtensions)){
		exit("<script>alert('缩略图格式错误！只支持后缀名为bmp,gif,jpeg,jpg,png 的文件');window.history.go(-1)</script>");
	}


	$file_name = date('YmdHis').'_'.rand(10,99).'.'.$Extensions;
	$upload_file = $attachment_dir.$file_name;
	$upload_absolute_file = ROOT_PATH.$upload_file;
	if (move_uploaded_file($_FILES[$filename]['tmp_name'], $upload_absolute_file)) {
		$record = array(
			'filename'			=>$file_name,
			'ffilename'			=>$_FILES [$filename]['name'],
			'path'				=>$upload_file,
			'ext'				=>$Extensions,
			'size'				=>$_FILES [$filename]['size'],
			'upload_date'		=>date("Y-m-d H:i:s")			
		);
		$id = $db->insert('cms_file',$record);
		return $upload_file;
	}
}
?>