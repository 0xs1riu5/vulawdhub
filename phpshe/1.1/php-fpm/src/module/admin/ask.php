<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'ask';
switch ($act) {
	//#####################@ 咨询回复 @#####################//
	case 'edit':
		$ask_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($_p_info['ask_replytext']) {
				$_p_info['ask_replytime'] = time();
				$_p_info['ask_state'] = 1;
			}
			else {
				$_p_info['ask_replytime'] = $_p_info['ask_state'] = 0;		
			}
			if ($db->pe_update('ask', array('ask_id'=>$ask_id), pe_dbhold($_p_info))) {
				pe_success('咨询回复成功!', $_g_fromto);
			}
			else {
				pe_error('咨询回复失败...');
			}
		}
		$sql = "select * from `".dbpre."ask` a,`".dbpre."product` b where a.`product_id` = b.`product_id` and a.`ask_id` = '{$ask_id}'";
		$info = $db->sql_select($sql);

		$seo = pe_seo($menutitle='咨询详情', '', '', 'admin');
		include(pe_tpl('ask_add.html'));
	break;
	//#####################@ 咨询删除 @#####################//
	case 'del':
		if ($db->pe_delete('ask', array('ask_id'=>is_array($_p_ask_id) ? $_p_ask_id : $_g_id))) {
			pe_success('咨询删除成功!');
		}
		else {
			pe_error('咨询删除失败...');
		}
	break;
	//#####################@ 咨询列表 @#####################//
	default :
		$sqlwhere = " and `ask_state` = '".intval($_g_state)."'";
		$_g_name && $sqlwhere .= " and b.`product_name` like '%{$_g_name}%'";
		$_g_text && $sqlwhere .= " and a.`ask_text` like '%{$_g_text}%'";
		$_g_user_name && $sqlwhere .= " and a.`user_name` like '%{$_g_user_name}%'";
		$sql = "select * from `".dbpre."ask` a,`".dbpre."product` b where a.`product_id` = b.`product_id` {$sqlwhere} order by a.`ask_id` desc";
		$info_list = $db->sql_selectall($sql, array(20, $_g_page));

		$seo = pe_seo($menutitle='商品咨询', '', '', 'admin');
		include(pe_tpl('ask_list.html'));
	break;
}
?>